<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>Mission_5-1</title>
</head>
<body>
<span style="font-size:50px;">藤井達也の投稿フォーム　(Mission5-1) </span><br>
皆さんご協力ありがとうございます。<br>
このフォームでは、<br>
・コメントの投稿  <br>
・コメントの削除  <br>
・コメントの編集  <br>
ができます。<br>
コメントと共に、名前とパスワードの入力も忘れないで下さい。m(_ _)m
<br>
<br>
<p>
<span style="color:red">コメントの投稿はコチラから！</span><br>
<!--入力フォーム-->
<form action="", method="post">
<input type="text", name="名前", placeholder="名前を入力">
<input type="text", name="コメント", placeholder="コメントを入力">
<input type="password", name="password", placeholder="passwordを入力">
<input type="submit", value="書き込み">
</form>
<br>
</p>

<p>
<span style="color:red">コメントの削除はコチラから！</span><br>
<!--削除フォーム-->
<form action="", method="post">
<input type="number", name="delete", placeholder="削除する投稿番号を入力">
<input type="password", name="delete_password", placeholder="passwordを入力">
<input type="submit", value="削除">
</form>
<br>
</p>

<p>
<span style="color:red">コメントの編集はコチラから！</span><br>
<!--編集フォーム-->
<!--まずは編集する投稿を特定-->
<form action="", method="post">
<input type="number", name="compile_number", placeholder="編集する投稿番号を入力">
<input type="password", name="compile_password", placeholder="passwordを入力">
<input type="submit", value="編集を開始">
</form>
<br>
<!--編集する-->
<form action="", method="post">
<input type="hidden", name="new_number", value=
    "<?php 
    //編集フォームからの入力を受け取る
    $compile_number=$_POST["compile_number"];
    $compile_password=$_POST["compile_password"];
    echo $compile_number;
    ?>"
>
<input type="text", name="new_name", value=
    "<?php 
    //MySQLを設定
    $dsn='データベース名';
    $user='ユーザー名';
    $pass='パスワード';
    $pdo=new PDO($dsn, $user, $pass,
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    //table内の名前をテキストボックス内に出力
    $sql=$pdo->prepare('SELECT * FROM Mission5 where id=:id 
    AND password=:password');
    $sql->bindValue(':id',$compile_number,PDO::PARAM_INT);
    $sql->bindValue(':password',$compile_password,PDO::PARAM_STR);
    $sql->execute();
    $result=$sql->fetch();
    echo $result[1];
    ?>"
>
<input type="text", name="new_comment", value=
    "<?php 
    //MySQLを設定
    $dsn='データベース名';
    $user='ユーザー名';
    $pass='パスワード';
    $pdo=new PDO($dsn, $user, $pass,
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    //table内の名前をテキストボックス内に出力
    $sql=$pdo->prepare('SELECT * FROM Mission5 where id=:id
    AND password=:password');
    $sql->bindValue(':id',$compile_number,PDO::PARAM_INT);
    $sql->bindValue(':password',$compile_password,PDO::PARAM_STR);
    $sql->execute();
    $result=$sql->fetch();
    echo $result[2];
    ?>"
> 
<input type="hidden", name="new_password", value=
        "<?php 
        echo $compile_password;
        ?>"
>
<input type="submit", value="編集を反映">
</form>
<br>

    <?php
    //入力の受け取り
    $name=$_POST["名前"];
    $comment=$_POST["コメント"];
    $date=date("Y-m-d H:i:s");
    $delete=$_POST["delete"];
    $password=$_POST["password"];
    $delete_password=$_POST["delete_password"];
    $new_number=$_POST["new_number"];
    $new_password=$_POST["new_password"];
    $new_name=$_POST["new_name"];
    $new_comment=$_POST["new_comment"];
    
    
    //$date=date("Y年m月d日 H時i分s秒");
    
    //MySQLで設定
    $dsn='データベース名';
    $user='ユーザー名';
    $pass='パスワード';
    $pdo=new PDO($dsn, $user, $pass,
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    //tableの削除
    /*$sql="DROP TABLE Mission5";
    $stmt=$pdo->query($sql);*/

    //tableの作製
    $sql = "CREATE TABLE IF NOT EXISTS Mission5"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(16),"
    . "comment TEXT,"
    . "date DATETIME,"
    . "password TEXT"
	.");";
    $stmt = $pdo->query($sql);   

    //tableの表示
    /*$sql='SHOW CREATE TABLE Mission5';
    $result=$pdo -> query($sql);
    foreach($result as  $row){
        echo $row[1];
        echo '<br>';
    }
    echo "<hr>";*/
    
    //tableにデータを入力
    if(!empty($name)&&!empty($comment)&&!empty($password)){
        $sql=$pdo-> prepare("INSERT INTO Mission5(name, comment, date, password)
        VALUES (:name, :comment, :date,:password)");
        $sql->bindValue(':name', $name, PDO::PARAM_STR);
        $sql->bindValue(':comment',$comment, PDO::PARAM_STR);
        $sql->bindValue(':date',$date, PDO::PARAM_STR);
        $sql->bindValue(':password',$password, PDO::PARAM_STR);
        $sql->execute();
    }

    //削除機能
    if(!empty($delete)&&!empty($delete_password)){
        $sql='delete from Mission5 where id=:id AND password=:password';
        $stmt=$pdo->prepare($sql);
        $stmt->bindValue(':id',$delete, PDO::PARAM_INT);
        $stmt->bindValue(':password',$delete_password, PDO::PARAM_STR);
        $stmt->execute();
    //番号の振り直し
        $sql='ALTER TABLE Mission5 DROP COLUMN id';
        $stmt=$pdo->query($sql);
        $sql='ALTER TABLE Mission5 ADD COLUMN id
        INT AUTO_INCREMENT PRIMARY KEY FIRST';
        $stmt=$pdo->query($sql);
    }
    /*$sql='DBCC CHECKIDENT (Mission5, RESEED, 1)';
    $stmt=$pdo->prepare($sql);
    $stmt->execute();*/
    //$stmt=$pdo->query($sql);

    //編集機能
    if(!empty($new_name)&&!empty($new_comment)){
        //$new_comment="hoge";
        $sql='UPDATE Mission5 SET name=:name , 
        comment=:comment, date=:date
        where id=:id';
        $stmt=$pdo->prepare($sql);
        $stmt->bindValue(':name', $new_name, PDO::PARAM_STR);
        $stmt->bindValue(':comment', $new_comment, PDO::PARAM_STR);
        $stmt->bindValue(':date', $date, PDO::PARAM_STR);
        $stmt->bindValue(':id', $new_number, PDO::PARAM_INT);
        $stmt->execute();
        //echo "hoge";
    }

    //tableのデータを出力
    $sql = 'SELECT * FROM Mission5';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
        echo $row['id'].",";
        echo $row['name'].",";
        echo $row['comment'].",";
        //echo $row['password'].",";
        echo $row['date']."<br>";
    }

    ?>
</body>
</html>