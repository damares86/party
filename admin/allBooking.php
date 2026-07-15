<?php

declare(strict_types=1);
require 'inc/header.php';

require_once '../vendor/autoload.php';

use App\OrderRepository;

$orders = new OrderRepository();
$list = $orders->findAll();
?>

<body>

    <main>

        <?php
        require 'inc/navbar.php';
        ?>

        <div>
            <div class="container px-4 py-5" id="featured-3">
                <h2 class="pb-2 border-bottom">Prenotazioni</h2>

                <a href="../booking.php" class="btn btn-success my-3">+ Aggiungi prenotazione</a>

                <div class="mb-5">
                    <label>Pagato</label>
                    <select id="filterPagato" class="form-select w-auto">
                        <option value="">Tutti</option>
                        <option value="Sì">Sì</option>
                        <option value="No">No</option>
                    </select>
                </div>
                <table id="table" class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width:10%">Numero prenotazione</th>
                            <th style="width:30%">Email</th>
                            <th style="width:10%">Totale</th>
                            <th style="width:10%">Pagato</th>
                            <th style="width:20%">Azioni</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        foreach ($list as $item) {
                        ?>
                            <tr>
                                <td><?= $item['order_number'] ?></td>
                                <td><?= $item['email'] ?></td>
                                <td><?= $item['bill'] ?> €</td>
                                <td>
                                    <?php
                                    $paid = $item['paid'] == 0 ? 'No' : 'Sì';
                                    echo $paid;
                                    ?>
                                </td>
                                <td>
                                    <a href="editBooking.php?id=<?= $item['id'] ?>" class="btn btn-warning">Modifica</a>
                                    <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#danger<?= $item['id'] ?>">Elimina
                                    </a>
                                    <!--Danger theme Modal -->
                                    <div class="modal fade text-left" id="danger<?= $item['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel120" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger">
                                                    <h5 class="modal-title white" id="myModalLabel120">
                                                        Sei sicuro?
                                                    </h5>
                                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                        <i data-feather="x"></i>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Se clicchi su 'Conferma' questa prenotazione verrà cancellata definitivamente.
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                                        <i class="bx bx-x d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Indietro</span>
                                                    </button>
                                                    <span class="d-none d-sm-block"><a href="../core/mngBooking.php?idToDel=<?= $item['id'] ?>" class="btn btn-danger ml-1">
                                                            Conferma
                                                        </a></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <?php

    require 'inc/footer.php';
    ?>