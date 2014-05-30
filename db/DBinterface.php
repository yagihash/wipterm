<?php
/**
 * SQLite interface class
 *
 */

class DBinterface {
  protected $db;

  public function __construct($db_path) {
    try {
      $this -> db = new PDO("sqlite:{$db_path}");
    } catch( Exception $e ) {
      throw new Exception("An error occured in opening database file.");
    }
  }

  public function __destruct() {
    $this -> db = NULL;
  }

  public function fetchAllEntries() {
    $q = "SELECT * FROM entries ORDER BY `order` ASC";
    $stmt = $this -> db -> prepare($q);
    $result = $stmt -> execute();
    if ($result)
      return $stmt;
    else
      return $result;
  }

  public function fetchClassedEntries() {
    $q = "SELECT * FROM entries ORDER BY class_id DESC";
    $stmt = $this -> db -> prepare($q);
    $result = $stmt -> execute();
    if ($result)
      return $stmt;
    else
      return $result;
  }

  public function fetchEntry($id) {
    $q = "SELECT * FROM entries WHERE id=?";
    $stmt = $this -> db -> prepare($q);
    $result = $stmt -> execute(array($id));
    if ($result)
      return $stmt -> fetch();
    else
      return $result;
  }

  public function doesExistEntry($entry_id) {
    $q = "SELECT * FROM entries WHERE id=?";
    $stmt = $this -> db -> prepare($q);
    $result = $stmt -> execute(array($entry_id));
    if ($result and $row = $stmt -> fetch()) {
      if ($row)
        return true;
    }
    return false;
  }
  
  public function doesExistKG($kg_id) {
    $q = "SELECT * FROM kg WHERE id=?";
    $stmt = $this -> db -> prepare($q);
    $result = $stmt -> execute(array($kg_id));
    if ($result and $row = $stmt -> fetch()) {
      if ($row)
        return true;
    }
    return false;
  }
  
  public function doesExistClass($class_id) {
    $q = "SELECT * FROM classes WHERE id=?";
    $stmt = $this -> db -> prepare($q);
    $result = $stmt -> execute(array($class_id));
    if ($result and $row = $stmt -> fetch()) {
      if ($row)
        return true;
    }
    return false;
  }
  
  public function doesExistGrade($grade_id) {
    $q = "SELECT * FROM grades WHERE id=?";
    $stmt = $this -> db -> prepare($q);
    $result = $stmt -> execute(array($grade_id));
    if ($result and $row = $stmt -> fetch()) {
      if ($row)
        return true;
    }
    return false;
  }

  public function checkPasswd($entry_id, $password) {
    $q = "SELECT password FROM entries WHERE id=?";
    $stmt = $this -> db -> prepare($q);
    $result = $stmt -> execute(array($entry_id));
    if ($result and $row = $stmt -> fetch()) {
      if ($row) {
        $value = explode(":", $row[0]);
        $salt = $value[0];
        $hash = $value[1];
        if ($hash === hash("sha512", $salt . $password))
          return true;
      }
    }
    return false;
  }

  public function registerEntry($entry) {
    $back = "<script src='./js/back.js'></script>";

    $login_name = $entry["login_name"];
    $kg_id = $entry["kg_id"];
    $title = $entry["title"];
    $class_id = $entry["class_id"];
    $grade_id = $entry["grade_id"];
    $handout_name = $entry["handout_name"];
    $slide_name = $entry["slide_name"];
    $password = $entry["password"];

    // validate login_name
    if (strlen($login_name) > 20 and !preg_match("/\A[a-zA-Z0-9\-\_]+\z/", $login_name)) {
      throw new Exception("Login name is invalid.{$back}");
    }

    // validate kg_id
    if (!preg_match("/\A[0-9]+\z/", $kg_id) or !$this -> doesExistKG($kg_id)) {
      throw new Exception("Invalid ID for KG.{$back}");
    }

    // validate title
    if (strlen($title) > 256) {
      throw new Exception("Too long title.{$back}");
    }

    // validate class_id
    if (!preg_match("/\A[0-9]+\z/", $class_id) or !$this -> doesExistClass($class_id)) {
      throw new Exception("Invalid ID for classification.{$back}");
    }

    // validate grade_id
    if (!preg_match("/\A[0-9]+\z/", $grade_id) or !$this -> doesExistGrade($grade_id)) {
      throw new Exception("Invalid ID for grade.{$back}");
    }

    // validate handout_name
    if (!file_exists(__DIR__ . "/../files/handouts/" . $handout_name)) {
      throw new Exception("Uploaded handout file doesn't exist.{$back}");
    }

    // validate slide_name
    if (!file_exists(__DIR__ . "/../files/slides/" . $slide_name)) {
      throw new Exception("Uploaded slide file doesn't exist.{$back}");
    }

    // validate password
    if (!$password) {
      throw new Exception("Empty password.{$back}");
    }
    $salt = bin2hex(openssl_random_pseudo_bytes(16));
    $password = $salt . ":" . hash("sha512", $salt . $password);

    $q = "INSERT INTO 'entries'(login_name, kg_id, title, class_id, grade_id, handout_name, slide_name, password) VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $this -> db -> prepare($q);
    $result = $stmt -> execute(array($login_name, $kg_id, $title, $class_id, $grade_id, $handout_name, $slide_name, $password));
    return $result;
  }

