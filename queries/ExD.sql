DROP VIEW IF EXISTS todosAtivos;
CREATE VIEW todosAtivos AS (
	SELECT R.userid, T.typecnt typeid,  R.regcounter regid, P.pagecounter pageid, R.nome
    FROM tipo_registo T, registo R, pagina P, reg_pag RP
    WHERE T.userid=R.userid AND T.userid=RP.userid AND T.userid=P.userid
        AND T.typecnt=R.typecounter AND T.typecnt=RP.typeid
        AND R.regcounter=RP.regid AND P.pagecounter=RP.pageid
        AND T.ativo AND R.ativo AND P.ativa AND RP.ativa);

SELECT DISTINCT userid
FROM tipo_registo
WHERE ativo AND (userid) NOT IN (
	SELECT DISTINCT userid
	FROM tipo_registo
	WHERE ativo AND (userid, typecnt) NOT IN (
		SELECT DISTINCT userid, typeid typecnt
		FROM todosAtivos));