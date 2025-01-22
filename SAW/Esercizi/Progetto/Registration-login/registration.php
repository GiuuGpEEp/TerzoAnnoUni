<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    $firstname =htmlentities(trim($_POST["firstname"]));
    $lastname = htmlentities(trim($_POST["lastname"]));
    $email = htmlentities(trim($_POST["email"]));
    $password = htmlentities(trim($_POST["pass"]));
    $confirm = htmlentities(trim($_POST["confirm"]));

    $errors = array();

    // Validazione dei campi
    if(empty($firstname)){ $errors[] = "Il nome è obbligatorio.";}
    if (empty($lastname)){ $errors[] = "Il cognome è obbligatorio.";}
    if (empty($email)){ $errors[] = "L'email è obbligatoria.";}
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){ $errors[] = "Email non valida.";}
    if (empty($password)){ $errors[] = "La password è obbligatoria.";}
    if (strlen($password) < 8){ $errors[] = "La password deve contenere almeno 8 caratteri.";}
    if ($password !== $confirm){ $errors[] = "Le password non corrispondono.";}
    
    if(empty ($errors)){

        include '../dbConnection.php';

        $checkQuery = $conn->prepare("SELECT email FROM Users WHERE email = ?");
        $checkQuery->bind_param("s", $email);
        $checkQuery->execute();
        $checkQuery->store_result();

        if($checkQuery->num_rows > 0){ 
            $errors[] = "Email già registrata.";
        }else{
            $hash = password_hash($password, PASSWORD_BCRYPT);
            if($email === 'parkouracademy.admin@gmail.com'){
                $role = 'admin';
            } else {
                $role = 'user';
            }
             
            $stmt = $conn->prepare("INSERT INTO Users (firstname, lastname, email, password, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $firstname, $lastname, $email, $hash, $role);
            
            if(!($stmt->execute())){
                $errors[] = "Registrazione non riuscita riprova...";
            }

            $_SESSION['username'] = $email;
            $_SESSION['role'] = $role;
            $stmt->close();
        }

        $checkQuery->close();
        $conn->close();
    }
    
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<h1 class='error'>$error</h1>";
        }
        echo "<script>
            window.alert('Si è verificato un errore nella richiesta, alcuni campi potrebbero essere vuoti.');
         </script>"; 
        header("Refresh:2, url=Form.php");
    } else {
        echo "<h1>Registrazione avvenuta con successo!</h1>";
        echo "<p>Se tra meno di 5 secondi non vieni reindirizzato alla pagina, clicca <a href='../ShowProfile/show_profile.php'>qui</a> </p>";
        header("Refresh:2, url=../ShowProfile/show_profile.php");
    }

} else {
    echo "<script>
            window.alert('Si è verificato un errore nella richiesta, metodo non valido.');
         </script>"; 
    header("Location: Form.php");
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    
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
        
    </div>
</body>
</html>
