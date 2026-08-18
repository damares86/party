<?php

declare(strict_types=1);


use App\ProductRepository;

require_once __DIR__ . '/../vendor/autoload.php';   // If installed via composer


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

    if($products->insert([
        'name' => $name
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

    if($products->update($idToMod,[
        'name' => $name
    ])){
        header("Location: ../admin/allFood.php?msg=foodEdit");
        exit;
    }else{
        header("Location: ../admin/allFood.php?err=foodNoEdit");
        exit;
    }

}
