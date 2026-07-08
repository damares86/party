<?php

declare(strict_types=1);

require 'inc/header.php';

use App\ProductRepository;

$products = new ProductRepository();
$list = $products->findAll();

?>

<body class="text-center">

    <main class="form-signin">
        <form>
            <img class="mb-4" src="assets/img/logo_agnelli.png" alt="" width="72" height="57">
            <h1 class="mb-5 ">Partynsieme</h1>
            <h5>Cassa</h5>
            <p><a href="booking.php">Booking --></a></p>
            <div class="col-12 my-5">
                <label for="country" class="form-label">Tipo di cibo</label>
                <select class="form-select" name="items[0][product_id]" required>
                    <option value="">---</option>
                    <?php
                    foreach ($list as $item) {

                    ?>
                        <option value="<?= $item['id'] ?>"><?= $item['name'] ?> (<?= $item['code'] ?>)</option>

                    <?php
                    }
                    ?>
                </select>
                <div class="row">
                    <div class="col-8">
                        <label for="code" class="form-label">Codice</label>
                        <input type="number" class="form-control" id="address" placeholder="00000" required>
                    </div>
                    <div class="col-4">
                        <label for="address" class="form-label">Lettera</label>
                        <select class="form-select mb-3" id="country" required>
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
                </div>
            </div>

            <button class="w-100 btn btn-lg btn-primary" type="submit">Invia</button>
            <?php
            require 'inc/footer.php';
            ?>

        </form>
    </main>


</body>

</html>