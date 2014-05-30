<?php
function postParamValidate($param) {
  return (isset($_POST[$param]) and !is_array($_POST[$param])) ? $_POST[$param] : false;
}

function escapeHTML($s) {
  return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}
