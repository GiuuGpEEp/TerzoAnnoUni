<?php

<!DOCTYPE html>
<html lang="it">
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="profileStyle.css">
    <link rel="icon" href="../Logo32.ico" type="image/x-icon">
    <script src="showProfileScript.js"></script>
</head>
<body>
    <div class="wrapper">
        <header class="header">
            <?php include '../NavBar/NavBar.php'; ?>
        </header>
        <div class="content">
            <div class="title">
                <h1>Modifica del profilo - Parkour Academy</h1>
                <p>Aggiorna i dettagli relativi al tuoi profilo</p>
            </div>
            <div class="profileContainer">
                <div class="userSection" id="textSection">
                <form class="signUpForm" onsubmit="return checkForm()" action="registration.php" method="post">
                            <div>
                                <label for="firstname">Nome:</label>
                                <input type="text" id="firstname" name="firstname" placeholder="Inserisci il tuo nome">
                                <span class="error" id="errorFirstname">Il campo nome e' obbligatorio</span>
                                <label for="lastname">Cognome:</label>
                                <input type="text" id="lastname" name="lastname" placeholder="Inserisci il tuo cognome">
                                <span class="error" id="errorLastname">Il campo cognome e' obbligatorio</span>
                                <label for="email">Email:</label>
                                <input type="email" id="email" name="email" placeholder="Inserisci la tua email">
                                <span class="error" id="errorMail">La email non e' valida</span>
                                <label for="pass">Password:</label>
                            </div>
                            <input type="submit" class="button" id="submitButton" name="submit" value="Registrati">
                        </form>
                </div>
            </div>
            <div class="buttonContainer">
                <button class="button" id="editButton" type="button">Modifica Profilo</button>
                <div class="logoutContainer">
                    <a href="logout.php" class="button" id="logoutButton"> 
                        <img id="logIcon" src="logoutIcon.png" alt="Logout Icon" width="30" height="30">
                        Logout
                    </a> 
                </div>    
            </div>    
        </div>

        <footer class="Sfooter">
            <div class="footerContent">
                <p class="footerContent"><span id="footerTitle">Hai bisogno di aiuto?</span></p>
                <button id="footerButton" class="button" type="button">Contattaci</button>
            </div>
            <div id="footerCopy">
                <p>© 2024 Parkour Academy. All Rights Reserved.</p>
            </div>
        </footer>
    </div>
</body>
</html>
