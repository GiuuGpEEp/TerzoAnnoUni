CREATE TABLE Users (
    firstname VARCHAR(255) NOT NULL,
    lastname VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL PRIMARY KEY,
    password VARCHAR(255) NOT NULL,
    descrizione TEXT,
    età INT,
    genere ENUM('Uomo', 'Donna', 'Altro') -- Puoi personalizzare i valori
);
