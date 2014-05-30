<?php
require_once (__DIR__ . "/common.php");
require_once (__DIR__ . "/db/DBinterface.php");
$db = new DBinterface("./database.db");

?>
<!DOCTYPE html>
<html lang="ja">

  <head>
<?php
    require_once ("head.php");
?>
  </head>

  <body>
<?php require_once(__DIR__ ."/top_bar.php"); ?>
    <div id="wrap">
<?php require_once(__DIR__ ."/page_header.php"); ?>
      <div id="main" class="content">
      </div>
    </div>
  </body>
  
</html>