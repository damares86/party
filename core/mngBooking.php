<?php

declare(strict_types=1);


use App\ProductRepository;
use App\OrderRepository;

require_once __DIR__ . '/../vendor/autoload.php';   // If installed via composer


$products = new ProductRepository();
$orders = new OrderRepository();

if (filter_input(INPUT_GET, 'idToDel')) {
    $id = filter_input(INPUT_GET, 'idToDel');
    if ($orders->delete($id)) {
        header("Location: ../admin/allBooking.php?msg=bookingDelete");
        exit;
    } else {
        header("Location: ../admin/allBooking.php?err=bookingNoDelete");
        exit;
    }
}

$operation = filter_input(INPUT_POST, "operation");

if ($operation == "add") {

    // get email
    $email = filter_input(INPUT_POST, 'email');

    // range for the random letter

    $last = $orders->findLast();
    $new_order_number = $last['order_number'] + 1;

    // IF PLACES ARE NOT USED, COMMENT THIS CODE
    $place = filter_input(INPUT_POST, 'place');
    $packages = count($_POST['items']);
    $bill = $packages * 5;

    // inserire ordine nella tabella 'orders'
    if (!$orders->insert([
        'email' => $email,
        'place_id' => $place,
        'order_number' => $new_order_number,
        'qty' => $packages,
        'bill' => $bill
    ])) {
        header("Location: ../index.php?err=errAddBooking");
        exit;
    }


    // get the inserted order id
    $order_inserted = $orders->findLast();
    $order_inserted_id = $order_inserted['id'];

    // create an array with all the products, for the email
    $order_products = [];

    // inserisco i cibi
    $orders->table = 'orders_details';
    $letters = range('A', 'Z');

    $pia_letter = $letters[random_int(0, count($letters) - 1)];
    // salsiccie
    if (!$orders->insert([
        'orders_id' => $order_inserted_id,
        'product_code' => 'PIA',
        'letter' => $pia_letter,
        'qty' => $packages,
        'products_id' => 0
    ])) {
        header("Location: ../index.php?err=errAddProductBooking");
        exit;
    }


    // ciclo le bevande
    $bev_arr = [];
    foreach ($_POST['items'] as $item) {
        // creo un arraiy con gli id delle bevande
        $bev_arr[] = $item['product_id'];
        /*         $new_order_detail = $orders->insert([
            'orders_id' => $order_inserted_id,
            'product_code' => 'BEV',
            'letter' => $bev_letter,
            'qty' => 1,
            'products_id' => $item['product_id']
            ]);
            */

        // recupero nome bevanda per la mail di riepilogo
        $prod_stmt = $products->findById($item['product_id']);
        $product_name = $prod_stmt['name'];

        $order_products[] = array(
            'product_name' => $product_name
        );
    }

    $bev_str = implode(',', $bev_arr);
    $bev_letter = $letters[random_int(0, count($letters) - 1)];
    if (!$orders->insert([
        'orders_id' => $order_inserted_id,
        'product_code' => 'BEV',
        'letter' => $bev_letter,
        'products_id' => $bev_str
    ])) {
        header("Location: ../index.php?err=errAddProductBooking");
        exit;
    }

    $error = 0;

    // email send with the order data
    $from = 'noreply3@istitutoagnelli.it';

    $subject = "Riepilogo prenotazione $new_order_number per Partyinsieme";

    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: {$from}\r\n";
    $headers .= "Reply-To: {$from}\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    $output = '
        <html>
            <head>
                <meta charset="utf-8">
            </head>
            <body style="font-family:Arial,Helvetica,sans-serif;background:#f5f5f5;padding:30px;">

                <div style="max-width:100%;margin:auto;background:#ffffff;padding:30px;border-radius:8px;">
                    <h2 style="text-align:center">Partynsieme - Agnelli</h2>
                    <h3 style="background-color:#ff0000;color:#fff;">ATTENZIONE: la prenotazione non sarà valida fino a quando non verrà saldato l\'importo. <u>Non sarà possibile pagare la sera stessa</u>, l\'importo dovrà essere saldato nei giorni precedenti.<br>
                    Per pagare sarà necessario comunicare la <strong>mail usata per la prenotazione e il numero d\'ordine</strong>.
                    </h3>
                    
                    <h2 style="margin-top:0;">
                    Numero prenotazione: <u>' . $new_order_number . '</u>
                    </h2>
                    <ul>';
    foreach ($order_products as $list_item) {
        $output .= '    <li style="margin:1em auto;">1 pacchetto con <strong>' . $list_item['product_name'] . '</strong> </li>';
    }

    $output .= '    </ul>
                    <hr>
                    <p>Totale pacchetti: <strong>' . $packages . '</strong></p>
                    <p>Prezzo totale da pagare: <strong>' . $bill . '€</strong></p>
                    <hr>
                    <p>In caso di errori presenti nell\'ordine, contattare <a href="mailto:economo@agnelli.it">economo@agnelli.it</a></p>
                </div>
            </body>
        </html>
        ';

    if (!mail($email, $subject, $output, $headers)) {
        $error++;
    }

    if ($error > 0) {
        header("Location: ../index.php?err=errSendMail");
        exit;
    } else {
        header("Location: ../index.php?msg=mailSend");
        exit;
    }
} else if ($operation == "edit") {

    $idToMod = filter_input(
        INPUT_POST,
        'idToMod',
        FILTER_VALIDATE_INT
    );

    if (!$idToMod) {
        die('ID ordine non valido');
    }


    /*
 * Recuperiamo gli items
 */
    $items = $_POST['items'] ?? [];

    if (empty($items)) {
        die('L\'ordine deve contenere almeno un pacchetto');
    }


    /*
 * Dati ordine
 */
    $email = filter_input(
        INPUT_POST,
        'email',
        FILTER_VALIDATE_EMAIL
    );

    $placeId = filter_input(
        INPUT_POST,
        'place',
        FILTER_VALIDATE_INT
    );

    $paid = isset($_POST['paid']) ? 1 : 0;


    /*
 * Dati pacchetti
 */

    // Numero di pacchetti
    $qty = count($items);

    // Prezzo fisso del pacchetto
    $packagePrice = 5;

    // Totale ordine
    $bill = $qty * $packagePrice;


    /*
 * Recuperiamo gli ID delle bevande
 *
 * Esempio:
 *
 * [
 *     2,
 *     8,
 *     2,
 *     8
 * ]
 *
 * diventa:
 *
 * "2,8,2,8"
 */
    $productsIds = array_column($items, 'product_id');

    $productsString = implode(',', $productsIds);


    $orders = new OrderRepository();


    try {

        /*
     * Inizio transazione
     */
        $orders->beginTransaction();


        /*
     * ==========================================
     * ORDERS_DETAILS - PIA
     * ==========================================
     *
     * Aggiorniamo il numero di pacchetti.
     */
        $orders->table = 'orders_details';

        $orders->updateByConditions(
            [
                'orders_id' => $idToMod,
                'product_code' => 'PIA'
            ],
            [
                'qty' => $qty
            ]
        );


        /*
     * ==========================================
     * ORDERS_DETAILS - BEV
     * ==========================================
     *
     * Aggiorniamo la lista delle bevande.
     */
        $orders->updateByConditions(
            [
                'orders_id' => $idToMod,
                'product_code' => 'BEV'
            ],
            [
                'products_id' => $productsString
            ]
        );


        /*
     * ==========================================
     * ORDERS
     * ==========================================
     *
     * Aggiorniamo i dati principali dell'ordine.
     */
        $orders->table = 'orders';

        $orders->update(
            $idToMod,
            [
                'email' => $email,
                'place_id' => $placeId,
                'qty' => $qty,
                'bill' => $bill,
                'paid' => $paid
            ]
        );


        /*
     * ==========================================
     * CONFERMA
     * ==========================================
     */
        $orders->commit();


        /*
     * Redirect
     */
        header(
            'Location: ../admin/allBooking.php?msg=bookingEditOk'
        );

        exit;
    } catch (Throwable $e) {

        $orders->rollBack();

        header(
            'Location: ../admin/allBooking.php?err=bookingEditFail'
        );

        exit;
    }
} else if ($operation == "book") {

    $order_id = filter_input(INPUT_POST, 'idToUse');
    $orders->table = "orders_details";

    if ($orders->update($order_id, ['used' => 1])) {
        header("Location: ../manage.php?msg=bookSucc");
        exit;
    } else {
        header("Location: ../manage.php?err=bookErr");
        exit;
    }
} else if ($operation == "check") {
    // controllo prenotazione da usare
    $order_number = filter_input(INPUT_POST, 'number');
    $order_check = $orders->findBy([
        'order_number' => $order_number
    ]);


    $id = $order_check[0]['id'];
    $code = filter_input(INPUT_POST, 'product_code');
    $letter = filter_input(INPUT_POST, 'letter');
    $orders->table = "orders_details";
    $prod_check = $orders->findBy([
        'orders_id' => $id,
        'product_code' => $code,
        'letter' => $letter
    ]);

    $msg = '';

    if (count($prod_check) == 0) {
        $msg = 'err=noOrder';
    } else if ($prod_check[0]['used'] == 0) {
        $msg = 'msg=orderToUse&email=' . $order_check[0]['email'] . '&order_number=' . $order_check[0]['order_number'] . '&id=' . $order_check[0]['id'] . '&code=' . $code . '&letter=' . $letter . '';
    } else if ($prod_check[0]['used'] == 1) {
        $msg = 'err=orderUsed&email=' . $order_check[0]['email'] . '&order_number=' . $order_check[0]['order_number'] . '&id=' . $order_check[0]['id'];
    }

    header("Location: ../manage.php?$msg");
    exit;
} else if ($operation == "search") {
    // ricerca prenotazione da pagare

    $email = filter_input(INPUT_POST, 'email');
    $order_number = filter_input(INPUT_POST, 'number');
    $order_check = $orders->findBy([
        'order_number' => $order_number,
        'email' => $email
    ]);

    $msg = '';

    if (count($order_check) == 0) {
        $msg = 'err=noOrder';
    } else if ($order_check[0]['paid'] == 0) {
        $msg = 'msg=orderToPay&email=' . $order_check[0]['email'] . '&order_number=' . $order_check[0]['order_number'] . '&id=' . $order_check[0]['id'];
    } else if ($order_check[0]['paid'] == 1) {
        $msg = 'msg=orderPaid&email=' . $order_check[0]['email'] . '&order_number=' . $order_check[0]['order_number'] . '&id=' . $order_check[0]['id'];
    }

    header("Location: ../payment.php?$msg");
    exit;
} else if ($operation == "pay") {
    // gestione dei pagamenti con paid 0/1
    $id = filter_input(INPUT_POST, 'id');

    if ($orders->update($id, ['paid' => 1])) {

        ////////////////////////////////////////////////
        ///    RECUPERO INFORMAZIONI ORDINE
        ////////////////////////////////////////////////

        $order_paid = $orders->findById($id);
        $order_number = $order_paid['order_number'];
        $email = $order_paid['email'];
        $qty = $order_paid['qty'];

        $orders->table = 'orders_details';
        $pia_products = $orders->findBy(['orders_id' => $id, 'product_code' => 'PIA']);

        $bev_products = $orders->findBy(['orders_id' => $id, 'product_code' => 'BEV']);

        ////////////////////////////////////////////////
        ///    INVIO MAIL DI CONFERMA PAGAMENTO
        ////////////////////////////////////////////////

        $error = 0;

        // email send with the order data
        $from = 'noreply3@istitutoagnelli.it';

        $subject = "Conferma pagamento prenotazione $order_number per Partyinsieme";

        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: {$from}\r\n";
        $headers .= "Reply-To: {$from}\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        $output = '
        <html>
            <head>
                <meta charset="utf-8">
            </head>
            <body style="font-family:Arial,Helvetica,sans-serif;background:#f5f5f5;padding:30px;">

                <div style="max-width:100%;margin:auto;background:#ffffff;padding:30px;border-radius:8px;">
                    <h2 style="text-align:center">Partynsieme - Agnelli</h2>
                    <h3 style="background-color:#00ff00;color:#000; padding:0.5em;">Prenotazione pagata</h3>
                    
                    <h2 style="margin-top:0;">
                    Dati prenotazione <u>' . $order_number . '</u>
                    </h2>
                    <ul>
                    <li style="margin:1em auto;">' . $qty . ' piatti  -> cod. <strong>PIA-' . $order_number . $pia_products[0]['letter'] . '</strong></li>
                    <li style="margin:1em auto;">' . $qty . ' bibite -> cod. <strong>BEV-' . $order_number . $bev_products[0]['letter'] . '</strong></li>
                    <ul>';
        $bev_arr = explode(',', $bev_products[0]['products_id']);
        foreach ($bev_arr as $list_item) {

            $product_data = $products->findById($list_item);

            $output .= '    <li style="margin:1em auto;">1 ' . $product_data['name'] . '</strong></li>';
        }

        $output .= '        </ul>
                        </ul>
                    <hr>
                    <p>In caso di errori presenti nell\'ordine, contattare <a href="mailto:economo@agnelli.it">economo@agnelli.it</a></p>
                </div>
            </body>
        </html>
        ';

        if (!mail($email, $subject, $output, $headers)) {
            $error++;
        }

        if ($error > 0) {
            header("Location: ../payment.php?msg=paidSucc&err=errPaySendMail");
            exit;
        } else {
            header("Location: ../payment.php?msg=paidSucc");
            exit;
        }
    } else {
        header("Location: ../payment.php?err=paidErr");
        exit;
    }
}

exit;
