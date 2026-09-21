<?php

declare(strict_types=1);

use App\ResetRepository;

require_once __DIR__ . '/../vendor/autoload.php';   // If installed via composer

$reset = new ResetRepository();

if (filter_input(INPUT_GET, 'op')) {

    if (filter_input(INPUT_GET, 'op') == 'reset') {

        $tables_name = ['orders', 'orders_details', 'place', 'products'];
        $error = 0;
        foreach ($tables_name as $table) {
            if(!$reset->truncate($table)){
                $error++;
            }
        }
        $error == 0 ? $msg = 'msg=resetOk' : 'err=resetFail';

        header("Location: ../admin/index.php?$msg");
        exit;

    } else {
        header("Location: ../admin/index.php?err=errReset");
        exit;
    }
} else {
    header("Location: ../admin/index.php?err=errReset");
    exit;
}
