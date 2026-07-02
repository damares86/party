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
    $letters = range('A', 'Z');

    // cycle the products
    foreach ($_POST['items'] as $item) {

        // get the last order number and increment it
        $last = $orders->findLast();
        $new_code_number = substr($last['code'], 0, -1) + 1;
        // random letter for the code
        $letter = $letters[random_int(0, count($letters) - 1)];

        // generate the new order code
        $code = $new_code_number . $letter;

        $new_order = $orders->insert([
            'email' => $email,
            'code' => $code,
            'products_id' => $item['product_id'],
            'qty' => $item['qty']
        ]);
    }
    
    header("Location: ../booking.php?msg=bookingAddOk");
    exit;
} else if ($operation == "edit") {
}












exit;
