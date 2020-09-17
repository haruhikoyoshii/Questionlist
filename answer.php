<?php
require_once ('config.php');
// データベース接続
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
} catch (PDOException $e) {
    exit('データベース接続失敗 ' . $e->getMessage());
}
if (isset($_POST['send']) && !empty($_POST['answer'])) {
    // プリペアドステートメントを使い、安全にデータベースに登録されるようにしている
    $sql = 'INSERT INTO answers (answer_body, question_id) values(:answer_body, :question_id)';
    $query = $dbh->prepare($sql);
    $query->bindValue(':answer_body', $_POST['answer'], PDO::PARAM_STR);
    $query->bindValue(':question_id', $_GET['id'], PDO::PARAM_STR);
    $query->execute(); // データベースに保存される
    $success = '解答を送信しました！';
} elseif (isset($_POST['send']) && (empty($_POST['answer']))) {
    $success_error = '解答を入力してください';
}
//質問の取得
$get_sql = "select * from questions WHERE questions.id = ? ";
$get_query = $dbh->prepare($get_sql);
$get_query->bindParam(1, $_GET['id'], PDO::PARAM_INT);
$get_query->execute();
$contacts = $get_query->fetchAll(PDO::FETCH_ASSOC);
//回答の取得
$answer_sql = "select * from answers";
$answer_stmt = $dbh->query($answer_sql);
$answer_contacts = $answer_stmt->fetchAll(PDO::FETCH_ASSOC);
//question_idに紐づく回答の作成
$groupedAnswers = [];
foreach ($answer_contacts as $answer) {
    $groupedAnswers[$answer['question_id']][] = $answer;
}
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>介護の質問</title>
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>
    <link rel="stylesheet" media="all" href="assets/css/style.css"/>
</head>
<body>
<main>
    <h1><font face="Comic Sans MS">Q&A</font></h1>
    <?php if (isset($success)): // 解答の送信が完了した時に表示 ?>
        <div class="success-answer"><?= $success ?></div>
    <?php endif ?>
    <?php if (empty($_POST['answer'])): ?>
        <div class="success-question"><?= $success_error ?></div>
    <?php endif ?>
    <?php foreach ($contacts as $contact): ?>
        <a><b>Q.</b><?= htmlspecialchars($contact['question_body'], ENT_QUOTES); ?></a>
    <?php endforeach; ?>
    <form action="answer.php?id=<?= $_GET['id'] ?>" method="post">
        <div class="care-item">
            <label>
                <span class="d-block"><font face="Comic Sans MS"><b>A</b>質問に解答する</font></span>
                <textarea name="answer"><?= $_POST['answer'] ?></textarea>
            </label>
        </div>
        <div class="care-btn">
            <button type="submit" name="send">解答する</button>
        </div>
    </form>
    <a href="questionlist.php">他の質問を見てみる</a>
</main>
</body>
</html>
