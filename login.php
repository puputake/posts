<?php
session_start();
require('dbconnect.php');

// クッキーに値が保存されている場合
if ($_COOKIE['email'] !== '') {
  $email = $_COOKIE['email'];
}

// フォームが送信された場合
if (!empty($_POST)) {
  // メールアドレスにクッキー以外の値が入力された場合
  $email = $_POST['email'];
  
  // 入力欄が空でない場合
  if ($_POST['email'] != '' && $_POST['password'] !== '') {
    $login = $db->prepare('SELECT * FROM members WHERE email=? AND password=?');
    $login->execute(array(
      $_POST['email'],
      sha1($_POST['password'])
    ));
    $member = $login->fetch();

    // ログインに成功した場合
    if ($member) {
      $_SESSION['id'] = $member['id'];
      $_SESSION['time'] = time();

      // メールアドレスをクッキーに保存
      if ($_POST['save'] === 'on') {
        setcookie('email', $_POST['email'], time()+60*60*24*14);
      }

      header('Location: index.php');
      exit();
    } else {
      $error['login'] = 'failed';
    }
  } else {
    $error['login'] = 'blank';
  }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="style.css" />
  <title>ログインする</title>
</head>

<body class="container">
  <div id="wrap">
    <div id="head">
      <h1>ログインする</h1>
    </div>
    <div id="content">
      <div id="lead">
        <p>メールアドレスとパスワードを記入してログインしてください。</p>
        <p>入会手続きがまだの方はこちらからどうぞ。</p>
        <p>&raquo;<a href="join/">入会手続きをする</a></p>
      </div>
      <form action="" method="post">
        <dl>
          <dt>メールアドレス</dt>
          <dd>
            <input type="text" name="email" class="form-control" size="35" maxlength="255" value="<?php print(htmlspecialchars($email, ENT_QUOTES)); ?>">
            <?php if ($error['login'] === 'blank') : ?>
              <p class="error">*メールアドレスとパスワードをご記入ください</p>
            <?php endif; ?>
            <?php if ($error['login'] === 'failed') : ?>
              <p class="error">*ログインに失敗しました。正しく入力してください</p>
            <?php endif; ?>

          </dd>
          <dt>パスワード</dt>
          <dd>
            <input type="password" name="password" class="form-control" size="10" maxlength="20" value="<?php print(htmlspecialchars($_POST['password'], ENT_QUOTES)); ?>">
          </dd>
          <dt>ログイン情報の記録</dt>
          <dd>
            <input id="save" type="checkbox" name="save" value="on">
            <label for="save">次回からは自動的にログインする</label>
          </dd>
        </dl>
        <div>
          <input type="submit" value="ログインする" />
        </div>
      </form>
    </div>
    <div id="foot">
    </div>
  </div>
</body>

</html>