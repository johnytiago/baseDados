DROP VIEW IF EXISTS todosAtivos;
CREATE VIEW todosAtivos AS (
	SELECT SQL_NO_CACHE R.userid, T.typecnt typeid,  R.regcounter regid, P.pagecounter pageid, R.nome
    FROM tipo_registo T, registo R, pagina P, reg_pag RP
    WHERE T.ativo AND R.ativo AND P.ativa AND RP.ativa
		AND T.userid=R.userid AND T.userid=RP.userid AND T.userid=P.userid
        AND T.typecnt=R.typecounter AND T.typecnt=RP.typeid
        AND R.regcounter=RP.regid AND P.pagecounter=RP.pageid);


-- alinea a
SELECT SQL_NO_CACHE A.userid, B.nome, B.email, COUNT(distinct pageid) AS NumPags, count(*) NumRegs, count(*)/COUNT(distinct pageid) AS NumeroMedioRegistosPorPagina
FROM  todosAtivos A, utilizador B
WHERE A.userid=B.userid
GROUP BY userid, nome, email
ORDER BY 6 DESC;

-- alinea b
SELECT SQL_NO_CACHE userid, pageid, nome AS Nome_Registo
FROM todosAtivos
ORDER BY userid, pageid, Nome_Registo