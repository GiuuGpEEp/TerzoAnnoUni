CREATE TABLE corsi (
    id INT PRIMARY KEY AUTO_INCREMENT,
    categoria ENUM('bambini', 'adulti', 'adolescenti') NOT NULL,
    giorno VARCHAR(20) NOT NULL,
    ora TIME NOT NULL
);

