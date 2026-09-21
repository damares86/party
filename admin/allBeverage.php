<?php

declare(strict_types=1);

// Stesso header usato nelle altre pagine admin (adatta il percorso se serve)
require 'inc/header.php';
require_once '../vendor/autoload.php';

use App\OrderRepository;
use App\ProductRepository;

$products = new ProductRepository();
$orders   = new OrderRepository();

// 1) Inizializzo i totali a 0 per TUTTI i prodotti presenti in tabella products
$totals = [];
foreach ($products->findAll() as $product) {
    $totals[(int) $product['id']] = [
        'name'  => $product['name'],
        'total' => 0,
    ];
}

// 2) Prendo solo le righe di orders_details con product_code = BEV
$orders->table = 'orders_details';
$rows = $orders->findBy(['product_code' => 'BEV']);

$unknown = 0; // id presenti negli ordini ma non nella tabella products

foreach ($rows as $row) {
    // 3) Esplodo la stringa "6,10,2,6" in array
    $ids = explode(',', (string) $row['products_id']);

    foreach ($ids as $id) {
        $id = (int) trim($id);

        if ($id <= 0) {
            continue; // salta vuoti e "0"
        }

        if (isset($totals[$id])) {
            $totals[$id]['total']++;
        } else {
            $unknown++;
        }
    }
}

$grandTotal = array_sum(array_column($totals, 'total'));

$pagename = 'beverageTotals';
?>

<body>

    <main>
        <?php require 'inc/navbar.php'; ?>
        <div>
            <div class="container px-4 py-5" id="featured-3">
                <h3 class="mt-4 mb-3">Totale bevande ordinate</h3>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>Prodotto</th>
                                <th class="text-end">Totale ordinato</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($totals as $item) { ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['name']) ?></td>
                                    <td class="text-end"><b><?= $item['total'] ?></b></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Totale bevande</th>
                                <th class="text-end"><?= $grandTotal ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <?php if ($unknown > 0) { ?>
                    <div class="alert alert-warning">
                        Attenzione: <?= $unknown ?> bevande negli ordini hanno un id non presente nella tabella prodotti.
                    </div>
                <?php } ?>

                <?php require 'inc/footer.php'; ?>
            </div>
        </div>
    </main>

</body>

</html>