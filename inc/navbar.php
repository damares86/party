<?php

$active_index = '';
$active_payment = '';
$active_booking = '';
$link_index = 'index.php';
$link_payment = 'payment.php';
$link_booking = 'booking.php';

if ($pagename == 'index') {
    $active_index = 'active';
    $link_index = '#';
} else if ($pagename == 'payment') {
    $active_payment = 'active';
    $link_payment = '#';
} else if ($pagename == 'booking') {
    $active_booking = 'active';
    $link_booking = '#';
}

?>

<div class="container">
    <header class="d-flex justify-content-center py-3">
        <ul class="nav nav-pills">
            <li class="nav-item"><a href="<?= $link_index ?>" class="nav-link <?= $active_index ?>">Cassa</a></li>
            <li class="nav-item"><a href="<?= $link_payment ?>" class="nav-link <?= $active_payment ?>">Pagamento</a></li>
            <li class="nav-item"><a href="<?= $link_booking ?>" class="nav-link <?= $active_booking ?>">Prenota</a></li>
        </ul>
    </header>
</div>