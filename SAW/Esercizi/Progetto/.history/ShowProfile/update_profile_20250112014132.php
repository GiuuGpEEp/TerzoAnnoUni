<?php
session_start();
if (!isset($_SESSION['username'])) {
    window.alert("Effettua il login per accedere a questa pagina.");
    header("Location: ../Registration-login/Form.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST"){

    $firstname =htmlentities(trim($_POST["firstname"]));
    $lastname = htmlentities(trim($_POST["lastname"]));
    $email = htmlentities(trim($_POST["email"]));
    

    $errors = [];



}


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