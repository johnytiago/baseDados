SELECT U.userid, U.nome, U.email, COUNT(RP.regid) AS numRegs,
	COUNT(DISTINCT P.pagecounter) AS numPags,
	COUNT(RP.regid)/COUNT(DISTINCT P.pagecounter) registosPorPagina
FROM utilizador U,
	pagina P LEFT JOIN reg_pag RP
		ON P.pagecounter=RP.pageid
			AND P.userid=RP.userid
WHERE U.userid = P.userid
GROUP BY userid, nome, email
ORDER BY registosPorPagina DESC;