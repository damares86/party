<?php

declare(strict_types=1);
require 'inc/header.php';

require_once '../vendor/autoload.php';

use App\PlaceRepository;
$places = new PlaceRepository();

$id = filter_input(INPUT_GET,'id');
$place = $places->findById($id);
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

                <h2 class="pb-2 border-bottom">Modifica ambiente</h2>
                <form action="../core/mngPlaces.php" method="POST">
                    <div class="col-12 my-2">
                        <div class="col-12 mb-4">
                            <label for="name" class="form-label">Nome ambiente</label>
                            <input type="text" class="form-control" name="name" value="<?= $place['name'] ?>" required>
                            <div class="invalid-feedback">
                                Inserire un nome di ambiente
                            </div>
                        </div>
                        <input type="hidden" name="operation" value="edit">
                        <input type="hidden" name="idToMod" value="<?= $place['id'] ?>">
                    <button class="w-100 btn invia btn-lg text-white" type="submit">Invia</button>
                    </div>

                </form>
            </div>
        </div>
    </main>

    <?php

    require 'inc/footer.php';
    ?>