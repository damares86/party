<?php

declare(strict_types=1);

require 'inc/header.php';
session_start();
if (!$_SESSION['loggedin']) {
    header("Location: error.php");
    exit;
}

use App\ProductRepository;
use App\OrderRepository;

$products = new ProductRepository();
$orders = new OrderRepository();
$list = $products->findAll();
$msg = '';
$order_number = '';
$email = '';
if ($_GET) {

    $email = filter_input(INPUT_GET, 'email');
    $order_number = filter_input(INPUT_GET, 'order_number');
    $msg = filter_input(INPUT_GET, 'msg');
}
$button = $msg == 'orderToUse' ? 'Conferma utilizzo' : 'Cerca';
$operation = $msg == 'orderToUse' ? 'book' : 'check';
$order = NULL;
if (filter_input(INPUT_GET, 'id')) {
    $id = filter_input(INPUT_GET, 'id');
    $code = filter_input(INPUT_GET, 'code');
    $letter = filter_input(INPUT_GET, 'letter');
    $orders->table = 'orders_details';
    $order = $orders->findBy([
        'orders_id' => $id,
        'product_code' => $code,
        'letter' => $letter
    ]);
}

$pagename = 'index';
?>

<body class="text-center">

    <main class="form-signin">
        <form action="core/mngBooking.php" method="POST">
            <img class="mb-4" src="assets/img/logo_agnelli.png" alt="" width="72" height="57">
            <h1 class="mb-3 ">Partyinsieme</h1>
            <?php
            require 'inc/navbar.php';

            require "inc/alert.php";
            ?>
            <?php
            $search = 'Cerca';
            if ($msg == 'orderToUse') {
                $search = 'Conferma';
            ?>
                <span class="mb-5"><a href="manage.php"><b><-- Cerca un altra prenotazione</b></a></span>

            <?php
            }
            ?>
            <h5 class="mt-3"><?= $search ?> prenotazione</h5>
            <div class="col-12 my-3">
                <div class="row">
                    <?php
                    if ($msg == 'usedSucc') {
                    ?>
                        <div class="my-3 p-3 bg-success text-white">
                            <b>Ordine utilizzato</b>
                        </div>
                        <div class="my-3">
                            <b>Cerca un altro ordine</b>
                        </div>
                    <?php
                    }

                    if ($msg != 'orderToUse') {
                    ?>

                        <div class="col-md-4">
                            <label for="address" class="form-label">Codice</label>
                            <select class="form-select mb-3" name="product_code" required>
                                <option value="">---</option>
                                <option value="PIA">PIA</option>
                                <option value="BEV">BEV</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="code" class="form-label">Numero</label>
                            <input type="number" class="form-control" name="number" placeholder="00000" required>
                        </div>
                        <div class="col-md-4">
                            <label for="address" class="form-label">Lettera</label>
                            <select class="form-select mb-3" name="letter" required>
                                <option value="">---</option>
                                <?php
                                $alfabeto = range('A', 'Z');
                                foreach ($alfabeto as $letter) {
                                ?>
                                    <option value="<?= $letter ?>"><?= $letter ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    <?php
                    }
                    if ($msg == 'orderToUse') {
                    ?>
                        <div class="col-8 border">
                            Prodotto
                        </div>
                        <div class="col-4 border">
                            Quantità
                        </div>
                        <div class="col-12">
                            <div class="row mb-3 order-row border-bottom">

                                <input type="hidden" name="idToUse" value="<?= $order[0]['id'] ?>">
                                <?php
                                $prod = '';
                                if ($order[0]['product_code'] == "PIA") {
                                    $prod = 'Piatto';
                                ?>
                                    <div class="col-8 py-3 border">
                                        <p class="product-price"><b><?= $prod ?></b></p>
                                    </div>
                                    <div class="col-4 py-3 border">

                                        <b>
                                            <?= $order[0]['qty'] ?>
                                        </b>

                                    </div>
                                    <?php
                                } else {
                                    $prod_id = explode(',', $order[0]['products_id']);
                                    foreach ($prod_id as $item) {

                                        $drink = $products->findById($item);
                                        $prod = $drink['name'];
                                    ?>
                                        <div class="col-8 py-3 border">
                                            <p class="product-price"><b><?= $prod ?></b></p>
                                        </div>
                                        <div class="col-4 py-3 border">

                                            <b>
                                                1
                                            </b>

                                        </div>
                                <?php
                                    }
                                }
                                ?>

                            </div>
                        </div>
                    <?php
                    }

                    ?>


                    <input type="hidden" name="operation" value="<?= $operation ?>">

                    <button class="mt-3 w-100 btn btn-lg text-white" type="submit"><?= $button ?></button>
                </div>
            </div>
            </div>
        </form>
        <?php
        require 'inc/footer.php';
        ?>
    </main>


</body>

</html>