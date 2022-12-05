<?php

namespace Meals\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\PollResultIsNotValidException;
use Meals\Domain\Poll\PollResult;

class PollResultValidator
{
    public function validate(PollResult $result): void
    {
        # Check poll results e.g. for time constraints (Monday 6-22)
        $date = getdate($result->getTime());
        
	if ($date["wday"] == "1"
	    && $date["hours"] > 5
	    && $date["hours"] < 22 )
	{
	    return;
	} else {
            throw new PollResultIsNotValidException();
        }
    }
}
