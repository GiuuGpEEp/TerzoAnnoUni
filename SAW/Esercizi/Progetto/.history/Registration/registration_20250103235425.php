<?php
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];
    $password = $_POST["pass"];
    $confirm = $_POST["confirm"];

    // Validazione dei dati
    if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || $password !== $confirm) {
        $error_message = 'Errore: tutti i campi sono obbligatori e le password devono corrispondere.';

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Errore: email non valida';
    
    } elseif (strlen($password) < 8) {
        $error_message = 'Errore: la password deve contenere almeno 8 caratteri.';
    
    } else {
        echo '<h1>Registrazione avvenuta con successo!</h1>';
        header("Refresh:1, url=Form.html");
        exit;
    }
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
            margin-top: 20px;
        }
        /* Altri stili CSS */
    </style>
</head>
<body>
    <?php if ($error_message): 
        <h1 class="error">echo $error_message; </h1>
        endif; 
    header("Refresh:1, url=Form.html");
    ?>
</body>
</html>
