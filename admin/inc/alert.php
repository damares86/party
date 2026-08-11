<?php

    if(filter_input(INPUT_GET,"msg")){
    ?>
    <div class="alert alert-success alert-dismissible fade show shadow" role="alert">
      <i class="bi bi-check-circle"></i>
      <?php
      $msg = filter_input(INPUT_GET,"msg");
      $alert_label = "msg_$msg";
      echo $$alert_label;
      ?>
      <button class="btn-close" type="button" data-bs-dismiss="alert"
        aria-label="Close"></button>
    </div>
    <?php
    }

    if(filter_input(INPUT_GET,"err")){
      ?>
      <div class="alert alert-danger alert-dismissible fade show shadow" role="alert">
        <i class="bi bi-x-circle"></i>
        <?php
        $err= filter_input(INPUT_GET,"err");
        $alert_label = "err_$err";
        echo $$alert_label;
        ?>
        <button class="btn-close" type="button" data-bs-dismiss="alert"
          aria-label="Close"></button>
      </div>
      <?php
      }
?>