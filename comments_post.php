<?php
require_once (__DIR__ . "/common.php");
require_once (__DIR__ . "/db/DBinterface.php");

$db = new DBinterface("./database.db");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("HTTP/1.1 403 Forbidden");
  die("403 Forbidden");
}

if ($_POST["token"] !== $_SESSION["token"]) {
  header("HTTP/1.1 403 Forbidden");
  die("403 Forbidden");
}

$comment = array("entry_id" => postParamValidate("entry_id"), "name" => postParamValidate("name"), "value" => postParamValidate("value"));
if (isset($_POST["pf"]))
  $comment["pf"] = postParamValidate("pf");
if (array_search(false, $comment, true) !== false) {
  die("Invalid params.<script src='./js/back.js'></script>");
}
try {
  $db -> insertComment($comment);
} catch(Exception $e) {
  die($e -> getMessage());
}

header("Location: {$base_url}/comments.php?id={$comment['entry_id']}");
