<?php

namespace tests\Meals\Unit\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\PollIsNotOpenException;
use Meals\Application\Component\Validator\PollIsOpenValidator;
use Meals\Domain\Poll\PollResult;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use \DateTime;

class PollIsOpenValidatorTest extends TestCase
{
    use ProphecyTrait;

    public function testSuccessful()
    {
	$datetime = $this->prophesize(DateTime::class);
	$datetime->format('D')->willReturn('Mon');
	$datetime->format('H')->willReturn('7');

        $validator = new PollIsOpenValidator();
        verify($validator->validate($datetime->reveal()))->null();
    }

    public function testFail()
    {
        $this->expectException(PollIsNotOpenException::class);

	$datetime = $this->prophesize(DateTime::class);
	$datetime->format('D')->willReturn('Wed');
	$datetime->format('H')->willReturn('17');

        $validator = new PollIsOpenValidator();
        $validator->validate($datetime->reveal());
    }
}
