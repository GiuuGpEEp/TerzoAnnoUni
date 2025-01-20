<?php
session_start();

// Controlla se l'utente è loggato e imposta la classe del bottone
if (!isset($_SESSION['username'])) {
    $buttonClass = "prenotationButtonNoLogin";
} else {
    $buttonClass = "prenotationButtonLogin";
}

if (isset($_GET['get_course']) || isset($_GET['search'])) {
    include '../dbConnection.php';

    // Ottieni l'email dell'utente se loggato
    if(isset($_SESSION['username'])){
        $email = $_SESSION['username'];
    } else {
        $email = null;
    }

    // Se è presente un parametro di ricerca, aggiungi un filtro alla query
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $searchTerm = $_GET['search'];
        $searchTerm = htmlspecialchars($searchTerm);
        $searchTerm = "%" . $searchTerm . "%";  //%search% mi permette di cercare tutte le parole che contengono search e non che sono uguali a search (usato spesso con like)
        $stmt = $conn->prepare("SELECT * FROM Corsi WHERE giorno LIKE ? OR calendario LIKE ?");
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
    }
    else {
        $stmt = $conn->prepare("SELECT * FROM Corsi");
    }

    $corsi = [];
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $corsi[] = $row;
            }
        }
    }
    
    $userCourses = [];
    if ($email) {
        $checkQuery = $conn->prepare("SELECT corso_id FROM prenotazioni WHERE email = ?");
        $checkQuery->bind_param("s", $email);
        if ($checkQuery->execute()) {
            $result = $checkQuery->get_result();
            while ($row = $result->fetch_assoc()) {
                $userCourses[] = $row['corso_id'];
            }
        }
        $checkQuery->close();
    }

    $stmt->close();
    $conn->close();
    
    $response = [
        'corsi' => $corsi,
        'userCourses' => $userCourses
    ];

    // Restituisci i dati come JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; 
}
?>

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
                    
                </div>
                <div class="colonnaCorso" id="colonnaRagazzi">
                   
                </div>
                <div class="colonnaCorso" id="colonnaAdulti">
                    
                </div>
            </div>
        </div>
        <?php include '../Footer/footer.php'; ?>
    </div>
</body>
<script>
document.addEventListener('DOMContentLoaded', function () {
    
    const searchForm = document.getElementById('searchBar');
    const searchInput = document.querySelector('input[name="search"]');
    const colonnaBambini = document.getElementById('colonnaBambini');
    const colonnaRagazzi = document.getElementById('colonnaRagazzi');
    const colonnaAdulti = document.getElementById('colonnaAdulti');

    // Funzione per caricare i corsi
    function caricaCorsi(url) {
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Errore nella richiesta');
                }
                return response.json();
            })
            .then(data => {
                // Pulisci le colonne
                colonnaBambini.innerHTML = '<div class="corsoTitle">Corso Bambini</div>';
                colonnaRagazzi.innerHTML = '<div class="corsoTitle">Corso Ragazzi</div>';
                colonnaAdulti.innerHTML = '<div class="corsoTitle">Corso Adulti</div>';

                const userCourses = data.userCourses;

                // Aggiungi i corsi alle colonne
                data.corsi.forEach(corso => {
                    const container = document.createElement('div');
                    container.classList.add('containerSingoloCorso');
                    container.innerHTML = `
                        <div class="corsoDescrizione">
                            <p class="giornoCorso">${corso.giorno}  ${corso.calendario}</p>
                            <p class="orarioCorso">${corso.oraInizio} <br> ${corso.oraFine}</p>
                        </div>`;

                    const button = document.createElement('button');
                    if (userCourses.includes(corso.id)) { //al posto della funzione isIn fatta in precedenza
                        button.className = "prenotationButtonNoLogin";
                        button.innerText = "Annulla Prenotazione";
                    } else {
                        button.className = <?php echo json_encode($buttonClass); ?>;
                        button.innerText = "Prenotati";
                    }
                    container.appendChild(button);

                    //Gestione Prenotazioni
                    
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
                console.error('There was a problem with the fetch operation:', error);
            });
    }

    // Carica tutti i corsi all'inizio
    caricaCorsi('corsi.php?get_course=true');

    // Gestisci la ricerca
    searchForm.addEventListener('submit', function (event) {
        event.preventDefault();
        const searchValue = searchInput.value.trim();
        if (searchValue) {
            caricaCorsi(`corsi.php?search=${encodeURIComponent(searchValue)}`);
        } else {
            caricaCorsi('corsi.php?get_course=true');
        }
    });
});

</script>
</html>
