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
            <img class="mb-2" src="assets/img/logo_agnelli.png" alt="" width="72" height="47">
            <h1 class="mb-3">Partynsieme</h1>
            <h5>Prenotazione</h5>
            <p><a href="index.php"><-- Cassa</a></p>
            <div class="col-12 my-2">
                <div class="col-12 mb-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" placeholder="mail@mail.it" required>
                    <div class="invalid-feedback">
                        Inserire una email valida
                    </div>
                </div>
                <h6 class="border-top pt-3">Ordine</h6>

                <div id="orderContainer">
                    <div class="row mb-3 order-row">
                        <div class="col-5">
                            <label for="country" class="form-label">Cibo</label>
                            <select class="form-select product-select" name="items[0][product_id]" required>
                                <option value="">---</option>
                                <?php foreach ($list as $item): ?>
                                    <option
                                        value="<?= $item['id'] ?>"
                                        data-price="<?= $item['price'] ?>">
                                        <?= htmlspecialchars($item['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-3">
                            <label for="address" class="form-label">Quantità</label>
                            <input
                                type="number"
                                class="form-control"
                                name="items[0][qty]"
                                min="1"
                                value="1"
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


                </div>
            </div>
            <input type="hidden" name="operation" value="add">
            <button class="w-50 btn btn-success mb-4" type="button" id="more">+ Aggiungi</button>
            <div class="row border-top mt-3 p-2">
                <p><strong>Prezzo totale:</strong>
                    <span id="grandTotal"> €</span>
                </p>
            </div>
            <button class="w-100 btn btn-lg btn-primary" type="submit">Invia</button>
            <?php
            require 'inc/footer.php';
            ?>

        </form>
    </main>

    <script>
        const container = document.getElementById('orderContainer');
        const addButton = document.getElementById('more');

        let index = 1;

        addButton.addEventListener('click', function() {

            const firstRow = container.querySelector('.order-row');
            const newRow = firstRow.cloneNode(true);

            // reset select
            const select = newRow.querySelector('select');
            select.name = `items[${index}][product_id]`;
            select.selectedIndex = 0;

            // reset qty
            const qty = newRow.querySelector('input');
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

        const firstRow = document.querySelector('.order-row');
        updatePrice(firstRow);
    </script>

</body>

</html>