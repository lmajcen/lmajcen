<?php
    
    include("baza_podataka.php");
    
    $odluka = $_POST["odluka"];
    $zahtjevId = $_POST["zahtjevId"];
    $korisnikId = $_POST["korisnikId"];

    if ($odluka == 1) {
        $zahtjevId = ltrim($zahtjevId,"zahtjevDa-");
    }
    else{
        $zahtjevId = ltrim($zahtjevId,"zahtjevNe-");
    }

    $upit = "SELECT prodajem_valuta_id, iznos, kupujem_valuta_id, korisnik_id FROM zahtjev where zahtjev_id = $zahtjevId";
    $rezultat = mysqli_query($spojka, $upit);
    $valutaId = mysqli_fetch_array($rezultat);
    $prodajnaValutaId = $valutaId[0];
    $kupovnaValutaId = $valutaId[2];
    $prodajnaValutaIznos = $valutaId[1];
    $kupac_id = $valutaId[3];

    $upit = "SELECT aktivno_od, aktivno_do, tecaj FROM valuta WHERE valuta_id = $prodajnaValutaId";
    $rezultat = mysqli_query($spojka, $upit);
    $vrijeme = mysqli_fetch_array($rezultat);
    $aktivnoOd = $vrijeme[0];
    $aktivnoDo = $vrijeme[1];
    $prodajnaValutaTecaj = $vrijeme[2];

    $upit = "SELECT tecaj FROM valuta WHERE valuta_id = $kupovnaValutaId";
    $rezultat = mysqli_query($spojka, $upit);
    $vrijeme = mysqli_fetch_array($rezultat);
    $kupovnaValutaTecaj = $vrijeme[0];

    $trenutnoVrijeme = date("H:i:s");

    if ($trenutnoVrijeme > $aktivnoOd && $trenutnoVrijeme < $aktivnoDo) {
        
        
        $update = "UPDATE `zahtjev` SET `prihvacen`= $odluka WHERE zahtjev_id = $zahtjevId";
        $rezultat = mysqli_query($spojka, $update);
        if ($odluka == 1) {
            echo "Odobreno!";
            $upit = "SELECT iznos FROM sredstva WHERE korisnik_id = $kupac_id AND valuta_id = $prodajnaValutaId";
            $rezultat = mysqli_query($spojka, $upit);
            $iznos = mysqli_fetch_array($rezultat);
            $stariIznos = $iznos[0];
            $noviIznos = $stariIznos - $prodajnaValutaIznos;
            
            $update = "UPDATE sredstva SET iznos = $noviIznos WHERE korisnik_id = $kupac_id AND valuta_id = $prodajnaValutaId";
            $rezultat = mysqli_query($spojka, $update);

            $upit = "SELECT iznos FROM sredstva WHERE korisnik_id = $kupac_id AND valuta_id = $kupovnaValutaId";
            $rezultat = mysqli_query($spojka, $upit);
            $iznos = mysqli_fetch_array($rezultat);
            $stariIznos = $iznos[0];
            $noviIznos = $stariIznos + round((($prodajnaValutaTecaj/$kupovnaValutaTecaj) * $prodajnaValutaIznos), 2);

            $upit = "SELECT `sredstva_id` FROM `sredstva` WHERE `korisnik_id`=$kupac_id AND `valuta_id`= $kupovnaValutaId";
            $rezultat = mysqli_query($spojka, $upit);
            if (mysqli_num_rows($rezultat) == 0) {
                $insert = "INSERT INTO `sredstva`(`sredstva_id`, `korisnik_id`, `valuta_id`, `iznos`) VALUES (DEFAULT, $kupac_id, $kupovnaValutaId, $noviIznos)";
                $rezultat = mysqli_query($spojka, $insert);
            }
            else {
            $update = "UPDATE sredstva SET iznos = $noviIznos WHERE korisnik_id = $kupac_id AND valuta_id = $kupovnaValutaId";
            $rezultat = mysqli_query($spojka, $update);
            }

        }
        else echo "Odbijeno!";
    }
    else echo "Možete prihvatiti zahtjev samo u vremnu od ". $aktivnoOd." do ".$aktivnoDo." !";

    
?>