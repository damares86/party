<?php

declare(strict_types=1);

namespace App;

final class OrderRepository extends CrudRepository
{
    protected string $table = 'orders';


}
