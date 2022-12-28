<?php

namespace Meals\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\PollIsNotOpenException;
use Meals\Domain\Poll\Poll;
use \DateTime;

class PollIsOpenValidator
{
    public function validate(DateTime $date): void
    {
        if ($date->format('D') == "Mon"
            && $date->format('H') > 5
	    && $date->format('H') < 22
	) {
	    return;
	} else {
            throw new PollIsNotOpenException();
        }
    }
}
