
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