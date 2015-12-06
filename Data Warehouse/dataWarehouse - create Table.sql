CREATE TABLE dataWarehouse(
	email VARCHAR(255) NOT NULL,
	dia INT NOT NULL,
    mes INT NOT NULL,
    ano INT NOT NULL,
    media float NOT NULL,
    FOREIGN KEY (email) REFERENCES d_utilizador (email) ON DELETE CASCADE,
    FOREIGN KEY (dia, mes, ano) REFERENCES d_tempo (dia, mes, ano) ON DELETE CASCADE 
);

CREATE TABLE d_utilizador(
	email VARCHAR(255) NOT NULL,
    nome VARCHAR(255) NOT NULL,
    pais VARCHAR(45) NOT NULL,
    categoria VARCHAR(45) NOT NULL,
PRIMARY KEY (email)
);
    
CREATE TABLE d_tempo(
	dia INT NOT NULL,
    mes INT NOT NULL,
    ano INT NOT NULL,
PRIMARY KEY (dia, mes, ano)
);