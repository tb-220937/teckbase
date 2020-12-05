<!DOCTYPE html>
<html lang ="ja"> 
<head>
    <meta charset="UTF-8">
    <title> mission_5-1.php</title>
</head>
<body>
<?php
 //データベースに接続//
 $dsn = 'データベース名';
 $user = 'ユーザー名';
 $password = 'パスワード';
 $pdo = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
 //データベース内にテーブルを作成//
 $sql = "CREATE TABLE IF NOT EXISTS 5_1_2"
 ."("
 ."id INT AUTO_INCREMENT PRIMARY KEY,"
 ."name char(32),"
 ."comment TEXT,"
 ."date TEXT,"
 ."pass TEXT"
 .");";
 $stmt = $pdo->query($sql);
 //テーブルが作れてるか確認用
 /*$sql ='SHOW TABLES';
 $result = $pdo -> query($sql);
 foreach($result as $row){
     echo $row[0];
     echo '<br>';
 }
 echo "<hr>";*/
 
 //テーブルの構成要素確認用
 /*$sql ='SHOW CREATE TABLE 5_1_2';
 $result = $pdo -> query($sql);
 foreach($result as $row){
     echo $row[1];
 }
 echo "<hr>";*/

 $editNO='';
 $editname='';
 $editcomment='';
 
 //データを挿入//
 $sql = $pdo -> prepare("INSERT INTO 5_1_2 (name,comment,date,pass) VALUES (:name,:comment,:date,:pass)");
 $sql ->bindParam(':name',$name, PDO::PARAM_STR);
 $sql ->bindParam(':comment',$comment, PDO::PARAM_STR);
 $sql ->bindParam(':date',$date, PDO::PARAM_STR);
 $sql ->bindParam(':pass',$pass, PDO::PARAM_STR);
 $name = $_POST["name"];
 $comment = $_POST["comment"];
 $date= date("Y/m/d H:i:s");
 $pass = $_POST["pass"];
 $editnumber= $_POST["editnumber"];
 //名前とコメントとパスワードが空じゃないとき送信されたら実行する
 if(empty($editnumber) && !empty($name) && !empty($comment) && !empty($pass) && $_POST["submit"]){
 $sql -> execute();
 }
 
//削除したい投稿のパスワードを取得
$deletenum=$_POST["deletenum"];
if(!empty($deletenum) && !empty($pass) && $_POST["delete"]){
    $sql = 'SELECT * FROM 5_1_2';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach($results as $row){
     if($deletenum==$row['id']){
     $delpass=$row['pass'];
 }
 }
 //パスワードがあってるとき削除
 if($pass==$delpass){
 $id =$deletenum;
 $sql = 'delete from 5_1_2 where id=:id';
 $stmt = $pdo->prepare($sql);
 $stmt->bindParam(':id',$id, PDO::PARAM_INT);
 $stmt->execute();
 }
 }
 //編集したい投稿のパスワードを取得
 $editnum=$_POST["editnum"];
 if(!empty($editnum) && !empty($pass) && $_POST["edit"]){
     $sql = 'SELECT * FROM 5_1_2';
     $stmt = $pdo->query($sql);
     $results = $stmt->fetchAll();
     foreach($results as $row){
        if($editnum==$row['id']){
           $editpass=$row['pass'];
        }
        //パスワードがあってるとき番号と名前とコメントを取得
        elseif($pass==$editpass){
            $sql = 'SELECT * FROM 5_1_2';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach($results as $row){
                if($editnum==$row['id']){
                   $editNO=$row['id'];
                   $editname=$row['name'];
                   $editcomment=$row['comment'];
                }
                }
                }
                }
 }
 //編集
 if(!empty($editnumber) && !empty($name) && !empty($comment) && !empty($pass) && $_POST["submit"]){
 $id = $editnumber;
 $name = $_POST["name"];
 $comment = $_POST["comment"];
 $date = date("Y/m/d H:i:s");
 $pass = $_POST["pass"];
 $sql = 'UPDATE 5_1_2 SET name=:name,comment=:comment,date=:date,pass=:pass WHERE id=:id';
 $stmt = $pdo->prepare($sql);
 $stmt->bindParam(':name',$name, PDO::PARAM_STR);
 $stmt->bindParam(':comment',$comment, PDO::PARAM_STR);
 $stmt->bindParam(':date',$date, PDO::PARAM_STR);
 $stmt->bindParam(':pass',$pass, PDO::PARAM_STR);
 $stmt->bindParam(':id',$id, PDO::PARAM_INT);
 $stmt->execute();
 }
 ?>
  <form action="" method="post">
        <input type="hidden" name="editnumber" 
               value="<?php
                        if(empty($editNO==false)){
                           echo $editNO;}?>">
        <input type="text" name="name" placeholder="名前" 
               value="<?php 
                        if(empty($editname==false)){
                           echo $editname;}?>">
        <input type="text" name="comment"  placeholder="コメント"
               value="<?php
                        if(empty($editcomment==false)){
                           echo $editcomment;}?>">
        <input type="submit" name="submit">
        <input type="number" name="deletenum" placeholder="削除対象番号">
        <input type="submit" name="delete">
        <input type="number" name="editnum" placeholder="編集対象番号">
        <input type="submit" name="edit" value="編集">
        <input type="text"   name="pass" placeholder="パスワード">
    </form>
<?php 
 $dsn = 'mysql:dbname=tb220937db;host=localhost';
 $user = 'tb-220937';
 $password = 'SyE8SJQhNH';
 $pdo = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
 //データを表示//
 $sql = 'SELECT * FROM 5_1_2';
 $stmt = $pdo->query($sql);
 $results = $stmt->fetchAll();
 foreach($results as $row){
     echo $row['id'].'　';
     echo $row['name'].'　';
     echo $row['comment'].'　';
     echo $row['date'].'<br>';
 echo"<hr>";
 }
 ?>
 </body>
 </html>