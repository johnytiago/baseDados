<html>
    <body>
<?php
    $userid = $_REQUEST['user_id'];
    $typeid = $_REQUEST['type_id'];
    try
    {
        $host = "db.ist.utl.pt";
        $user ="ist177956";
        $password = "xkuj7282";
        $dbname = $user;
        $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        //UPDATE
        $sql = "UPDATE tipo_registo SET ativo = false WHERE userid=$userid AND typecnt=$typeid;";
        $db->query($sql);

        $sql = "UPDATE registo SET ativo = false WHERE typecounter=$typeid;";
        $db->query($sql);

        $sql = "UPDATE reg_pag SET ativa = false WHERE userid=$userid AND typeid=$typeid;";
        $db->query($sql);
;
        $db = null;
    }
    catch (PDOException $e)
    {
        echo("<p>ERROR: {$e->getMessage()}</p>");
    }
?>
    </body>
</html>
