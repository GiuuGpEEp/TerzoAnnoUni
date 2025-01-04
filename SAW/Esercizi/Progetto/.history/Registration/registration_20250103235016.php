<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];
    $password = $_POST["pass"];
    $confirm = $_POST["confirm"];

    // Validazione dei dati
    if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || $password !== $confirm) {
        echo '<h1 class="error">Errore: tutti i campi sono obbligatori e le password devono corrispondere.</h1>';
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<h1 >Errore: email non valida</h1>';
        header("Refresh:1, url=Form.html");
        exit;
    }

    if (strlen($password) < 8) {
        echo '<h1 class="error">Errore: la password deve contenere almeno 8 caratteri.</h1>';
        header("Refresh:1, url=Form.html");
        exit;
    }

    echo '<h1>Registrazione avvenuta con successo!</h1>';
    header("Refresh:1, url=Form.html");
    exit;
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

        .error {
            color: red;
        }
        /* Altri stili CSS */
    </style>
</head>
<body>
</body>
</html>
