<html>
    <body>
<?php
    $userid = $_REQUEST['user_id'];
    $typeid = $_REQUEST['type_id'];
    $campid = $_REQUEST['camp_id'];
    try
    {
        $host = "db.ist.utl.pt";
        $user ="ist177956";
        $password = "xkuj7282";
        $dbname = $user;
        $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        //UPDATE
        $sql = "UPDATE campo SET ativo = false WHERE userid=$userid AND typecnt=$typeid AND campocnt=$campid;";
        $db->query($sql);

        $sql = "UPDATE valor SET ativo = false WHERE userid=$userid AND typeid=$typeid AND campoid=$campid;";
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
