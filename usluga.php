<?php
include 'baza_podataka.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
                </div>  
                <nav>
                <ul>
                    <li><a href="index.php">Početna</a></li>
                    <li><a href="usluga.php">Usluga</a></li>
                    <li><a href="o_autoru.html">O autoru</a></li>
                    <li><a href="prijava_dizajn.php">Prijava</a></li>
                </ul>
                </nav>
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
                        <a href="usluga.php?<?php echo "naziv=".$row["naziv"]."&slika=".$row["slika"]."&zvuk=".$row["zvuk"]."&tecaj=".$row["tecaj"];?>">
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



        <div class="footer">
            <p> Kontakt: 095/456-4563</p>
            <p>E-mail: mjenjacnicaharon@yopmail.com</p>
        </div>

    </body>
</html>
