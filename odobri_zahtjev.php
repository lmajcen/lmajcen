<?php
    include("baza_podataka.php");
    session_start();
    if (isset($_SESSION['uloga'])) {
        if ($_SESSION['uloga'] == 1 || $_SESSION['uloga'] == 0) {
            $korisnikId = $_SESSION['korisnikId'];
        }
        else header('Location: prijava_dizajn.php');
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mjenjačnica HARON</title>
        <link rel="stylesheet" href="./css/style.css">
    </head>
    <body>
        <header>
            <div class="container">
                <div id= "naslovnica">
                    <h1>Mjenjačnica <span class="highlight">HARON</span></h1>
                    <nav>
                    <ul>
                        <li><a href="o_autoru.html" target="_blank" >O autoru</a></li>
                        <li><a href="odjava.php">Odjava</a></li>
                     </ul>
                    </nav>
                </div>
            </div>

            <div class="container">
                <div id= "MeniRegistriraniKorisnik">
                <nav>
                    <ul>
                        <li><a href="vrijednosti_valuta.php">Vrijednosti valuta</a></li>
                        <li><a href="raspoloziva_sredstva_zahtjevi.php">Raspoloživa sredstva i zahtjevi</a></li>
                        <?php 
                        if ($_SESSION["uloga"] == 0 || $_SESSION["uloga"] == 1) {
                            echo '
                                <li><a href="odobri_zahtjev.php">Odobravanje zahtjeva</a></li>
                            ';
                        }
                        if ($_SESSION["uloga"] == 1) {
                            echo '
                                <li><a href="azuriraj_valute.php">Ažuriranje valute</a></li>
                            ';
                        }
                        if ($_SESSION["uloga"] == 0) {
                            echo '
                                <li><a href="korisnici.php">Korisnici</a></li>
                                <li><a href="pregled_unos_valute.php">Pregled i unos valute</a></li>
                                <li><a href="prodaja.php">Prodaja</a></li>
                            ';
                        }
                            ?>
                     </ul>
                    </nav>
                </div>
            </div>
        </header>
        
        <section id="ZahtjeviKorisnika">
        <div class="container">
                <form action="" method="POST" enctype="multipart/from-data">
                            <table>
                            <thead>
                            <tr>
                                <th> ID Prodajne valute </th>
                                <th> ID Kupovne valute </th>
                                <th> Iznos </th>
                                <th> Status </th>
                            </tr>
                            </thead>
                <?php

                    $upit = "SELECT valuta_id from valuta WHERE moderator_id = $korisnikId";
                    $rezultat = mysqli_query($spojka, $upit);
                    $valuteString="";
                    if (mysqli_num_rows($rezultat)) {
                        while ($valute = mysqli_fetch_array($rezultat)) {
                            $valuteString .= $valute[0].", ";
                        }
                        $valuteString = rtrim($valuteString, ", ");
                        
                        $upit = "SELECT 
                        (SELECT naziv FROM valuta WHERE valuta_id=prodajem_valuta_id) as prodajnaValuta,
                        (SELECT naziv FROM valuta WHERE valuta_id=kupujem_valuta_id) as kupovnaValuta,
                        zahtjev.iznos, zahtjev.zahtjev_id FROM zahtjev Where zahtjev.prihvacen = 2 AND prodajem_valuta_id IN($valuteString)";

                        $rezultat2 = mysqli_query($spojka,$upit);
                        if (mysqli_num_rows($rezultat2)) {
                            while($row = mysqli_fetch_array($rezultat2))
                            {
                            echo'   
                                <tr>
                                    <td>'.$row [0].'</td>
                                    <td>'.$row [1].'</td>
                                    <td>'.$row [2].'</td>
                                    <td><button id="zahtjevDa-'.$row[3].'" class="da" name="da">Prihvati</button>
                                    <button id="zahtjevNe-'.$row[3].'" class="ne" name="ne">Odbij</button></td>
                                </tr>';
                            } 
                        }
                        else echo'   
                        <tr>
                            <td colspan="4">Nema podataka</td>
                        </tr>';
                        
                    }
                    else echo'   
                        <tr>
                            <td colspan="4">Nema podataka</td>
                        </tr>';
                   ?> 
                </table>
                </form>
        </div>
        </section>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
                   <script type="text/javascript">
                        $(".da").click(function(){

                            let zahtjevId = this.id;
                            let da = 1;
                            let korisnikId = <?php echo $korisnikId; ?>;

                            $.ajax({
                                type: "POST",
                                url: "obradaZahtjeva.php",
                                data: {zahtjevId: zahtjevId, odluka: da, korisnikId: korisnikId},
                                success: function(response){
                                    alert(response);
                                    console.log(response);
                                }
                            });
                        });
                        $(".ne").click(function(){

                        let zahtjevId = this.id;
                        let ne = 0;
                        let korisnikId = <?php echo $korisnikId; ?>;
                            $.ajax({
                                type: "POST",
                                url: "obradaZahtjeva.php",
                                data: {zahtjevId: zahtjevId, odluka: ne, korisnikId: korisnikId},
                                success: function(response){
                                    alert(response);
                                    console.log(response);
                                }
                            });
                        });
        </script>
    </body>
</html>