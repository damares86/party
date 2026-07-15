<?php

declare(strict_types=1);


use App\ProductRepository;
use App\OrderRepository;

require_once __DIR__ . '/../vendor/autoload.php';   // If installed via composer

$debug = new \bdk\Debug(array(
    'collect' => true,
    'output' => true,
));

$products = new ProductRepository();
$orders = new OrderRepository();

if(filter_input(INPUT_GET,'idToDel')){
    $id = filter_input(INPUT_GET,'idToDel');
    if($orders->delete($id)){
        header("Location: ../admin/allBooking.php?msg=bookingDelete");
        exit;
    }else{
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

    // inserire ordine nella tabella 'orders'
    $new_order = $orders->insert([
        'email' => $email,
        'order_number' => $new_order_number
    ]);

    // get the inserted order id
    $order_inserted = $orders->findLast();
    $order_inserted_id = $order_inserted['id'];

    // create an array with all the products, for the email
    $order_products = [];
    $total_price = 0;
    $letters = range('A', 'Z');

    // cycle the products
    foreach ($_POST['items'] as $item) {


        $letter = $letters[random_int(0, count($letters) - 1)];
        $orders->table = 'orders_details';

        $new_order_detail = $orders->insert([
            'orders_id' => $order_inserted_id,
            'products_id' => $item['product_id'],
            'product_letter' => $letter,
            'qty' => $item['qty']
        ]);

        $prod_stmt = $products->findById($item['product_id']);
        $product_name = $prod_stmt['name'];
        $product_code = $prod_stmt['code'];
        $product_price = $prod_stmt['price'] * $item['qty'];

        $order_products[] = array(
            'product_name' => $product_name,
            'product_code' => $product_code,
            'product_price' => $product_price,
            'product_letter' => $letter,
            'qty' => $item['qty']
        );

        $total_price += $product_price;
    }

    $orders->table = 'orders';
    $orders->update($order_inserted_id, ['bill' => $total_price]);

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

                <div style="max-width:700px;margin:auto;background:#ffffff;padding:30px;border-radius:8px;">
                    <h2 style="text-align:center">Partynsieme - Agnelli</h2>
                    <h3 style="background-color:#ff0000;color:#fff;">ATTENZIONE: la prenotazione non sarà valida fino a quando non verrà saldato l\'importo. <u>Non sarà possibile pagare la sera stessa</u>, l\'importo dovrà essere saldato nei giorni precedenti.<br>
                    Per pagare sarà necessario comunicare la <strong>mail usata per la prenotazione e il numero d\'ordine</strong>.
                    </h3>
                    
                    <h2 style="margin-top:0;">
                    Dati prenotazione <u>' . $new_order_number . '</u>
                    </h2>
                    <ul>';
    foreach ($order_products as $list_item) {
        $output .= '    <li style="margin:1em auto;">' . $list_item['qty'] . 'x ' . $list_item['product_name'] . ' -> cod. <strong>' . $list_item['product_code'] . '-' . $new_order_number . $list_item['product_letter'] . '</strong> - ' . $list_item['product_price'] . '€</li>';
    }

    $output .= '    </ul>
                    <hr>
                    <p>Prezzo totale da pagare: <strong>' . $total_price . '€</strong></p>
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
        header("Location: ../booking.php?err=errSendMail");
        exit;
    } else {
        header("Location: ../booking.php?msg=mailSend");
        exit;
    }
} else if ($operation == "edit") {
    // modifica delle prenotazioni



} else if ($operation == "book") {

    $order_number = filter_input(INPUT_POST, 'number');
    $order_to_check = $orders->findBy(['order_number' => $order_number]);

    if ($order_to_check[0]['paid'] == 0) {
        header("Location: ../index.php?err=noPaid");
        exit;
    }

    $orders_id = $order_to_check[0]['id'];
    $products_id = filter_input(INPUT_POST, 'product_id');
    $product_letter = filter_input(INPUT_POST, 'letter');

    $orders->table = 'orders_details';

    $order_check = $orders->findBy([
        'orders_id' => $orders_id,
        'products_id' => $products_id,
        'product_letter' => $product_letter
    ]);

    if ($order_check[0]['used'] == 1) {
        header("Location: ../index.php?err=used");
        exit;
    }
    $id = $order_check[0]['id'];
    if ($orders->update($id, ['used' => 1])) {
        header("Location: ../index.php?msg=bookSucc");
        exit;
    } else {
        header("Location: ../index.php?err=bookErr");
        exit;
    }
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
        $msg = 'msg=noOrder';
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
        
        $orders->table = 'orders_details';
        $order_products = $orders->findBy(['orders_id' => $id]);


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

                <div style="max-width:700px;margin:auto;background:#ffffff;padding:30px;border-radius:8px;">
                    <h2 style="text-align:center">Partynsieme - Agnelli</h2>
                    <h3 style="background-color:#00ff00;color:#000; padding:0.5em;">Prenotazione pagata</h3>
                    
                    <h2 style="margin-top:0;">
                    Dati prenotazione <u>' . $order_number . '</u>
                    </h2>
                    <ul>';
        foreach ($order_products as $list_item) {
            
            $product_data = $products->findById($list_item['products_id']);

            $output .= '    <li style="margin:1em auto;">' . $list_item['qty'] . 'x ' . $product_data['name'] . ' -> cod. <strong>' . $product_data['code'] . '-' . $order_number . $list_item['product_letter'] . '</strong></li>';
        }

        $output .= '    </ul>
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
            header("Location: ../payment.php?err=errPaySendMail");
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
