<?php

declare(strict_types=1);
require 'inc/header.php';

require_once '../vendor/autoload.php';

use App\ProductRepository;

$products = new ProductRepository();
$id = filter_input(INPUT_GET,'id');
$prod = $products->findById($id);
?>

<body>

    <main>

        <?php
        require 'inc/navbar.php';
        ?>

        <div>
            <div class="container px-4 py-5" id="featured-3">
                <h2 class="pb-2 border-bottom">Modifica tipo di cibo</h2>
                <form action="../core/mngProducts.php" method="POST">
                    <div class="col-12 my-2">
                        <div class="col-12 mb-4">
                            <label for="name" class="form-label">Tipo di cibo</label>
                            <input type="text" class="form-control" name="name" value="<?= $prod['name'] ?>" required>
                            <div class="invalid-feedback">
                                Inserire un nome di cibo
                            </div>
                        </div>
                        <div class="col-12 mb-4">
                            <label for="name" class="form-label">Codice</label>
                            <input type="text" class="form-control" name="code" value="<?= $prod['code'] ?>" required>
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
                                value="<?= $prod['price'] ?>" required>
                            <div class="invalid-feedback">
                                Inserire un prezzo valido
                            </div>
                        </div>
                        <input type="hidden" name="operation" value="edit">
                        <input type="hidden" name="idToMod" value="<?= $prod['id'] ?>">
                    <button class="w-100 btn invia btn-lg text-white" type="submit">Invia</button>
                    </div>

                </form>
            </div>
        </div>
    </main>

    <?php

    require 'inc/footer.php';
    ?>