<?php
    include 'baza_podataka.php';
    session_start();
    
        
    
    if(isset($_POST['prijavise'])){

        $Ime = $_POST ['Ime'];
        $Zaporka = $_POST ['Zaporka'];
        $upit = "SELECT * FROM korisnik  WHERE korisnicko_ime = '$Ime' AND lozinka = '$Zaporka'";
        $rezultat = mysqli_query($spojka, $upit);
        
        if (mysqli_num_rows($rezultat)) {
           
            if(isset($Ime) && !empty($Ime) && isset($Zaporka) && !empty($Zaporka))
            {
                $prijava = true; 
                $uloga = mysqli_fetch_array($rezultat);
                $korisnikUloga = $uloga["tip_korisnika_id"];
               
                if($prijava) {
                    header("Location:vrijednosti_valuta.php");
                    $upit = "SELECT tip_korisnika_id, korisnik_id from korisnik WHERE korisnicko_ime = '$Ime' AND lozinka = '$Zaporka'";
                    $query = mysqli_query($spojka, $upit);
                    $korisnickiPodatci = mysqli_fetch_array($query);
                    $_SESSION['uloga'] = $korisnickiPodatci[0];
                    $_SESSION['korisnikId'] = $korisnickiPodatci[1];
                }
            }
        }
        else{
            echo "Krivo ste unijeli podatke ili niste ništa unijeli, molim probajte ponovo!";
        }
    }
    
?>