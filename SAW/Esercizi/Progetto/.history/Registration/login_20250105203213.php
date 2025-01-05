<?php

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    $email =htmlentities(trim($_POST["email"]));
    $password = htmlentities(trim($_POST["pass"]));

    $errors = [];
    
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