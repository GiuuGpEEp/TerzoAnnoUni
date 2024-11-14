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

document.addEventListener("DOMContentLoaded", function() { //inserendo questo ho la certezza che prima si eseguire il javascript sia caricato tutto il DOM
    let nome = prompt("Tu, Come ti chiami? (segnetto meno)");
    if(!nome){
        nome = "GiusPepe";
    }
    console.log(nome);

    let paragrafo = document.getElementById('benvenuto');
    if (paragrafo) {
        original =  paragrafo.textContent; 
        paragrafo.innerHTML = original+nome;
    } else {
        console.warn("Elemento con id 'benvenuto' non trovato nel DOM.");
    }

    let calc = document.getElementById("calcButton");
    let clessidra = document.getElementById("ImmagineAnimata");
    let testoCless = document.getElementById("testoClessidra");
    calc.addEventListener("click", () => {
        window.open(
            "../../Calculator/calculator.html",
            "targetIframe",
            "popupWindow",                  
            "width=600,height=400,left=100,top=100,popup=true"   
        );
        document.getElementById("targetIframe").style.border = "2px solid #164f0b";
        testoCless.classList.add("fadeInAnimation");
        clessidra.style.transition = " 30s linear";
        clessidra.classList.add("rotate"); 
    });
    
    clessidra.addEventListener("transitionend",() =>{
        document.getElementById("targetIframe").src = "about:blank";
        document.getElementById("targetIframe").style.border = "none";
        testoCless.classList.remove("fadeInAnimation");
    })

    let closeButton = document.getElementById("closeButton");
    closeButton.addEventListener("click",() =>{
        clessidra.classList.remove("rotate");
        clessidra.style.transition = "";
        document.getElementById("targetIframe").src = "about:blank";
        document.getElementById("targetIframe").style.border = "none";
        testoCless.classList.remove("fadeInAnimation");
    })


});


