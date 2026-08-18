<?php

declare(strict_types=1);
require 'inc/header.php';

require_once '../vendor/autoload.php';

use App\AccountsRepository;
$accounts = new AccountsRepository();

$id = filter_input(INPUT_GET,'id');
$account = $accounts->findById($id);
?>

<body>

    <main>

        <?php
        require 'inc/navbar.php';
        ?>

        <div>
            <div class="container px-4 py-5" id="featured-3">
                <?php
                require "inc/alert.php";
                ?>

                <h2 class="pb-2 border-bottom">Modifica utente</h2>
                <form action="../core/mngAccounts.php" method="POST">
                    <div class="col-12 my-2">
                        <div class="col-12 mb-4">
                            <label for="name" class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" value="<?= $account['username'] ?>" required>
                            <div class="invalid-feedback">
                                Inserire uno username
                            </div>
                        </div>
                        <div class="col-12 mb-4">
                            <label for="password" class="form-label">Nuova password (opzionale)</label>
                            <input type="password" class="form-control" name="password">                            
                        </div>
                        
                        <input type="hidden" name="operation" value="edit">
                        <input type="hidden" name="idToMod" value="<?= $account['id'] ?>">
                    <button class="w-100 btn invia btn-lg text-white" type="submit">Invia</button>
                    </div>

                </form>
            </div>
        </div>
    </main>

    <?php

    require 'inc/footer.php';
    ?>