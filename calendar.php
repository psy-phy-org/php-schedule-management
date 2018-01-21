<?php require 'menu.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8" />
<title>スケジュール管理</title>
<style>
table {
  border-collapse: collapse;

}
td {
padding: 10px;
}
</style>
</head>
<body>
	<h1>予定登録日の選択</h1>
	<p>予定を登録する日を選択してください。</p>
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

<!-- カレンダーの表示 -->
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
// 1日の曜日まで移動
$wd1 = date("w", mktime(0, 0, 0, $m, 1, $y));
for($i = 1; $i <= $wd1; $i ++) {
  echo "<td>　</td>";
}

$d = 1;
while(checkdate($m, $d, $y)) {
  // 日付リンクの表示
  $link = "register.php?y=%04d&m=%02d&d=%02d";
  echo "<td><a href=\"" . sprintf($link, $y, $m, $d) . "\">{$d}</a></td>";

  // 今日が土曜日の場合の処理
  if(date("w", mktime(0, 0, 0, $m, $d, $y)) == 6) {
    // 週を終了
    echo "</tr>";

    // 次の週がある場合は新たな行を準備
    if(checkdate($m, $d + 1, $y)) {
      echo "<tr>";
    }
  }

  // 日付を1つ進める
  $d ++;
}

// 最後の週の土曜日まで移動
$wdx = date("w", mktime(0, 0, 0, $m + 1, 0, $y));
for($i = 1; $i < 7 - $wdx; $i ++) {
  echo "<td>　</td>";
}
?>
</tr>
	</table>
</body>
</html>