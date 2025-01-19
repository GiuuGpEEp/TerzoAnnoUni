document.addEventListener('DOMContentLoaded', function() {
    fetch('corsi.php?get_course=true')
    .then(response => {
        if(!response.ok) {
            throw new Error('Errore nella richiesta');
        }
        return response.json();
    })
    .then