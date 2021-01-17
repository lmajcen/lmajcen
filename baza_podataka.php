<?php
  
  $posluzitelj = "localhost";
  $korisnik = "iwa_2019";
  $lozinka = "foi2019";
  $bazapodataka = "iwa_2019_vz_projekt";

  $spojka = mysqli_connect($posluzitelj, $korisnik, $lozinka, $bazapodataka);
  mysqli_set_charset($spojka, "utf-8");
  if(!$spojka)
  {
  	die("Dogodila se greška kod povezivanja, molim pokušajte ponovo!" . mysqli_connect_error());
  }
 
 

?>