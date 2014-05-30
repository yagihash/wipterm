<?php
require_once (__DIR__ . "/common.php");
require_once (__DIR__ . "/db/DBinterface.php");

$isAuthed = isset($_SESSION["isAuthed"]) ? $_SESSION["isAuthed"] : false;
if (!$isAuthed) {
  header("HTTP/1.1 403 Forbidden");
  die("403 Forbidden");
}

$db = new DBinterface("./database.db");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("HTTP/1.1 403 Forbidden");
  die("403 Forbidden");
}

if ($_POST["token"] !== $_SESSION["token"]) {
  header("HTTP/1.1 403 Forbidden");
  die("403 Forbidden");
}

$db -> setOrder();

header("Location: {$base_url}/admin.php");
