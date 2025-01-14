<style>

    .Sfooter { 
        color: #fff; 
        text-align: center;
        font-family: Arial, sans-serif;
        margin: 0;
        width: 100%;
    }
    
    .footerContent {
        width: 100%;
        margin-bottom: 20px;  
        background-color: #2c3e50; 
        margin: 0;
        flex-shrink: 0;
    }
    
    #footerButton{
        font-size: 16px;
        font-weight: bold; 
        padding: 5px; 
        margin-top: 5px;
        margin-bottom: 10px;
        width: 100px;
        height: 25px; 
        border-radius: 5px;    
        float: none;
    }
    
    #footerTitle{
        position: relative; 
        font-size: 20px; 
        font-weight: bold;
        text-align: center;
        background-color: #2c3e50;
        display: inline-block; 
    }
    
    #footerTitle::before, #footerTitle::after {
        content: "";
        display: inline-block; 
        width: 100%; 
        height: 2px;
        background-color: rgb(0, 0, 0);
    }
    
    #footerTitle::before{
        margin-bottom: 5px;
    }
    
    #footerCopy {
        font-size: 13px ; 
        border-top: 1px solid #4a4a4a; 
        padding: 4px; 
        background-color: #151d25;
        color: #ccc; 
    }

</style>

<footer class="Sfooter">
    <div class="footerContent">
        <p class="footerContent"><span id="footerTitle">Hai bisogno di aiuto?</span></p>
        <a id="footerButton" class="button" href="../" type="button">Contattaci</a>
    </div>
    <div id="footerCopy">
        <p>© 2024 Parkour Academy. All Rights Reserved.</p>
    </div>
</footer>