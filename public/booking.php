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
            <img class="mb-2" src="assets/img/logo_agnelli.png" alt="" width="72" height="47">
            <h1 class="mb-3">Partynsieme</h1>
            <h5>Prenotazione</h5>
            <div class="col-12 my-2">
                <div class="col-12 mb-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" placeholder="mail@mail.it" required>
                    <div class="invalid-feedback">
                        Inserire una email valida
                    </div>
                </div>
                <h6 class="border-top pt-3">Ordine</h6>

                <div id="orderContainer">
                    <div class="row mb-3 order-row">
                        <div class="col-7">
                            <label for="country" class="form-label">Cibo</label>
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
                        </div>
                        <div class="col-4">
                            <label for="address" class="form-label">Quantità</label>
                            <input
                                type="number"
                                class="form-control"
                                name="items[0][qty]"
                                min="1"
                                value="1"
                                required>
                        </div>
                        <div class="col-1 pt-4">
                            <button type="button" class="btn btn-danger btn-sm remove-row d-none">
                                X
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <button class="w-50 btn btn-lg btn-success mb-4" type="button" id="more">+ Aggiungi</button>

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
            const btn = newRow.querySelector('.remove-row');
            btn.classList.remove('d-none');

            container.appendChild(newRow);

            index++;
        });


        // delete con delega eventi
        document.addEventListener('click', function(e) {

            if (e.target.classList.contains('remove-row')) {
                const row = e.target.closest('.order-row');

                if (row) {
                    row.remove();
                }
            }

        });
    </script>

</body>

</html>