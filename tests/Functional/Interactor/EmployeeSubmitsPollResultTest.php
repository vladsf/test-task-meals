<?php

namespace tests\Meals\Functional\Interactor;

use Meals\Application\Component\Validator\Exception\AccessDeniedException;
use Meals\Application\Component\Validator\Exception\PollIsNotOpenException;
use Meals\Application\Component\Validator\Exception\PollIsNotActiveException;
use Meals\Application\Feature\Poll\UseCase\EmployeeSubmitsPollResult\Interactor;
use Meals\Domain\Dish\DishList;
use Meals\Domain\Employee\Employee;
use Meals\Domain\Menu\Menu;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Poll\Poll;
use Meals\Domain\Poll\PollResult;
use Meals\Domain\User\Permission\Permission;
use Meals\Domain\User\Permission\PermissionList;
use Meals\Domain\User\User;
use tests\Meals\Functional\Fake\Provider\FakeEmployeeProvider;
use tests\Meals\Functional\Fake\Provider\FakeDishProvider;
use tests\Meals\Functional\Fake\Provider\FakePollProvider;
use tests\Meals\Functional\Fake\Provider\FakePollResultProvider;
use tests\Meals\Functional\FunctionalTestCase;

class EmployeeSubmitsPollResultTest extends FunctionalTestCase
{
    public function testSuccessful()
    {
        $poll = $this->performTestMethod($this->getEmployeeWithPermissions(), $this->getPollResult(true), $this->getPollDate(true));
        verify($poll)->equals($poll);
    }

    public function testPollIsNotOpen()
    {
        $this->expectException(PollIsNotOpenException::class);

        $poll = $this->performTestMethod($this->getEmployeeWithPermissions(), $this->getPollResult(true), $this->getPollDate(false));
        verify($poll)->equals($poll);
    }

    public function testPollIsNotActive()
    {
        $this->expectException(PollIsNotActiveException::class);

        $poll = $this->performTestMethod($this->getEmployeeWithPermissions(), $this->getPollResult(false), $this->getPollDate(true));
        verify($poll)->equals($poll);
    }

    private function performTestMethod(Employee $employee, PollResult $pollResult, string $datetime): PollResult
    {
        $this->getContainer()->get(FakeEmployeeProvider::class)->setEmployee($employee);
	$this->getContainer()->get(FakePollResultProvider::class)->setPollResult($pollResult);

	$poll = $pollResult->getPoll();
	$this->getContainer()->get(FakePollProvider::class)->setPoll($poll);

	$dish = $pollResult->getDish();
        $this->getContainer()->get(FakeDishProvider::class)->setDish($dish);

        return $this->getContainer()->get(Interactor::class)->submitPollResult(
		$pollResult->getId(),
                $poll->getId(), 
		$employee->getId(),
		$dish->getId(),
		$pollResult->getEmployeeFloor(),
		$datetime
	);
    }

    private function getEmployeeWithPermissions(): Employee
    {
        return new Employee(
            1,
            $this->getUserWithPermissions(),
            4,
            'Surname'
        );
    }

    private function getUserWithPermissions(): User
    {
        return new User(
            1,
            new PermissionList(
                [
                    new Permission(Permission::VIEW_ACTIVE_POLLS),
                ]
            ),
        );
    }

    private function getEmployeeWithNoPermissions(): Employee
    {
        return new Employee(
            1,
            $this->getUserWithNoPermissions(),
            4,
            'Surname'
        );
    }

    private function getUserWithNoPermissions(): User
    {
        return new User(
            1,
            new PermissionList([]),
        );
    }

    private function getPoll(bool $active): Poll
    {
        return new Poll(
            1,
            $active,
            new Menu(
                1,
                'title',
                new DishList([]),
            )
        );
    }

    private function getPollDate(bool $open): string
    {
        return $open ? '2022-11-07 06:33:40' : '2022-11-08 06:33:40';
    }

    private function getPollResult(bool $active = true): PollResult
    {
	$poll = $this->getPoll($active);
        $employee = $this->getEmployeeWithPermissions();
        return new PollResult(
	    1,
            $poll,
            $employee,
	    new Dish(
                1,
	        'title',
		'description'),
	    7
        );
    }
}
