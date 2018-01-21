<?php
header("Content-Type: text/html; charset=utf-8");
error_reporting(E_ALL & ~ E_NOTICE);

$dsn = 'mysql:dbname=schedule_01; host=localhost; charset=utf8';
$user = 'root';
$password = 'root';

$option = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_CASE => PDO::CASE_NATURAL,
    PDO::ATTR_PERSISTENT => true
);

try {
    $dbh = new PDO($dsn, $user, $password, $option);
    $dbh->exec('SET NAMES utf8');

    $stmt = $dbh->query('SELECT * FROM schedule');
    while ($row = $stmt->fetch()) {
        $exists_s_date = $row['s_date'];
        $exists_s_time = $row['s_time'];
        $exists_s_date = explode(" ", $exists_s_date);
        $exists_s_date = $exists_s_date[0];

        // 登録するデータを定義
        $title = $_GET['title'];
        // YYYY-mm-dd HH:ii:ss 形式に整形
        $timestamp = mktime(sprintf('%02d', $_GET['s_time_hour']), sprintf('%02d', $_GET['s_time_minute']), sprintf('%02d', $_GET['s_time_second']), $_GET['s_date_month'], $_GET['s_date_day'], $_GET['s_date_year']);
        $s_date = date("Y-m-d", $timestamp);
        $s_time = date("H:i:s", $timestamp);
        $memo = $_GET['memo'];

        if ($exists_s_time == $s_time && $exists_s_date == $s_date) {
            echo "すでに登録されています。<br>\n";
            $exists = 1;
            echo '<a href="calendar.php">戻る</a>';
            exit();
        } else {
            $exists = 0;
        }
    } // endwile

    if ($exists == 0) {
        $sql = <<<SQL
INSERT INTO schedule (title,s_date,s_time,memo) VALUES(?, ?, ?, ?)
SQL;

        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $title, PDO::PARAM_STR);
        $stmt->bindValue(2, $s_date, PDO::PARAM_STR);
        $stmt->bindValue(3, $s_time, PDO::PARAM_STR);
        $stmt->bindValue(4, $memo, PDO::PARAM_STR);

        $stmt->execute();
    }

    if ($sql) {
        //echo "登録しました。";
        header('Location: index.php');
        exit();
    }

    $dbh = null;
} catch (PDOException $e) {
    die('ERR! : ' . $e->getMessage());
}
?>