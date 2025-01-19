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

function goCorsi