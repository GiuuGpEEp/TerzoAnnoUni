CREATE TABLE corsi (
    id INT PRIMARY KEY AUTO_INCREMENT,
    categoria ENUM('bambini', 'adulti', 'ragazzi') NOT NULL,
    giorno VARCHAR(20) NOT NULL,
    da
    oraInizio TIME NOT NULL
    oraFine TIME NOT NULL
);


