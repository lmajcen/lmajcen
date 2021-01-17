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
        
        <section id="AzurirajKorisnika">
            <div class="box">
                <h1>Ažuriraj korisnika</h1>
                <form action="" method="POST">
                    
                    <label for="selectKorisnik">Odaberite korisnika: </label><br>
                    <select id="selectKorisnik" name="korisnikId">
                    <option value="none">Odaberi...</option>
                    <?php
                
                        $upit = "SELECT korisnik_id, CONCAT(ime, ' ', prezime) AS fullname FROM korisnik";
                        $rezultat = mysqli_query($spojka, $upit);
                        while($row = mysqli_fetch_array($rezultat))
                        {
                    ?>
                            <option value="<?php echo $row[0]; ?>"><?php echo $row[1]; ?></option>
                    <?php
                        }
                    ?>
                    <input type="submit" value="Odaberi" name="submit-choose-user">
                    </select><br>
                </form>
            </div>
            <?php
            
                if (isset($_POST['submit-choose-user'])) {
                    
                    $korisnikIdPost = $_POST['korisnikId'];
                    $upit = "SELECT ime, prezime, korisnicko_ime, lozinka, email, tip_korisnika_id, slika FROM korisnik WHERE korisnik_id = $korisnikIdPost";
                    $rezultat = mysqli_query($spojka, $upit);
                    $podatci = mysqli_fetch_array($rezultat);
                    switch ($podatci[5]) {
                        case '0':
                            $tipKorisnikaSelect = '
                                <option value="0">Administrator</option>
                                <option value="1">Moderator</option>
                                <option value="2">Korisnik</option>
                            ';
                            break;
                        case '1':
                            $tipKorisnikaSelect = '
                                <option value="1">Moderator</option>
                                <option value="0">Administrator</option>
                                <option value="2">Korisnik</option>
                            ';
                            break;
                        case '2':
                            $tipKorisnikaSelect = '
                                <option value="2">Korisnik</option>
                                <option value="0">Administrator</option>
                                <option value="1">Moderator</option>
                            ';
                            break;
                        
                        default:
                            break;
                    }
                    
                }  
                if (isset($_POST["submit-update-user"])) {
                    $korIdUpdate = $_POST['korIdUpdate'];
                    $imeUpdate = $_POST['imeUpdate'];
                    $prezimeUpdate = $_POST['prezimeUpdate'];
                    $korImeUpdate = $_POST['korImeUpdate'];
                    $lozinkaUpdate = $_POST['lozinkaUpdate'];
                    $emailUpdate = $_POST['emailUpdate'];
                    $tipKorisnikaUpdate = $_POST['tipKorisnikaUpdate'];
                    $slikaUpdate = $_POST['slikaUpdate'];
                    $update = "UPDATE `korisnik` SET `tip_korisnika_id`= $tipKorisnikaUpdate, `korisnicko_ime`='$korImeUpdate', `lozinka`= '$lozinkaUpdate', `ime`= '$imeUpdate', `prezime`= '$prezimeUpdate', `email`= '$emailUpdate', `slika`= '$slikaUpdate' WHERE korisnik_id = $korIdUpdate";
                    $rezultat = mysqli_query($spojka, $update);
                    echo "<meta http-equiv='refresh' content='0' />";
                }
            ?>

            <div id="updateKorisnik" class="box">
                <form method="POST">
                    <input type="text" style="display:none;" value="<?php echo $korisnikIdPost;?>" name="korIdUpdate">
                    <label for="Ime">Ime:</label><br>
                    <input type="text" id="Ime" placeholder="Unesi!" name="imeUpdate" required value="<?php if (isset($_POST['submit-choose-user'])) {
                        echo $podatci[0];
                    } 
                        ?>"><br>
                    <label for="Prezime">Prezime:</label><br>
                    <input type="text" id="Prezime" placeholder="Unesi!" name="prezimeUpdate" required value="<?php if (isset($_POST['submit-choose-user'])) {
                        echo $podatci[1];
                    } 
                        ?>"><br>
                    <label for="Korisničko Ime">Korisničko ime:</label><br>
                    <input type="text" id="Korisničko ime" placeholder="Unesi!" name="korImeUpdate" required value="<?php if (isset($_POST['submit-choose-user'])) {
                        echo $podatci[2];
                    } 
                        ?>"><br>
                    <label for="Lozinka">Lozinka:</label><br>
                    <input type="text" id="Lozinka" placeholder="Unesi!" name="lozinkaUpdate" required value="<?php if (isset($_POST['submit-choose-user'])) {
                        echo $podatci[3];
                    } 
                        ?>"><br>
                    <label for="E-mail:">E-mail:</label><br>
                    <input type="text" id="E-mail" placeholder="Unesi!" name="emailUpdate" required value="<?php if (isset($_POST['submit-choose-user'])) {
                        echo $podatci[4];
                    } 
                        ?>"><br>
                    <br>
                    <label for="tipKorisnika">Tip korisnika: </label><br>
                    <select name="tipKorisnikaUpdate">
                    
                        <?php if (isset($_POST['submit-choose-user'])) {
                            echo $tipKorisnikaSelect;
                      }
                            ?>
                    </select><br>
                    <label for="Slika">Slika:</label><br>
                    <input type="text" id="Slika" placeholder="Unesi!" name="slikaUpdate" value="<?php if (isset($_POST['submit-choose-user'])) {
                        echo $podatci[6];
                    } 
                        ?>"><br>
                    <input type="submit" value="Ažuriraj korisnika!" name="submit-update-user" class="submit-amount"></input>
                </form>
            </div>
        </section>

        <section id="DodajKorisnika">
            <div class="box">
                <h1> Unesi korisnika </h1>
                <form action="" method="POST">
                    <label for="Ime">Ime:</label><br>
                    <input type="text" id="Ime" placeholder="Unesi!" name="ime" required><br>
                    <label for="Prezime">Prezime:</label><br>
                    <input type="text" id="Prezime" placeholder="Unesi!" name="prezime" required><br>
                    <label for="Korisničko Ime">Korisničko ime:</label><br>
                    <input type="text" id="Korisničko ime" placeholder="Unesi!" name="korIme" required><br>
                    <label for="Lozinka">Lozinka:</label><br>
                    <input type="text" id="Lozinka" placeholder="Unesi!" name="lozinka" required><br>
                    <label for="E-mail:">E-mail:</label><br>
                    <input type="text" id="E-mail" placeholder="Unesi!" name="email" required><br>
                    <br>

                    <select name="tipKorisnika">
                    <?php
                
                    $upit = "SELECT tip_korisnika_id, naziv FROM tip_korisnika";
                    $rezultat = mysqli_query($spojka, $upit);
                    while($row = mysqli_fetch_array($rezultat))
                    {
                    ?>
                    <option value="<?php echo $row[0]; ?>"><?php echo $row["naziv"]; ?></option>
                    <?php
                    }
                    ?>
                    
                    </select>

                    <br>
                    <label for="Slika">Slika:</label><br>
                    <input type="text" id="Slika" placeholder="Unesi!" name="slika"><br>
                    <input type="submit" value="Dodaj korisnika!" name="submit-add-user" class="submit-amount"></input>
                </form>

                <?php
                    if (isset($_POST["submit-add-user"])) {
                        $ime = $_POST["ime"];
                        $prezime = $_POST["prezime"];
                        $korIme = $_POST["korIme"];
                        $lozinka = $_POST["lozinka"];
                        $email = $_POST["email"];
                        $tipKorisnika = $_POST["tipKorisnika"];
                        $slika = $_POST["slika"];

                        $insert = "INSERT INTO `korisnik`(`korisnik_id`, `tip_korisnika_id`, `korisnicko_ime`, `lozinka`, `ime`, `prezime`, `email`, `slika`) VALUES (NULL, $tipKorisnika, '$korIme', '$lozinka', '$ime', '$prezime', '$email', '$slika')";
                        $rezultat = mysqli_query($spojka, $insert);
                        echo "<meta http-equiv='refresh' content='0' />";
                    }

                    

                    
                ?>
            </div>

        </section>

        <section id="PopisKorisnika">
        <div class="container">
                <table>
                    <thead>
                        <tr>
                            <th> Korisnik ID </th>
                            <th> Slika </th>
                            <th> Username </th>
                            <th> Ime </th>
                            <th> Prezime </th>
                            <th> E-mail </th>
                            <th> Tip korisnika </th>
                        </tr>
                    </thead>
                    <?php
                        $upit = "SELECT korisnik_id, slika, korisnicko_ime, ime, prezime, email, tip_korisnika_id FROM korisnik";

                        $rezultat = mysqli_query($spojka,$upit);
                        while($row = mysqli_fetch_array($rezultat))
                        {
                    ?>
                            <tr>
                                <td> <?php echo $row [0]; ?> </td>
                                <td> <img src="<?php echo $row [1]; ?>" alt="korisnikSlika"> </td>
                                <td> <?php echo $row [2]; ?> </td>
                                <td> <?php echo $row [3]; ?> </td>
                                <td> <?php echo $row [4]; ?> </td>
                                <td> <?php echo $row [5]; ?> </td>
                                <td> <?php echo $row [6]; ?> </td>
                            </tr>
                        <?php
                        } 
                        ?> 
                </table>
        </div>
        </section>
    </body>
</html>