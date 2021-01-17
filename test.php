<?php
include('baza_podataka.php');
$imeUpdate = $_POST['imeUpdate'];
$prezimeUpdate = $_POST['prezimeUpdate'];
$korImeUpdate = $_POST['korImeUpdate'];
$lozinkaUpdate = $_POST['lozinkaUpdate'];
$emailUpdate = $_POST['emailUpdate'];
$tipKorisnikaUpdate = $_POST['tipKorisnikaUpdate'];
$slikaUpdate = $_POST['slikaUpdate'];

echo $imeUpdate." ".
$prezimeUpdate." ".
$korImeUpdate." ".
$lozinkaUpdate." ".
$emailUpdate." ".
$tipKorisnikaUpdate." ".
$slikaUpdate." ";

$update = "UPDATE `korisnik` SET `tip_korisnika_id`= $tipKorisnikaUpdate, `korisnicko_ime`=$korImeUpdate, `lozinka`= $lozinkaUpdate, `ime`= $imeUpdate, `prezime`= $prezimeUpdate, `email`=$emailUpdate, `slika`= $slikaUpdate WHERE korisnik_id = 3";
                        $rezultat = mysqli_query($spojka, $update);
?>