  public function updateEntry($entry) {
    $back = "<script src='./js/back.js'></script>";

    $entry_id = $entry["id"];
    $login_name = $entry["login_name"];
    $kg_id = $entry["kg_id"];
    $title = $entry["title"];
    $class_id = $entry["class_id"];
    $grade_id = $entry["grade_id"];
    $handout_name = $entry["handout_name"];
    $slide_name = $entry["slide_name"];
    $password = $entry["password"];

    $former = $this -> fetchEntry($entry_id);

    $updates = array("password" => $former["password"]);

    if ($this -> checkPasswd($entry_id, $password)) {
      // validate kg_id
      if (!preg_match("/\A[0-9]+\z/", $kg_id) or !$this -> doesExistKG($kg_id)) {
        throw new Exception("Invalid ID for KG.{$back}");
      } else if ($former["kg_id"] !== $kg_id) {
        $updates["kg_id"] = $kg_id;
      }

      // validate title
      if (strlen($title) > 256) {
        throw new Exception("Too long title.{$back}");
      } else if ($former["title"] !== $title) {
        $updates["title"] = $title;
      }

      // validate class_id
      if (!preg_match("/\A[0-9]+\z/", $class_id) or !$this -> doesExistClass($class_id)) {
        throw new Exception("Invalid ID for classification.{$back}");
      } else if ($former["class_id"] !== $class_id) {
        $updates["class_id"] = $class_id;
      }

      // validate grade_id
      if (!preg_match("/\A[0-9]+\z/", $grade_id) or !$this -> doesExistGrade($grade_id)) {
        throw new Exception("Invalid ID for grade.{$back}");
      } else if ($former["grade_id"] !== $grade_id) {
        $updates["grade_id"] = $grade_id;
      }

      // validate handout_name
      if (!file_exists(__DIR__ . "/../files/handouts/" . $handout_name)) {
        // throw new Exception("Uploaded handout file doesn't exist.");
      } else if ($handout_name !== "" and $former["handout_name"] !== $handout_name) {
        $updates["handout_name"] = $handout_name;
        unlink(__DIR__ . "/../files/handouts/" . $former["handout_name"]);
      }

      // validate slide_name
      if (!file_exists(__DIR__ . "/../files/slides/" . $slide_name)) {
        // throw new Exception("Uploaded slide file doesn't exist.");
      } else if ($slide_name !== "" and $former["slide_name"] !== $slide_name) {
        $updates["slide_name"] = $slide_name;
        unlink(__DIR__ . "/../files/slides/" . $former["slide_name"]);
      }

      $q = "UPDATE 'entries' SET ";
      foreach ($updates as $column => $value) {
        $q .= "{$column}=?,";
      }
      $q = substr($q, 0, -1);
      $q .= " WHERE id=?";
      $update_values = array_values($updates);
      $update_values[] = $entry_id;
      $stmt = $this -> db -> prepare($q);
      $result = $stmt -> execute($update_values);
      return $result;
    } else {
      throw new Exception("Wrong password.{$back}");
    }
  }

  public function deleteEntry($entry_id, $password, $admin = false) {
    $back = "<script src='./js/back.js'></script>";
    $entry = $this -> fetchEntry($entry_id);

    if ($admin or $this -> checkPasswd($entry_id, $password)) {
      $q = "DELETE FROM entries WHERE id=?";
      $stmt = $this -> db -> prepare($q);
      $result = $stmt -> execute(array($entry_id));
      unlink(__DIR__ . "/../files/slides/" . $entry["slide_name"]);
      unlink(__DIR__ . "/../files/handouts/" . $entry["handout_name"]);
      return $result;
    } else {
      throw new Exception("Wrong password.{$back}");
    }
  }

