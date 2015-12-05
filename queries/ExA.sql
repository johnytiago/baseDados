SELECT userid, email, nome, SUM(sucesso) AS SUCESSO, COUNT(sucesso) AS N_TENTATIVAS
FROM login NATURAL JOIN utilizador
GROUP BY userid
HAVING SUM(sucesso) < COUNT(sucesso)