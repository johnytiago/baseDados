<?php session_start(); ?>
<html>
    <head>
      <link rel='stylesheet' href='http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css'>
    </head>
    <body>
<?php
    $UserId = $_REQUEST['UserId'];
    $TypeId = $_REQUEST['TypeId'];
    $RegName = $_REQUEST['RegName'];
    try
    {
        $host = "db.ist.utl.pt";
        $user ="ist178058";
        $password = "password";
        $dbname = $user;
        $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        ////CAMPOS
        $sql = "SELECT campocnt AS CampoId, nome AS CampoName FROM campo WHERE userid='$UserId' AND typecnt='$TypeId';";
        $result = $db->query($sql);

        echo("<div class='container' id='content' tabindex='-1'>");
        echo("<div class='row'>");
        echo("<h3> Adicionar valores aos campos </h3>\n");
        echo("<form action='inserirCampos.php' method='post'>\n");

        $_SESSION["UserId"] = $UserId;
        $_SESSION["TypeId"] = $TypeId; 
        $_SESSION["RegName"] = $RegName; 
        
        $iterator = 0;
        foreach($result as $row) {
          echo("<p><div class='.col-md-4'><b>Campo: </b>\"{$row['CampoName']}\" com <b>Id: </b>{$row['CampoId']}, </div><div class='.col-md-4 .col-md-offset-4'><b>Insira novo valor</b>: ");
          echo("<input name=\"report[<?= $iterator ?>][campoId]\" type='hidden' value='{$row['CampoId']}' \"/>");
          echo("<input name=\"report[<?= $iterator ?>][novoValor]\" type='text' size='20' /></div> </p>\n");
          $iterator++; 
        }
        echo("<p><input type='submit' value='Submit'/></p>\n");
        echo("</form>\n");
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