  public function putFile2Dir($tmp_file, $mode = 0) {
    $targets = array(__DIR__ . '/../files/slides/', __DIR__ . '/../files/handouts/');
    $target = $targets[$mode];

    if ($tmp_file["error"] !== 0) {
      throw new Exception("Error No.{$tmp_file["error"]}: Check this and contact admin.<br /><a href='http://jp.php.net/manual/ja/features.file-upload.errors.php'>http://jp.php.net/manual/ja/features.file-upload.errors.php</a>");
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $type = $finfo -> file($tmp_file['tmp_name']);
    if (!isset($tmp_file["error"]) or !is_int($tmp_file["error"])) {
      throw new Exception("An error occured in file uploading.");
    } else if ($type !== "application/pdf") {
      throw new Exception("Only pdf file can be accepted.");
    } else if ($tmp_file["size"] > 20000000) {
      throw new Exception("Uploaded file is too large.");
    } else {
      if (move_uploaded_file($tmp_file["tmp_name"], ($file_path = $target . bin2hex(openssl_random_pseudo_bytes(16)) . ".pdf"))) {
        chmod($file_path, 0644);
        $file_name = basename($file_path);
        return $file_name;
      } else {
        throw new Exception("An error occured in saving file.");
      }
    }
  }

  public function getAllClasses() {
    $q = "SELECT id, value FROM classes";
    $stmt = $this -> db -> prepare($q);
    $result = $stmt -> execute();
    if ($result)
      return $stmt;
    else
      return $result;
  }

  public function getClass($class_id) {
    $q = "SELECT value FROM classes WHERE id=?";
    $stmt = $this -> db -> prepare($q);
    $result = $stmt -> execute(array($class_id));
    $row = $stmt -> fetch();
    return $row["value"];
  }

  public function getAllKG() {
    $q = "SELECT id, value FROM kg";
    $stmt = $this -> db -> prepare($q);
    $result = $stmt -> execute();
    if ($result)
      return $stmt;
    else
      return $result;
  }

  public function getKG($kg_id) {
    $q = "SELECT value FROM kg WHERE id=?";
    $stmt = $this -> db -> prepare($q);
    $result = $stmt -> execute(array($kg_id));
    $row = $stmt -> fetch();
    return $row["value"];
  }

  public function getAllGrades() {
    $q = "SELECT id, value FROM grades";
    $stmt = $this -> db -> prepare($q);
    $result = $stmt -> execute();
    if ($result)
      return $stmt;
    else
      return $result;
  }

  public function getGrade($grade_id) {
    $q = "SELECT value FROM grades WHERE id=?";
    $stmt = $this -> db -> prepare($q);
    $result = $stmt -> execute(array($grade_id));
    $row = $stmt -> fetch();
    return $row["value"];
  }

  public function insertComment($comment) {
    $back = "<script src='./js/back.js'></script>";

    // validate entry_id
    if (!preg_match("/\A[0-9]+\z/", $comment["entry_id"]) or !$this -> doesExistEntry($comment["entry_id"])) {
      throw new Exception("Invalid entry id.{$back}");
    }

    // validate name
    if (strlen($comment["name"]) > 20) {
      throw new Exception("Too long name.{$back}");
    }

    // validate value
    if (strlen($comment["value"]) > 1024) {
      throw new Exception("Too long comment.{$back}");
    }

    if (!isset($comment["pf"]) or !($comment["pf"] <= 2 and $comment["pf"] >= 0))
      $comment["pf"] = 0;
    $values = array($comment["entry_id"], $comment["pf"], $comment["name"], $comment["value"]);

    $q = "INSERT INTO 'comments'(entry_id, pf, name, value) VALUES(?, ?, ?, ?)";
    $stmt = $this -> db -> prepare($q);
    $result = $stmt -> execute($values);
    return $result;
  }

  public function fetchComments($entry_id) {
    $q = "SELECT * FROM comments WHERE entry_id=?";
    $stmt = $this -> db -> prepare($q);
    $result = $stmt -> execute(array($entry_id));
    if ($result)
      return $stmt;
    else
      return $result;
  }

  public function setOrder() {
    $entries = $this -> fetchClassedEntries();
    $q = "UPDATE entries SET `order`=? WHERE id=?";
    $stmt = $this -> db -> prepare($q);
    $i = 1;
    while ($row = $entries -> fetch()) {
      $stmt -> execute(array($i, $row["id"]));
      $i++;
    }
  }

  public function changeOrder($entry_id, $dest) {
    $q = "UPDATE entries SET `order`=? WHERE id=?";
    $stmt = $this -> db -> prepare($q);
    $entries = $this -> fetchAllEntries();
    $i = 1;
    while ($row = $entries -> fetch()) {
      if ($i >= $dest) {
        $stmt -> execute(array($i + 1, $row["id"]));
      }
      $i++;
    }
    $stmt -> execute(array($dest, $entry_id));
    $entries = $this -> fetchAllEntries();
    $i = 1;
    while ($row = $entries -> fetch()) {
      if ($row["id"] != $entry_id) {
        $stmt -> execute(array($i, $row["id"]));
        $i++;
      }
    }
  }

  public function countEntries() {
    $q = "SELECT count(*) FROM entries";
    $stmt = $this -> db -> prepare($q);
    $stmt -> execute();
    $row = $stmt -> fetch();
    return $row[0];
  }

}
