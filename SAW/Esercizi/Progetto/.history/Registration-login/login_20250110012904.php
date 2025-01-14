<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    $email = trim($_POST["email"]);
    $password = trim($_POST["pass"]);

    $errors = [];

    // Validazione dei campi
    if (empty($email)) { $errors[] = "L'email è obbligatoria."; }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = "Email non valida."; }
    if (empty($password)) { $errors[] = "La password è obbligatoria."; }

    if (empty($errors)) {
        // Connessione al database
        $conn = mysqli_connect("localhost", "root", "", "bozzadb");
        if (!$conn) {
            die("Connessione al database fallita: " . mysqli_connect_error());
        }

        // Query combinata per email e password
        $stmt = $conn->prepare("SELECT email, password FROM Users WHERE email = ?");
        $stmt->bind_param("s", $email);

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) { 
                $user = $result->fetch_assoc();  //traduco in un array associativo

                // Verifica della password
                if (!(password_verify($password, $user['password']))) { //password_verify permette di confrontare una password non cifrata con una cifrate
                    $errors[] = "Password errata.";
                }   
                
            } else {
                $errors[] = "Email non registrata.";
            }
        } else {
            $errors[] = "Errore nell'esecuzione della query.";
        }
        $_SESSION['username'] = $user['email'];
        $stmt->close();
        $conn->close();
    }

    // Mostra gli errori
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<h1 class='error'>$error</p>";
            header("Refresh:2, url=Form.php");
        }
    } else {
        echo "<h1>Login effettuato con successo!</h1>";
        echo "<p>Se tra meno di 5 secondi non vieni reindirizzato alla pagina </p>";
        echo "<p>, clicca <a href='../ShowProfile/show_profile.php'>qui</a>.</p>";
        header("Refresh:2, url=../ShowProfile/show_profile.php");
    }
    
}
?>


<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            margin-top: 10px;
        }
    </style>
</head>
<body>
    
</body>
</html>