<?php

declare(strict_types=1);


use App\ProductRepository;

require_once __DIR__ . '/../vendor/autoload.php';   // If installed via composer

$debug = new \bdk\Debug(array(
    'collect' => true,
    'output' => true,
));

$products = new ProductRepository();

if(filter_input(INPUT_GET,'idToDel')){
    $id = filter_input(INPUT_GET,'idToDel');
    if($products->delete($id)){
        header("Location: ../admin/allFood.php?msg=foodDelete");
        exit;
    }else{
        header("Location: ../admin/allFood.php?err=foodNoDelete");
        exit;
    }
}

$operation = filter_input(INPUT_POST, 'operation');

if($operation == 'add'){
    $name = filter_input(INPUT_POST,'name');
    $code = filter_input(INPUT_POST,'code');
    $price = filter_input(INPUT_POST,'price');

    if($products->insert([
        'name' => $name,
        'code' => $code,
        'price' => $price
    ])){
        header("Location: ../admin/allFood.php?msg=foodAdd");
        exit;
    }else{
        header("Location: ../admin/allFood.php?err=foodNoAdd");
        exit;
    }
}else if($operation == 'edit'){
    $idToMod = filter_input(INPUT_POST,'idToMod');
    $name = filter_input(INPUT_POST,'name');
    $code = filter_input(INPUT_POST,'code');
    $price = filter_input(INPUT_POST,'price');

    if($products->update($idToMod,[
        'name' => $name,
        'code' => $code,
        'price' => $price
    ])){
        header("Location: ../admin/allFood.php?msg=foodEdit");
        exit;
    }else{
        header("Location: ../admin/allFood.php?err=foodNoEdit");
        exit;
    }

}
