<?php

namespace Meals\Application\Component\Provider;

use Meals\Domain\Dish\Dish;
use Meals\Domain\Employee\Employee;

interface DishProviderInterface
{
    public function getDish(int $dishId): Dish;
}
