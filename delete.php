<?php


try {
    $dsn = 'mysql:dbname=schedule_01; host=localhost';
    $user = 'root';
    $password = 'root';

    $option = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
        PDO::ATTR_PERSISTENT => true
    );

    $dbh = new PDO($dsn, $user, $password, $option);


    $stmt = $dbh->prepare('DELETE FROM schedule WHERE id=?');
    $stmt->bindValue(1, PDO::PARAM_INT);
    $stmt->execute(array(
        $_GET['id']
    ));

} catch (PDOException $e) {
    print "ERR! : {$e->getMessage()}";
} finally {
    $pdo = null;
}

header('Location: index.php');
exit();