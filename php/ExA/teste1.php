<html>
    <body>
<?php
    $userid = $_REQUEST['user_id'];
    $pageName = $_REQUEST['pageName'];
    try
    {
        $host = "db.ist.utl.pt";
        $user ="ist177956";
        $password = "xkuj7282";
        $dbname = $user;
        $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        //PAGINA
        $sql = "SELECT MAX(pagecounter) AS MaxPageId FROM pagina;";
        $result = $db->query($sql);
        foreach($result as $row)
        {}
        $MaxPageId=$row['MaxPageId'];

        //SEQUENCIA
        $sql = "SELECT MAX(contador_sequencia) AS MaxSeqId FROM sequencia;";
        $result = $db->query($sql);
        foreach($result as $row)
        {}
        $MaxSeqId=$row['MaxSeqId'];

        //DATA
        $sql = "SELECT NOW()";
        $result = $db->query($sql);
        foreach($result as $row)
        {}
        $timeStamp=$row['NOW()'];

        //INSERCOES
        $sql = "INSERT INTO sequencia (userid, contador_sequencia,moment) VALUES ($userid, $MaxSeqId+1, '$timeStamp');";
        $db->query($sql);

        $sql = "INSERT INTO pagina (userid, pagecounter, nome, idseq, ativa) VALUES ($userid, $MaxPageId+1, '$pageName', $MaxSeqId+1, true);";
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
