function checkdata(){
    /*Devo controllare il valore di ritorno: se ho false non invio il form*/
    
    event.preventDefault(); //evito che il form venga inviato in caso ho elementi non corretti
    let isValid = true; //variabile per scegliere se inviare o meno il form (posso anche non usarla ma ritornare sempre false)
    
    const errors = document.querySelectorAll('.error');     //seleziono tutti gli elementi in .class
    errors.forEach(error => error.style.display = 'none');  //li metto tutti a none
    
    let nome = document.getElementById("firstname").value;
    if(!nome){
        document.getElementById("errorFirstname").style.display = 'inline'; //mostro l'errore
        isValid = false; //essendo false non invio il form
    }

    let cognome = document.getElementById("lastname").value;
    if(!cognome){
        document.getElementById("errorLastname").style.display = 'inline'; //mostro l'errore
        isValid = false; //essendo false non invio il form
    }

    let mail = document.getElementById("email").value;
    var emailPattern = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if(!mail){
        document.getElementById("errorMail").style.display = 'inline'; //mostro l'errore
        isValid = false; //essendo false non invio il form
    }

    if(!emailPattern.test(mail)) isValid = false;

    

    if(isValid){
        window.alert("Form inviato!");
        //logica php per invio effettivo del form
    }else{
        window.alert("Form non inviato!");
    }
    return isValid;
}
