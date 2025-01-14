<?php 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Parkour Academy</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="icon" href="../Logo32.ico" type="image/x-icon">
        <script src="Script.js"></script>
    </head>
    <body>
        <div id="sfondo">
            <header class="header">
                <div class="logo-container">
                    <div id="logo-image-container">
                        <img src="Logo.png" class="logo-image" width="90px" height="88px" alt="ParkourAcademyLogo">
                    </div>
                </div>
                <nav class="menu">
                    <ul id="main-menu">
                        <li class="menu-item">
                            <a class="menu-link" onclick="goHome()">
                                <span class="">Home</span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="/contattaci">
                                <span class="">Corsi</span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="/corsi">
                                <span class="">Istruttori</span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <?php if(isset($_SESSION['username'])){
                                echo "<a class='menu-link' href='../ShowProfile/show_profile.php'> 
                                <span class=''> Profilo </span>
                                </a>";  
                                }else{
                                <a class="menu-link" href="../Registration-login/Form.html">
                                    <span class="">Sign-up e Login</span>
                                </a>
                            }
                            ?>
                        </li>
                    </ul>
                </nav>
            </header>
            <div id="titolo">
                Parkour Academy
                <button id="who" type="button" onclick="">Chi siamo? </button>
            </div>
        </div>
        <div id="mainContent">
            <div id="titolo2">
                 Che Cosa Offriamo?
            </div>
            <div class="image-container">
                <div class="image-item">
                    <img src="Img1.png" alt="Img1" width="450px" height="350px">
                    <p class="Sottotitoli"> Corsi di Gruppo</p>
                    <p class="Descrizioni">Unisciti ai corsi di gruppo per divertirti e allenarti con altri appassionati di parkour. Sia che tu sia un principiante o un esperto, ci sono corsi adatti a tutti i livelli e a tutte le età.</p>
                    <button class="button" type="button" onclick="">Scopri i Corsi</button>
                </div>
                <div class="image-item">
                    <img src="Img2.png" alt="Img2" width="450px" height="350px">
                    <p class="Sottotitoli"> Eventi Speciali</p>
                    <p class="Descrizioni"> Dai workshop ad ulteriori eventi, ci sono tante opportunità per mettere alla prova le tue abilità e imparare qualcosa di nuovo.</p>
                    <button id="CalendarButton" class="button" type="button" onclick="">Consulta il Calendario</button>
                </div>
            </div>
            <div id="SubsSection">
                <p id="subsTitle" class="Sottotitoli"> Unisciti a noi per un'avventura entusiasmante nel parkour </p>
                <p id="subsDescription" class="Descrizioni"> Iscriviti per poter consultare gli orari dei corsi ed iniziare ad allenarti con noi</p>
                <button id="subsButton" type="button" onclick="">Iscriviti Ora</button>
            </div>
        </div>
        <footer class="Sfooter">
            <div class="footerContent">
                 <p class="footerContent"><span id="footerTitle"> Hai bisogno di aiuto ? </span></p>
                 <button id="footerButton" class="button" type="button" onclick="">Contattaci</button>
            </div>
            <div id="footerCopy">
                <p>© 2024 Parkour Academy. All Rights Reserved.</p>

            </div>
        </footer>

    </body>
</html>