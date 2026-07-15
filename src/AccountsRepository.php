<?php

declare(strict_types=1);

namespace App;

final class AccountsRepository extends CrudRepository
{
    protected string $table = 'accounts';


}
