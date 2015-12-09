SELECT U.userid, U.nome, U.email, COUNT(RP.regid) AS NumRegs, COUNT(DISTINCT P.pagecounter) AS NumPags, count(RP.regid)/COUNT(DISTINCT P.pagecounter) AS NumeroMedioRegistosPorPagina
FROM utilizador U, pagina P LEFT JOIN reg_pag RP
	ON P.pagecounter=RP.pageid AND P.userid=RP.userid
WHERE U.userid=P.userid
GROUP BY userid, nome, email
ORDER BY 6 DESC;
