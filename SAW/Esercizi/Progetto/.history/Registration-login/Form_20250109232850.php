<!DOCTYPE html>
<html lang="it">
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="RegistrationStyle.css">
    <link rel="icon" href="../Logo32.ico" type="image/x-icon">
</head>

<body>
    <div class="wrapper">
            <header class="header">
                <?php include '../ShowProfileNavBar/NavBar.php' ; ?>
            </header>
            <div class="content">
                <p class="intro-text">Benvenuto! Registrati per creare un account, in modo da iniziare ad allenarti con noi. <br> Oppure accedi se possiedi gia' un account</p>
                <div class="Forms">
                    <div class="form-section" id="formSectionSu">
                        <div class="form-title">Crea un nuovo account</div>
                        <form class="signUpForm" onsubmit="return checkForm()" action="registration.php" method="post">
                            <div>
                                <label for="firstname">Nome:</label>
                                <input type="text" id="firstname" name="firstname" placeholder="Inserisci il tuo nome">
                                <span class="error" id="errorFirstname">Il campo nome e' obbligatorio</span>
                                <label for="lastname">Cognome:</label>
                                <input type="text" id="lastname" name="lastname" placeholder="Inserisci il tuo cognome">
                                <span class="error" id="errorLastname">Il campo cognome e' obbligatorio</span>
                                <label for="email">Email:</label>
                                <input type="email" id="email" name="email" placeholder="Inserisci la tua email">
                                <span class="error" id="errorMail">La email non e' valida</span>
                                <label for="pass">Password:</label>
                                <input type="password" id="pass" name="pass" placeholder="Inserisci la tua password">
                                <span class="error" id="errorPass">La password deve contenere almeno 8 caratteri</span>
                                <label for="confirm">Conferma Password:</label>
                                <input type="password" id="confirm" name="confirm" placeholder="Conferma la tua password">
                                <span class="error" id="errorConfirm"></span>
                                <input type="checkbox" id="showPassword" onclick="viewPassword()"> Mostra Password
                            </div>
                            <input type="submit" class="button" id="submitButton" name="submit" value="Registrati">
                        </form>
                    </div>

                    <div class="form-section" id="formSectionL">
                        <div class="form-title">Effettua il login</div>
                        <div class="loginForm">
                            <form class="LoginForm" onsubmit="return checkLogin()" action="login.php" method="post">
                                <div>
                                    <label for="email">Email:</label>
                                    <input type="email" id="loginEmail" name="email" placeholder="Inserisci la tua email" >
                                    <span class="error" id="loginErrorMail">Mail obbligatoria</span>
                                    <label for="pass">Password:</label>
                                    <input type="password" id="loginPass" name="pass" placeholder="Inserisci la tua password" >
                                    <span class="error" id="loginErrorPass">La password deve contenere almeno 8 caratteri</span>
                                    <input type="checkbox" id="showPasswordLogin" onclick="viewPasswordLogin()"> Mostra Password
                                </div>
                                <input type="submit" class="button" id="LoginsubmitButton" name="submit" value="Login">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="Sfooter">
                <div class="footerContent">
                     <p class="footerContent"><span id="footerTitle"> Hai bisogno di aiuto ? </span></p>
                     <button id="footerButton" class="button" type="button" onclick="">Contattaci</button>
                </div>
                <div id="footerCopy">
                    <p>© 2024 Parkour Academy. All Rights Reserved.</p>
                </div>
            </footer>
        </div>
<script src="RegistrationScript.js"></script>
</body>
</html>
