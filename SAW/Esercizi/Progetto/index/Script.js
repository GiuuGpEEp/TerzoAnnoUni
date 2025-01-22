document.addEventListener("DOMContentLoaded", function() {
let titolo = document.getElementById("titolo");
titolo.classList.add("fadeInAnimation");
});

function goHome(){
    window.location.href = "index.php";
}

function goPresentation(){
    window.location.href = "../Presentation/presentationPage.php";
}

function goCorsi(){
    window.location.href = "../Corsi/corsi.php";
}

function goEventi(){
    window.location.href = "../Eventi/eventi.php";
}

function goRegistration(){
    window.location.href = "../Registration-login/Form.php";
}

function goProfile(){
    window.location.href = "../ShowProfile/show_profile.php";
}