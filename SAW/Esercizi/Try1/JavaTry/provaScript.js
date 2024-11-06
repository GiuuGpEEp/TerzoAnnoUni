let original = "";

function modificaTesto(){
    let elemento = document.getElementById('prova'); 
    if(elemento){ //controllo se l'elemento esiste
        original = elemento.textContent;
        elemento.innerHTML = "Suca Sceesh";
    } else {
        console.error("Elemento non trovato");
    }
}

function annulla(){
    let elemento = document.getElementById('prova');
    if(elemento){
        elemento.innerHTML = original; //original è globale e viene modificato nella funzione precedente
    }else {
        console.error("Elemento non trovato");
    }
    
}

function goHome(){
    window.location.href = "../Try1.html";
}

