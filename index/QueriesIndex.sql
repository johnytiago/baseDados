-- alinea a
SELECT SQL_NO_CACHE U.userid, U.nome, U.email, COUNT(RP.regid) AS NumRegs, COUNT(DISTINCT P.pagecounter) AS NumPags, count(RP.regid)/COUNT(DISTINCT P.pagecounter) AS NumeroMedioRegistosPorPagina
FROM utilizador U, pagina P LEFT JOIN reg_pag RP
	ON P.pagecounter=RP.pageid AND P.userid=RP.userid
WHERE U.userid=P.userid
GROUP BY userid, nome, email
ORDER BY 6 DESC;

-- alinea b
SELECT SQL_NO_CACHE RP.userid, RP.pageid, RP.regid, R.nome AS Nome_Registo
FROM reg_pag RP, registo R
WHERE RP.userid=R.userid AND RP.regid=R.regcounter
ORDER BY RP.userid, RP.pageid, RP.regid;
