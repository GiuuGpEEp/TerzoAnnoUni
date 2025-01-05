<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    $firstname = htmlentities(trim($_POST["firstname"]));
    $lastname = htmlentities(trim($_POST["lastname"]));
    $email = htmlentities(trim($_POST["email"]));
    $password = htmlentities(trim($_POST["pass"]));
    $confirm = htmlentities(trim($_POST["confirm"]));

    $errors = [];

    // Validazione dei campi
    if (empty($firstname)) {$errors[] = "Il nome è obbligatorio.";}
    if (empty($lastname)) {$errors[] = "Il cognome è obbligatorio.";}
    if (empty($email)) {$errors[] = "L'email è obbligatoria.";}
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) [{$errors[] = "Email non valida.";}]
    if (empty($password)) $errors[] = "La password è obbligatoria.";
    if (strlen($password) < 8) $errors[] = "La password deve contenere almeno 8 caratteri.";
    if ($password !== $confirm) $errors[] = "Le password non corrispondono.";

    if (empty($errors)) {
        // Connessione al database
        $conn = mysqli_connect("localhost", "GiuuG", "Samubruttatestadicazzo1", "BozzaProgetto");
        if (!$conn) {
            die("Connessione al database fallita: " . mysqli_connect_error());
        }

        // Controlla se l'email è già registrata
        $checkQuery = $conn->prepare("SELECT id FROM Users WHERE email = ?");
        $checkQuery->bind_param("s", $email);
        $checkQuery->execute();
        $checkQuery->store_result();

        if ($checkQuery->num_rows > 0) {
            $errors[] = "L'email è già registrata.";
        } else {
            // Email non trovata, procedi con l'inserimento
            $hash = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $conn->prepare("INSERT INTO Users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $firstname, $lastname, $email, $hash);

            if ($stmt->execute()) {
                echo "<h1>Registrazione avvenuta con successo!</h1>";
                header("Refresh:2; url=Form.html");
                exit;
            } else {
                $errors[] = "Errore durante la registrazione: " . $conn->error;
            }

            $stmt->close();
        }

        $checkQuery->close();
        mysqli_close($conn);
    }

    // Mostra errori
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<h1 class='error'>$error</h1>";
        }
    }
}
?>
