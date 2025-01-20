


<!DOCTYPE html>
<html>
<head>
    <title>PA - Corsi</title>
    <link rel="icon" href="../Logo32.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="corsiStyle.css">
</head>
<body>
    <div class="wrapper">
        <header class="header">
            <?php include '../Navbar/navbar.php'; ?>
        </header>
        <div class="content">
            <div class="title">
                <h1>Corsi</h1>
                <p>Scopri i nostri corsi e scegli quello che fa per te. <br></p>
            </div>
            <form id="searchBar" action="search.php" method="get">
                    <div class="searchContainer">
                        <input type="text" placeholder="Cerca..." name="search" required>
                        <button type="submit" id="searchButton">Cerca</button>        
                    </div>
                </form>
            <div class="tabellaCorsi">
                <div class="colonnaCorso" id="colonnaBambini">
                    <div class="corsoTitle">Corso Bambini</div>
                </div>
                <div class="colonnaCorso" id="colonnaRagazzi">
                    <div class="corsoTitle">Corso Ragazzi</div>
                </div>
                <div class="colonnaCorso" id="colonnaAdulti">
                    <div class="corsoTitle">Corso Adulti</div>
                </div>
            </div>
        </div>
        <?php include '../Footer/footer.php'; ?>
    </div>
</body>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const buttonClass = <?php echo json_encode($buttonClass); ?>;

    fetch('corsi.php?get_course=true')
        .then(response => {
            if (!response.ok) {
                throw new Error('Errore nella richiesta');
            }
            return response.json();
        })
        .then(data => {
            const colonnaBambini = document.getElementById('colonnaBambini');
            const colonnaRagazzi = document.getElementById('colonnaRagazzi');
            const colonnaAdulti = document.getElementById('colonnaAdulti');

            const userCourses = data.userCourses; // Lista dei corsi per cui l'utente è già registrato

            data.corsi.forEach(corso => {
                const container = document.createElement('div');
                container.classList.add('containerSingoloCorso');
                container.innerHTML = `
                    <div class="corsoDescrizione">
                        <p class="giornoCorso">${corso.giorno}  ${corso.calendario}</p>
                        <p class="orarioCorso">${corso.oraInizio} <br> ${corso.oraFine}</p>
                    </div>`;

                // Aggiungi il pulsante "Prenotati" o "Annulla prenotazione"
                const button = document.createElement('button');
                if (userCourses.includes(corso.id)) {
                    button.className = "prenotationButtonNoLogin";
                    button.innerText = "Annulla Prenotazione";
                } else {
                    button.className = buttonClass;
                    button.innerText = "Prenotati";
                }
                container.appendChild(button);

                // Aggiungi il listener al bottone
                button.addEventListener('click', function () {
                    if (button.innerText === "Prenotati") {
                        if (buttonClass === "prenotationButtonNoLogin") {
                            alert('Devi effettuare il login per prenotare un corso');
                        } else {
                            fetch("prenotazioni.php", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    idCorso: corso.id
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                alert(data.message);
                                if (!data.error) {
                                    button.className = "prenotationButtonNoLogin";
                                    button.innerText = "Annulla Prenotazione";
                                }
                            })
                            .catch(error => console.error("Errore:", error));
                        }
                    } else if (button.innerText === "Annulla Prenotazione") {
                        fetch("annullaPrenotazione.php", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                idCorso: corso.id
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            alert(data.message);
                            if (!data.error) {
                                button.className = buttonClass;
                                button.innerText = "Prenotati";
                            }
                        })
                        .catch(error => console.error("Errore:", error));
                    }
                });

                // Aggiungi il contenitore alla colonna appropriata
                if (corso.categoria === 'bambini') {
                    colonnaBambini.appendChild(container);
                }
                if (corso.categoria === 'ragazzi') {
                    colonnaRagazzi.appendChild(container);
                }
                if (corso.categoria === 'adulti') {
                    colonnaAdulti.appendChild(container);
                }
            });
        })
        .catch(error => {
            console.error("There was a problem with the fetch operation:", error);
        });
});
</script>
</html>
