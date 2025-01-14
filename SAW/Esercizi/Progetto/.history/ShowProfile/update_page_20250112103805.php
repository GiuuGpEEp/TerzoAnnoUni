<?php
session_start();
if (!isset($_SESSION['username'])) {
    window.alert("Effettua il login per accedere a questa pagina.");
    header("Location: ../Registration-login/Form.php");
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
        $descrizione = $user['descrizione'];
        $età = $user['età'];
        $genere = $user['genere'];
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
    header("Refresh:2, url=../Registration-login/Form.php");
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
                    <form class="UpdateForm" action="update_profile.php" method="post">
                        <div>
                            <label for="firstname">Nome:</label>
                            <input type="text" id="firstname" name="firstname" value="<?php echo htmlentities($name); ?>">
                            <label for="lastname">Cognome:</label>
                            <input type="text" id="lastname" name="lastname" value="<?php echo htmlentities($name); ?>">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" placeholder="Inserisci la tua email">
                            <label for="età">Età:</label>
                            <input type="number" id="età" name="età" placeholder="Inserisci una tua età">
                            <label for="genere">Genere:</label>
                            <select name="genere" id="genere">
                                <option value="Uomo">Uomo</option>
                                <option value="Donna">Donna</option>
                                <option value="Altro">Altro</option>
                                <option value="null"> </option>
                            </select>    
                            <label for="descrizione">Descrizione:</label>
                            <textarea id="descrizione" name="descrizione" placeholder="Inserisci una tua descrizione"></textarea>
                        </div>
                        <div class="buttonContainer">
                            <input type="submit" class="button" id="updateButton" name="submit" value="Salva"></button>
                        </div>
                    </form>
                </div>     
            </div>
        </div>

        <?php include '../Footer/footer.php'; ?>
    
    </div>
</body>
</html>
