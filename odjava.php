<?php 
    session_start();

    if (session_id() != "") {

        session_unset();
        session_destroy();
    }
    else echo"sesija ne postoji!";
    header("Location: prijava_dizajn.php");
?>