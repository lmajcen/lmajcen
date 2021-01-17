<?php
include 'baza_podataka.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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

        <section id="Galerija">
        <div class="container">
                <?php
                    $db = mysqli_select_db($spojka,'iwa_2019_vz_projekt');
                    $upit= "SELECT * FROM valuta";
                    $query_run = mysqli_query($spojka,$upit);
                    while($row = mysqli_fetch_array($query_run))
                    {
                        ?>
                        <div class="box">
                        <a href="vrijednosti_valuta.php?<?php echo "naziv=".$row["naziv"]."&slika=".$row["slika"]."&zvuk=".$row["zvuk"]."&tecaj=".$row["tecaj"];?>">
                        <img src="<?php echo $row['slika']. "\" height=\"100\" width=\"170\"" ?>"/>
                        </a>
                        <?php
                        $ime = $row['naziv'];
                        echo "<p> Valuta: $ime </p>";
                        echo "</div>";
                        echo "<br>";   
                    }
                    
                ?> 
        </div>
        </section>
        <?php 
            if (isset($_GET['naziv'])) {
                $naziv = $_GET["naziv"];
                $slika = $_GET["slika"];
                $zvuk = $_GET["zvuk"];
                $tecaj = $_GET["tecaj"];
                
                
                if (isset($zvuk) && !empty($zvuk)) 
                    echo '<div class="container mb-150">
                        <h3>'.$naziv.'</h3>
                        <img src="'.$slika.'" alt="zastava države">
                            <audio controls> 
                                <source src="'.$zvuk.'" type="audio/ogg">
                            </audio>
                        <hr>
                        <p>Tečaj: <b>',$tecaj.'</b> HRK</p>
                    </div>';

                else
                echo '<div class="container mb-150">
                    <h3>'.$naziv.'</h3>
                    <img src="'.$slika.'" alt="zastava države">
                    <hr>
                    <p>Tečaj: <b>',$tecaj.'</b> HRK</p>
                </div>';
            }
        ?>
        </div>
    </body>
</html>