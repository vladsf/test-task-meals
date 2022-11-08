<?php

namespace tests\Meals\Functional\Interactor;

use Meals\Application\Component\Validator\Exception\AccessDeniedException;
use Meals\Application\Component\Validator\Exception\PollResultIsNotValidException;
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
use tests\Meals\Functional\Fake\Provider\FakePollProvider;
use tests\Meals\Functional\Fake\Provider\FakePollResultProvider;
use tests\Meals\Functional\FunctionalTestCase;

class EmployeeSubmitsPollResultTest extends FunctionalTestCase
{
    public function testSuccessful()
    {
        $poll = $this->performTestMethod($this->getEmployeeWithPermissions(), $this->getPollResult(true));
        verify($poll)->equals($poll);
    }

    public function testPollResultIsNotValid()
    {
        $this->expectException(PollResultIsNotValidException::class);

        $poll = $this->performTestMethod($this->getEmployeeWithPermissions(), $this->getPollResult(false));
        verify($poll)->equals($poll);
    }

    private function performTestMethod(Employee $employee, PollResult $pollResult): PollResult
    {
        $this->getContainer()->get(FakeEmployeeProvider::class)->setEmployee($employee);
        $this->getContainer()->get(FakePollResultProvider::class)->setPollResult($pollResult);

        return $this->getContainer()->get(Interactor::class)->getPollResult($employee->getId(), $pollResult->getId());
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

    private function getPollResult(bool $valid): PollResult
    {
	$poll = $this->getPoll(true);
        $employee = $this->getEmployeeWithPermissions();
        return new PollResult(
	    1,
	    $poll,
            $employee,
	    new Dish(
                1,
	        'title',
		'description'),
	    7,
            $valid ? 1667846020 : 1667932420,
        );
    }
}
