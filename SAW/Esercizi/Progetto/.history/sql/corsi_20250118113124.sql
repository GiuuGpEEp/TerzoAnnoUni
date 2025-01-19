CREATE TABLE corsi (
    id INT PRIMARY KEY AUTO_INCREMENT,
    categoria ENUM('bambini', 'adulti', 'ragazzi') NOT NULL,
    giorno VARCHAR(20) NOT NULL,
    oraInizio TIME NOT NULL
    oraFine TIME NOT NULL
);

INSERT INTO corsi (categoria, giorno, oraInizio, oraFine) VALUES
('bambini', 'Lunedì', '09:00:00', '10:00:00'),
('adulti', 'Martedì', '18:00:00', '19:30:00'),
('ragazzi', 'Mercoledì', '15:00:00', '16:30:00'),
('bambini', 'Giovedì', '10:00:00', '11:00:00'),
('adulti', 'Venerdì', '19:00:00', '20:30:00');

