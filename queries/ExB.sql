DROP VIEW IF EXISTS todosAtivos;
CREATE VIEW todosAtivos AS (
	SELECT R.userid, T.typecnt typeid,  R.regcounter regid, P.pagecounter pageid, R.nome
    FROM tipo_registo T, registo R, pagina P, reg_pag RP
    WHERE T.userid=R.userid AND T.userid=RP.userid AND T.userid=P.userid
        AND T.typecnt=R.typecounter AND T.typecnt=RP.typeid
        AND R.regcounter=RP.regid AND P.pagecounter=RP.pageid
        AND T.ativo AND R.ativo AND P.ativa AND RP.ativa);

SELECT userid, typeid, regid, nome
FROM todosAtivos A
WHERE (userid, typeid, regid, pageid) NOT IN (
    SELECT B1.userid, typeid, regid, pageid
    FROM (SELECT userid, typeid,  regid
        FROM todosAtivos) B1,
        (SELECT userid, pageid
        FROM todosAtivos) B2
    WHERE B1.userid = B2.userid
        AND (B1.userid, typeid, regid, pageid) NOT IN (
            SELECT userid, typeid,  regid, pageid
            FROM todosAtivos))
ORDER BY userid;