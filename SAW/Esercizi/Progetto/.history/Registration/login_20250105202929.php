<?php

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    $email =htmlentities(trim($_POST["email"]));
    $password = htmlentities(trim($_POST["pass"]));

    // Leggi il contenuto del file JSON
    $json_data = file_get_contents('Es.json');
    $users = json_decode($json_data, true);

    // Controlla se l'utente esiste e la password è corretta
    foreach ($users as $user) {
        if ($user['username'] == $username && $user['password'] == $password) {
            $_SESSION['username'] = $username;
            header('Location: dashboard.php');
            exit();
        }
    }

    $error = "Username o password errati";
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    
</body>
</html>