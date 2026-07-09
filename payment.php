<?php

declare(strict_types=1);

require 'inc/header.php';

use App\ProductRepository;

$products = new ProductRepository();
$list = $products->findAll();

?>

<body class="text-center">

    <main class="form-signin">
        <form action="core/mngBooking.php" method="POST">
            <img class="mb-4" src="assets/img/logo_agnelli.png" alt="" width="72" height="57">
            <h1 class="mb-5 ">Partynsieme</h1>
            <h5>Pagamento</h5>
            <p><a href="booking.php">Booking --></a></p>
            <p><a href="index.php">Cassa --></a></p>
            <div class="col-12 my-5">

                <div class="col-12 mb-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" placeholder="mail@mail.it" required>
                    <div class="invalid-feedback">
                        Inserire una email valida
                    </div>
                </div>

                <div class="col-12 mb-4">
                    <label for="code" class="form-label">Numero ordine</label>
                    <input type="number" class="form-control" name="number" placeholder="00000" required>
                </div>

                <input type="hidden" name="operation" value="search">

                <button class="w-100 btn btn-lg btn-primary" type="submit">Cerca</button>
                <?php
                require 'inc/footer.php';
                ?>
            </div>
        </form>
    </main>


</body>

</html>