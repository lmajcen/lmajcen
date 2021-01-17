<?php
    include("baza_podataka.php");
    session_start();
    if (isset($_SESSION['uloga'])) {
        if ($_SESSION['uloga'] == 2 || $_SESSION['uloga'] == 1 || $_SESSION['uloga'] == 0) {
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

        <section id="KupiProdajAzuriraj">
        <div class="DodajValutu">
                    <h1>Odaberi valutu i dodaj neki iznos!</h1>
                    <form name="DodajValutu" class="add-amount-form" method="POST">
                        <input type="number" placeholder="Unesi iznos!" step="any" name="iznos" >
                        <select name="valutaId">
                            <?php 
                                $upit = "SELECT valuta_id, naziv from valuta";
                                $rezultat = mysqli_query($spojka, $upit);
                                while ($valuta = mysqli_fetch_array($rezultat))
                                   echo '<option value="'.$valuta[0].'">'.$valuta[1].'</option>';
                            ?>
                        </select>
                        <p class="message"></p>
                        <input type="submit" value="Dodaj!" name="submit-amount" class="submit-amount"></input>
                        <?php 
                            if (isset($_POST["submit-amount"])) {
                                $valuta = $_POST["valutaId"];
                                $dodajIznos = $_POST["iznos"];

                                $provjera = "SELECT sredstva_id FROM sredstva WHERE korisnik_id = ".$korisnikId." AND valuta_id = ".$valuta;
                                $rezultat = mysqli_query($spojka, $provjera);

                                if (mysqli_num_rows($rezultat) == 0) {
                                    $insert = "INSERT INTO `sredstva`(`sredstva_id`, `korisnik_id`, `valuta_id`, `iznos`) VALUES (NULL, $korisnikId, $valuta, $dodajIznos)";
                                    $rezultat = mysqli_query($spojka, $insert);
                                    
                                }
                                else{
                                    $upit = "SELECT iznos FROM sredstva WHERE korisnik_id =".$korisnikId." AND valuta_id = ".$valuta;
                                    $rezultat = mysqli_query($spojka, $upit);
                                    $iznos = mysqli_fetch_array($rezultat);
                                    $trenutniIznos = $iznos[0];
                                    $noviIznos = $trenutniIznos + $dodajIznos;
                                    $update = "UPDATE sredstva SET iznos=".$noviIznos." WHERE korisnik_id = ".$korisnikId." AND valuta_id = ".$valuta;
                                    $rezultat = mysqli_query($spojka, $update);
                                }
                            }
                        ?>
                    </form>
        </div>
        <div class="AzurirajValutu">
                    <h1>Odaberi valutu i azuriraj iznos!</h1>
                    <form name="AzurirajValutu" class="add-amount-form" method="POST">
                        <input type="number" placeholder="Unesi iznos!"  step="any" name="iznosChange" >
                        <select name="valutaIdChange">
                            <?php 
                                $upit = "SELECT valuta_id, naziv from valuta";
                                $rezultat = mysqli_query($spojka, $upit);
                                while ($valuta = mysqli_fetch_array($rezultat))
                                   echo '<option value="'.$valuta[0].'">'.$valuta[1].'</option>';
                            ?>
                        </select>
                        <p class="message"></p>
                        <input type="submit" value="Azuriraj!" name="submit-amount-change" class="submit-amount"></input>
                        <?php 
                            if (isset($_POST["submit-amount-change"])) {
                                $valuta = $_POST["valutaIdChange"];
                                $dodajIznos = $_POST["iznosChange"];

                                $provjera = "SELECT sredstva_id FROM sredstva WHERE korisnik_id = ".$korisnikId." AND valuta_id = ".$valuta;
                                $rezultat = mysqli_query($spojka, $provjera);
                                   
                                $update = "UPDATE sredstva SET iznos=".$dodajIznos." WHERE korisnik_id = ".$korisnikId." AND valuta_id = ".$valuta;
                                $rezultat = mysqli_query($spojka, $update);
                                echo "Uspješno ažurirano!";
                            }
                        ?>
                    </form>
        </div>
        <div class="ProdajValutu">
                    <h1>Odaberi valutu koju želiš prodati i iznos!</h1>
                    <form name="ProdajValutu" class="add-amount-form" method="POST" action="">
                        <input type="number" placeholder="Unesi iznos!"  step="any" name="iznosProdaja">
                        <select name="valutaIz">
                        <?php 
                                $upit = "SELECT s.valuta_id, v.naziv FROM sredstva s, valuta v WHERE s.valuta_id=v.valuta_id AND korisnik_id=$korisnikId";
                                $rezultat = mysqli_query($spojka, $upit);
                                while ($valuta = mysqli_fetch_array($rezultat))
                                   echo '<option value="'.$valuta[0].'">'.$valuta[1].'</option>';
                            ?> 
                        </select>
                        <select name="valutaU">
                        <?php 
                                $upit = "SELECT valuta_id, naziv from valuta";
                                $rezultat = mysqli_query($spojka, $upit);
                                while ($valuta = mysqli_fetch_array($rezultat))
                                   echo '<option value="'.$valuta[0].'">'.$valuta[1].'</option>';
                            ?> 
                        </select>
                        <p class="message"></p>
                        <input type="submit" name="submit-amount-prodaja" value="Prodaj!" class="submit-amount"></input>
                        <?php 
                            if (isset($_POST["submit-amount-prodaja"])) {
                                $valutaIz = $_POST["valutaIz"];
                                $valutaU = $_POST["valutaU"];
                                $prodajaIznos = $_POST["iznosProdaja"];

                                $provjeraIz = "SELECT iznos FROM sredstva WHERE korisnik_id = ".$korisnikId." AND valuta_id = ".$valutaIz;
                                $provjeraU = "SELECT sredstva_id FROM sredstva WHERE korisnik_id = ".$korisnikId." AND valuta_id = ".$valutaU;
                                $rezultatIz = mysqli_query($spojka, $provjeraIz);
                                $fetch = mysqli_fetch_array($rezultatIz);
                                $iznosIz = $fetch[0];
                                $rezultatU = mysqli_query($spojka, $provjeraU);

                                $boolValutaIz = true;
                                $boolValutaU = true;

                                if ($prodajaIznos > $iznosIz) {
                                    echo "Nemate dovoljan iznos na računu!";
                                    $boolValutaIz = false;
                                }
                                
                                if ($boolValutaIz && $boolValutaU && ($valutaIz != $valutaU)) {
                                    $insert = "INSERT INTO `zahtjev`(`zahtjev_id`, `korisnik_id`, `iznos`, `prodajem_valuta_id`, `kupujem_valuta_id`, `datum_vrijeme_kreiranja`, `prihvacen`) VALUES (NULL, $korisnikId, $prodajaIznos, $valutaIz, $valutaU, NOW(), '2')";
                                    $rezultat = mysqli_query($spojka, $insert);
                                    echo "Zahtjev uspješno poslan!";
                                }
                               
                                else{
                                    echo "Odaberite različite valute!";
                                }
                            }
                        ?>
                    </form>
        </div>
        </section>


        <section id="PrikazSredstava">
        <div class="PrikazSredstava">
                    <h1>Prikaz raspoloživih sredstava u određenim valutama!</h1>
                    <table>
                        <thead>
                            <tr>
                                <th>Vrsta valute</th>
                                <th>iznos valute</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                            $upit = "SELECT valuta.naziv, sredstva.iznos from valuta JOIN sredstva WHERE valuta.valuta_id = sredstva.valuta_id AND sredstva.korisnik_id = '".$korisnikId."'";
                            $rezultat = mysqli_query($spojka, $upit);
                            while ($valute = mysqli_fetch_array($rezultat)) {
                                ?>
                        
                            <tr>
                                <td><?php echo $valute[0]; ?></td>
                                <td><?php echo $valute[1]; ?></td>
                            </tr>
                        <?php   
                            }
                            ?>
                        </tbody>
                        
                    </table>
        </div>
        </section>

        <section id="Status">                   
        <div class="container">
        <h1>Status zahtjeva</h1>
                <form action="" method="POST" enctype="multipart/from-data">
                            <table>
                            <thead>
                            <tr>
                                <th> Iznos </th>
                                <th> ID Prodajne valute </th>
                                <th> ID Kupovne valute </th>
                                <th> Status </th>
                            </tr>
                            </thead>
                <?php
                   $upit = "SELECT 
                   (SELECT naziv FROM valuta WHERE valuta_id=prodajem_valuta_id) as prodajnaValuta,
                   (SELECT naziv FROM valuta WHERE valuta_id=kupujem_valuta_id) as kupovnaValuta,
                   z.iznos, z.prihvacen FROM zahtjev z WHERE z.korisnik_id = $korisnikId";
                   $rezultat = mysqli_query($spojka,$upit);
                   while($row = mysqli_fetch_array($rezultat))
                   {
                       ?>
                       <tr>
                            <td> <?php echo $row [2]; ?> </td>
                            <td> <?php 
                            
                            echo $row [0]; ?> </td>
                            <td> <?php echo $row [1]; ?> </td>
                            <td> <?php
                            if ($row[3] == 2) {
                                echo "U procesu!";
                             }
                            if ($row[3] == 1) {
                                echo "Prihvaćeno!";
                            }
                            if ($row[3] == 0) {
                                echo "Odbijeno!";
                            }
                                ?> </td>
                            
                        </tr>
                        <?php
                   } 
                ?> 
                </table>
                </form>
        </div>
        </section>

    </body>
</html>