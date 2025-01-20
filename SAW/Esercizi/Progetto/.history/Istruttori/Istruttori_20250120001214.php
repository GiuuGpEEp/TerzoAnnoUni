<!DOCTYPE html>
<html lang="it">
<head>
    <title>Istruttori</title>
    <style>
        html, body {
            background-color: #ece4e4;
            height: 100%;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
        }

        .wrapper {
            display: flex;
            flex-direction: column; /* Imposta una colonna verticale per il layout */
            min-height: 100vh; /* Assicura che il wrapper occupi l'intera altezza della finestra */
        }

        .content {
            flex: 1; /* Spinge il footer in basso */
            padding: 20px;
            text-align: center;
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

        .button {
            background-color: #ff7962; /* Arancione */
            border: none;
            color: white;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 18px; 
            margin-top: 15px;
            border-radius: 10px;
            padding: 10px 20px;
            font-family: Montserrat, sans-serif;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #941600; /* Arancione scuro */
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <header class="header">
            <?php include '../NavBar/NavBar.php'; ?>
        </header>
        <div class="content">
            <h1 class="error">Nessun Istruttore ancora regisr</h1>

        </div>
        <?php include '../Footer/footer.php'; ?>
        
    </div>
</body>
</html>
