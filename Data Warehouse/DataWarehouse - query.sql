SELECT U.categoria, U.email, D.mes, D.ano, D.Media_Tentativas_Login
FROM d_utilizador U,   (SELECT A.email, A.mes, A.ano, B.pais, AVG(A.numero_tentativas_login) AS Media_Tentativas_Login
						FROM dataWarehouse A, d_utilizador B
						WHERE A.email=B.email 
						GROUP BY A.email, A.ano, A.mes WITH ROLLUP) D
WHERE U.email=D.email AND U.pais='Portugal'
GROUP BY U.categoria, U.email, D.mes, D.ano, D.Media_Tentativas_Login;
