<?php

declare(strict_types=1);
require 'inc/header.php';

require_once '../vendor/autoload.php';

use App\OrderRepository;
use App\ProductRepository;
use App\PlaceRepository;

$products = new ProductRepository();
$list = $products->findAll();

$orders = new OrderRepository();

$place = new PlaceRepository();
$place_list = $place->findAll();

$idToMod = filter_input(INPUT_GET, 'id');

$orderToMod = $orders->findById($idToMod);

$orders->table = 'orders_details';
$order_bev = $orders->findBy(['orders_id' => $idToMod, 'product_code' => 'BEV']);
$bev_arr = explode(',', $order_bev[0]['products_id']);

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

                <h2 class="pb-2 border-bottom">Modifica prenotazione numero: <u><?= $orderToMod['order_number'] ?></u></h2>

                <form action="../core/mngBooking.php" method="POST">

                    <div class="col-12 my-2">
                        <div class="col-12 mb-4">
                            <label for="email" class="form-label">Ambiente</label>
                            <select class="form-select product-select" name="place" required>
                                <option value="">---</option>
                                <?php
                                $selected = '';
                                foreach ($place_list as $p) {
                                    if ($p['id'] == $orderToMod['place_id']) {
                                        $selected = 'selected';
                                    }
                                ?>

                                    <option value="<?= $p['id'] ?>" <?= $selected ?>><?= $p['name'] ?></option>

                                <?php
                                }
                                $selected = '';
                                ?>
                            </select>
                            <div class="invalid-feedback">
                                Selezionare almeno una scelta
                            </div>
                        </div>
                        <div class="col-12 mb-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="mail@mail.it" value="<?= $orderToMod['email'] ?>" required>
                            <div class="invalid-feedback">
                                Inserire una email valida
                            </div>
                        </div>
                        <h6 class="border-top pt-3">Ordine</h6>

                        <div id="orderContainer">
                            <?php
                            $i = 0;
                            foreach ($bev_arr as $ord) {

                            ?>
                                <div class="row mb-3 order-row">

                                    <input
                                        type="hidden"
                                        name="items[<?= $i ?>][detail_id]"
                                        value="<?= $ord ?>">

                                    <div class="col-4 pt-4">
                                        <p class="product-price">1 pacchetto</p>
                                    </div>
                                    <div class="col-7">
                                        <label for="country" class="form-label">Bevanda</label>
                                        <select class="form-select product-select" name="items[<?= $i ?>][product_id]" required>

                                            <?php
                                            $selected = '';
                                            foreach ($list as $item) {
                                                if ($ord == $item['id']) {
                                                    $selected = 'selected';
                                                }
                                            ?>
                                                <option
                                                    value="<?= $item['id'] ?>"
                                                    <?= $selected ?>>
                                                    <?= htmlspecialchars($item['name']) ?>
                                                </option>
                                            <?php
                                                $selected = '';
                                            }
                                            ?>
                                        </select>
                                    </div>


                                    <div class="col-1 pt-4">
                                        <button type="button" class="btn btn-danger btn-sm remove-row">
                                            X
                                        </button>
                                    </div>
                                </div>
                            <?php
                                $i++;
                            }
                            ?>

                        </div>
                    </div>
                    <input type="hidden" name="operation" value="edit">
                    <input type="hidden" name="idToMod" value="<?= $idToMod ?>">
                    <button class="btn btn-success mb-4" type="button" id="more">+ Aggiungi</button>
                    <div class="row border-top mt-3 p-2">
                        <p><strong>Prezzo totale:</strong>
                            <span id="grandTotal"> €</span>
                        </p>
                        <div class="form-check form-switch">
                            <label class="form-check-label" for="flexSwitchCheckDefault">Pagato</label>
                            <?php
                            $checked = '';
                            if ($orderToMod['paid'] == 1) {
                                $checked = 'checked';
                            }
                            ?>
                            <input class="form-check-input" type="checkbox" role="switch" name="paid" id="flexSwitchCheckDefault" <?= $checked ?>>
                        </div>
                    </div>
                    <button class="w-100 btn btn-success btn-lg text-white my-3" type="submit">Invia</button>


                </form>
                <p></p>
            </div>
        </div>
    </main>

    <script>
        const container = document.getElementById('orderContainer');
        const addButton = document.getElementById('more');

        const PACKAGE_PRICE = 5;

        let index = <?= count($bev_arr) ?>;


        // ============================
        // AGGIUNTA PACCHETTO
        // ============================

        addButton.addEventListener('click', function() {

            const firstRow = container.querySelector('.order-row');
            const newRow = firstRow.cloneNode(true);

            // nuova select
            const select = newRow.querySelector('.product-select');

            select.name = `items[${index}][product_id]`;
            select.selectedIndex = 0;

            // nuova riga: nessun detail_id
            const detailId = newRow.querySelector('input[name$="[detail_id]"]');

            if (detailId) {
                detailId.name = `items[${index}][detail_id]`;
                detailId.value = '';
            }

            // mostra il pulsante elimina
            const deleteButton = newRow.querySelector('.remove-row');
            deleteButton.classList.remove('d-none');

            container.appendChild(newRow);

            index++;

            updateSummary();
        });


        // ============================
        // ELIMINAZIONE PACCHETTO
        // ============================

        document.addEventListener('click', function(e) {

            if (!e.target.classList.contains('remove-row')) {
                return;
            }

            const row = e.target.closest('.order-row');

            if (!row) {
                return;
            }

            // impedisce di eliminare l'ultimo pacchetto
            const rows = container.querySelectorAll('.order-row');

            if (rows.length <= 1) {
                alert('Deve rimanere almeno un pacchetto.');
                return;
            }

            row.remove();

            updateSummary();
        });


        // ============================
        // RIEPILOGO
        // ============================

        function updateSummary() {

            const packageCount =
                container.querySelectorAll('.order-row').length;

            const total = packageCount * PACKAGE_PRICE;

            document.getElementById('grandTotal').textContent =
                total.toFixed(2);
        }


        // inizializzazione
        updateSummary();
    </script>

    <?php

    require 'inc/footer.php';
    ?>