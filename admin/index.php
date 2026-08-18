<?php

require 'inc/header.php';
require_once '../vendor/autoload.php';

use App\PlaceRepository;
use App\OrderRepository;

$orders = new OrderRepository();

$place = new PlaceRepository();
$place_list = $place->findAll();

?>

<body>

    <main>

        <?php
        require 'inc/navbar.php';
        ?>

        <div>
            <div class="container px-4 py-5" id="featured-3">
                <?php
                require "inc/alert.php";
                ?>

                <h2 class="pb-2 border-bottom">Dashboard</h2>
                <div class="row py-5">
                    <div class="feature col">
                        <div class="feature-icon bg-gradient">
                            <i class="fa fa-calendar-check"></i>
                        </div>
                        <a href="allBooking.php" class="icon-link">
                            <h2>Prenotazioni</h2>
                        </a>
                    </div>
                    <div class="feature col">
                        <div class="feature-icon bg-gradient">
                            <i class="fa fa-user"></i>
                        </div>
                        <a href="allAccounts.php" class="icon-link">
                            <h2>Utenti</h2>
                        </a>
                    </div>
                    <div class="feature col">
                        <div class="feature-icon bg-gradient">
                            <i class="fa fa-cutlery"></i>
                        </div>
                        <a href="allFood.php" class="icon-link">
                            <h2>Tipi di bevande</h2>
                        </a>
                    </div>
                    <div class="feature col">
                        <div class="feature-icon bg-gradient">
                            <i class="fa fa-house"></i>
                        </div>
                        <a href="allPlaces.php" class="icon-link">
                            <h2>Ambienti</h2>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width:10%">Ambiente</th>
                                <th style="width:30%">Prenotazioni</th>
                                <th style="width:30%">Soldi da raccogliere / totali</th>
                            </tr>
                        </thead>
                        <?php
                        $total_orders = 0;
                        $total_orders_bill = 0;
                        $total_orders_paid = 0;

                        foreach ($place_list as $p) {
                            // conto gli ordini per ambiente
                            $place_id = $p['id'];
                            $place_order = $orders->findBy(['place_id' => $place_id]);
                            $place_order_count = count($place_order);
                            $place_order_bill_total = 0;
                            $place_order_bill_paid = 0;
                            foreach ($place_order as $item) {

                                // ordini totali
                                $total_orders++;

                                // soldi totali dell'ambiente
                                $place_order_bill_total += $item['bill'];

                                // soldi totali
                                $total_orders_bill += $item['bill'];

                                if ($item['paid'] == 1) {
                                    $place_order_bill_paid += $item['bill'];
                                    $total_orders_paid += $item['bill'];
                                }
                            }
                            $color_paid = 'danger';
                            if ($place_order_bill_paid == $place_order_bill_total && $place_order_bill_total != 0) {
                                $color_paid = 'success';
                            }

                        ?>
                            <tr>
                                <td><?= $p['name'] ?></td>
                                <td><?= $place_order_count ?></td>
                                <td><span class="text-<?= $color_paid ?>"><?= $place_order_bill_paid ?> € / <?= $place_order_bill_total ?> €</span></td>

                            </tr>
                        <?php
                        }
                        ?>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <?php
                $color_paid = 'danger';
                if ($total_orders_paid == $total_orders_bill && $total_orders_bill != 0) {
                    $color_paid = 'success';
                }
                ?>
                <div class="row text-left">
                    <div class="col-6">
                        <b>Numero totale di ordini:</b> <?= $total_orders ?>
                    </div>
                    <div class="col-6">
                        <b>Totale soldi da raccogliere / totali:</b> <span class="text-<?= $color_paid ?>"><?= $total_orders_paid ?> € / <?= $total_orders_bill ?> €</span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php

    require 'inc/footer.php';
    ?>