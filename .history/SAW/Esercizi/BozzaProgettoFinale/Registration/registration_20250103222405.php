<?php
if (isset($_POST["submit"])) {
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
