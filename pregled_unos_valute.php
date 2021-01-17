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
        
        <section id="PopisValuta">
        <div class="container">
                <table>
                    <thead>
                        <tr>
                            <th> Naziv </th>
                            <th> Tečaj </th>
                            <th> Moderator </th>
                            <th> Aktivno od </th>
                            <th> Aktivno do </th>
                            <th> Datum ažuriranja </th>
                        </tr>
                    </thead>
                    <?php
                        $upit = "SELECT naziv, tecaj, moderator_id, aktivno_od, aktivno_do, datum_azuriranja FROM valuta";

                        $rezultat = mysqli_query($spojka,$upit);
                        while($row = mysqli_fetch_array($rezultat))
                        {
                            $datumStaro = explode("-", $row[5]);
                            $datumNovo = $datumStaro[2].".".$datumStaro[1].".".$datumStaro[0];
                    ?>
                            <tr>
                                <td> <?php echo $row [0]; ?> </td>
                                <td> <?php echo $row [1]; ?> </td>
                                <td> <?php echo $row [2]; ?> </td>
                                <td> <?php echo $row [3]; ?> </td>
                                <td> <?php echo $row [4]; ?> </td>
                                <td> <?php echo $datumNovo; ?> </td>
                            </tr>
                        <?php
                        } 
                        ?> 
                </table>
        </div>
        </section>

        <section id="UnesiValutu">
            <div class="box">
                <h1> Unesi valutu: </h1>
                <form method="POST">

                    <label for="Naziv">Naziv:</label><br>
                    <input type="text" id="Naziv" placeholder="Unesi!" required name="nazivValute"><br>
                    <label for="Tečaj">Tečaj:</label><br>
                    <input type="text" id="Tečaj" placeholder="Unesi!" required name="tecaj"><br>
                    <label for="Aktivno od">Aktivno od:</label><br>
                    <input type="text" id="AktivnoOd" placeholder="Sati-Minute-Sekunde" required name="aktivnoOd"><br>
                    <label for="Aktivno do">Aktivno do:</label><br>
                    <input type="text" id="AktivnoDo" placeholder="Sati-Minute-Sekunde" required name="aktivnoDo"><br>
                    <label for="Slika:">Slika:</label><br>
                    <input type="text" id="Slika" placeholder="Unesi!" required name="slika"><br>
                    <label for="Zvuk">Zvuk:</label><br>
                    <input type="text" id="Zvuk" placeholder="Unesi!" name="zvuk"><br>
                    <br>

                    <select name="nazivModeratora">
                    <?php
                    $upit = "SELECT korisnicko_ime, korisnik_id FROM korisnik WHERE tip_korisnika_id = 1";
                    $rezultat = mysqli_query($spojka, $upit);
                    while($row = mysqli_fetch_array($rezultat))
                    {
                    ?>
                    <option value="<?php echo $row[1]; ?>"><?php echo $row[0]; ?></option>;
                    <?php
                    }
                    ?>
                    
                    </select>

                    <br>
                    <input type="submit" value="Unesi valutu!" name="submit-add-valuta" class="submit-amount"></input>
                </form>
                <?php 
                    if (isset($_POST["submit-add-valuta"])) {

                        $nazivValute = $_POST["nazivValute"];
                        $tecaj = $_POST["tecaj"];
                        $aktivnoOd = $_POST["aktivnoOd"];
                        $aktivnoDo = $_POST["aktivnoDo"];
                        $slika = $_POST["slika"];
                        $zvuk = $_POST["zvuk"];
                        $nazivModeratora = $_POST["nazivModeratora"];
                        
                        $insert = "INSERT INTO `valuta`(`valuta_id`, `moderator_id`, `naziv`, `tecaj`, `slika`, `zvuk`, `aktivno_od`, `aktivno_do`, `datum_azuriranja`) VALUES (NULL, '$nazivModeratora', '$nazivValute', $tecaj, '$slika', '$zvuk', '$aktivnoOd', '$aktivnoDo', NOW())";
                        $rezultat = mysqli_query($spojka, $insert);
                    }
                ?>
            </div>
        </section>
        <section id="azurirajValutu">
            <div class="box">
                <h1>Ažuriraj valutu</h1>
                <form action="" method="POST">

                    <label for="selectValuta">Odaberite valutu: </label><br>
                    <select id="selectValuta" name="valutaId">
                    <?php
                
                        $upit = "SELECT valuta_id, naziv FROM valuta";
                        $rezultat = mysqli_query($spojka, $upit);
                        while($row = mysqli_fetch_array($rezultat))
                        {
                    ?>
                    <option value="<?php echo $row[0]; ?>"><?php echo $row["naziv"]; ?></option>
                    <?php
                    }
                    ?>
                    </select><br>
                    <input type="submit" value="Odaberite valutu!" name="submitvalutaChoose" class="submit-amount"></input>
                </form>
            <?php
                    
                    if (isset($_POST["submitvalutaChoose"])) {

                        $valutaIdPost = $_POST['valutaId'];
                        $upit = "SELECT 
                        (SELECT CONCAT(ime, ' ', prezime) FROM korisnik WHERE korisnik.korisnik_id=valuta.moderator_id) as fullname,
                        valuta_id, moderator_id, naziv, tecaj, slika, zvuk, aktivno_od, aktivno_do FROM `valuta` WHERE valuta_id = $valutaIdPost";
                        $rezultat = mysqli_query($spojka, $upit);
                        $podatci = mysqli_fetch_array($rezultat);
                        
                                             
                    }
                ?>
                <form action="" method="POST">
                    <label for="Naziv">Naziv:</label><br>
                    <input type="text" id="Naziv" placeholder="Unesi!" required name="nazivValute" value="<?php if(isset($_POST["submitvalutaChoose"])) echo $podatci[3];?>"><br>
                    <label for="Tečaj">Tečaj:</label><br>
                    <input type="text" id="Tečaj" placeholder="Unesi!" required name="tecaj" value="<?php if(isset($_POST["submitvalutaChoose"])) echo $podatci[4];?>"><br>
                    <label for="Aktivno od">Aktivno od:</label><br>
                    <input type="text" id="AktivnoOd" placeholder="Sati-Minute-Sekunde" required name="aktivnoOd" value="<?php if(isset($_POST["submitvalutaChoose"])) echo $podatci[7];?> "><br>
                    <label for="Aktivno do">Aktivno do:</label><br>
                    <input type="text" id="AktivnoDo" placeholder="Sati-Minute-Sekunde" required name="aktivnoDo" value="<?php if(isset($_POST["submitvalutaChoose"])) echo $podatci[8];?> "><br>
                    <label for="Slika:">Slika:</label><br>
                    <input type="text" id="Slika" placeholder="Unesi!" required name="slika" value="<?php if(isset($_POST["submitvalutaChoose"])) echo $podatci[5];?>"><br>
                    <label for="Zvuk">Zvuk:</label><br>
                    <input type="text" id="Zvuk" placeholder="Unesi!" name="zvuk" value="<?php if(isset($_POST["submitvalutaChoose"])) echo $podatci[6];?> "><br>
                    <label for="selectValuta">Odaberite moderatora: </label><br>
                    <input type="text" style="display:none;" name="valutaIdPost" value="<?php echo $valutaIdPost;?>">
                    <select id="selectModerator" name="moderatorId">
                    <?php
                        echo '<option value="'.$podatci[2].'">'.$podatci[0].'</option>';
                        $upit = "SELECT CONCAT(ime, ' ', prezime) as fullname, korisnik_id FROM korisnik WHERE tip_korisnika_id = 1 AND korisnik_id NOT IN ($podatci[2])";
                        $rezultat = mysqli_query($spojka, $upit);
                        while($row = mysqli_fetch_array($rezultat))
                        {
                    ?>
                    <option value="<?php echo $row[1]; ?>"><?php echo $row[0]; ?></option>
                    <?php
                    }
                    ?>
                    </select><br>
                    <br>
                    <input type="submit" value="Ažuriraj valutu!" name="submitValuta" class="submit-amount"></input>
                </form>
                <?php
                    if (isset($_POST["submitValuta"])) {
                        $valutaIdPost = $_POST["valutaIdPost"];
                            $naziv = $_POST["nazivValute"];
                            $tecaj = $_POST["tecaj"];
                            $aktivnoOd = $_POST["aktivnoOd"];
                            $aktivnoDo = $_POST["aktivnoDo"];
                            $slika = $_POST["slika"];
                            $zvuk = $_POST["zvuk"];
                            $moderatorId= $_POST["moderatorId"];
                            $datum = date("Y-m-d");
                            $update = "UPDATE `valuta` SET `moderator_id`=$moderatorId,`naziv`='$naziv',`tecaj`=$tecaj,`slika`='$slika',`zvuk`='$zvuk',`aktivno_od`='$aktivnoOd',`aktivno_do`='$aktivnoDo',`datum_azuriranja`=NOW() WHERE valuta_id = $valutaIdPost";
                            $rezultat = mysqli_query($spojka, $update);
                        
                        
                        
                        
                    }
                ?>
            </div>


        </section>









    
                   
                    