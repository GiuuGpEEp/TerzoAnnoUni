CREATE TABLE corsi (
    id INT PRIMARY KEY AUTO_INCREMENT,
    categoria ENUM('bambini', 'adulti', 'ragazzi') NOT NULL,
    giorno VARCHAR(20) NOT NULL,
    oraInizio TIME NOT NULL
);

