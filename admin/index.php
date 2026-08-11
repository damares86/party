<?php

require 'inc/header.php';

?>

<body>

    <main>

        <?php
        require 'inc/navbar.php';
        ?>

        <div>
            <div class="container px-4 py-5" id="featured-3">
                <h2 class="pb-2 border-bottom">Dashboard</h2>
                <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
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
                </div>
            </div>
        </div>
        </div>
    </main>

    <?php

    require 'inc/footer.php';
    ?>