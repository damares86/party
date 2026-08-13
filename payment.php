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
$order_number = '' ;
$email = '' ;
if ($_GET) {

    $email =filter_input(INPUT_GET, 'email');
    $order_number = filter_input(INPUT_GET, 'order_number');
    $msg = filter_input(INPUT_GET, 'msg');
}

$button = $msg == 'orderToPay' ? 'Paga' : 'Cerca';
$operation = $msg == 'orderToPay' ? 'pay' : 'search';
$bill = '';
if (filter_input(INPUT_GET, 'id')) {
    $id = filter_input(INPUT_GET, 'id');
    $order = $orders->findById($id);
    $bill = $order['bill'];
}

$pagename = 'payment';
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
            <h5>Pagamento</h5>
            <div class="col-12 my-5">
                <?php
                if ($msg == 'orderToPay') {
                ?>
                    <a href="payment.php"><b>Cerca un altro ordine</b></a>

                <?php
                }
                ?>
                <?php
                if ($msg == 'paidSucc') {
                ?>
<!--                     <div class="my-3 p-3 bg-success text-white">
                        <b>Ordine pagato</b>
                    </div> -->
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
                if ($msg == 'orderToPay') {
                ?>
                    <div class="col-12 mb-4">
                        <label for="code" class="form-label">Totale da pagare</label>
                        <br><b><?= $bill ?> €</b>
                    </div>

                    <input type="hidden" name="id" value="<?= $id ?>">
                <?php
                } else if ($msg == 'orderPaid') {
                ?>
<!--                     <div class="my-3 p-3 bg-success text-white">
                        <b>Ordine pagato</b>
                    </div> -->
                <?php
                }
                ?>

                <input type="hidden" name="operation" value="<?= $operation ?>">

                <button class="w-100 btn btn-lg text-white" type="submit"><?= $button ?></button>
            </div>
        </form>
        <?php
        require 'inc/footer.php';
        ?>
    </main>


</body>

</html>