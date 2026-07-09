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
            <h3 style="background-color:#ff0000;color:#fff;">ATTENZIONE: la prenotazione non sarà valida fino a quando non verrà saldato l\'importo. <u>Non sarà possibile pagare la sera stessa</u>, l\'importo dovrà essere saldato nei giorni precedenti.
            </h3>
            
            <h2 style="margin-top:0;">
            Dati prenotazione <u>' . $new_order_number . '</u>
            </h2>
            <ul>';
    foreach ($order_products as $list_item) {
        $output .= '<li style="margin:1em auto;">' . $list_item['qty'] . 'x ' . $list_item['product_name'] . ' -> cod. <strong>' . $list_item['product_code'] . '-' . $new_order_number . $list_item['product_letter'] . '</strong> - ' . $list_item['product_price'] . '€</li>';
    }

    $output .= '</ul>
        <hr>
        <p>Prezzo totale da pagare: <strong>' . $total_price . '€</strong></p>
        <hr>
        <p>In caso di errori presenti nell\'ordine, contattare <a href="mailto:economo@agnelli.it">economo@agnelli.it</a></p>
        </div>
        </body>
        </html>
        ';

    print_r($output);
    exit;

    //////////////////////////////////////////
    // invio email
    //////////////////////////////////////////


} else if ($operation == "edit") {
    // modifica delle prenotazioni
} else if ($operation == "book") {
    // gestione delle prenotazioni con used 0/1
} else if ($operation == "payment") {
    // gestione dei pagamenti con paid 0/1
}












exit;
