<?php require 'menu.php'; ?>
<?php
#header("Content-Type: text/html; charset=utf-8");
error_reporting(E_ALL & ~ E_NOTICE);
?>

<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>日別予定表</title>
<style>
table {
	border-collapse: collapse;
  width:100%;
}

.w50 {
	width: 50px;
	text-align: right;
}
td.a {
width: 14.28%;
height: 50px;
vertical-align: top;
}
</style>
</head>

<body>
	<h1>日別予定表</h1>
<?php

$now = time();
$y = date("Y", $now);
$m = date("n", $now);
$d = date("j", $now);
if ($_POST['y']) {
    if ($y != $_POST['y'] || $m != $_POST['m']) {
        $y = $_POST['y'];
        $m = $_POST['m'];
        $d = 0;
        $now = mktime(0, 0, 0, $m, 1, $y);
    }
}
?>

<!-- 年月選択リストを表示する -->
<?php
if(isset($_POST ["y"])) {
  // 選択された年月を取得する
  $y = intval($_POST ["y"]);
  $m = intval($_POST ["m"]);
} else {
  // 現在の年月を取得する
  $ym_now = date("Ym");
  $y = substr($ym_now, 0, 4);
  $m = substr($ym_now, 4, 2);
}

// 年月選択リストを表示する
echo "<form method='POST' action=''>";

// 年
echo "<select name='y'>";
for($i = $y - 2; $i <= $y + 2; $i ++) {
  echo "<option";
  if($i == $y) {
    echo " selected ";
  }
  echo ">$i</option>";
}
echo "</select>年";

// 月
echo "<select name='m'>";
for($i = 1; $i <= 12; $i ++) {
  echo "<option";
  if($i == $m) {
    echo " selected ";
  }
  echo ">$i</option>";
}
echo "</select>月";
echo "<input type='submit' value='表示' name='sub1'>";
echo "</form>";
?>

<table border="1">
  <tr>
    <th>日</th>
    <th>月</th>
    <th>火</th>
    <th>水</th>
    <th>木</th>
    <th>金</th>
    <th>土</th>
  </tr>

  <tr>
<?php

$d = 1;

// 1日の曜日を取得
$firstday = date("w", mktime(0, 0, 0, $m, 1, $y));

// その数だけ空白を表示
for ($i = 1; $i <= $firstday; $i ++) {
    echo "<td>　</td>";
}

// 1日から月末日までの表示
while (checkdate($m, $d, $y)) {

    $dsn = 'mysql:dbname=schedule_01; host=localhost; charset=utf8';
    $user = 'root';
    $password = 'root';

    $option = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
        PDO::ATTR_PERSISTENT => true
    );

    $dbh = new PDO($dsn, $user, $password, $option);

    $stmt = $dbh->query("SELECT * FROM schedule ORDER BY s_date");

    echo "<td class=\"a\">";
echo $d;
echo "<br>\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $id = $row['id'];
        $title = $row['title'];
        $s_date = $row['s_date'];

        $b = explode(" ", $s_date);
        $c = $b[0];
        $e = explode("-", $c);
        $f = $e[2];
        $x = $e[1];

        if (($f == $d) && ($m == $x)) {
            $link = "timetable.php?s_date_year=%04d&s_date_month=%02d&s_date_day=%02d";
            echo "<a href=\"" . sprintf($link, $y, $m, $d) . "\">{$title}</a><br>";
        }

    } // end while

    echo "</td>";

    // 今日が土曜日の場合は…
    if (date("w", mktime(0, 0, 0, $m, $d, $y)) == 6) {
        // 週を終了
        echo "</tr>\n";

        // 次の週がある場合は新たな行を準備
        if (checkdate($m, $d + 1, $y)) {
            echo "<tr>\n";
        }
    } // end if

    $d ++;
}
// 最後の週の土曜日まで移動
$lastday = date("w", mktime(0, 0, 0, $m + 1, 0, $y));
for ($i = 1; $i < 7 - $lastday; $i ++) {
    echo "<td></td>";
}

?>
</table>

<?php
$y = date('Y');
$m = date('m');
$d = date('d');
// 選択するデータを定義
// YYYY-mm-dd HH:ii:ssに整形
$timestamp = mktime(0,0,0,$_GET['s_date_month'],$_GET['s_date_day'],$_GET['s_date_year']);
$s_date  = date("Y-m-d",$timestamp);
$s_time  = date("H:i:s",$timestamp);

$timestamp2 = mktime(0,0,0, $m, $d, $y);
$s_date2  = date("Y-m-d",$timestamp2);
?>

<p>&nbsp;</p>

	<table border="1" width="100%">
<?php
for ($i = 7; $i < 20; $i ++) {
    print "<tr>";
    print "<td class='w50'>";
    print $i;
    print "</td>";

    print "<td>";

    try {
        $dbh = new PDO($dsn, $user, $password, $option);
if($s_date){
        $stmt = $dbh->query("SELECT * FROM schedule WHERE s_date=' " . $s_date . " ' ");
}else{
    $stmt = $dbh->query("SELECT * FROM schedule WHERE s_date=' " . $s_date2 . " ' ");
}
        while ($row = $stmt->fetch()) {
            //var_dump($row);
// 日付リンクの表示
            $title = $row['title'];
            $id = $row['id'];
            $s_year = $row['s_year'];
            $s_month = $row['s_month'];
            $s_date = $row['s_date'];
            $s_time = $row['s_time'];
            $memo = $row['memo'];

            if ($i == $s_time) {
                print $title;
                print '　：　';
                print $memo;
                print '　　';
                $link = "update.php?id=$id";
                echo "<a href=\"" . sprintf($link) . "\">編集";
            }
        }

        print "</td>";
    } catch (PDOException $e) {
        exit('Connention faild.' . $e->getMessage());
    }

    $db = null;
}
?>
</table>

</body>
</html>