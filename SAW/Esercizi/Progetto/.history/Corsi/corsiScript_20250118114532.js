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
            container.classList.add('containerSingoloCorso');
            container.innerHTML = `
                <div class="corsoDescrizione">
                    <p class="giornoCorso">Lunedì</p>
                    <p class="orarioCorso">16:00 <br> 17:00</p>
                </div>    
                <button class="prenotationButton">Prenotati</button> 
