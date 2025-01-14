<!DOCTYPE html>
<html lang="it">
    <head>
        <title>PA - Chi Siamo?</title>
        <link rel="stylesheet" type="text/css" href="presentationStyle.css">
    </head>
    <body>
    <div class="wrapper">
        <header class="header">
            <?php include '../NavBar/NavBar.php'; ?>
        </header>
        <div class="content">
            <div class="title">
                <h1>Il Tuo Spazio Personale - Parkour Academy</h1>
                <p>Benvenuto, <?php echo htmlentities($name . " " . $surname); ?>!</p>
            </div>    
        </div>
        
    </body>
</html>    