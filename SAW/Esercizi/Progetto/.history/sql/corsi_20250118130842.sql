CREATE TABLE corsi (
    id INT PRIMARY KEY AUTO_INCREMENT,
    categoria ENUM('bambini', 'adulti', 'ragazzi') NOT NULL,
    giorno VARCHAR(20) NOT NULL,
    calendario DATE NOT NULL,
    oraInizio TIME NOT NULL,
    oraFine TIME NOT NULL
);

INSERT INTO corsi (categoria, giorno, calendario, oraInizio, oraFine) VALUES
('bambini', 'Lunedì', '2023-10-02', '15:00:00', '16:00:00'),
('adulti', 'Martedì', '2023-10-03', '18:00:00', '19:30:00'),
('ragazzi', 'Mercoledì', '2023-10-04', '17:00:00', '18:30:00'),
('bambini', 'Giovedì', '2023-10-05', '15:00:00', '16:00:00'),
('adulti', 'Venerdì', '2023-10-06', '18:00:00', '19:30:00'),
('ragazzi', 'Sabato', '2023-10-07', '10:00:00', '11:30:00'),
('bambini', 'Lunedì', '2023-10-09', '15:00:00', '16:00:00'),
('adulti', 'Martedì', '2023-10-10', '18:00:00', '19:30:00'),
('ragazzi', 'Mercoledì', '2023-10-11', '17:00:00', '18:30:00'),
('bambini', 'Giovedì', '2023-10-12', '15:00:00', '16:00:00');
('ragazzi', 'Mercoled', '2023-10-12', '15:00:00', '16:00:00');

