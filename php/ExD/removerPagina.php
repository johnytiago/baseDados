<html>
    <body>
<?php
    $userid = $_REQUEST['user_id'];
    $pageid = $_REQUEST['page_id'];
    try
    {
        $host = "db.ist.utl.pt";
        $user ="ist177956";
        $password = "xkuj7282";
        $dbname = $user;
        $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        //UPDATE
        $sql = "UPDATE pagina SET ativa = false WHERE userid=$userid AND pagecounter=$pageid;";
        $db->query($sql);

        $sql = "UPDATE reg_pag SET ativa = false WHERE pageid=$pageid;";
        $db->query($sql);
;
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
