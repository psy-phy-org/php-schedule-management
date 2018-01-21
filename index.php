<?php require 'menu.php'; ?>

<head>
<meta charset="utf-8">
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
<header>
<h1>月別予定表</h1>
</header>
<?php
//header("Content-Type: text/html; charset=utf-8");

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
    echo "<br>";
    // データセルの表示
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // foreach ($stmt as $row){
        $id = $row['id'];
        $title = $row['title'];
        $s_date = $row['s_date'];

        $b = explode(" ", $s_date);
        $c = $b[0];
        $e = explode("-", $c);
        $f = $e[2];
        $x = $e[1];

        if (($f == $d) && ($x == $m)) {
            $link = "update.php?id=$id";
            echo "  <a href=\"" . sprintf ( $link, $y, $m, $d ) . "\">{$title}</a><br>";
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