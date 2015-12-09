SELECT U.userid
FROM utilizador U
WHERE NOT EXISTS (
    SELECT *
    FROM tipo_registo T
    WHERE U.userid = T.userid
        AND NOT EXISTS (
        SELECT *
        FROM reg_pag R
        WHERE T.userid = R.userid
            AND T.typecnt = R.typeid));