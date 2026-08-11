<?php

declare(strict_types=1);

require 'inc/header.php';

use App\ProductRepository;
use App\PlaceRepository;

$products = new ProductRepository();
$list = $products->findAll();

$place = new PlaceRepository();
$place_list = $place->findAll();

$pagename = 'booking';
?>

<body class="text-center">

    <main class="form-signin">
        <form id="bookingForm" action="core/mngBooking.php" method="POST">
            <img class="mb-2" src="assets/img/logo_agnelli.png" alt="" width="72" height="47">
            <h1 class="mb-3">Partyinsieme</h1>
            <?php
            session_start();
            if ($_SESSION['loggedin']) {
                require 'inc/navbar.php';
            }
            ?>
            <h5>Prenotazione</h5>

            <div class="col-12 my-2">
                <div class="col-12 mb-4">
                    <label for="email" class="form-label">Ambiente</label>
                    <select class="form-select product-select" name="place" required>
                        <option value="">---</option>
                        <?php foreach ($place_list as $p): ?>

                            <option value="<?= $p['id'] ?>"><?= $p['name'] ?></option>

                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">
                        Selezionare almeno una scelta
                    </div>
                </div>
                <div class="col-12 mb-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" placeholder="mail@mail.it" required>
                    <div class="invalid-feedback">
                        Inserire una email valida
                    </div>
                </div>
                <h6 class="border-top pt-3">Ordine</h6>
                <p>Ogni pacchetto comprende un piatto di salsiccia e patatine più la bibita. Il costo del singolo pacchetto è di 5€</p>

                <div id="orderContainer" class="border-top">
                    <div class="row mb-3 order-row">
                        <div class="col-4 pt-4">
                            <p class="product-price">1 pacchetto</p>
                        </div>
                        <div class="col-7">
                            <label for="country" class="form-label">Bevanda</label>
                            <select class="form-select product-select" name="items[0][product_id]" required>
                                <option value="">---</option>
                                <?php foreach ($list as $item): ?>
                                    <option
                                        value="<?= $item['id'] ?>">
                                        <?= htmlspecialchars($item['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>


                        <div class="col-1 py-4 px-2">
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
                <p class="mb-1">
                    <strong>Pacchetti:</strong>
                    <span id="packageCount">1</span>
                </p>

                <p class="mb-0">
                    <strong>Prezzo totale:</strong>
                    <span id="grandTotal">5.00</span> €
                </p>
            </div>
            <button class="w-100 btn btn-success btn-lg text-white my-3" type="submit">
                Continua
            </button>
            <div class="modal fade" id="orderSummaryModal" tabindex="-1" aria-labelledby="orderSummaryModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" id="orderSummaryModalLabel">
                                Riepilogo ordine
                            </h5>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Chiudi">
                            </button>
                        </div>

                        <div class="modal-body">

                            <p class="mb-3">
                                <strong>Riepilogo dei pacchetti:</strong>
                            </p>

                            <ul id="orderSummary" class="list-group mb-3">
                            </ul>

                            <div class="border-top pt-3">
                                <p class="mb-1">
                                    <strong>Pacchetti:</strong>
                                    <span id="modalPackageCount">0</span>
                                </p>

                                <p class="mb-0">
                                    <strong>Totale:</strong>
                                    <span id="modalGrandTotal">0.00</span> €
                                </p>
                            </div>

                        </div>

                        <div class="modal-footer">

                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">
                                Indietro
                            </button>

                            <button
                                type="button"
                                class="btn btn-success"
                                id="confirmOrder">
                                Invia
                            </button>

                        </div>

                    </div>
                </div>
            </div>
            <?php
            require 'inc/footer.php';
            ?>

        </form>
    </main>

    <script>
        const container = document.getElementById('orderContainer');
        const addButton = document.getElementById('more');

        const PACKAGE_PRICE = 5;

        let index = 1;

        addButton.addEventListener('click', function() {

            const firstRow = container.querySelector('.order-row');
            const newRow = firstRow.cloneNode(true);

            // aggiorna il nome della select
            const select = newRow.querySelector('.product-select');
            select.name = `items[${index}][product_id]`;
            select.selectedIndex = 0;

            // mostra il bottone elimina
            const btn = newRow.querySelector('.remove-row');
            btn.classList.remove('d-none');

            // aggiunge la nuova riga
            container.appendChild(newRow);

            index++;

            updateSummary();
        });


        // elimina un pacchetto
        document.addEventListener('click', function(e) {

            if (e.target.classList.contains('remove-row')) {

                const row = e.target.closest('.order-row');

                if (row) {
                    row.remove();
                    updateSummary();
                }
            }

        });


        // aggiorna pacchetti e prezzo totale
        function updateSummary() {

            const rows = document.querySelectorAll('.order-row');

            const packageCount = rows.length;
            const total = packageCount * PACKAGE_PRICE;

            document.getElementById('packageCount').textContent = packageCount;
            document.getElementById('grandTotal').textContent = total.toFixed(2);
        }


        // inizializzazione
        updateSummary();

        const form = document.getElementById('bookingForm');

        const modalElement = document.getElementById('orderSummaryModal');
        const orderModal = new bootstrap.Modal(modalElement);

        const orderSummary = document.getElementById('orderSummary');
        const modalPackageCount = document.getElementById('modalPackageCount');
        const modalGrandTotal = document.getElementById('modalGrandTotal');

        const confirmOrder = document.getElementById('confirmOrder');


        // Quando l'utente clicca "Continua"
        form.addEventListener('submit', function(e) {

            // blocca il submit
            e.preventDefault();

            // svuota il riepilogo precedente
            orderSummary.innerHTML = '';

            const rows = document.querySelectorAll('.order-row');

            rows.forEach(function(row, index) {

                const select = row.querySelector('.product-select');

                const selectedOption =
                    select.options[select.selectedIndex];

                const drinkName =
                    selectedOption.textContent.trim();

                const li = document.createElement('li');

                li.className =
                    'list-group-item d-flex justify-content-between align-items-center';

                li.innerHTML = `
            <span>
                <strong>Pacchetto ${index + 1}</strong>
                <br>
                <small>Bevanda: ${drinkName}</small>
            </span>

            <span>
                ${PACKAGE_PRICE.toFixed(2)} €
            </span>
        `;

                orderSummary.appendChild(li);
            });

            const packageCount = rows.length;
            const total = packageCount * PACKAGE_PRICE;

            modalPackageCount.textContent = packageCount;
            modalGrandTotal.textContent = total.toFixed(2);

            // mostra la modale
            orderModal.show();
        });


        // Conferma effettiva dell'ordine
        confirmOrder.addEventListener('click', function() {

            // invia realmente il form
            form.submit();

        });
    </script>

</body>

</html>