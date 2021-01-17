<?php
session_start();

 include("baza_podataka.php");
    
 $valutaId = $_POST["valutaId"];
 $tecaj = $_POST["tecaj"];

 $upit = "SELECT datum_azuriranja FROM valuta where valuta_id = $valutaId";
 $rezultat = mysqli_query($spojka, $upit);
 $info = mysqli_fetch_array($rezultat);
 $zadnjeAzurirano = $info[0];

 $danas = date("Y-m-d");
if ($_SESSION["uloga"] == 0 || $_SESSION["uloga"] == 1) {
    
    if ($danas != $zadnjeAzurirano) {
        $update = "UPDATE valuta SET tecaj = $tecaj, datum_azuriranja = NOW() where valuta_id = $valutaId";
        $rezultat = mysqli_query($spojka, $update);
        echo "Tecaj uspješno ažuriran";
   }
   else{
       echo "Danas ste već ažurirali tečaj!";
    }
}
else{
    $update = "UPDATE valuta SET tecaj = $tecaj, datum_azuriranja = NOW() where valuta_id = $valutaId";
    $rezultat = mysqli_query($spojka, $update);
    echo "Tecaj uspješno ažuriran";
}

?>