
CREATE TABLE mails (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    nome VARCHAR(255) NOT NULL,        
    email VARCHAR(255) NOT NULL,       
    messaggio TEXT NOT NULL,           
    data_invio TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
);
