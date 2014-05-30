<?php
require_once (__DIR__ . "/common.php");
require_once (__DIR__ . "/db/DBinterface.php");

$isAuthed = isset($_SESSION["isAuthed"]) ? $_SESSION["isAuthed"] : false;
if (!$isAuthed) {
  header("HTTP/1.1 403 Forbidden");
  die("403 Forbidden");
}

$db = new DBinterface("./database.db");

$entry_id = postParamValidate("entry_id");
try {
  if ($entry_id) {
    $db -> deleteEntry($entry_id, "", true);
  }
} catch(Exception $e) {
  die($e -> getMessage());
}

header("Location: {$base_url}/admin.php");
