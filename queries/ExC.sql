explain SELECT A.userid, B.nome, B.email, COUNT(distinct pageid) AS NumPags, count(*) NumRegs, count(*)/COUNT(distinct pageid) AS NumeroMedioRegistosPorPagina
FROM  reg_pag A NATURAL JOIN utilizador B
GROUP BY userid, pageid
ORDER BY 6 DESC;
