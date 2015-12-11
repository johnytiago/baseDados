SELECT R.userid, R.typecounter, R.regcounter, R.nome
FROM registo R
WHERE NOT EXISTS (
	SELECT *
	FROM pagina P
	WHERE P.userid = R.userid
		AND NOT EXISTS (
			SELECT *
			FROM reg_pag RP
			WHERE R.userid = RP.userid
				AND R.typecounter = RP.typeid
            	AND R.regcounter = RP.regid
				AND P.pagecounter = RP.pageid));