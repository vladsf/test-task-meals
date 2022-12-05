<?php

namespace Meals\Application\Component\Validator;

class PollDateValidator
{
    public static function isValidDate($date): bool
    {
        # Check date for time constraints (Monday 6-22)
        # $date is filled with getdate()
        
	if ($date["wday"] == "1"
	    && $date["hours"] > 5
	    && $date["hours"] < 22 )
	{
	    return true;
	}

	return false;
    }
}
