<?php

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    $email =htmlentities(trim($_POST["email"]));
    $password = htmlentities(trim($_POST["pass"]));

    $errors = [];

    if (empty($email)){ $errors[] = "L'email è obbligatoria.";}
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){ $errors[] = "Email non valida.";}
    if (empty($password)){ $errors[] = "La password è obbligatoria.";}
    if (strlen($password) < 8){ $errors[] = "La password deve contenere almeno 8 caratteri.";}

    
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