<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) { // Verifica che il form sia stato inviato e che sia stato usato il metodo POST
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];
    $password = $_POST["pass"];
    $confirm = $_POST["confirm"];

    // Validazione dei dati
    if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || $password !== $confirm) {
        echo "Errore: tutti i campi sono obbligatori e le password devono corrispondere.";
        exit;
    }

    

    echo "<h1> Registrazione avvenuta con successo! <h1>";
    header("Refresh:1, url=Form.html");
    
    //// Connessione al database
    //$conn = new mysqli("localhost", "username", "password", "database");
//
    //// Verifica connessione
    //if ($conn->connect_error) {
    //    die("Connessione fallita: " . $conn->connect_error);
    //}
//
    //// Inserimento dati nel database
    //$stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)");
    //$stmt->bind_param("ssss", $firstname, $lastname, $email, password_hash($password, PASSWORD_DEFAULT));
//
    //if ($stmt->execute()) {
    //    echo "Registrazione avvenuta con successo!";
    //} else {
    //    echo "Errore durante la registrazione: " . $stmt->error;
    //}
//
    //$stmt->close();
    //$conn->close();
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h1 {
            color: green;
            text-align: center;
            margin-top: 20px;
        }
        /* Altri stili CSS */
    </style>
</head>
<body>
</body>
</html>
