<?php
session_start();
if (!isset($_SESSION['username'])) {
    window.alert("Effettua il login per accedere a questa pagina.");
    header("Location: ../Registration-login/Form.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST"){

    $errors = [];

    $firstname =htmlentities(trim($_POST["firstname"]));
    $lastname = htmlentities(trim($_POST["lastname"]));
    $email = htmlentities(trim($_POST["email"]));
    $età = htmlentities(trim($_POST["età"]));
    $genere = htmlentities(trim($_POST["genere"]));
    $descrizione = htmlentities(trim($_POST["descrizione"]));

    if(empty($firstname)){ $errors[] = "Il nome è obbligatorio.";}
    if (empty($lastname)){ $errors[] = "Il cognome è obbligatorio.";}
    if (empty($email)){ $errors[] = "L'email è obbligatoria.";}
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){ $errors[] = "Email non valida.";}
    
    if(empty ($errors)){
        


    $conn = mysqli_connect("localhost", "root", "", "bozzadb");
    if (!$conn) {
        die("Connessione al database fallita: " . mysqli_connect_error());
    }

    $username = $_SESSION['username'];
    $stmt = $conn->prepare("UPDATE Users SET firstname= ?, lastname= ?, email= ?, descrizione= ?, età= ?, genere= ? WHERE email = ?");
    $stmt->bind_param("ssssiss", $firstname, $lastname, $email, $descrizione, $età, $genere, $username);

    if(!($stmt->execute())){
        $errors[] = "Registrazione non riuscita riprova...";
    }

    $_SESSION['username'] = $email;

    $stmt->close();
    $conn->close();

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<h1 class='error'>$error</h1>";
        }
        header("Refresh:2, url=update_page.php");
        exit();
    }

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<h1 class='error'>$error</h1>";
        }
        header("Refresh:2, url=Form.php");
    } else {
        echo "<h1>Aggiornamento del profilo avvenuto con successo!</h1>";
        header("Refresh:2, url=../ShowProfile/show_profile.php");
    }


} else {
    window.alert("Errore nella richiesta.");
    header("Location: Form.php");
}

?>