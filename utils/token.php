<?php
function issueToken() {
  if(!isset($_SESSION["token"])){
    $token = bin2hex(openssl_random_pseudo_bytes(32));
    $_SESSION["token"] = $token;
  }
  return $_SESSION["token"];
}

function checkToken($token) {
  return $_SESSION["token"] === $token;
}
