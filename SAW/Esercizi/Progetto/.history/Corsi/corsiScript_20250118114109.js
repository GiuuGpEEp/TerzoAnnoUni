document.addEventListener('DOMContentLoaded', function() {
    fetch('corsi.php?get_course=true')
    .then(response => {
        if(!response.ok) {
            throw new Error('Errore nella richiesta');
        }
        return response.json();
    })
    .then(data => {
        const colonnaBambini = document.getElementById('colonnaBambini');
        const colonnaRagazzi = document.getElementById('colonnaRagazzi');
        const colonnaAdulti = document.getElementById('colonnaAdulti');

        data.forEach(corso => {
            const container = document.createElement('div');
            
