<?php

declare(strict_types=1);

namespace App;

final class OrderRepository extends CrudRepository
{
    public string $table = 'orders';


}
