<!DOCTYPE html>
<html>
<head>
    <title>掲示板</title>
</head>
<body>
    <?php
    $filename = 'posts.txt';

    // フォームが送信された場合
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['name']) && isset($_POST['content'])) {
            $name = trim($_POST['name']);
            $content = trim($_POST['content']);

            if ($name !== '' && $content !== '') {
                $post = $name . "\t" . $content . "\n";
                file_put_contents($filename, $post, FILE_APPEND);
                header('Location: ' . $_SERVER['PHP_SELF'] . '?complete=1');
                exit; 
            } else {
                echo "<p>エラー: 名前と投稿内容を入力してください。</p>";
            }
        }
    }

    // 投稿完了画面
    if (isset($_GET['complete']) && $_GET['complete'] == 1) {
        echo "<h1>投稿が完了しました。</h1>";
        echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="get">';
        echo '<input type="submit" value="投稿一覧へ戻る">';
        echo '</form>';
    } else {
        // 投稿フォームと投稿一覧の表示
        ?>
        <h1>掲示板</h1>
        <h1>新規投稿</h1>
        <form method="post" action="">
            <label for="name">name:</label>
            <input type="text" id="name" name="name" required>
            <br><br>
            <label for="content">投稿内容:</label>
            <textarea id="content" name="content" required></textarea>
            <br><br>
            <input type="submit" value="送信">
        </form>

        <h2>投稿内容一覧</h2>
        <table border="1">
            <tr>
                <th>No</th>
                <th>名前</th>
                <th>投稿内容</th>
            </tr>
            <?php
            if (file_exists($filename)) {
                $posts = file($filename, FILE_IGNORE_NEW_LINES);
                foreach ($posts as $index => $post) {
                    list($name, $content) = explode("\t", $post);
                    echo "<tr>";
                    echo "<td>" . ($index + 1) . "</td>";
                    echo "<td>" . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . "</td>";
                    echo "<td>" . nl2br(htmlspecialchars($content, ENT_QUOTES, 'UTF-8')) . "</td>";
                    echo "</tr>";
                }
            }
            ?>
        </table>
        <?php
    }
    ?>
</body>
</html>