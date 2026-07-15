<?php

declare(strict_types=1);
require 'inc/header.php';

require_once '../vendor/autoload.php';

use App\ProductRepository;

$products = new ProductRepository();

$list = $products->findAll();
?>

<body>

    <main>

        <?php
        require 'inc/navbar.php';
        ?>

        <div>
            <div class="container px-4 py-5" id="featured-3">
                <h2 class="pb-2 border-bottom">Aggiungi tipo di cibo</h2>
                <form action="../core/mngProducts.php" method="POST">
                    <div class="col-12 my-2">
                        <div class="col-12 mb-4">
                            <label for="name" class="form-label">Tipo di cibo</label>
                            <input type="text" class="form-control" name="name" placeholder="es. Patatine" required>
                            <div class="invalid-feedback">
                                Inserire un nome di cibo
                            </div>
                        </div>
                        <div class="col-12 mb-4">
                            <label for="name" class="form-label">Codice</label>
                            <input type="text" class="form-control" name="code" placeholder="es. PAT" required>
                            <div class="invalid-feedback">
                                Inserire un codice
                            </div>
                        </div>
                        <div class="col-12 mb-4">
                            <label for="name" class="form-label">Prezzo</label>
                            <input
                                class="form-control w-25"
                                type="number"
                                name="price"
                                step="0.01"
                                min="0"
                                value="0.00" required>
                            <div class="invalid-feedback">
                                Inserire un prezzo valido
                            </div>
                        </div>
                        <input type="hidden" name="operation" value="add">
                    <button class="w-100 btn invia btn-lg text-white" type="submit">Invia</button>
                    </div>

                </form>
            </div>
        </div>
    </main>

    <?php

    require 'inc/footer.php';
    ?>