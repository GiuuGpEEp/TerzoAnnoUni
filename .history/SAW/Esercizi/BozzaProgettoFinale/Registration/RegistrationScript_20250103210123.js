function goHome(){
    window.location.href = "../index/index.html";
}

function checkForm() {
    let isValid = true;
    
    const errors = document.querySelectorAll('.error');
    errors.forEach(error => error.style.display = 'none');
    
    const showError = (elementId) => {
        document.getElementById(elementId).style.display = 'inline';
        isValid = false;
    }

    // Controllo campi
    const nome = document.getElementById("firstname").value.trim();
    if (!nome) {
        showError("errorFirstname");
    }

    const cognome = document.getElementById("lastname").value.trim();
    if (!cognome) {
        showError("errorLastname");
    }

    const mail = document.getElementById("email").value.trim();
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!mail) {
        showError("errorMail");
    } else if (!emailPattern.test(mail)) {
        showError("errorMail");
    }

    const password = document.getElementById("pass").value.trim();
    if (!password) {
        showError("errorPass");
    }

    const confirm = document.getElementById("confirm").value.trim();
    if (password !== confirm) {
        document.getElementById("errorConfirm").innerText = "Le password non corrispondono";
        showError("errorConfirm");
    }

    // Risultato della validazione
    if (isValid) {
        window.alert("Form inviato!");
        document.getElementById("registrationForm").submit();
    } else {
        window.alert("Errore nell'invio del Form: Controlla i campi!");
    }

    return isValid;
}

function checkLogin(){

    let isValid = true;
    const showError = (elementId) => {
        document.getElementById(elementId).style.display = 'inline';
        isValid = false;
    }
    const mail = document.getElementById("email").value.trim();
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!mail) {
        showError("loginerrorMail");
    } else if (!emailPattern.test(mail)) {
        showError("errorMail");
    }

    const password = document.getElementById("pass").value.trim();
    if (!password) {
        showError("errorPass");
    }

    return isValid;
}