<?php 
    session_start();
?>

<style>
        .menu {
        display: flex;
        margin-left: auto;
        min-height: 40px;
        max-width: 100%;
        align-items: center;
        background: linear-gradient(to left, transparent 35%, #dd5e30 );
    }

    #main-menu {
        display: flex;
        flex-wrap: wrap;
        font-weight: bold;
        font-size: 32px; /* Increased */
        font-family: Montserrat, sans-serif;
        justify-content: flex-end;
        align-items: center;
        margin-right: 20px;
        margin-left: 20px;
        padding: 0;
        list-style: none;
    }


    .menu-item {
        position: relative;
        margin: 0 20px;
        border: 2px solid transparent;
        border-radius: 5px;
        transition: all 200ms ease-in-out;
    }

    .menu-item:hover {
        border-color: white;
    }

    .menu-link {
        text-decoration: none;
        color: white;
        font-weight: bold;
        padding: 5px 10px;
        transition: color 200ms ease-in-out;
        font-size: 24px; /* Increased */
    }

    .menu-link:hover {
        color: #502915;
    }

    .menu-item.active {
        border-color: white;
    }

</style>

<nav class="menu">
    <ul id="main-menu">
        <li class="menu-item">
            <a class="menu-link" onclick="goHome()">Home</a>
        </li>
        <li class="menu-item">
            <a class="menu-link" href="/corsi">Corsi</a>
        </li>
        <li class="menu-item">
            <a class="menu-link" href="/istruttori">Istruttori</a>
        </li>
        <li class="menu-item">
            <?php if(isset($_SESSION['username']))
                echo "<a class='menu-link' href='ShowProfile/show_profile.php'> Profilo</a>"
            ?>    
            <?php else
                echo "<a class='menu-link' href='../Registration-login/Form.html'> Sign-up e Login </a>"
            ?>
        </li>
    </ul>
</nav>