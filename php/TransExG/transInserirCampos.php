<?php session_start(); ?>
<html>
    <head>
        <meta charset="utf-8"/>
        <link rel='stylesheet' href='http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css'>
    </head>
    <body>
<?php
    $UserId = $_SESSION["UserId"];
    $TypeId = $_SESSION["TypeId"];
    $RegName = $_SESSION["RegName"];
    try
    {
        $host = "db.ist.utl.pt";
        $user ="ist178058";
        $password = "password";
        $dbname = $user;
        $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->query("start transaction;");

        //SEQUENCIA
        $sql = "SELECT MAX(contador_sequencia) AS MaxSeqId FROM sequencia;";
        $result = $db->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $MaxSeqId = $row['MaxSeqId'];

        //DATA
        $sql = "SELECT NOW()";
        $result = $db->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $timeStamp=$row['NOW()'];

        //MAXREGID
        $sql = "SELECT MAX(regcounter) AS MaxRegCounter FROM registo;";
        $result = $db->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $MaxRegCounter = $row['MaxRegCounter'];

        ////INSERCOES
        $MaxSeqId++;
        $sql = "INSERT INTO sequencia (userid, contador_sequencia, moment) VALUES ($UserId, $MaxSeqId, '$timeStamp');";
        $db->query($sql);
        $sql = "INSERT INTO registo (userid, typecounter, regcounter, nome, idseq, ativo) VALUES ($UserId, $TypeId, $MaxRegCounter+1, '$RegName', $MaxSeqId, true);";
        $db->query($sql);
        foreach ($_REQUEST['report'] as $campo) {
          $MaxSeqId++;
          $sql = "INSERT INTO sequencia (userid, contador_sequencia, moment) VALUES ($UserId, $MaxSeqId, '$timeStamp');";
          $db->query($sql);
          $campoId = (int)$campo['campoId']; 
          $campoValor = $campo['novoValor'];
          $sql = "INSERT INTO valor (userid, typeid, regid, campoId, valor, idseq, ativo) VALUES ($UserId, $TypeId, $MaxRegCounter+1, $campoId, '$campoValor', $MaxSeqId, true);";
          $db->query($sql);
        }
        $db->query("commit;");

        echo '<div class="container">
                <div class="row">
                  <h2>Sucesso! Campos alterados.</h2> 
                </div>
              </div>';

        $db = null;
    }
    catch (PDOException $e)
    {
      $db->query("rollback;");
      echo("<p>ERROR: {$e->getMessage()}</p>");
    }
?>
    </body>
</html>
