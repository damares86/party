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

    $sal_letter = $letters[random_int(0, count($letters) - 1)];
    // salsiccie
    if (!$orders->insert([
        'orders_id' => $order_inserted_id,
        'product_code' => 'SAL',
        'letter' => $sal_letter,
        'qty' => $packages,
        'products_id' => 0
    ])) {
        header("Location: ../index.php?err=errAddProductBooking");
        exit;
    }

    $pat_letter = $letters[random_int(0, count($letters) - 1)];
    // patatine
    if (!$orders->insert([
        'orders_id' => $order_inserted_id,
        'product_code' => 'PAT',
        'letter' => $pat_letter,
        'qty' => $packages,
        'products_id' => 0
    ])) {
        header("Location: ../index.php?err=errAddProductBooking");
        exit;
    }

    // ciclo le bevande
    foreach ($_POST['items'] as $item) {
        $bev_letter = $letters[random_int(0, count($letters) - 1)];

        $new_order_detail = $orders->insert([
            'orders_id' => $order_inserted_id,
            'product_code' => 'BEV',
            'letter' => $bev_letter,
            'qty' => 1,
            'products_id' => $item['product_id']
        ]);

        $prod_stmt = $products->findById($item['product_id']);
        $product_name = $prod_stmt['name'];

        $order_products[] = array(
            'product_name' => $product_name
        );
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
    print_r($output);
    exit;
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

    $idToMod = filter_input(INPUT_POST, 'idToMod', FILTER_VALIDATE_INT);

    if (!$idToMod) {
        die('ID ordine non valido');
    }

    $items = $_POST['items'] ?? [];

    $orders = new OrderRepository();

    // La tabella dei dettagli
    $orders->table = 'orders_details';

    try {

        // Inizio transazione
        $orders->beginTransaction();

        /*
     * 1. Recuperiamo i dettagli attualmente presenti
     *    per questo ordine
     */
        $existingDetails = $orders->findBy([
            'orders_id' => $idToMod
        ]);

        /*
     * Creiamo un array con gli ID presenti nel POST.
     *
     * Nel tuo esempio:
     *
     * 51
     * 52
     * nuovo -> nessun ID
     */
        $postedDetailIds = [];

        foreach ($items as $item) {

            if (!empty($item['detail_id'])) {
                $postedDetailIds[] = (int)$item['detail_id'];
            }
        }


        /*
     * 2. ELIMINAZIONI
     *
     * Se un dettaglio esiste nel DB ma il suo ID
     * non è più presente nel POST, significa che
     * l'utente lo ha eliminato.
     */
        foreach ($existingDetails as $detail) {

            $detailId = (int)$detail['id'];

            if (!in_array($detailId, $postedDetailIds, true)) {

                $orders->delete($detailId);
            }
        }


        /*
     * 3. INSERT / UPDATE
     */
        foreach ($items as $item) {

            $productId = filter_var(
                $item['product_id'] ?? null,
                FILTER_VALIDATE_INT
            );

            if (!$productId) {
                throw new RuntimeException(
                    'Prodotto non valido'
                );
            }


            /*
         * DETAIL ID PRESENTE
         *
         * È un record già esistente → UPDATE
         */
            if (!empty($item['detail_id'])) {

                $detailId = (int)$item['detail_id'];

                $orders->update($detailId, [
                    'products_id' => $productId
                ]);
            } else {

                /*
             * DETAIL ID ASSENTE
             *
             * È una nuova riga → INSERT
             */
                $orders->insert([
                    'orders_id' => $idToMod,
                    'products_id' => $productId
                ]);
            }
        }


        /*
     * 4. Aggiornamento dell'ordine principale
     *
     * Qui puoi aggiornare email, ambiente, ecc.
     */

        $orders->table = 'orders';

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

        $orders->update($idToMod, [
            'email' => $email,
            'place_id' => $placeId
        ]);
        // Aggiorna l'ordine principale
        $orders->table = 'orders';

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

        // Ogni pacchetto costa 5 €
        $packagePrice = 5;

        // Il numero di pacchetti corrisponde al numero
        // di elementi presenti nel POST
        $bill = count($items) * $packagePrice;

        $paid = $_POST['paid'] ? 1 : 0;

        $orders->update($idToMod, [
            'email'    => $email,
            'place_id' => $placeId,
            'bill'     => $bill,
            'paid' => $paid
        ]);

        /*
     * Se arriviamo qui senza errori,
     * confermiamo tutte le modifiche.
     */
        $orders->commit();


        header(
            'Location: ../admin/allBooking.php?msg=bookingEditOk'
        );

        exit;
    } catch (Throwable $e) {

        /*
     * Qualsiasi errore annulla TUTTE le modifiche.
     */
        $orders->rollBack();

        die('Errore durante la modifica dell\'ordine: '
            . $e->getMessage());
    }
} else if ($operation == "book") {

    $order_number = filter_input(INPUT_POST, 'number');
    $order_to_check = $orders->findBy(['order_number' => $order_number]);

    if ($order_to_check[0]['paid'] == 0) {
        header("Location: ../index.php?err=noPaid");
        exit;
    }

    if ($order_to_check[0]['used'] == 1) {
        header("Location: ../index.php?err=used");
        exit;
    }

    $id = $order_to_check[0]['id'];
    if ($orders->update($id, ['used' => 1])) {
        header("Location: ../manage.php?msg=bookSucc");
        exit;
    } else {
        header("Location: ../manage.php?err=bookErr");
        exit;
    }
} else if ($operation == "check") {
    // ricerca prenotazione da pagare

    $email = filter_input(INPUT_POST, 'email');
    $order_number = filter_input(INPUT_POST, 'number');
    $order_check = $orders->findBy([
        'order_number' => $order_number,
        'email' => $email
    ]);

    $msg = '';

    if (count($order_check) == 0) {
        $msg = 'msg=noOrder';
    } else if ($order_check[0]['used'] == 0) {
        $msg = 'msg=orderToUse&email=' . $order_check[0]['email'] . '&order_number=' . $order_check[0]['order_number'] . '&id=' . $order_check[0]['id'];
    } else if ($order_check[0]['used'] == 1) {
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
        $sal_products = $orders->findBy(['orders_id' => $id,'product_code'=>'SAL']);

        $pat_products = $orders->findBy(['orders_id' => $id,'product_code'=>'PAT']);
        $bev_products = $orders->findBy(['orders_id' => $id,'product_code'=>'BEV']);

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
                    <li style="margin:1em auto;">'.$qty.' piatti salsiccia -> cod. <strong>SAL-'.$order_number.$sal_products[0]['letter'].'</strong></li>
                    <li style="margin:1em auto;">'.$qty.' piatti patatine -> cod. <strong>PAT-'.$order_number.$pat_products[0]['letter'].'</strong></li>';
        foreach ($bev_products as $list_item) {

            $product_data = $products->findById($list_item['products_id']);

            $output .= '    <li style="margin:1em auto;">1 ' . $product_data['name'] . ' -> cod. <strong>BEV-' . $order_number . $list_item['letter'] . '</strong></li>';
        }

        $output .= '    </ul>
                    <hr>
                    <p>In caso di errori presenti nell\'ordine, contattare <a href="mailto:economo@agnelli.it">economo@agnelli.it</a></p>
                </div>
            </body>
        </html>
        ';
        print_r($output);
        exit;

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
