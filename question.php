<?php
require_once ('config.php');
//エラーのカラム情報を格納する変数
$error_columns = [];
// データベース接続
if (isset($_POST['send']) && !empty($_POST['question'])) {
    $db_host = DB_HOST;      // サーバーのホスト名
    $db_name = DB_NAME;      // データベース名
    $db_user = DB_USER;      // データベースのユーザー名
    $db_pass = DB_PASS;      // データベースのパスワード
    try {
        $dbh = new PDO(
            'mysql:host=' . $db_host .
            ';dbname=' . $db_name .
            ';charset=utf8mb4',
            $db_user,
            $db_pass,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        // プリペアドステートメントを使い、安全にデータベースに登録されるようにしている
        $sql = 'INSERT INTO questions (question_body) values(:question_body )';
        $query = $dbh->prepare($sql);
        $query->bindValue(':question_body', $_POST['question'], PDO::PARAM_STR);
        $query->execute(); // データベースに保存される
        $success = '質問を送信しました！';
    } catch (PDOException $e) {
        exit('データベース接続失敗 ' . $e->getMessage());
    }
} elseif (isset($_POST['send']) && (empty($_POST['question']))) {
    $success_error = '質問を入力してください';
}
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Q&A</title>
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>
    <link rel="stylesheet" media="all" href="assets/css/style.css"/>
</head>
<body>
<main>
    <h1><font face="Comic Sans MS"> Q&A </font></h1>
    <?php if (isset($success)): // 質問の送信が完了した時に表示 ?>
        <div class="success-question"><?= $success ?></div>
    <?php endif ?>
    <?php if (empty($_POST['question'])): ?>
        <div class="success-question"><?= $success_error ?></div>
    <?php endif ?>
    <form action="question.php" method="post">
        <div class="care-item">
            <label>
                <span class="d-block"><b>Q</b>気軽に質問してください！</span>
                <textarea name="question"><?= $_POST['question'] ?></textarea>
            </label>
        </div>
        <div class="care-btn">
            <button type="submit" name="send">質問する</button>
        </div>
    </form>
    <a href="questionlist.php">他の質問を見てみる</a>
</main>
</body>
</html>
