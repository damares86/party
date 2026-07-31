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
$order_products = $orders->findBy(['orders_id' => $idToMod]);
?>

<body>

    <main>

        <?php
        require 'inc/navbar.php';
        ?>

        <div>
            <div class="container px-4 py-5" id="featured-3">
                <h2 class="pb-2 border-bottom">Modifica prenotazione</h2>

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
                            foreach ($order_products as $ord) {

                            ?>
                                <div class="row mb-3 order-row">
                                    <div class="col-5">
                                        <label for="country" class="form-label">Cibo</label>
                                        <select class="form-select product-select" name="items[<?= $i ?>][product_id]" required>

                                            <?php
                                            $selected = '';
                                            foreach ($list as $item) {
                                                if ($ord['products_id'] == $item['id']) {
                                                    $selected = 'selected';
                                                }
                                            ?>
                                                <option
                                                    value="<?= $item['id'] ?>"
                                                    data-price="<?= $item['price'] ?>"
                                                    <?= $selected ?>>
                                                    <?= htmlspecialchars($item['name']) ?>
                                                </option>
                                            <?php
                                                $selected = '';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <label for="address" class="form-label">Quantità</label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            name="items[<?= $i ?>][qty]"
                                            min="0"
                                            value="<?= $ord['qty'] ?>"
                                            required>
                                    </div>
                                    <div class="col-3">
                                        <label for="address" class="form-label">Prezzo</label>
                                        <p class="product-price">0.00 €</p>
                                    </div>

                                    <div class="col-1 pt-4">
                                        <button type="button" class="btn btn-danger btn-sm remove-row d-none">
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
                    <button class="btn btn-success mb-4" type="button" id="more">+ Aggiungi</button>
                    <div class="row border-top mt-3 p-2">
                        <p><strong>Prezzo totale:</strong>
                            <span id="grandTotal"> €</span>
                        </p>
                    </div>
                    <button class="w-100 btn btn-success btn-lg text-white my-3" type="submit">Invia</button>
                    <?php
                    require 'inc/footer.php';
                    ?>

                </form>
                <p></p>
            </div>
        </div>
    </main>

    <script>
        const container = document.getElementById('orderContainer');
        const addButton = document.getElementById('more');

        let index = <?= count($order_products) ?>;

        addButton.addEventListener('click', function() {

            const firstRow = container.querySelector('.order-row');
            const newRow = firstRow.cloneNode(true);

            // reset select
            const select = newRow.querySelector('.product-select');
            select.name = `items[${index}][product_id]`;
            select.selectedIndex = 0;

            // reset qty
            const qty = newRow.querySelector('input[name$="[qty]"]');
            qty.name = `items[${index}][qty]`;
            qty.value = 1;

            // mostra bottone delete nelle nuove righe
            // mostra bottone delete nelle nuove righe
            const btn = newRow.querySelector('.remove-row');
            btn.classList.remove('d-none');

            // reset del prezzo visualizzato
            newRow.querySelector('.product-price').textContent = '0.00 €';

            container.appendChild(newRow);

            // aggiorna il prezzo della nuova riga
            updatePrice(newRow);

            index++;
        });

        document.addEventListener('change', function(e) {

            if (e.target.classList.contains('product-select')) {
                updatePrice(e.target.closest('.order-row'));
            }

        });
        document.addEventListener('input', function(e) {

            if (e.target.name.endsWith('[qty]')) {
                updatePrice(e.target.closest('.order-row'));
            }

        });

        function updatePrice(row) {

            const select = row.querySelector('.product-select');
            const qty = row.querySelector('input[name$="[qty]"]');
            const price = row.querySelector('.product-price');

            const option = select.options[select.selectedIndex];

            if (!option.dataset.price) {
                price.textContent = '0.00 €';
                return;
            }

            const total =
                parseFloat(option.dataset.price) * parseInt(qty.value || 1);

            price.textContent = total.toFixed(2) + ' €';

            updateGrandTotal();
        }

        function updateGrandTotal() {

            let total = 0;

            document.querySelectorAll('.order-row').forEach(function(row) {

                const priceText = row.querySelector('.product-price').textContent;

                total += parseFloat(priceText.replace('€', '').trim()) || 0;

            });

            document.getElementById('grandTotal').textContent = total.toFixed(2);
        }

        // delete con delega eventi
        document.addEventListener('click', function(e) {

            if (e.target.classList.contains('remove-row')) {
                const row = e.target.closest('.order-row');

                if (row) {
                    row.remove();
                    updateGrandTotal();
                }
            }

        });

        document.querySelectorAll('.order-row').forEach(updatePrice);
    </script>

    <?php

    require 'inc/footer.php';
    ?>