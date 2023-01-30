<!DOCTYPE html>

<html lang="ja">

<head>

    <meta charset="UTF-8">

    <title>keijiban</title>

</head>

<body>
    
    <h1>好きな映画は何ですか？</h1>
    
    <?php
    
    //データベース接続
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    //テーブル作成
    $sql = "CREATE TABLE IF NOT EXISTS keijiban"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "date DATETIME,"
    . "password TEXT"
    .");";
    $stmt = $pdo->query($sql);
    
    //送信ボタンが押された場合
    if (isset($_POST['post_submit'])){
        $edi_id = $_POST["editnum"];
        $name = $_POST["name"];
        $comment = $_POST["str"];
        $date = date("Y/m/d H:i:s");
        $pass = $_POST["pass"];
        //新規投稿
        if (empty($edi_id)&&!empty($name)&&!empty($comment)){
            $sql = $pdo -> prepare("INSERT INTO keijiban (name, comment, date, password) VALUES (:name, :comment, :date, :password)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':date', $date, PDO::PARAM_STR);
            $sql -> bindParam(':password', $pass, PDO::PARAM_STR);
            $sql -> execute();
        //編集
        }elseif(!empty($edi_id)){
            $sql = 'UPDATE keijiban SET name=:name,comment=:comment WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':id', $edi_id, PDO::PARAM_INT);
            $stmt->execute();
        }else{
            echo "error<br><br>";
        }
    }
    
    //削除ボタンが押された場合
    if (isset($_POST['del_submit'])){
        $del_id = $_POST["del_num"];
        $del_pass = $_POST["del_pass"];
        $sql = 'SELECT * FROM m5_1 WHERE id=:id ';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $del_id, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll();
        foreach ($results as $row){
        }
            
        
        if(!empty($del_id)&&!empty($del_pass)&&$del_pass==$row['password']){
            $sql = 'delete from keijiban where id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $del_id, PDO::PARAM_INT);
            $stmt->execute();
        }else{
            echo "error<br><br>";
        }
    }
    
    //編集ボタンが押された場合
    if (isset($_POST['edi_submit'])){
        $edi_id = $_POST['edi_num'];
        $edi_pass = $_POST['edi_pass'];
        $sql = 'SELECT * FROM keijiban WHERE id=:id ';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $edi_id, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll();
        foreach ($results as $row){
        }
        if(!empty($edi_id)&&!empty($edi_pass)&&$edi_pass==$row['password']){
            $sql = 'SELECT * FROM keijiban WHERE id=:id ';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $edi_id, PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                $editing_num = $row['id'];
                $editing_name = $row['name'];
                $editing_comment = $row['comment'];
                $editing_pass = $row['password'];
            }
        }else{
            echo "error<br><br>";
        }
    }
    
    // データベースの内容表示
    $sql = 'SELECT * FROM keijiban';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['date'];
        echo '<br>';
    }
    echo "<hr>";
    ?>
    
    
    <!--投稿フォーム-->
    <form action="" method="post">
        <input type="hidden" name="editnum"  placeholder="編集したい投稿番号" value = "<?php if (isset($editing_num)){echo $editing_num;} ?>"><br>
        <input type="text" name="name"  placeholder="名前" value = "<?php if (isset($editing_name)){echo $editing_name;} ?>"><br>
        <input type="text" name="str" placeholder="コメント" value = "<?php if (isset($editing_comment)){echo $editing_comment;} ?>"><br>
        <input type="passowrd" name="pass" placeholder="password" value = "<?php if (isset($editing_pass)){echo $editing_pass;} ?>">
        <input type="submit" name="post_submit">
    </form><br>
    <!--削除フォーム-->
    <form action="" method="post">
        <input type="number" name="del_num"  placeholder="削除番号"><br>
        <input type="password" name="del_pass" placeholder="password" >
        <input type="submit" name="del_submit" value="削除">
    </form><br>
    <!--編集フォーム-->
    <form action="" method="post">
        <input type="number" name="edi_num"  placeholder="編集対象番号"><br>
        <input type="password" name="edi_pass" placeholder="password" >
        <input type="submit" name="edi_submit" value="編集">
    </form>
</body>
</html>
