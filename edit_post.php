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

if (isset($_POST["delete"]) and $_POST["delete"] == "delete") {
  try {
    $db -> deleteEntry(postParamValidate("id"), postParamValidate("password"));
    header("Location: {$base_url}/");
    die("Deleted.");
  } catch(Exception $e) {
    die($e -> getMessage());
  }
}

$entry = array("id" => postParamValidate("id"), "login_name" => postParamValidate("login_name"), "title" => postParamValidate("title"), "class_id" => postParamValidate("class_id"), "kg_id" => postParamValidate("kg_id"), "grade_id" => postParamValidate("grade_id"), "password" => postParamValidate("password"));
if (array_search(false, $entry, true) !== false) {
  die("Invalid params.<script src='./js/back.js'><script>");
}
try {
  if (isset($_FILES["slide"]) and $_FILES["slide"]["name"]) {
    $slide_name = $db -> putFile2Dir($_FILES["slide"], 0);
  } else {
    $slide_name = "";
  }
  if (isset($_FILES["handout"]) and $_FILES["handout"]["name"]) {
    $handout_name = $db -> putFile2Dir($_FILES["handout"], 1);
  } else {
    $handout_name = "";
  }
  $entry["slide_name"] = $slide_name;
  $entry["handout_name"] = $handout_name;
} catch(Exception $e) {
  die($e -> getMessage());
}

try {
  $db -> updateEntry($entry);
  header("Location: {$base_url}/");
} catch(Exception $e) {
  die($e -> getMessage());
}
