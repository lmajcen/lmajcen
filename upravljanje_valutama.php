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
                        <li><a href="index.php">Početna</a></li>
                        <li><a href="usluga.php">Usluga</a></li>
                        <li><a href="o_autoru.html">O autoru</a></li>
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
        <div class="DodajValutu">
        <section id="AzurirajValutu">
        <div class="container">
                            <table>
                            <thead>
                            <tr>
                                <th> Naziv </th>
                                <th> Tečaj </th>
                                <th> Aktivno od </th>
                                <th> Aktivno do  </th>
                                <th> Akcija  </th>
                            </tr>
                            </thead>
                <?php
                   $upit = "SELECT naziv, tecaj, aktivno_od, aktivno_do, valuta_id FROM valuta WHERE moderator_id = $korisnikId";
                   
                   $rezultat = mysqli_query($spojka,$upit);
                   while($row = mysqli_fetch_array($rezultat))
                   {
                       ?>
                       <tr>
                        <td> <?php echo $row [0]; ?> </td>
                        <td> <?php echo $row [1]; ?> </td>
                        <td> <?php echo $row [2]; ?> </td>
                        <td> <?php echo $row [3]; ?> </td>
                        <td> <?php echo '<button id="'.$row[4].'" class="btnValutaChange">Promijeni tečaj</button>' ?> </td>
                            
                        </tr>
                        <?php
                   } 
                   
                   ?> 
                   
                    <div id="popUpValutaChange" style="display: none;">
                        <input id="valID" type="text" name="valutaId" style="display:none;">
                        <input id="valTecaj" type="text" name="valutaChange">
                        <input id="promjeniTecaj"type="submit" value="Ažuriraj tečaj!" name="azurirajValutuMod">
                    </div>
                    
                </table>
        </div>
        </section>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
                   <script type="text/javascript">
                        $(".btnValutaChange").click(function(){
                            $("#popUpValutaChange").show();

                            let valutaId = this.id;
                            document.getElementById("valID").value = valutaId;
                            console.log("radi");

                        });
                        $("#promjeniTecaj").click(function(){

                            let valutaId = document.getElementById("valID").value;
                            let valutaTecaj = document.getElementById("valTecaj").value;

                            $.ajax({
                                type: "POST",
                                url: "modPromjenaValute.php",
                                data: {valutaId: valutaId, tecaj: valutaTecaj},
                                success: function(response){
                                    alert(response);
                                    console.log(response);
                                }
                            });
                            document.getElementById("valTecaj").value = "";
                            $("#popUpValutaChange").hide();
                        });
                        
                   </script>
    </body>
</html>