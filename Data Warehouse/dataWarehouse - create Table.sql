DROP TABLE IF EXISTS d_utilizador;
CREATE TABLE d_utilizador(
	email VARCHAR(255) NOT NULL,
	nome VARCHAR(255) NOT NULL,
	pais VARCHAR(45) NOT NULL,
	categoria VARCHAR(45) NOT NULL,
PRIMARY KEY (email)
);

DROP TABLE IF EXISTS d_tempo;   
CREATE TABLE d_tempo(
	dia INT NOT NULL,
	mes INT NOT NULL,
	ano INT NOT NULL,
PRIMARY KEY (dia, mes, ano)
);

DROP TABLE IF EXISTS dataWarehouse_login;
CREATE TABLE dataWarehouse_login(
	email VARCHAR(255) NOT NULL,
	dia INT NOT NULL,
	mes INT NOT NULL,
	ano INT NOT NULL,
	numero_tentativas_login INT NOT NULL,
PRIMARY KEY (email, dia, mes, ano),
FOREIGN KEY (email) REFERENCES d_utilizador (email) ON DELETE CASCADE,
FOREIGN KEY (dia, mes, ano) REFERENCES d_tempo (dia, mes, ano)
);


INSERT d_utilizador
	SELECT email, nome, pais, categoria
	FROM utilizador;

INSERT d_tempo (
	SELECT DISTINCT DATE_FORMAT(moment, '%d'), DATE_FORMAT(moment, '%m'),
		DATE_FORMAT(moment, '%Y')
	FROM login);

INSERT dataWarehouse_login 	(
	SELECT B.email, DATE_FORMAT(A.moment, '%d'), DATE_FORMAT(A.moment, '%m'),
		DATE_FORMAT(A.moment, '%Y'), COUNT(*)
	FROM login A, utilizador B
	WHERE A.userid = B.userid
	GROUP BY A.moment, B.email);
