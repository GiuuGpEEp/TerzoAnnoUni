<!DOCTYPE html>
<html>
<head>
    <title>PA - Corsi</title>
    <link rel="icon" href="../Logo32.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="corsiStyle.css">
</head>
<body>
    <div class="wrapper">
        <header class="header">
            <?php include '../Navbar/navbar.php'; ?>
        </header>
        <div class="content">
            <div class="title">
                <h1>Corsi</h1>
                <p>Scopri i nostri corsi e scegli quello che fa per te. <br></p>
            </div>
            <div class="tabellaCorsi">
                <div class="colonnaCorso">
                    <div class="corsoTitle">Corso Bambini</div>
                    <div class="containerSingoloCorso">
                        <div class="corsoDescrizione">
                            <p class="giornoCorso">Lunedì</p>
                            <p class="orarioCorso">16:00 <br> 17:00</p>
                        </div>
                    </div>
                </div>
                <div class="colonnaCorso">
                    <div class="corsoTitle">Corso Ragazzi</div>
                    <div class="containerSingoloCorso">
                        <div class="corsoDescrizione">
                            <p class="giornoCorso">Martedì</p>
                            <p class="orarioCorso">16:00 <br> 17:00</p>
                        </div>
                    </div>
                </div>
                <div class="colonnaCorso">
                    <div class="corsoTitle">Corso Adulti</div>
                    <div class="containerSingoloCorso">
                        <div class="corsoDescrizione">
                            <p class="giornoCorso">Lunedì</p>
                            <p class="orarioCorso">16:00 <br> 17:00</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../Footer/footer.php'; ?>
    </div>
</body>
</html>