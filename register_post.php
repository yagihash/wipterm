<?php
require_once (__DIR__ . "/common.php");
require_once (__DIR__ . "/db/DBinterface.php");

$db = new DBinterface("./database.db");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("HTTP/1.1 403 Forbidden");
  die("403 Forbidden");
}

if($_POST["token"] !== $_SESSION["token"]) {
  header("HTTP/1.1 403 Forbidden");
  die("403 Forbidden");
}

$entry = array("login_name" => postParamValidate("login_name"), "title" => postParamValidate("title"), "class_id" => postParamValidate("class_id"), "kg_id" => postParamValidate("kg_id"), "grade_id" => postParamValidate("grade_id"), "password" => postParamValidate("password"));
if (array_search(false, $entry, true) !== false) {
  die("Invalid params.");
}

if (!isset($_FILES["slide"]) or !isset($_FILES["handout"])) {
  die("Invalid params.");
}
try {
  $slide_name = $db -> putFile2Dir($_FILES["slide"], 0);
  $handout_name = $db -> putFile2Dir($_FILES["handout"], 1);
} catch(Exception $e) {
  die($e -> getMessage());
}
$entry["slide_name"] = $slide_name;
$entry["handout_name"] = $handout_name;

try {
  $db -> registerEntry($entry);
  header("Location: {$base_url}/");
} catch(Exception $e) {
  die($e -> getMessage());
}
