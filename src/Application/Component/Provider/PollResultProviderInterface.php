<?php

namespace Meals\Application\Component\Provider;

use Meals\Domain\Dish\Dish;
use Meals\Domain\Employee\Employee;
use Meals\Domain\Poll\Poll;
use Meals\Domain\Poll\PollList;
use Meals\Domain\Poll\PollResult;

interface PollResultProviderInterface
{
    public function getPollResult(int $pollResultId): PollResult;
}
