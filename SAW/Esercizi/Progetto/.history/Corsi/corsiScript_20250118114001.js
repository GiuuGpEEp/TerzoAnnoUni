document.addEventListener('DOMContentLoaded', function() {
    fetch('corsi.php?get_course=true')
    .then(response => {
        if(!response.ok) {
            throw new Error('Errore nella richiesta');
        }
        return response.json();
    })
    .then(data => {
        const corsi = data;
        const corsiContainer = document.querySelector('.corsi-container');
        for(let corso of corsi) {
            const corsoContainer = document.createElement('div');
            corsoContainer.classList.add('corso');
            corsoContainer.innerHTML = `
                <div class="corso-image">
                    <img src="${corso.immagine}" alt="${corso.nome}">
                </div>
                <div class="corso-info