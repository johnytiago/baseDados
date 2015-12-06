SELECT U.categoria, U.email, D.mes, D.ano, D.Media_Tentativas_Login
FROM d_utilizador U,   (SELECT A.email, A.mes, A.ano, AVG(A.numero_tentativas_login) AS Media_Tentativas_Login
						FROM dataWarehouse A, d_utilizador B
						WHERE A.email=B.email 
						GROUP BY A.email, A.ano, A.mes WITH ROLLUP) D
WHERE U.email=D.email AND U.pais='Portugal'
ORDER BY U.categoria, U.email, -D.ano DESC, -D.mes DESC, D.Media_Tentativas_Login;
