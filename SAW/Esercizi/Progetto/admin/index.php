<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../Registration-login/Form.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <title>Admin Area</title>
    <link rel="icon" href="../Logo32.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="adminStyle.css">
</head>
<body>
    <div class="wrapper">
        <header class="header">
            <?php include '../NavBar/NavBar.php'; ?>
        </header>
        <div class="content">
            <div class="title">
                <h1>Admin Area</h1>
            </div>
            <div class="shared-container">
                <div class="admin-section">
                    <h2>Users</h2>
                    <table class="table">
                        <tr>
                            <th>Firstname</th>
                            <th>Lastname</th>
                            <th>Email</th>
                            <th>Role</th>
                        </tr>
                        <?php
                        include '../dbConnection.php';
                        $result = $conn->query("SELECT firstname, lastname, email, role FROM Users");
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['firstname']}</td>
                                    <td>{$row['lastname']}</td>
                                    <td>{$row['email']}</td>
                                    <td>{$row['role']}</td>
                                  </tr>";
                        }
                        $conn->close();
                        ?>
                    </table>
                </div>
                <div class="admin-section">
                    <h2>Prenotazioni</h2>
                    <table class="table">
                        <tr>
                            <th>ID Prenotazione</th>
                            <th>Corso ID</th>
                            <th>Email</th>
                            <th>Data Prenotazione</th>
                        </tr>
                        <?php
                        include '../dbConnection.php';
                        $result = $conn->query("SELECT id, corso_id, email, data_prenotazione FROM prenotazioni");
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['corso_id']}</td>
                                    <td>{$row['email']}</td>
                                    <td>{$row['data_prenotazione']}</td>
                                  </tr>";
                        }
                        $conn->close();
                        ?>
                    </table>
                </div>
            </div>
            <div class="admin-section">
                <h2>Corsi</h2>
                <table>
                    <tr>
                        <th>Categoria</th>
                        <th>Giorno</th>
                        <th>Calendario</th>
                        <th>Ora Inizio</th>
                        <th>Ora Fine</th>
                        <th>Azioni</th>
                    </tr>
                    <?php
                    include '../dbConnection.php';
                    $result = $conn->query("SELECT id, categoria, giorno, calendario, oraInizio, oraFine FROM corsi");
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['categoria']}</td>
                                <td>{$row['giorno']}</td>
                                <td>{$row['calendario']}</td>
                                <td>{$row['oraInizio']}</td>
                                <td>{$row['oraFine']}</td>
                                <td><button class='edit-button' data-id='{$row['id']}'>Modifica</button></td>
                              </tr>";
                    }
                    $conn->close();
                    ?>
                </table>
            </div>
            <div class="admin-section">
                <h2>Messaggi</h2>
                <table class="table">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Messaggio</th>
                        <th>Data Invio</th>
                        <th>Azioni</th>
                    </tr>
                    <?php
                    include '../dbConnection.php';
                    $result = $conn->query("SELECT id, nome, email, messaggio, data_invio FROM mails");
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['nome']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['messaggio']}</td>
                                <td>{$row['data_invio']}</td>
                                <td><button class='delete-button' data-id='{$row['id']}'>Elimina</button></td>
                              </tr>";
                    }
                    $conn->close();
                    ?>
                </table>
            </div>
        </div>
        <?php include '../Footer/footer.php'; ?>
    </div>
    
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Modifica Corso</h2>
            <form id="editForm">
                <input type="hidden" id="editId">
                <label for="editCategoria">Categoria:</label>
                <select id="editCategoria">
                    <option value="bambini">Bambini</option>
                    <option value="adulti">Adulti</option>
                    <option value="ragazzi">Ragazzi</option>
                </select>
                <label for="editGiorno">Giorno:</label>
                <input type="text" id="editGiorno">
                <label for="editCalendario">Calendario:</label>
                <input type="date" id="editCalendario">
                <label for="editOraInizio">Ora Inizio:</label>
                <input type="time" id="editOraInizio">
                <label for="editOraFine">Ora Fine:</label>
                <input type="time" id="editOraFine">
                <button type="submit">Salva Modifiche</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('editModal');
            const span = document.getElementsByClassName('close')[0];
            const editForm = document.getElementById('editForm');

            document.querySelectorAll('.edit-button').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    fetch(`getCorso.php?id=${id}`)
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('editId').value = data.id;
                            document.getElementById('editCategoria').value = data.categoria;
                            document.getElementById('editGiorno').value = data.giorno;
                            document.getElementById('editCalendario').value = data.calendario;
                            document.getElementById('editOraInizio').value = data.oraInizio;
                            document.getElementById('editOraFine').value = data.oraFine;
                            modal.style.display = 'block';
                        });
                });
            });

            span.onclick = function () {
                modal.style.display = 'none';
            }

            window.onclick = function (event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            }

            editForm.addEventListener('submit', function (event) {
                event.preventDefault();
                const id = document.getElementById('editId').value;
                const categoria = document.getElementById('editCategoria').value;
                const giorno = document.getElementById('editGiorno').value;
                const calendario = document.getElementById('editCalendario').value;
                const oraInizio = document.getElementById('editOraInizio').value;
                const oraFine = document.getElementById('editOraFine').value;

                fetch('updateCorso.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: id,
                        categoria: categoria,
                        giorno: giorno,
                        calendario: calendario,
                        oraInizio: oraInizio,
                        oraFine: oraFine
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Corso aggiornato con successo');
                        location.reload();
                    } else {
                        alert('Errore durante l\'aggiornamento del corso');
                    }
                });
            });

            document.querySelectorAll('.delete-button').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    if (confirm('Sei sicuro di voler eliminare questo messaggio?')) {
                        fetch('deleteMessage.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ id: id })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Messaggio eliminato con successo');
                                location.reload();
                            } else {
                                alert('Errore durante l\'eliminazione del messaggio');
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
