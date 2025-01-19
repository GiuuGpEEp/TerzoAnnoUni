
CREATE TABLE mails (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Chiave primaria unica per ogni messaggio
    nome VARCHAR(255) NOT NULL,        -- Campo per il nome, obbligatorio
    email VARCHAR(255) NOT NULL,       -- Campo per l'email, obbligatorio
    messaggio TEXT NOT NULL,           -- Campo per il messaggio, obbligatorio
    data_invio TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Data e ora di invio del messaggio
);
