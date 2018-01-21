<?php
define('DB_NAME', 'schedule_01');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');

$dsn = 'mysql:dbname=' . DB_NAME . '; host=' . DB_HOST . '; charset=utf8';
// dsn：Data Source Name

$option = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_STRINGIFY_FETCHES => false
);

try {
  $db = new PDO($dsn, DB_USER, DB_PASS);
  $stmt = $db->query('SELECT * FROM schedule');
  while($row = $stmt->fetch()) {
    $es_date = $row ['s_date'];
    $es_time = $row ['s_time'];

    // 入れるデータを定義
    $title = $_GET ['title'];
    $s_date = $_GET ['s_date_year'] . "-" . $_GET ['s_date_month'] . "-" . $_GET ['s_date_day'];
    $s_time = sprintf('%02d', $_GET ['s_time_hour']) . ":" . sprintf('%02d', $_GET ['s_time_minute']);
    $memo = $_GET ['memo'];

    $val2 = $s_time . ":00";
    $val3 = $s_date . " 00:00:00";

    if($es_time == $val2 && $es_date == $val3) {
      echo 'この時間はすでに予約されています。';
      $exist = 1;
      exit();
    } else {
      $exist = 0;
    }
  } // endwile

  if($exist == 0) {
    $sql = <<<SQL
INSERT INTO schedule (title,s_date,s_time,memo) VALUES(?, ?, ?, ?)
SQL;

    $stmt = $db->prepare($sql);
    $stmt->bindValue(1, $title, PDO::PARAM_STR);
    $stmt->bindValue(2, $s_date, PDO::PARAM_STR);
    $stmt->bindValue(3, $s_time, PDO::PARAM_STR);
    $stmt->bindValue(4, $memo, PDO::PARAM_STR);

    $stmt->execute();
  }
} catch(PDOException $e) {
  exit('Connention faild.' . $e->getMessage());
}

// SQL文がTUREなら成功、
if($sql) {
  echo "予約しました。";
  exit();
}

$db = null;
?>