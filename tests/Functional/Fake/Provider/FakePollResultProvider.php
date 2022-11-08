<?php

namespace tests\Meals\Functional\Fake\Provider;

use Meals\Application\Component\Provider\PollResultProviderInterface;
use Meals\Domain\Poll\Poll;
use Meals\Domain\Poll\PollResult;

class FakePollResultProvider implements PollResultProviderInterface
{
    /** @var PollResult */
    private $result;

    public function getPollResult(int $resultId): PollResult
    {
        return $this->result;
    }

    /**
     * @param Poll $poll
     */
    public function setPollResult(PollResult $result): void
    {
        $this->result = $result;
    }
}
