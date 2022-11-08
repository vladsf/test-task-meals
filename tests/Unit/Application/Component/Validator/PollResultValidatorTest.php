<?php

namespace tests\Meals\Unit\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\PollResultIsNotValidException;
use Meals\Application\Component\Validator\PollResultValidator;
use Meals\Domain\Poll\PollResult;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class PollResultValidatorTest extends TestCase
{
    use ProphecyTrait;

    public function testSuccessful()
    {

	$pollResult = $this->prophesize(PollResult::class);
        $pollResult->getTime()->willReturn(1667846020); // Monday, November 7, 2022 6:33:40 PM

        $validator = new PollResultValidator();
        verify($validator->validate($pollResult->reveal()))->null();
    }

    public function testFail()
    {
        $this->expectException(PollResultIsNotValidException::class);

	$pollResult = $this->prophesize(PollResult::class);
        $pollResult->getTime()->willReturn(1667932420); // Tuesday, November 8, 2022 6:33:40 PM

        $validator = new PollResultValidator();
        $validator->validate($pollResult->reveal());
    }
}
