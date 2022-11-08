<?php

namespace Meals\Application\Feature\Poll\UseCase\EmployeeSubmitsPollResult;

use Meals\Application\Component\Provider\EmployeeProviderInterface;
use Meals\Application\Component\Provider\PollResultProviderInterface;
use Meals\Application\Component\Validator\PollResultValidator;
use Meals\Application\Component\Validator\UserHasAccessToViewPollsValidator;
use Meals\Domain\Poll\Poll;
use Meals\Domain\Poll\PollResult;

class Interactor
{
    /** @var EmployeeProviderInterface */
    private $employeeProvider;

    /** @var PollResultProviderInterface */
    private $pollResultProvider;

    /** @var PollResultValidator */
    private $pollResultValidator;

    /**
     * Interactor constructor.
     * @param EmployeeProviderInterface $employeeProvider
     * @param PollResultProviderInterface $pollResultProvider
     * @param PollResultValidator $pollResultValidator
     */
    public function __construct(
        EmployeeProviderInterface $employeeProvider,
        PollResultProviderInterface $pollResultProvider,
        PollResultValidator $pollResultValidator
    ) {
        $this->employeeProvider = $employeeProvider;
        $this->pollResultProvider = $pollResultProvider;
        $this->pollResultValidator = $pollResultValidator;
    }

    public function getPollResult(int $employeeId, int $pollResultId): PollResult
    {
        $employee = $this->employeeProvider->getEmployee($employeeId);
        $pollResult = $this->pollResultProvider->getPollResult($pollResultId);

        $this->pollResultValidator->validate($pollResult);

        return $pollResult;
    }
}
