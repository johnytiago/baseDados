CREATE TABLE dataWarehouse(
	email VARCHAR(255) NOT NULL,
	dia INT NOT NULL,
    mes INT NOT NULL,
    ano INT NOT NULL,
    numero_tentativas_login INT NOT NULL,
    FOREIGN KEY (email) REFERENCES d_utilizador (email) ON DELETE CASCADE,
    FOREIGN KEY (dia, mes, ano) REFERENCES d_tempo (dia, mes, ano)
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


INSERT d_utilizador SELECT email, nome, pais, categoria FROM utilizador;
INSERT d_tempo (SELECT DATE_FORMAT(moment, '%d'), DATE_FORMAT(moment, '%m'), DATE_FORMAT(moment, '%Y') from sequencia);
INSERT dataWarehouse 	(SELECT B.email, DATE_FORMAT(A.moment, '%d'), DATE_FORMAT(A.moment, '%m'), DATE_FORMAT(A.moment, '%Y'), COUNT(*)
						FROM login A, utilizador B
						WHERE A.userid=B.userid
						GROUP BY A.moment, B.email);