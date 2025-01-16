<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = htmlspecialchars($_POST['nome']);
    $email = htmlspecialchars($_POST['email']);
    $oggetto = htmlspecialchars($_POST['oggetto']);
    $messaggio = htmlspecialchars($_POST['messaggio']);

    // Invia email
    $to = "info@parkouracademy.com";
    $subject = "Nuovo messaggio da $nome";
    $body = "Nome: $nome\nEmail: $email\nOggetto: $oggetto\nMessaggio:\n$messaggio";
    $headers = "From: $email";

    if (mail($to, $subject, $body, $headers)) {
        echo "Messaggio inviato con successo!";
    } else {
        echo "Errore durante l'invio del messaggio. Riprova più tardi.";
    }
}

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <title>PA - Contattaci</title> 
    <link rel="icon" href="../Logo32.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="contactStyle.css">
</head>
<body>
    <div class="wrapper">
        <header class="header">
            <?php include '../Navbar/navbar.php'; ?>
        </header>
        <div class="content">
            <div class="title">
                <h1>Contattaci</h1>
                <p>Per qualsiasi informazione o chiarimento, non esitare a contattarci. <br>
                Siamo a tua disposizione per rispondere a qualsiasi domanda tu possa avere.</p>
            </div>
            <div class="contactANDForm">                    
                <div class="formSection" id="messageFormSection">
                    <div class="formTitle">Scrivici</div>
                    <form class="messageForm" action="contact.php" method="post">
                        <label for="name">Il tuo nome:</label>
                        <input type="text" id="name" name="name" required>
                        <label for="email">La tua email:</label>
                        <input type="email" id="email" name="email" required>
                        <label for="message">Messaggio:</label>
                        <textarea id="message" name="message" required></textarea>
                        <div>
                            <input type="submit" class="button" value="Invia">
                        </div>    
                    </form>
                </div>
                <div class="formSection" id="contactSection">
                    <div class="formTitle">In alternativa</div>
                    <div class="contactInfo">
                        <p class="contactText"> Contattaci sui social </p>
                        <div class="whatsapp">
                            <img id="waLogo" src= whatsappLogo.png alt="whatsapp" class="waLogo" width="25" height="25">
                            <p class="waText">+39 349 799 1113</p>
                        </div>
                        <hr>
                        <div class="facebook">
                            <img id="fbLogo" src= facebookLogo.png alt="facebook" class="fbLogo" width="25" height="25">
                            <a href="https://www.facebook.com/people/Parkour-Academy-Genova/61566051531407/?mibextid=wwXIfr&rdid=dRSvkAbtz2PpHfyx&share_url=https%3A%2F%2Fwww.facebook.com%2Fshare%2F1Epi2Xb36n%2F%3Fmibextid%3DwwXIfr"><p class="fbText">Parkour Academy Genova</p></a>
                        </div>
                        <hr>
                        <div class="instagram">
                            <img id="igLogo" src= instagramLogo.png alt="instagram" class="igLogo" width="25" height="25">
                            <a href="https://www.instagram.com/parkour_academy_genova/"><p class="igText">parkour_academy_genova</p></a>    
                        </div>
                        <hr>
                        <div class="gmail">
                            <img id="gmLogo" src= gmailLogo.png alt="gmail" class="gmLogo" width="25" height="25">
                            <p class="gmText"> ParkourAcademyGenova@gmail.com</p>
                        </div>
                        <hr>    
                    </div>            
                </div>
            </div>        
        </div>
        <?php include '../Footer/footer.php'; ?>
    </div>
</body>

</html>