<?php

declare(strict_types=1);

require 'inc/header.php';

use App\AccountsRepository;

$accounts = new AccountsRepository();

?>

<body class="text-center">

    <main class="form-signin">
        <form action="core/mngAccounts.php" method="POST">
            <img class="mb-4" src="assets/img/logo_agnelli.png" alt="" width="72" height="57">
            <h1 class="h3 mb-3 fw-normal">Registrazione utente</h1>

            <div class="form-floating">
                <input type="text" class="form-control" name="username" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Username</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" name="password" id="floatingPassword" placeholder="Password">
                <label for="floatingPassword">Password</label>
            </div>
            
            <input type="hidden" name="operation" value="add">

            <button class="w-100 mt-3 btn btn-lg btn-primary" type="submit">Registra</button>
            <p class="mt-5 mb-3 text-muted">
                Developed by
                <a href="http://www.dmweblab.com" target="_blank">
                    <img src="assets/img/dmweblab_logo.png" alt="Logo">
                </a>
            </p>
        </form>
    </main>


</body>

</html>