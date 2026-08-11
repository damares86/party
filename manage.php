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

$email = $_GET['email'] ? filter_input(INPUT_GET, 'email') : '';
$order_number = $_GET['order_number'] ? filter_input(INPUT_GET, 'order_number') : '';
$msg = filter_input(INPUT_GET, 'msg');

$button = $msg == 'orderToUse' ? 'Conferma utilizzo' : 'Cerca';
$operation = $msg == 'orderToUse' ? 'book' : 'check';

if (filter_input(INPUT_GET, 'id')) {
    $id = filter_input(INPUT_GET, 'id');
    $order = $orders->findById($id);
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
            ?>
            <h5>Cerca prenotazione</h5>
            <div class="col-12 my-5">
                <div class="row">
                    <?php
                    if ($msg == 'orderToUse') {
                    ?>
                        <a href="manage.php"><b><-- Cerca un altra prenotazione</b></a>

                    <?php
                    }
                    ?>
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
                    ?>
                    <div class="col-12 mb-4">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="mail@mail.it" required value="<?= $email ?>">
                        <div class="invalid-feedback">
                            Inserire una email valida
                        </div>
                    </div>

                    <div class="col-12 mb-4">
                        <label for="code" class="form-label">Numero ordine</label>
                        <input type="number" class="form-control" name="number" placeholder="00000" required value="<?= $order_number ?>">
                    </div>
                    <?php
                    if ($msg == 'orderToUse') {
                    ?>
                        <div class="col-4 mb-3 border">
                            Pacchetto
                        </div>
                        <div class="col-8 mb-3 border">
                            Bevanda
                        </div>
                        <?php
                        $orders->table = 'orders_details';
                        $order_products = $orders->findBy(['orders_id' => $id]);

                        $i = 0;
                        foreach ($order_products as $ord) {

                        ?>
                            <div class="row mb-3 order-row border-bottom">

                                <input
                                    type="hidden"
                                    name="items[<?= $i ?>][detail_id]"
                                    value="<?= $ord['id'] ?>">

                                <div class="col-4">
                                    <p class="product-price">1 pacchetto</p>
                                </div>
                                <div class="col-8">

                                    <?php
                                    $name = '';
                                    foreach ($list as $item) {
                                        if ($ord['products_id'] == $item['id']) {
                                            $name = htmlspecialchars($item['name']);
                                            break;
                                        }
                                    }
                                    ?>
                                    <b>
                                        <?= $name ?>
                                    </b>

                                </div>

                            </div>
                    <?php
                            $i++;
                        }
                    }
                    ?>


                    <input type="hidden" name="operation" value="<?= $operation ?>">

                    <button class="w-100 btn btn-lg text-white" type="submit"><?= $button ?></button>
                    <?php
                    require 'inc/footer.php';
                    ?>
                </div>
            </div>
            </div>
        </form>
    </main>


</body>

</html>