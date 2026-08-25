<?php

declare(strict_types=1);
require 'inc/header.php';

require_once '../vendor/autoload.php';

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

                <h2 class="pb-2 border-bottom">Reset dati </h2>
                <div class="col-12 my-2 text-center">
                    <div class="col-12 mb-4 bg-danger text-white py-5">
                        <h1>ATTENZIONE!</h1>
                        <h3>Se si preme sul tasto "Reset" <strong><u>tutti i dati verranno cancellati definitivamente</u></strong> e non potranno più essere recuperati.</h3>
                        <h3>Cliccare sul tasto solo se si è veramente sicuri.</h3>
                    </div>
                    <a href="#" class="btn btn-danger w-25 text-center btn invia btn-lg text-white" data-bs-toggle="modal" data-bs-target="#danger">Reset</a>


                </div>

            </div>
        </div>
    </main>
    <!--Danger theme Modal -->
    <div class="modal fade text-left" id="danger" tabindex="-1" role="dialog" aria-labelledby="myModalLabel120" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <form action="../core/mngProducts.php" method="POST">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title white" id="myModalLabel120">
                            Sei sicuro?
                        </h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <i data-feather="x"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        Se clicchi su 'Conferma' <strong><u> tutti i dati verranno cancellati definitivamente.</u></strong>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Indietro</span>
                        </button>
                        <span class="d-none d-sm-block"><a href="../core/mngReset.php?op=reset" class="btn btn-danger ml-1">
                                Conferma
                            </a></span>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php

    require 'inc/footer.php';
    ?>