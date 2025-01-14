<?php
    session_start();
    

    if(isset($_SESSION['valid_user'])) {
    
        $_SESSION = []; // Reset dell'array di sessione
    
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time()-86400, '/');
            // Reset del cookie di sessione
        }
        session_destroy(); // Chiusura sessione
        header('Location: https://site.tld'); // Reindirizzamento
        exit; // Fine script

    } else {
        header('Location: https://site.tld'); // Reindirizzamento
        exit; // Fine script
    }
?>