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
                    <form class="profileForm" action="update_profile.php" method="post">
                        <div class="nameSurname"><?php echo htmlentities($name . " " . $surname); ?></div>
                        <div class="description">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlentities($email); ?>">
                            <hr class="descriptionLine">
                            <label for="descrizione">Descrizione:</label>
                            <input type="text" id="descrizione" name="descrizione" value="<?php echo htmlentities($descrizione); ?>">
                            <hr class="descriptionLine">
                            <label for="età">Età:</label>
                            <input type="number" id="età" name="età" value="<?php echo htmlentities($età); ?>">
                            <hr class="descriptionLine">
                            <label for="genere">Genere:</label>
                            <input type="text" id="genere" name="genere" value="<?php echo htmlentities($genere); ?>">
                            <hr class="descriptionLine">
                            <input type="submit" class="button" id="updateButton" name="submit" value="Aggiorna">
                        </div>

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
