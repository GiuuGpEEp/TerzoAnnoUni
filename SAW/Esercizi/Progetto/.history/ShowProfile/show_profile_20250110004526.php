<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../Registration-login/Form.html");
    exit();
}

$errors = [];
$conn = mysqli_connect("localhost", "root", "", "bozzadb");

if (!$conn) {
    die("Connessione al database fallita: " . mysqli_connect_error());
}

$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT * FROM Users WHERE email = ?");
$stmt->bind_param("s", $username);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $name = $user['firstname'];
        $surname = $user['lastname'];
        $email = $user['email'];
    } else {
        $errors[] = "Utente non trovato.";
    }
} else {
    $errors[] = "Errore nell'esecuzione della query.";
}

$stmt->close();
$conn->close();

if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<h1 class='error'>$error</h1>";
    }
    header("Refresh:2, url=../Registration-login/Form.html");
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
                <h1>Il Tuo Spazio Personale - Parkour Academy</h1>
                <p>Benvenuto, <?php echo htmlspecialchars($name . " " . $surname); ?>!</p>
            </div>
            <div class="profileContainer">
                <div class="userSection" id="imageSection">
                    <div class="imageContainer">
                        <img id="profileImage" src="profileImage.png" alt="Immagine Utente" width="125" height="125">
                    </div>    
                </div>
                <div class="userSection" id="textSection">
                    <div class="nameSurname"><?php echo htmlspecialchars($name . " " . $surname); ?></div>
                    <div class="description">
                        <p>Email: <?php echo htmlspecialchars($email); ?></p>
                        <hr>
                    </div>
                </div>
            </div>
            <div class="buttonContainer">
                <button  id="editButton" type="button">Modifica Profilo</button>
                <button id="deleteButton" type="button">Logout</button>
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
