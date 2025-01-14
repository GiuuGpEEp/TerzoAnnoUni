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
            <div class="contactForm">
                <div class="form
                <form action="contactForm.php" method="post">
                    <label for="name">Nome:</label>
                    <input type="text" id="name" name="name" required>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    <label for="message">Messaggio:</label>
                    <textarea id="message" name="message" required></textarea>
                    <input type="submit" value="Invia">
                </form>
            </div>    
        </div>
        <?php include '../Footer/footer.php'; ?>
    </div>
</body>

</html>