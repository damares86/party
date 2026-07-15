<?php

declare(strict_types=1);


use App\AccountsRepository;

require_once __DIR__ . '/../vendor/autoload.php';   // If installed via composer

$debug = new \bdk\Debug(array(
    'collect' => true,
    'output' => true,
));

$accounts = new AccountsRepository();

$operation = filter_input(INPUT_POST, 'operation');


if ($operation == 'login') {
    $username = filter_input(INPUT_POST, 'username');
    $account = $accounts->findBy(['username' => $username]);
    $postpass = filter_input(INPUT_POST, 'password');

    if (count($account) > 0 && password_verify($postpass, $account[0]['password'])) {
        session_start();
        $_SESSION['loggedin'] = true ;
        $_SESSION['username'] = $username ;

        header("Location: ../index.php?msg=loginOk");
        exit;
    } else {
        header("Location: ../login.php?err=noLogin");
        exit;
    }
} else if ($operation == 'add') {

    $username = filter_input(INPUT_POST, 'username');
    $postpass = filter_input(INPUT_POST, "password");
    $password = password_hash($postpass, PASSWORD_BCRYPT);

    if ($accounts->insert([
        'username' => $username,
        'password' => $password
    ])) {
        header("Location: ../login.php?msg=accountAdd");
        exit;
    } else {
        header("Location: ../login.php?err=accountNoAdd");
        exit;
    }
} else if ($operation == 'edit') {
}
