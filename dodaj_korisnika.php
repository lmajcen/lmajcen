<?php
include("baza_podataka.php");
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
                </ul>
                </nav>
            </div>
        </header>


        <section id="DodajKorisnika">
            <div class="box">
                <h1> Podaci o autoru: </h1>
                <form action="/action_page.php">
                    <label for="Ime">Ime:</label><br>
                    <input type="text" id="Ime" placeholder="Unesi!" ><br>
                    <label for="Prezime">Prezime:</label><br>
                    <input type="text" id="Prezime" placeholder="Unesi!" ><br>
                    <label for="Korisničko Ime">Korisničko ime:</label><br>
                    <input type="text" id="Korisničko ime" placeholder="Unesi!" ><br>
                    <label for="Lozinka">Lozinka:</label><br>
                    <input type="text" id="Lozinka" placeholder="Unesi!" ><br>
                    <label for="E-mail:">E-mail:</label><br>
                    <input type="text" id="E-mail" placeholder="Unesi!" ><br>
                    <br>

                    <select name="naziv">
                    <?php
                
                    $upit = "SELECT naziv FROM tip_korisnika";
                    $rezultat = mysqli_query($spojka, $upit);
                    while($row = mysqli_fetch_array($rezultat))
                    {
                    ?>
                    <option><?php echo $row["naziv"]; ?></option>;
                    <?php
                    }
                    ?>
                    
                    </select>

                    <br>
                    <label for="Slika">Slika:</label><br>
                    <input type="text" id="Slika" placeholder="Unesi!"><br>
                    <input type="submit" value="Dodaj korisnika!" name="submit-amount" class="submit-amount"></input>
                </form>
            </div>
        </section>

        <div class="footer">
            <p> Kontakt: 095/456-4563</p>
            <p>E-mail: mjenjacnicaharon@yopmail.com</p>
        </div>

    </body>
</html>