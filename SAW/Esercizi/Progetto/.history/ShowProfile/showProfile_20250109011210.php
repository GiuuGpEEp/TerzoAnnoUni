<?php
    session_start();
    if(!isset($_SESSION['username'])){
        header("Location: ../Registration-login/Form.html");
        exit();
    }
    
    $conn = mysqli_connect("localhost", "root", "", "bozzadb");
    if(!$conn){
        die("Connessione al database fallita: " . mysqli_connect_error());
    }

    $username = $_SESSION['username'];
    $stmt = $conn->prepare("SELECT email, password FROM Users WHERE email = '$username'");
    $sql = "SELECT * FROM users WHERE username = ";
    $result = $conn->query($sql);
    if($result->num_rows == 0){
        die("Errore: utente non trovato");
    }
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $surname = $row['surname'];
    $description = $row['description'];
    $image = $row['image'];
    $conn->close();

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
                            <a class="menu-link" href="../Registration-login/Form.html">
                                <span class="">Sign-up e Login</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </header>
            <div class="content">
                <div class="title">
                    <h1>Il Tuo Spazio Personale - Parkour Academy</h1>
                    <p> (Da cambiare nomi degli elementi nel file e sistemare)</p>
                </div>
                <div class="profileContainer">
                    <div class="userSection" id="imageSection"> <!-- userImage -->
                        <div class="imageContainer">
                            <img id="profileImage" src="profileImage.png" alt="Immagine Utente" width="125" height="125">
                        </div>
                    </div>
                    <div class="userSection" id="textSection">
                        <div class="nameSurname">Nome Cognome</div>
                        <div class="description">
                            <p> Descrizione</p>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="Sfooter">
                <div class="footerContent">
                     <p class="footerContent"><span id="footerTitle"> Hai bisogno di aiuto ? </span></p>
                     <button id="footerButton" class="button" type="button" onclick="">Contattaci</button>
                </div>
                <div id="footerCopy">
                    <p> © 2024 Parkour Academy. All Rights Reserved. </p>
                </div>
            </footer>
        </div>
</body>
</html>