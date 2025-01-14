<?php
    session_start();
    
    if (!isset($_SESSION['username'])) {
        header("Location: ../Registration-login/Form.html");
        exit();
    }
    
    $_SESSION = []; 
    session_destroy(); 
    header(Location: https://site.tld'); 
    exit();

?>