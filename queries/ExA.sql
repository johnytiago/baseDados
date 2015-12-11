SELECT userid, email, nome, SUM(sucesso) SUCESSO,
	COUNT(sucesso) N_TENTATIVAS
FROM login NATURAL JOIN utilizador
GROUP BY userid, email, nome
HAVING SUM(sucesso) < COUNT(sucesso);