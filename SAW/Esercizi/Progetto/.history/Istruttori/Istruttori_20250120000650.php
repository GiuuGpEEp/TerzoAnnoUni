<!DOCTYPE html>
<html lang="it">
<head>
    <title>Istruttori</title>
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
        
        </div>
        <?php include '../Footer/footer.php'; ?>
        
    </div>
</body>
</html>
