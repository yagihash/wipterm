<?php
require_once (__DIR__ . "/common.php");

$targets = array(__DIR__ . "/files/slides/", __DIR__ . "/files/handouts/");
if (!isset($_GET["mode"]) or $_GET["mode"] >= count($targets))
  die("Input valid mode.");
$target = $targets[$_GET["mode"]];

$file_name = basename(isset($_GET["f"]) ? $_GET["f"] : "");
if(preg_match("/\A[a-z0-9]{32}\.pdf\z/", $file_name)){
  $file_path = $target . $file_name;
  if (file_exists($file_path)) {
    header("Content-Type: application/pdf");
    header('Content-Disposition: inline; filename="'.basename($file_name).'"');
    readfile($file_path);
  } else {
    header("HTTP/1.1 403 Forbidden");
    die("403 Forbidden");
  }
} else {
  header("HTTP/1.1 403 Forbidden");
  die("403 Forbidden");
}
