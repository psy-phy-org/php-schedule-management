<?php require 'menu.php'; ?>
<?php

error_reporting(E_ALL & ~ E_NOTICE);

function showOpth($start, $end)
{
    for ($i = $start; $i <= $end; $i ++) {
        print("<option value='" . $i . "'>" . $i . "</option>");
    }
}

function showOptm($start, $end)
{
    for ($i = $start; $i <= $end; $i ++) {
        if ($i % 30 == 0) {
            print("<option value='" . $i . "'>" . $i . "</option>");
        }
    }
}

$y = $_GET['y'];
$m = $_GET['m'];
$d = $_GET['d'];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8" />
<title>スケジュール管理</title>
</head>
<body>
  <h1>スケジュール管理（2. 登録）</h1>
  <form method="get" action="register_do.php">
    <table border="0">
      <tr>
        <th align="right">予定名：</th>
        <td><input type="text" name="title" size="50" maxlength="255" /></td>
      </tr>
      <tr>
        <th align="right">日付：</th>
        <td><input type="text" name="s_date_year" size="10" maxlength="255"
          value="<?php echo $y; ?>" /> <input type="text" name="s_date_month"
          size="10" maxlength="255" value="<?php echo $m; ?>" /> <input
          type="text" name="s_date_day" size="10" maxlength="255"
          value="<?php echo $d; ?>" /></td>
      </tr>
      <tr>
        <th align="right">開始時間：</th>
        <td><select name="s_time_hour"><?php showOpth(9,18); ?></select>時 <select
          name="s_time_minute"><?php showOptm(0,59); ?></select>分</td>
      </tr>
      <tr>
        <th align="right">備考：</th>
        <td><input type="text" name="memo" size="70" maxlength="255" /></td>
      </tr>
      <tr>
        <td rowspan="2"><input type="submit" value="登録" /> <input
          type="reset" value="クリア" /></td>
      </tr>
    </table>
  </form>
</body>
</html>