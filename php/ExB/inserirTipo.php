<html>
    <body>
<?php
    $userid = $_REQUEST['user_id'];
    $typeName = $_REQUEST['typeName'];
    try
    {
        $host = "db.ist.utl.pt";
        $user ="ist177956";
        $password = "xkuj7282";
        $dbname = $user;
        $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        //PAGINA
        $sql = "SELECT MAX(typecnt) AS MaxTypeId FROM tipo_registo;";
        $result = $db->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $MaxTypeId=$row['MaxTypeId'];

        //SEQUENCIA
        $sql = "SELECT MAX(contador_sequencia) AS MaxSeqId FROM sequencia;";
        $result = $db->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $MaxSeqId=$row['MaxSeqId'];

        //DATA
        $sql = "SELECT NOW()";
        $result = $db->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $timeStamp=$row['NOW()'];

        //INSERCOES
        $sql = "INSERT INTO sequencia (userid, contador_sequencia,moment) VALUES ($userid, $MaxSeqId+1, '$timeStamp');";
        $db->query($sql);

        $sql = "INSERT INTO tipo_registo (userid, typecnt, nome, idseq, ativo) VALUES ($userid, $MaxTypeId, '$typeName', $MaxSeqId+1, true);";
        $db->query($sql);

        $db = null;
    }
    catch (PDOException $e)
    {
        echo("<p>ERROR: {$e->getMessage()}</p>");
    }
?>
    </body>
</html>
