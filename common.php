<?php
// for debugging
ini_set("error_reporting", E_ALL);
ini_set("display_errors", "1");
ini_set("session.cookie_httponly", 1);

date_default_timezone_set('Asia/Tokyo');

$utils = glob(__DIR__ . "/utils/*.php");
foreach ($utils as $file_path)
  require_once ($file_path);

$base_url = dirname($_SERVER["SCRIPT_NAME"]);

ini_set("session.use_only_cookies", 1);
ini_set("session.cookie_httponly", true);
ini_set("session.gc_maxlifetime", 60 * 60 * 10);
ini_set("session.cookie_path", $base_url."/");
session_start();

issueToken();
