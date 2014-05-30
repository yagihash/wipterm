<?php
require_once (__DIR__ . "/common.php");
require_once (__DIR__ . "/db/DBinterface.php");
$db = new DBinterface("./database.db");

if ($_SERVER["REQUEST_METHOD"] === "POST" and
    isset($_POST["id"]) and $_POST["id"] === "admin" and
    isset($_POST["password"]) and
    hash("sha512", $_POST["password"]) === "3581c8e3ecf4eafe8d375f93e66dd3c0ec4b9e82dd9f64ca7636c931c1e94c741e7f8cff4009669fae347107ba672610f6c89ea54ee4bb8a7340240a6bdd3293") {
  $_SESSION["isAuthed"] = true;
  session_regenerate_id(true);
  echo "authedauthedauthed";
}
$isAuthed = isset($_SESSION["isAuthed"]) ? $_SESSION["isAuthed"] : false;
?>
<!DOCTYPE html>
<html lang="ja">

  <head>
<?php
    require_once ("head.php");
?>
  </head>

  <body>
<?php
    require_once (__DIR__ . "/top_bar.php");
?>
    <div id="wrap">
<?php
      require_once (__DIR__ . "/page_header.php");
?>
      <div id="main" class="content">
        <h2>Admin menu</h2>
<?php
if($isAuthed):
?>
        <h3>登録削除</h3>
        <form class="register" method="POST" action="admin_delete.php">
          <label><span>ログイン名:</span>
            <select name="entry_id">
<?php
  $entries = $db -> fetchAllEntries();
  while($row = $entries -> fetch()):
?>
              <option value="<?=escapeHTML($row["id"]) ?>"><?=escapeHTML($row["login_name"]) ?></option>
<?php
  endwhile;
?>
            </select>
          </label>
          <input type="submit" value="削除" />
        </form>
        <h3>発表順変更</h3>
        <form class="register" method="POST" action="admin_.php">
          <label><span>ログイン名:</span>
            <select name="entry_id">
<?php
  $entries = $db -> fetchAllEntries();
  while($row = $entries -> fetch()):
?>
              <option value="<?=escapeHTML($row["id"]) ?>"><?=escapeHTML($row["login_name"]) ?></option>
<?php
  endwhile;
?>
            </select>
          </label>
          <input type="submit" value="変更" />
        </form>
<?php
else:
?>
        <form class="register" method="POST" action="admin.php">
          <label><span>ID:</span>
            <input type="text" name="id" autofocus required /></label>
          <label><span>PW:</span>
            <input type="password" name="password" required /></label>
          <input type="submit" value="Submit" />
        </form>
<?php
endif;
?>
      </div>
    </div>
  </body>

</html>