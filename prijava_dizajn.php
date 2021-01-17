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
        </header>
        <section id="PrijavaDizajn">
            <div class="container">
                    <form action="prijava.php" method="post">
                    <label for="Ime">Ime:</label><br>
                    <input type="text" id="Ime" name="Ime" placeholder="Unesite ime"><br>
                    <label for="Zaporka">Zaporka:</label><br>
                    <input type="password" id="zaporka" name="Zaporka" placeholder="Unesite zaporku"><br><br>
                    <input type="submit" value="Prijavi se!" name="prijavise">
                    </form>     
            </div>
        </section>
    </body>
</html>