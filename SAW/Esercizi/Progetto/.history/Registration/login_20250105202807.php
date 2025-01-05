<?php

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    $email =htmlentities(trim($_POST["loginEmail"]));
    $password = htmlentities(trim($_POST["lastname"]));

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
    <h2>Login</h2>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Login</button>
    </form>
</body>
</html>