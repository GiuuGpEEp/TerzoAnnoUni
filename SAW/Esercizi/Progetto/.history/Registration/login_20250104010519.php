<?php
if (isset($_POST["submit"])) {
    $email = $_POST["email"];
    $password = $_POST["pass"];

    // Validazione dei dati
    if (empty($email) || empty($password)) {
        echo "Errore: tutti i campi sono obbligatori.";
        exit;
    }

    // Connessione al database
    $conn = new mysqli("localhost", "username", "password", "database");

    // Verifica connessione
    if ($conn->connect_error) {
        die("Connessione fallita: " . $conn->connect_error);
    }

    // Verifica credenziali
    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            echo "Login avvenuto con successo!";
            // Avvia la sessione e salva i dati dell'utente
            session_start();
            $_SESSION["email"] = $email;
        } else {
            echo "Errore: password non corretta.";
        }
    } else {
        echo "Errore: utente non trovato.";
    }

    $stmt->close();
    $conn->close();
}
?>
if (isset($_POST["submit"])) {
    $email = $_POST["email"];
    $password = $_POST["pass"];

    // Validazione dei dati
    if (empty($email) || empty($password)) {
        echo "Errore: tutti i campi sono obbligatori.";
        exit;
    }

    // Leggi il file degli utenti
    $users = file_get_contents('users.txt');
    $users = explode("\n", $users);

    $user_found = false;
    foreach ($users as $user) {
        list($stored_email, $stored_password) = explode(',', trim($user));
        if ($stored_email === $email && password_verify($password, $stored_password)) {
            $user_found = true;
            break;
        }
    }

    if ($user_found) {
        echo "Login avvenuto con successo!";
        // Avvia la sessione e salva i dati dell'utente
        session_start();
        $_SESSION["email"] = $email;
    } else {
        echo "Errore: credenziali non corrette.";
    }
}