<?php

declare(strict_types=1);

require 'inc/header.php';

session_start();

use App\ProductRepository;
use App\OrderRepository;

$products = new ProductRepository();
$orders   = new OrderRepository();

$searched     = false;
$order        = null;
$codes        = [];
$error        = '';
$order_number = '';
$email        = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $searched     = true;
    $order_number = (string) filter_input(INPUT_POST, 'order_number', FILTER_VALIDATE_INT);
    $email        = trim((string) filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));

    if ($order_number === '' || $order_number === '0' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Inserisci un numero d\'ordine e un\'email validi.';
    } else {
        // 1) Cerco l'ordine con numero + email (come in payment.php)
        $orders->table = 'orders';
        $found = $orders->findBy([
            'order_number' => (int) $order_number,
            'email'        => $email,
        ]);

        if (empty($found)) {
            // Messaggio generico: non svela se esiste il numero o l'email
            $error = 'Nessun ordine trovato con questi dati.';
        } else {
            $order = $found[0];

            // 2) Se è pagato, recupero i codici da orders_details tramite orders_id
            if ((int) $order['paid'] === 1) {
                $orders->table = 'orders_details';
                $details = $orders->findBy(['orders_id' => $order['id']]) ?: [];

                // Prima i PIA, poi i BEV
                foreach (['PIA', 'BEV'] as $productCode) {
                    foreach ($details as $row) {
                        if ($row['product_code'] !== $productCode) {
                            continue;
                        }

                        // Es. PIA-003K / BEV-003K
                        $code = sprintf('%s-%03d%s', $row['product_code'], (int) $order['order_number'], $row['letter']);

                        $drinks = [];
                        if ($productCode === 'BEV') {
                            foreach (explode(',', (string) $row['products_id']) as $pid) {
                                $pid = (int) trim($pid);
                                if ($pid > 0) {
                                    $drink = $products->findById($pid);
                                    if ($drink) {
                                        $drinks[] = $drink['name'];
                                    }
                                }
                            }
                        }

                        $codes[] = [
                            'code'   => $code,
                            'type'   => $productCode,
                            'qty'    => (int) $row['qty'],
                            'drinks' => $drinks,
                            'used'   => (int) $row['used'] === 1,
                        ];
                    }
                }
            }
        }
    }
}

$pagename = 'checkOrder';
?>

<body class="text-center">

    <main class="form-signin">

        <form action="checkOrder.php" method="POST">

            <img class="mb-4" src="assets/img/logo_agnelli.png" alt="" width="72" height="57">
            <h1 class="mb-3">Partyinsieme</h1>

            <?php require 'inc/navbar.php'; ?>

            <h5 class="mt-3">Controlla il tuo ordine</h5>

            <div class="col-12 my-3">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?= htmlspecialchars($email) ?>" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="order_number" class="form-label">Numero d'ordine</label>
                        <input type="number" class="form-control" id="order_number" name="order_number"
                            value="<?= htmlspecialchars($order_number) ?>" min="1" required>
                    </div>
                </div>
                <button class="mt-2 w-100 btn btn-lg text-white" type="submit">Cerca</button>
            </div>

        </form>

        <?php if ($searched && $error !== '') { ?>
            <div class="my-3 p-3 bg-danger text-white">
                <b><?= htmlspecialchars($error) ?></b>
            </div>
        <?php } ?>

        <?php if ($order !== null) { ?>

            <?php if ((int) $order['paid'] === 1) { ?>

                <div class="my-3 p-3 bg-success text-white">
                    <b>Ordine n. <?= (int) $order['order_number'] ?> pagato</b>
                </div>

                <?php if (empty($codes)) { ?>
                    <p>Nessun codice associato a questo ordine.</p>
                <?php } else { ?>
                    <h5 class="mt-4">I tuoi codici</h5>

                    <?php foreach ($codes as $item) { ?>
                        <div class="my-3 p-3 border">
                            <p class="mb-1 fs-4"><b><?= htmlspecialchars($item['code']) ?></b></p>

                            <?php if ($item['type'] === 'PIA') { ?>
                                <p class="mb-0">Piatti: <?= $item['qty'] ?></p>
                            <?php } else { ?>
                                <p class="mb-0"><?= htmlspecialchars(implode(', ', $item['drinks'])) ?></p>
                            <?php } ?>

                            <?php if ($item['used']) { ?>
                                <span class="badge bg-secondary mt-2">Già utilizzato</span>
                            <?php } ?>
                        </div>
                    <?php } ?>
                <?php } ?>

            <?php } else { ?>

                <div class="my-3 p-3 bg-warning">
                    <b>Ordine n. <?= (int) $order['order_number'] ?>: pagamento non ancora registrato</b>
                </div>
                <p>I codici saranno visibili dopo il pagamento.</p>
                <a href="payment.php">Vai alla pagina di pagamento</a>

            <?php } ?>

        <?php } ?>

        <?php require 'inc/footer.php'; ?>

    </main>

</body>

</html>