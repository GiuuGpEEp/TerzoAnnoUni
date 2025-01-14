<?php
    session_start();
    if (!isset($_SESSION['username'])) {
        window.alert("Effettua il login per accedere a questa pagina.");
        header("Location: ../Registration-login/Form.php");
        exit();
    }
?>    

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
                <form class="signUpForm" action="registration.php" method="post">
                            <div>
                                <label for="firstname">Nome:</label>
                                <input type="text" id="firstname" name="firstname" placeholder="Inserisci il tuo nome">
                                <label for="lastname">Cognome:</label>
                                <input type="text" id="lastname" name="lastname" placeholder="Inserisci il tuo cognome">
                                <label for="descrizione">Descrizione:</label>
                                <input type="text" id="descrizione" name="descrizione" placeholder="Inserisci una tua descrizione">
                                <label for="età">Età:</label>
                                <input type="number" id="età" name="età" placeholder="Inserisci una tua descrizione">
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

        <?php include '../NavBar/NavBar.php'; ?>
    </div>
</body>
</html>
