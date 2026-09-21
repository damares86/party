<?php

declare(strict_types=1);


require_once __DIR__ . '/../vendor/autoload.php';   // If installed via composer

use App\PlaceRepository;
$places = new PlaceRepository();

if(filter_input(INPUT_GET,'idToDel')){
    $id = filter_input(INPUT_GET,'idToDel');
    if($places->delete($id)){
        header("Location: ../admin/allPlaces.php?msg=placeDelete");
        exit;
    }else{
        header("Location: ../admin/allPlaces.php?err=placeNoDelete");
        exit;
    }
}

$operation = filter_input(INPUT_POST, 'operation');

if($operation == 'add'){
    $name = filter_input(INPUT_POST,'name');

    if($places->insert([
        'name' => $name
    ])){
        header("Location: ../admin/allPlaces.php?msg=placeAdd");
        exit;
    }else{
        header("Location: ../admin/allPlaces.php?err=placeNoAdd");
        exit;
    }
}else if($operation == 'edit'){
    $idToMod = filter_input(INPUT_POST,'idToMod');
    $name = filter_input(INPUT_POST,'name');

    if($places->update($idToMod,[
        'name' => $name
    ])){
        header("Location: ../admin/allPlaces.php?msg=placeEdit");
        exit;
    }else{
        header("Location: ../admin/allPlaces.php?err=placeNoEdit");
        exit;
    }

}
