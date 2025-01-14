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
                    <form class="messageForm" action="contactForm.php" method="post">
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
                            <img src= whatsappLogo.png alt="whatsapp" class="waLogo" width="35" height="35">
                            <p class="waText">+39 1234567890</p>
                        </div>    
                    </div>            
                </div>
            </div>        
        </div>
        <?php include '../Footer/footer.php'; ?>
    </div>
</body>

</html>