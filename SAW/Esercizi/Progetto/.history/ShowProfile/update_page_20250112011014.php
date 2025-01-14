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
                <p class="updateText">Aggiorna i dettagli relativi al tuoi profilo</p>
            </div>
            <div class="profileContainer">
                <div class="userSection" id="textSection">
                <form class="UpdateForm" action="" method="post">
                            <div>
                                <label for="firstname">Nome:</label>
                                <input type="text" id="firstname" name="firstname" placeholder="Inserisci il tuo nome">
                                <label for="lastname">Cognome:</label>
                                <input type="text" id="lastname" name="lastname" placeholder="Inserisci il tuo cognome">
                                <label for="età">Età:</label>
                                <input type="number" id="età" name="età" placeholder="Inserisci una tua età">
                                <label for="genere">Genere:</label>
                                <select name="genere" id="genere">
                                    <option value="M">Maschio</option>
                                    <option value="F">Femmina</option>
                                    <option value="Altro">Altro</option>
                                    <option value="null"> </option>
                                </select>    
                                <label for="descrizione">Descrizione:</label>
                                <textarea id="descrizione" name="descrizione" placeholder="Inserisci una tua descrizione"></textarea>
                            </div>
                            <div class="buttonContainer">
                                <button class="button" id="editButton" type="submit">Salva</button>
                            <button class="button" id="editButton" type="submit">Salva</button>
                        </form>
                </div>
            </div>
        </div>

        <?php include '../Footer/footer.php'; ?>
    
    </div>
</body>
</html>
