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
//質問の取得
$sql = "select * from questions";
$stmt = $dbh->query($sql);
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
//回答の取得
$answer_sql = "select * from answers";
$answer_stmt = $dbh->query($answer_sql);
$answer_contacts = $answer_stmt->fetchAll(PDO::FETCH_ASSOC);
//質問と回答の紐付け
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
    <title>Q$A</title>
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>
    <link rel="stylesheet" media="all" href="assets/css/style.css"/>
</head>
<body>
<main>
    <h1><font face="Comic Sans MS">過去のQ＆A一覧</font></h1>
    <table border="4">
        <tr>
            <th>質問一覧</th>
            <th>解答一覧</th>
        </tr>
        <?php foreach ($contacts as $contact): ?>
            <tr>
                <td>
                    <a"answer.php?id=<?= $contact['id'] ?>
                    "><?= htmlspecialchars($contact['question_body'], ENT_QUOTES); ?></a>
                </td>
                <td>
                    <?php if (isset($groupedAnswers[$contact['id']])) : ?>
                        <div><?= htmlspecialchars(implode(', ', array_column($groupedAnswers[$contact['id']], 'answer_body')), ENT_QUOTES) ?></div>
                    <? else : ?>
                        <div>回答はまだありません</div>
                    <?php endif; ?>
                </td>
                <td>
                    <p class="answer"><a href="answer.php?id=<?= $contact['id'] ?>">解答する</a></p>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="question.php">質問する</a>
</main>
</body>
</html>
