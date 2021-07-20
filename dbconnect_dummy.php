<?php
try {
    $db = new PDO('mysql:dbname=******; host=******; charset=utf8', '******', "******");
} catch(PDOException $e) {
    print('DB接続エラー：' . $e->getMessage());
}
?>