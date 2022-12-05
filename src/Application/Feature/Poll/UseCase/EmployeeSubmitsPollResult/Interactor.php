<?php

namespace Meals\Application\Feature\Poll\UseCase\EmployeeSubmitsPollResult;

use Meals\Application\Component\Provider\EmployeeProviderInterface;
use Meals\Application\Component\Provider\PollResultProviderInterface;
use Meals\Application\Component\Provider\PollProviderInterface;
use Meals\Application\Component\Provider\DishProviderInterface;
use Meals\Application\Component\Validator\PollResultValidator;
use Meals\Application\Component\Validator\PollIsActiveValidator;
use Meals\Application\Component\Validator\UserHasAccessToViewPollsValidator;
use Meals\Domain\Poll\Poll;
use Meals\Domain\Poll\PollResult;
use Meals\Domain\Dish\Dish;
use Meals\Application\Component\Validator\Exception\PollIsClosedException;

class Interactor
{
    /** @var EmployeeProviderInterface */
    private $employeeProvider;

    /** @var PollProviderInterface */
    private $pollProvider;

    /** @var PollResultProviderInterface */
    private $pollResultProvider;

    /** @var DishProviderInterface */
    private $dishProvider;

    /** @var PollResultValidator */
    private $pollResultValidator;

    /** @var UserHasAccessToViewPollsValidator */
    private $userHasAccessToPollsValidator;

    /** @var PollIsActiveValidator */
    private $pollIsActiveValidator;

    /**
     * Interactor constructor.
     * @param EmployeeProviderInterface $employeeProvider
     * @param PollProviderInterface $pollProvider
     * @param PollResultProviderInterface $pollResultProvider
     * @param DishProviderInterface $dishProvider
     * @param UserHasAccessToViewPollsValidator $userHasAccessToPollsValidator
     * @param PollIsActiveValidator $pollIsActiveValidator
     * @param PollResultValidator $pollResultValidator
     */
    public function __construct(
        EmployeeProviderInterface $employeeProvider,
        PollProviderInterface $pollProvider,
        PollResultProviderInterface $pollResultProvider,
        DishProviderInterface $dishProvider,
        UserHasAccessToViewPollsValidator $userHasAccessToPollsValidator,
        PollIsActiveValidator $pollIsActiveValidator,
        PollResultValidator $pollResultValidator
    ) {
        $this->employeeProvider = $employeeProvider;
        $this->pollProvider = $pollProvider;
        $this->pollResultProvider = $pollResultProvider;
        $this->dishProvider = $dishProvider;
        $this->userHasAccessToPollsValidator = $userHasAccessToPollsValidator;
        $this->pollIsActiveValidator = $pollIsActiveValidator;
        $this->pollResultValidator = $pollResultValidator;
    }

    public function getPollResult(int $pollResultId): PollResult
    {
        $pollResult = $this->pollResultProvider->getPollResult($pollResultId);

        return $pollResult;
    }

    public function submitPollResult(int $pollResultId, int $pollId, int $employeeId, int $dishId, int $employeeFloor, int $ts = 0): PollResult
    {
        $employee = $this->employeeProvider->getEmployee($employeeId);
        $poll = $this->pollProvider->getPoll($pollId);
        $dish = $this->dishProvider->getDish($dishId);

        $this->userHasAccessToPollsValidator->validate($employee->getUser());

        $this->pollIsActiveValidator->validate($poll);

	# Check if poll is open for user submissions (on Mondays 6-22)
	$date = getdate(($ts > 0) ? $ts : time());
        
	if ($date["wday"] == "1" && $date["hours"] > 5 && $date["hours"] < 22 ) {
            $pollResult = new PollResult($pollResultId, $poll, $employee, $dish, $employeeFloor, $ts);
	} else {
            throw new PollIsClosedException();
        }

        $this->pollResultValidator->validate($pollResult);

        return $pollResult;
   
    }
}
