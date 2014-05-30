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
        <table id="presenters">
          <thead>
            <tr>
              <th>編集</th>
              <th>発表種別</th>
              <th>ログイン名</th>
              <th>KG</th>
              <th>学年</th>
              <th>スライド</th>
              <th>予稿</th>
              <th>評価/コメント</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>編集</th>
              <th>発表種別</th>
              <th>ログイン名</th>
              <th>KG</th>
              <th>学年</th>
              <th>スライド</th>
              <th>予稿</th>
              <th>評価/コメント</th>
            </tr>
          </tfoot>
          <tbody>
<?php
$entries = $db -> fetchAllEntries();
while($row = $entries -> fetch()):
?>
            <tr>
              <td><a href="./edit.php?id=<?=$row["id"] ?>">○</a></td>
              <td><?=escapeHTML($db -> getClass($row["class_id"])) ?></td>
              <td><?=escapeHTML($row["login_name"]) ?></td>
              <td><?=escapeHTML($db -> getKG($row["kg_id"])) ?></td>
              <td><?=escapeHTML($db -> getGrade($row["grade_id"])) ?></td>
              <td><a href="./file.php?mode=0&f=<?=escapeHTML($row["slide_name"]) ?>" target="_blank"><?=escapeHTML($row["title"]) ?></a></td>
              <td><a href="./file.php?mode=1&f=<?=escapeHTML($row["handout_name"]) ?>" target="_blank">○</a></td>
              <td><a href="./comments.php?id=<?=escapeHTML($row["id"]) ?>" target="_blank"><?=$row["class_id"] > 1 ? "評価/コメント" : "コメント" ?></a></td>
            </tr>
<?php
endwhile;
?>
          </tbody>
        </table>
      </div>
    </div>
  </body>

</html>