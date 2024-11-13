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

function Esercizio6(){

    let a = prompt("Dimmi il primo numero");
    let b = prompt("Dimmi il secondo numero");
    console.log(a+b); //Se utilizzo l'operatore + in questo caso ottengo una concatenazione.
    
    a = 5;
    b = 6;
    console.log(a+b); //in questo caso invece fornisce la somma numerica
    let myarray = [];
    myarray[25] = "Gionny";
    console.log(myarray.length);
    for(let i=0; i<myarray.length; i++){ //se metto <= esplode --> il ciclo continua all' "infinito" 
    myarray[i] = i*10;
    console.log(myarray[i]);
    } 

    //Oggetti javascript della pagina --> ne scorro i metodi. (location l'ho già utilizzato)
    //location.
    //navigator.
    //screen.
    //clear(); --> pulisce la console
}

document.addEventListener("DOMContentLoaded", function() { //inserendo questo ho la certezza che prima si eseguire il javascript sia caricato tutto il DOM
    let nome = prompt("Tu, Come ti chiami? (segnetto meno)");
    console.log(nome);

    let paragrafo = document.getElementById('benvenuto');
    if (paragrafo) {
        original =  paragrafo.textContent; 
        paragrafo.innerHTML = original+nome;
    } else {
        console.warn("Elemento con id 'benvenuto' non trovato nel DOM.");
    }

    let calc = this.getElementById("calcButton");
    calc.addEventListener("click", () => {
        window.open(
            "../../Calculator/calculator.html"
            "popupWindow",                  // Nome della finestra
    "width=600,height=400,left=100,top=100,resizable=yes,scrollbars=yes" /    
        )
    })
});


