<?php
    include("baza_podataka.php");
    session_start();
    if (isset($_SESSION['uloga'])) {
        if ($_SESSION['uloga'] == 0) {
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
                </div>
            </div>
        </header>

        <section id="Filter">
        <form method="POST">
            <h3>Filter:</h3>
            <label>Prikaz po moderatoru: </label>
            <select name="filterModerator">
                <?php
            
                    $upit = "SELECT CONCAT(ime, ' ', prezime) AS fullname, korisnik_id FROM korisnik WHERE tip_korisnika_id = 1";
                    $rezultat = mysqli_query($spojka, $upit);
                    while($row = mysqli_fetch_array($rezultat))
                    {
                ?>
                        <option value="<?php echo $row[1]; ?>"><?php echo $row[0]; ?></option>
                <?php
                    }
                ?>
            </select><br>
            <label>od: </label>
            <input type="text" name="od" placeholder="dd.mm.yyyy 00:00:00">
            <label>do: </label>
            <input type="text" name="do" placeholder="dd.mm.yyyy 00:00:00"><br><br>
            <input type="submit" name="filter" value="Filtriraj!">

        </form>
        </section>

        <section id="FilterTablica">
        <table>
            <thead>
                <tr>
                    <th>Naziv valute</th>
                    <th>Moderator</th>
                    <th>Ukupan iznos prodanih valuta</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if (isset($_POST["filter"])) {

                $moderatorId = $_POST["filterModerator"];
                $od = $_POST["od"];
                $do = $_POST["do"];
                
                $explodeOd = explode(" ", $od);
                $datumOd = $explodeOd[0];
                $vrijemeOd = $explodeOd[1];
                $datumExplodeOd = explode(".", $datumOd);
                $datumFinalOd = $datumExplodeOd[2].".".$datumExplodeOd[1].".".$datumExplodeOd[0];
                $datumVrijemeOd = $datumFinalOd." ".$vrijemeOd;

                $explodeDo = explode(" ", $do);
                $datumDo = $explodeDo[0];
                $vrijemeDo = $explodeDo[1];
                $datumExplodeDo = explode(".", $datumDo);
                $datumFinalDo = $datumExplodeDo[2].".".$datumExplodeDo[1].".".$datumExplodeDo[0];
                $datumVrijemeDo = $datumFinalDo." ".$vrijemeDo;


                $upit = "SELECT valuta_id from valuta WHERE moderator_id = $moderatorId";

                $rezultat = mysqli_query($spojka, $upit);
                $valuteString="";
                while ($valute = mysqli_fetch_array($rezultat)) {
                    $valuteString .= $valute[0].", ";
                }
                $valuteString = rtrim($valuteString, ", ");

                $upit = "SELECT 
                (SELECT naziv FROM valuta WHERE valuta_id = prodajem_valuta_id) as naziv, 
                (SELECT Concat(ime, ' ', prezime ) AS fullname FROM korisnik WHERE korisnik_id = $moderatorId) as moderator, 
                SUM(iznos) FROM zahtjev WHERE prihvacen = 1 AND datum_vrijeme_kreiranja BETWEEN '$datumVrijemeOd' AND '$datumVrijemeDo' AND prodajem_valuta_id IN($valuteString) GROUP BY naziv";

                $rezultat = mysqli_query($spojka,$upit);
                while($row = mysqli_fetch_array($rezultat))
                {
                    echo'
                    <tr>
                        <td>'.$row [0].'</td>
                        <td>'.$row[1].'</td>
                        <td>'.$row [2].'</td>
                    </tr>';
                } 
            }
            else{

                $upit = "SELECT 
                        (SELECT naziv FROM valuta WHERE valuta_id=prodajem_valuta_id) as naziv, 
                        (SELECT Concat(ime, ' ', prezime ) AS fullname FROM korisnik, valuta WHERE korisnik_id = moderator_id AND valuta_id=prodajem_valuta_id) as moderator, 
                        SUM(iznos) FROM zahtjev WHERE prihvacen=1 GROUP BY naziv";
    
                $rezultat = mysqli_query($spojka,$upit);
                while($row = mysqli_fetch_array($rezultat))
                {
                    echo'
                    <tr>
                        <td>'.$row [0].'</td>
                        <td>'.$row [1].'</td>
                        <td>'.$row [2].'</td>
                    </tr>';
                } 
                
            }
            ?>
            </tbody>
        </table>
        </section>      
    </body>
</html>