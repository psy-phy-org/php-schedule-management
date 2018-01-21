<?php
error_reporting(E_ALL & ~ E_NOTICE);

$dsn = 'mysql:dbname=reserve_02; host=localhost';
$user = 'root';
$password = 'root';

$option = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_CASE => PDO::CASE_NATURAL,
    PDO::ATTR_PERSISTENT => true
);

$timestamp = mktime(0,0,0,$_GET['s_date_month'],$_GET['s_date_day'],$_GET['s_date_year']);
$s_date  = date("Y-m-d",$timestamp);
$s_time  = date("H:i:s",$timestamp);

echo $s_date;

try {
  $dbh = new PDO($dsn, $user, $password, $option);
  $dbh->exec('SET NAMES utf8');

  $stmt = $dbh->query("SELECT * FROM schedule WHERE s_date=' " . $s_date . " ' ");
  while($row = $stmt->fetch()) {
    $title = $row ['title'];
    $s_date = $row ['s_date'];
    $s_time = $row ['s_time'];
    $memo = $row ['memo'];
    print <<<EOD
<table>
<tr>
    <td>$title</td>
    <td>$s_date</td>
    <td>$s_time</td>
    <td>$memo</td>
</tr>
</table>
EOD;
  }

  $db = null;

} catch(PDOException $e) {
  die('ERR! : ' . $e->getMessage());
}

?>