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
    <?php
    require_once (__DIR__ . "/top_bar.php");
    ?>
    <div id="wrap">
      <?php
      require_once (__DIR__ . "/page_header.php");
      ?>
      <div id="main" class="content">
        <form id="register" method="POST" action="register_post.php" enctype="multipart/form-data">
          <input type="hidden" name="token" value="<?=escapeHTML($_SESSION["token"]) ?>" />
          <label><span>種別:</span>
            <select name="class_id" required>
<?php
$classes = $db -> getAllClasses();
while($row = $classes -> fetch()):
?>
              <option value="<?=escapeHTML($row["id"]) ?>"><?=escapeHTML($row["value"]) ?></option>
<?php
endwhile;
?>
            </select></label>
          <label><span>ログイン名:</span>
            <input type="text" name="login_name" placeholder="Ex.) yagihash" maxlength="20" required />
          </label>
          <label><span>タイトル:</span>
            <input type="text" name="title" placeholder="Ex.) hogefugaの実装と提案" maxlength="100" required />
          </label>
          <label><span>KG:</span>
            <select name="kg_id" required>
<?php
$kg = $db -> getAllKG();
while($row = $kg -> fetch()):
?>
              <option value="<?=escapeHTML($row["id"]) ?>"><?=escapeHTML($row["value"]) ?></option>
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
              <option value="<?=escapeHTML($row["id"]) ?>"><?=escapeHTML($row["value"]) ?></option>
<?php
endwhile;
?>
            </select></label>
          <label><span>スライド:</span>
            <input type="file" name="slide" accept="application/pdf" required />
          </label>
          <label><span>予稿:</span>
            <input type="file" name="handout" accept="application/pdf" required />
          </label>
          <label><span>編集パスワード:</span><input type="text" name="password" placeholder="これ専用のものを作ってください" required></label>
          <input type="submit" value="登録" />
        </form>
      </div>
    </div>
  </body>

</html>