<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = htmlspecialchars(trim($_POST["email"]));
    $password = htmlspecialchars(trim($_POST["pass"]));
    $confirm = htmlspecialchars(trim($_POST["confirm"]));

    $errors = [];

    // Validazione dei campi
    if (empty($firstname)) $errors[] = "Il nome è obbligatorio.";
    if (empty($lastname)) $errors[] = "Il cognome è obbligatorio.";
    if (empty($email)) $errors[] = "L'email è obbligatoria.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email non valida.";
    if (empty($password)) $errors[] = "La password è obbligatoria.";
    if (strlen($password) < 8) $errors[] = "La password deve contenere almeno 8 caratteri.";
    if ($password !== $confirm) $errors[] = "Le password non corrispondono.";

    // Mostra errori o messaggio di successo
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<h1 class='error'>$error</p>";
        }
    } else {
        echo "<h1>Registrazione avvenuta con successo!</h1>";
    }
    header("Refresh:2, url=Form.html");
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
            text-align: center;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div>
        <p>
    </div>
</body>
</html>
