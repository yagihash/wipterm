<?php
require_once (__DIR__ . "/common.php");
require_once (__DIR__ . "/db/DBinterface.php");
$db = new DBinterface("./database.db");

$entry = $db -> fetchEntry($_GET["id"]);
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
        <form id="register" method="POST" action="edit_post.php" enctype="multipart/form-data">
          <input type="hidden" name="token" value="<?=escapeHTML($_SESSION["token"]) ?>" />
          <input type="hidden" name="id" value="<?=escapeHTML($entry["id"]) ?>" />
          <label><span>種別:</span>
            <select name="class_id" required>
<?php
$classes = $db -> getAllClasses();
while($row = $classes -> fetch()):
?>
              <option <?=$row["id"] == $entry["class_id"] ? "selected " : "" ?>value="<?=escapeHTML($row["id"]) ?>"><?=escapeHTML($row["value"]) ?></option>
<?php
endwhile;
?>
            </select></label>
          <label><span>ログイン名:</span>
            <input readonly type="text" name="login_name" placeholder="Ex.) yagihash" maxlength="20" value="<?=escapeHTML($entry["login_name"]) ?>" required />
          </label>
          <label><span>タイトル:</span>
            <input type="text" name="title" placeholder="Ex.) hogefugaの実装と提案" maxlength="100" value="<?=escapeHTML($entry["title"]) ?>" required />
          </label>
          <label><span>KG:</span>
            <select name="kg_id" required>
<?php
$kg = $db -> getAllKG();
while($row = $kg -> fetch()):
?>
              <option <?=$row["id"] == $entry["kg_id"] ? "selected " : "" ?>value="<?=escapeHTML($row["id"]) ?>"><?=escapeHTML($row["value"]) ?></option>
<?php
endwhile;
?>
            </select></label>
          <label><span>学年:</span>
            <select name="grade_id" required>
<?php
$grades = $db -> getAllGrades();
while($row = $grades -> fetch()):
?>
              <option <?=$row["id"] == $entry["grade_id"] ? "selected " : "" ?>value="<?=escapeHTML($row["id"]) ?>"><?=escapeHTML($row["value"]) ?></option>
<?php
endwhile;
?>
            </select></label>
          <label><span>スライド:</span>
            <input type="file" name="slide" accept="application/pdf" />
          </label>
          <label><span>予稿:</span>
            <input type="file" name="handout" accept="application/pdf" />
          </label>
          <label><span>削除:</span>
            <input type="checkbox" name="delete" value="delete" /></label>
          <label><span>編集パスワード:</span><input type="password" name="password" placeholder="登録時に作成したものを入力してください" required></label>
          <input type="submit" value="更新" />
        </form>
      </div>
    </div>
  </body>

</html>