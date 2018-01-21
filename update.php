<?php require 'menu.php'; ?>
<?php
error_reporting(E_ALL & ~ E_NOTICE);

$id = htmlspecialchars($_GET['id']);

$sql = <<<SQL
SELECT * FROM schedule WHERE id=?
SQL;

$dsn = 'mysql:dbname=schedule_01; host=localhost; charset=utf8';
$user = 'root';
$password = 'root';

$option = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_CASE => PDO::CASE_NATURAL,
    PDO::ATTR_PERSISTENT => true
);

try {
    $dbh = new PDO($dsn, $user, $password);

    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        $id
    ]);

    $row = $stmt->fetch();
    $id = htmlspecialchars($row['id']);
    $title = htmlspecialchars($row['title']);
    $s_date = htmlspecialchars($row['s_date']);
    $s_time = htmlspecialchars($row['s_time']);
    $memo = htmlspecialchars($row['memo']);
} catch (PDOException $e) {
    print "ERR! : {$e->getMessage()}";
} finally {
    $dbh = null;
}
?>

<head>
<meta charset="utf-8" />
</head>
<header>
  <h1>予定の変更</h1>
</header>

<div class="box">
  <form action="update_do.php" method="post" name="form" id="form">
    <fieldset>
      <table>
        <tr>
          <th>ID：</th>
          <td><input type="text" name="id" value="<?php echo $id ?>"
            readonly="readonly"></td>
        </tr>
        <tr>
          <th>タイトル：</th>
          <td><input type="text" name="title"
            value="<?php echo $title ?>"></td>
        </tr>
        <tr>
          <th>日付：</th>
          <td><input type="text" name="s_date"
            value="<?php echo $s_date ?>"></td>
        </tr>
        <tr>
          <th>時間：</th>
          <td><input type="text" name="s_time"
            value="<?php echo $s_time ?>"></td>
        </tr>
        <tr>
          <th>備考：</th>
          <td><input type="text" name="memo" value="<?php echo $memo ?>"></td>
        </tr>
      </table>
      <div>
        <p>
          <a href="javascript:document.form.submit()">更新する</a> <a
            href="delete.php?id=<?php echo $id?>">削除する</a>
        </p>
        <input type="hidden" name="form" value="" />


      </div>
    </fieldset>
  </form>

  <p></p>
  <!-- /.container -->
</div>


