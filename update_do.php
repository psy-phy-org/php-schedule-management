<?php
#header("Content-Type: text/html; charset=utf-8");

error_reporting(E_ALL & ~ E_NOTICE);

$id = htmlspecialchars($_POST['id']);
$title = htmlspecialchars($_POST['title']);
$s_date = htmlspecialchars($_POST['s_date']);
$s_time= htmlspecialchars($_POST['s_time']);
$memo = htmlspecialchars($_POST['memo']);

$dsn = 'mysql:dbname=schedule_01; host=localhost; charset=utf8';
$user = 'root';
$password = 'root';

$option = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_CASE => PDO::CASE_NATURAL,
    PDO::ATTR_PERSISTENT => true
);

$dbh = new PDO($dsn, $user, $password);

$stmt = $dbh->query('SELECT * FROM schedule');
while ($row = $stmt->fetch()) {
    $exists_id = $row['id'];
    $exists_s_date = $row['s_date'];
    $exists_s_time = $row['s_time'];
    //$exists_s_date = explode(" ", $exists_s_date);
    //$exists_s_date = $exists_s_date[0];

    if ($exists_s_time == $s_time && $exists_s_date == $s_date && $exists_id != $id) {
        echo "すでに登録されています。<br>\n";
        $exists = 1;
        echo '<a href="index.php">戻る</a>';
        exit();
    } else {
        $exists = 0;
    }
} // endwile

if ($exists == 0) {
$sql = <<<SQL
UPDATE schedule SET title=?, s_date=?, s_time=?, memo=? WHERE id=?
SQL;

try {
    $dbh = new PDO($dsn, $user, $password, $option);

    $stmt = $dbh->prepare($sql);

    $stmt->bindValue(1, $title, PDO::PARAM_STR);
    $stmt->bindValue(2, $s_date, PDO::PARAM_STR);
    $stmt->bindValue(3, $s_time, PDO::PARAM_STR);
    $stmt->bindValue(4, $memo, PDO::PARAM_STR);
    $stmt->bindValue(5, $id, PDO::PARAM_INT);

    $stmt->execute([
        $_POST['title'],
        $_POST['s_date'],
        $_POST['s_time'],
        $_POST['memo'],
        $_POST['id']
    ]);

} catch (PDOException $e) {
    print "ERR! : {$e->getMessage()}";
} finally {
    $dbh = null;
}
}
header('Location: index.php');
exit();