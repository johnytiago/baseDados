<html>
    <head>
      <link rel='stylesheet' href='http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css'>
      <meta charset="utf-8"/>
    </head>
    <body>
<?php
    $UserId = $_REQUEST['UserId'];
    $PageId = $_REQUEST['PageId'];
    try
    {
        $host = "db.ist.utl.pt";
        $user ="ist178058";
        $password = "password";
        $dbname = $user;
        $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT 
                    P.userid,
                    P.pagecounter AS pageId,
                    P.nome AS nomePagina,
                    TR.nome AS nomeTipo,
                    R.nome AS nomeRegisto,
                    C.nome AS nomeCampo,
                    V.valor AS valorCampo
                FROM
                    valor V,
                    campo C,
                    reg_pag RP,
                    pagina P,
                    registo R,
                    tipo_registo TR
                WHERE
                    V.userid = C.userid
                    AND V.userid = RP.userid
                    AND V.userid = P.userid
                    AND V.userid = R.userid
                    AND V.userid = TR.userid
                    AND TR.typecnt = RP.typeid
                    AND R.typecounter = RP.typeid
                    AND C.typecnt = RP.typeid
                    AND V.typeid = RP.typeid
                    AND R.regcounter = RP.regid
                    AND R.regcounter = V.regid
                    AND C.campocnt = V.campoid
                    AND P.pagecounter = RP.pageid
                    AND P.ativa = 1
                    AND P.userid = {$UserId}
                    AND pageId = {$PageId}
                ORDER BY nomeTipo, nomeRegisto
                        ";

        $result = $db->query($sql);

        echo("<div class='container' id='content' tabindex='-1'>");
        echo("<div class='row'>");
        echo("<h2> Registos Contidos na Página</h2>\n");
        
        $rowAnterior;
        foreach($result as $row) {
          // Define pageName only once
          if (!$rowAnterior)
            echo "<h3> Pagina: {$row['nomePagina']}</h3>\n";

          // Define nomeTipo only when its diff from the 1 b4
          if ($row['nomeTipo'] != $rowAnterior['nomeTipo'])
            echo "<h4>Tipo de Registo: {$row['nomeTipo']}</h4>\n";
          echo "<ul style='list-style-type: none'>"; 

          // Add regName if new reg
          if ($row['nomeRegisto'] != $rowAnterior['nomeRegisto'])
            echo "<li><b>Nome Registo</b>: {$row['nomeRegisto']}</li>\n";
              echo "<ul style='list-style-type: none'>";
              // In case of same reg and tipe, only an li is added here
              // with the field/ value
              if ($row['nomeRegisto'] != $rowAnterior['nomeRegisto'])
                echo "<li><b>{$row['nomeCampo']}</b>: {$row['valorCampo']}</h3></li>\n";
              echo "</ul>";
          echo "</ul>"; 
          $rowAnterior = $row;
        }

        echo("</div>");
        echo("</div>");

        $db = null;
    }
    catch (PDOException $e)
    {
        echo("<p>ERROR: {$e->getMessage()}</p>");
    }
?>
    </body>
</html>
