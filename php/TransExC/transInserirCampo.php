<html>
    <body>
<?php
    $userid = $_REQUEST['user_id'];
    $typeid = $_REQUEST['type_id'];
    $campname = $_REQUEST['camp_name'];
    try
    {
        $host = "db.ist.utl.pt";
        $user ="ist177956";
        $password = "xkuj7282";
        $dbname = $user;
        $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->query("start transaction;");

        //CAMPOS
        $sql = "SELECT MAX(campocnt) AS MaxCampId FROM campo;";
        $result = $db->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $MaxCampId=$row['MaxCampId'];

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

        $sql = "INSERT INTO campo (userid, typecnt, campocnt, nome, idseq, ativo) VALUES ($userid, $typeid, $MaxCampId+1,'$campname', $MaxSeqId+1, true);";
        $db->query($sql);

        $db->query("commit;");

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
