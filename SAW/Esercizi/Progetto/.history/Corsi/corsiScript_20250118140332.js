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
                    <p class="giornoCorso">${corso.giorno}  ${corso.calendario}</p>
                    <p class="orarioCorso">${corso.oraInizio} <br> ${corso.oraFine}</p>
                </div>    
                <button class="$button">Prenotati</button> `;
            if(corso.categoria === 'bambini') {
                colonnaBambini.appendChild(container);
            }
            if(corso.categoria === 'ragazzi') {
                colonnaRagazzi.appendChild(container);
            }
            if(corso.categoria === 'adulti') {
                colonnaAdulti.appendChild(container);
            }
        });
    })
    .catch(error => {
        console.error("There was a problem with the fetch operation:",error);
    });
});                