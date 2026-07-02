<?php

require_once __DIR__ . '/../vendor/autoload.php';   // If installed via composer
$debug = new \bdk\Debug(array(
    'collect' => true,
    'output' => true,
));
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <title>Partynsieme</title>

    <!-- <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sign-in/"> -->



    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Favicons -->

    <link rel="manifest" href="assets/img/manifest.json">
    <link rel="icon" href="assets/img/favicons/favicon.ico">
    <meta name="theme-color" content="#AC1819">

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>


    <!-- Custom styles for this template -->
    <link href="assets/css/signin.css" rel="stylesheet">
</head>