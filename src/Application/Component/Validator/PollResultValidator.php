<?php

namespace Meals\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\PollResultIsNotValidException;
use Meals\Application\Component\Validator\PollDateValidator;
use Meals\Domain\Poll\PollResult;

class PollResultValidator
{
    public function validate(PollResult $result): void
    {
	$date = getdate($result->getTime());

        if (!PollDateValidator::isValidDate($date)) {
            throw new PollResultIsNotValidException();
	}
    }
}
