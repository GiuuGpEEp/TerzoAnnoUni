<!DOCTYPE html>
<html lang="it">
<head>
    <title>Istruttori</title>
</head>
<style>
    
<body>
    <div class="wrapper">
        <header class="header">
            <?php include '../NavBar/NavBar.php'; ?>
        </header>
        <div class="content">
            <div class="title">
                <h1>Il Tuo Spazio Personale - Parkour Academy</h1>
                <p>Benvenuto, <?php echo htmlentities($name . " " . $surname); ?>!</p>
            </div>
            <div class="profileContainer">
                <div class="userSection" id="imageSection">
                    <div class="imageContainer">
                        <img id="profileImage" src="profileImage.png" alt="Immagine Utente" width="125" height="125">
                    </div>    
                </div>
                <div class="userSection" id="textSection">
                    <div class="nameSurname"><?php echo htmlentities($name . " " . $surname); ?></div>
                    <div class="description">
                        <p class="descriptionText"><b>Email</b>: <?php echo htmlentities($email); ?></p>
                        <hr class="descriptionLine">
                        <?php
                            
                            if(htmlentities($età) != NULL) {
                                echo "<p class='descriptionText'><b>Età:</b> " . htmlentities($età) . "</p>";
                            } else {
                                echo "<p class='descriptionText'><b>Età:</b> Età non indicata al momento.</p>";
                            }
                            echo "<hr class='descriptionLine'>";
                            if(htmlentities($genere) != NULL) {
                                echo "<p class='descriptionText'><b>Genere:</b> " . htmlentities($genere) . "</p>";
                            } else {
                                echo "<p class='descriptionText'><b>Genere:</b> Genere non indicato al momento.</p>";
                            }
                            echo "<hr class='descriptionLine'>";
                            if(htmlentities($descrizione) != NULL) {
                                echo "<div class='descriptionContainer'>";
                                echo "<p class='descriptionText'> <b>Descrizione:</b> " . htmlentities($descrizione) . "</p>";
                                echo "</div>";
                            } else {
                                echo "<p class='descriptionText'> <b>Descrizione:</b> " . trim('Nessuna descrizione disponibile al momento') . "</p>";
                            }
                        ?>
                    </div>
                </div>
            </div>
            <div class="buttonContainer">
                <a href="update_page.php" class="button" id="editButton">Modifica Profilo</a>
                <div class="logoutContainer">
                    <a href="logout.php" class="button" id="logoutButton"> 
                        <img id="logIcon" src="logoutIcon.png" alt="Logout Icon" width="30" height="30">
                        Logout
                    </a> 
                </div>    
            </div>    
        </div>

        <?php include '../Footer/footer.php'; ?>
        
    </div>
</body>
</html>
