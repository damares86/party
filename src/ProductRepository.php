<?php

declare(strict_types=1);

namespace App;

final class ProductRepository extends CrudRepository
{
    protected string $table = 'products';


}
