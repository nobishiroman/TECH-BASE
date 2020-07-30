<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>Mission_3-5</title>
</head>
<body>
<span style="font-size:50px;">藤井達也の投稿フォーム　(Mission_3-5) </span><br>
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
<!--フォームの入力-->
<form action="", method="post">
    <input type="text", name="名前", placeholder="名前を入力">
    <input type="text", name="コメント", placeholder="コメントを入力">
    <input tyoe="password", name="password", placeholder="パスワードを入力">
    <input type="submit", value="書き込み">
    <br>
</form>
</p>
<br>

<p>
<span style="color:red">コメントの削除はコチラから！</span><br>
<form action="", method="post">
    <input type="number", name="delete", placeholder="削除する投稿番号を入力">
    <input tyoe="password", name="delete_password", placeholder="パスワードを入力">
    <input type="submit", value="削除">
    <br>
</form>
</p>
<br>

<p>
<span style="color:red">コメントの編集はコチラから！</span><br>
<form action="", method="post">
    <input type="number", name="compile",
     placeholder="編集する投稿番号を入力">
    <input tyoe="password", name="compile_password", placeholder="パスワードを入力">
    <input type="submit", value="編集する">
    <br>
</form>
</p>

    <?php
    //入力の受け取り
    $name=$_POST["名前"];
    $comment=$_POST["コメント"];
    $password=$_POST["password"];
    $delete=$_POST["delete"];
    $delete_password=$_POST["delete_password"];
    $compile=$_POST["compile"];
    $compile_password=$_POST["compile_password"];
    $file="Mission_3-5.txt";
    $date=date("Y年m月d日 H時i分s秒");
    $number=1;
    $modify=null;
    $new_password=$compile_password;
    
    ?>

    <!--編集番号指定が入力された時の処理-->
    <!--編集する番号と内容をテキストボックスに表示-->
    <form action="", method="post">
        
        <input type="hidden", name="new_number", value=
        "<?php 
        echo $compile;
        ?>">
    
        <input type="text", name="new_name", value=
        "<?php 
        if($compile!=null){

            if(file_exists($file)){
                $fp=fopen($file, "r");
                $lines=file($file);
            
                foreach($lines as $line){
                    $components=explode("<>",$line);

                    if($components[0]==$compile 
                    && $components[4]==$compile_password){
                        //$modify=$line;
                        $name=$components[1];
                        echo $name;
                    }
                }
                
            fclose($file);
            }

        }
        ?>">

        <input type="text", name="new_comment", value=
        "<?php 
        if($compile!=null){

            if(file_exists($file)){
                $fp=fopen($file, "r");
                $lines=file($file);
            
                foreach($lines as $line){
                    $components=explode("<>",$line);

                    if($components[0]==$compile 
                    && $components[4]==$compile_password){
                        //$modify=$line;
                        $comment=$components[2];
                        //$new_password=$components[4];
                        echo $comment;
                    }
                }
                
            fclose($file);
            }

        }
        ?>">
        
        <input type="hidden", name="new_password", value=
        "<?php 
        echo $compile_password;
        ?>">

        <input type="submit", value="編集内容を反映">
        <br>
    </form>


    <?php
    //編集情報の受け取り
    $new_number=$_POST["new_number"];
    $new_name=$_POST["new_name"];
    $new_comment=$_POST["new_comment"];
    $new_date=date("Y年m月d日 H時i分s秒");
    $new_password=$_POST["new_password"];
   
    //削除依頼or編集依頼が来た時の処理
    if($delete!=0||$new_comment!=null){
        //echo $new_number;
        //指定された行or編集した行を削除
        if(file_exists($file)){
            $lines=file($file);
            
            foreach($lines as $line){
                $components=explode("<>",$line);
                //echo $new_number;
                if($delete!=0 && $delete_password==$components[4]){
                    unset($lines[$delete-1]);
                    array_merge($lines);
                    file_put_contents($file,$lines);
                }
                
                //echo $new_number;
                //echo $components[4];
                if($new_number==$components[0]&& $new_password==$components[4]){
                    unset($lines[$new_number-1]);
                    array_merge($lines);
                    file_put_contents($file,$lines);
                }
                
                //投稿番号の振り直し
                $fp=fopen($file,"r+");
                $components=explode("<>",$lines[0]);
                $components[0]=1;
                fwrite($fp,$components[0]);
                
                for($i=2;$i<=count($lines); $i++){
                    $line=fgets($fp);
                    $components=explode("<>",$line);
                    $components[0]=$i;
                    fwrite($fp,$components[0]);
                }
            }    
            fclose($fp);
        }
        
    }
    
    //echo $new_password;
    //echo $compile_password;
    //編集したコメントを最後に表示
    if($new_comment!=null){
        if(file_exists($file)){
            $fp=fopen($file, "a");
            $lines=file($file);
            $number=count($lines)+1;
            $merge=$number."<>".$new_name."<>".$new_comment.
            "<>".$new_date."<>".$new_password."<>".PHP_EOL;
            fwrite($fp,$merge);
            fclose($fp);
        }
    }
    


    //入力コメントが来た時の処理
    if(!empty($comment)&&empty($compile)){
        if(!empty($password)){
        
            //テキストの行数から通し番号を決定
            if(file_exists($file)){
                $lines=file($file);
                $number=count($lines)+1;
            }
            
            //投稿日時をつけてファイルに記録
            $merge=$number."<>".$name."<>".$comment."<>".$date
            ."<>".$password."<>".PHP_EOL;
            $fp=fopen($file, "a");
            fwrite($fp,$merge);
            fclose($fp);
        }
        else{
            
            echo "パスワードを入力してください";
        }
    }
        
    //ファイルの中身を表示
    if(file_exists($file)){
        $lines=file($file);
        
        foreach($lines as $line){
            $components=explode("<>",$line);
            $submerge=$components[0]."<>".$components[1].
            "<>".$components[2]."<>".$components[3];
            echo $submerge."<br>";
        }
    }  

    ?>
</body>
</html>