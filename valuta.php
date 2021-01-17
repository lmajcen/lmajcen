<?php 

$naziv = $_GET["naziv"];
$slika = $_GET["slika"];
$zvuk = $_GET["zvuk"];
$tecaj = $_GET["tecaj"];

?>

<html>
<head>
    <title></title>
</head>
<body>
    <div class="container">
        <h3><?php echo $naziv; ?></h3>
        <img src="<?php echo $slika; ?>" alt="zastava države">
        <?php
            if (isset($zvuk)) {
                
               echo '<audio controls> 
                        <source src="'.$zvuk.'" type="audio/ogg">
                    </audio>';
            } 
        ?>
        <hr>
        <p>Tečaj: <b><?php echo $tecaj; ?></b> HRK</p>
    </div>
</body>
</html>