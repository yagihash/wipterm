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
<?php
    require_once (__DIR__ . "/top_bar.php");
?>
    <div id="wrap">
<?php
      require_once (__DIR__ . "/page_header.php");
?>
      <div id="main" class="content">
        <form id="register" method="POST" action="comments_post.php">
          <input type="hidden" name="token" value="<?=escapeHTML($_SESSION["token"]) ?>" />
          <input type="hidden" name="entry_id" value="<?=escapeHTML($entry["id"]) ?>" />
          <label><span>名前:</span>
            <input type="text" name="name" placeholder="お名前" required autofocus /></label>
<?php
if($entry["class_id"] > 1):
?>
          <label><span>合否:</span>
            <select name="pf">
              <option value="0">---</option>
              <option value="1">不合格</option>
              <option value="2">合格</option>
            </select>
          </label>
<?php
endif;
?>
          <label><span>コメント:</span>
            <textarea name="value" placeholder="必須" required></textarea>
          </label>
          <input type="submit" value="投稿" />
        </form>
        <div id="comments">
<?php
$comments = $db -> fetchComments($_GET["id"]);
$pf_array = array("---", "不合格", "合格");
while($row = $comments -> fetch()):
?>
          <div class="comment">
            <span class="name">Comment by <?=escapeHTML($row["name"]) ?></span>
<?php
  if($entry["class_id"] > 1):
?>
            <span class="pf">評価: <?=escapeHTML($pf_array[$row["pf"]]) ?></span>
<?php
  endif;
?>
            <p class="comment"><?=str_replace("\n", "<br />", escapeHTML($row["value"])) ?></p>
            <span class="time"><?=escapeHTML($row["timestamp"]) ?></span>
          </div>
<?php
endwhile;
?>
        </div>
      </div>
    </div>
  </body>

</html>