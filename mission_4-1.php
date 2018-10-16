<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>mission_4-1</title>
</head>
<body>

<?php
//変数に各値を代入
$name = $_POST['name'];
$comment = $_POST['comment'];
$date = date("Y年m月d日 H時i分s秒");
$delete = $_POST['delete'];
$edit = $_POST['edit'];
$edithidden = $_POST['edithidden'];
$pass = $_POST['password'];
$deletePass = $_POST['deletePass'];
$editPass = $_POST['editPass'];

/*データベース*/
//接続(3-1)
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn,$user,$password);

//表作成(3-2)
$sql = "CREATE TABLE IF NOT EXISTS table1"
."("
."id INT AUTO_INCREMENT,"
."name char(32),"
."comment TEXT,"
."date char(32),"
."password char(32),"
."PRIMARY KEY(id)"
.");";
$stmt = $pdo->query($sql);

/*リセット機能
	$sql = "delete from table1";
	$results = $pdo->query($sql);
*/

//新規投稿機能
if(empty ($edithidden)){
	if(!empty($name) and !empty($comment) and !empty($pass)){
		//INSERTで入力(3-5)
		$sql = $pdo -> prepare("INSERT INTO table1 (id,name,comment,date,password) VALUES (:id,:name,:comment,:date,:password)");
		//パラメータに値を入れる
		$sql -> bindValue(':id', $id, PDO::PARAM_INT);
		$sql -> bindParam(':name',$name,PDO::PARAM_STR);
		$sql -> bindParam(':comment',$comment,PDO::PARAM_STR);
		$sql->bindParam(':date',$date,PDO::PARAM_STR);
		$sql->bindParam(':password',$pass,PDO::PARAM_STR);
		$sql -> execute();
	}
}

//削除機能
if (!empty($delete) and !empty($deletePass)) {
	$sql1 = "SELECT * FROM table1";
	$stmt = $pdo->query($sql1);
	foreach($stmt as $row){
		if($row['id'] == $delete and $row['password'] == $deletePass){
			$sql2 =  "delete from table1 where id=$delete";
			$results = $pdo->query($sql2);
		}
       		if ($row['id'] == $delete){
			if($row['password'] != $deletePass){
				echo "パスワードが違います";
				break;
			}
		}
	}
}

//編集機能　入力フォーム表示
if (!empty($edit) and !empty($editPass)) {
	$sql = "SELECT * FROM table1";
	$stmt = $pdo->query($sql);
	foreach($stmt as $row){
		if($row['id'] == $edit and $row['password'] == $editPass){
			$data0 = $row['id'];
			$data1 = $row['name'];
			$data2 = $row['comment'];
			$data3 = $row['date'];
			$data4 = $row['password'];
		}
		if($row['id'] == $edit){
			if($row['password'] != $editPass){
				echo"パスワードが違います。";
				break;
			}
		}
	}
}
		
//編集機能　ファイル上書き
if(!empty($edithidden) and !empty($name) and !empty($comment) and !empty($pass)){
	$sql = "SELECT * FROM table1";
	$stmt = $pdo->query($sql);
	foreach($stmt as $row){
		if($row['id'] == $edithidden){
			$id = $edithidden;
			$name2 = $name;
			$comment2 = $comment;
			$password2 = $pass;
			$sql = "update table1 set name='$name2',comment='$comment2',password='$password2'where id =$id";
			$results = $pdo->query($sql);
		}
	}
	unset($edithidden);
}

?>

	<form method="post" action="mission_4-1.php">
 	<p><input type="text" name="name" value = "<?php echo $data1;?>" placeholder = "名前"></p>
 	<p><input type="text" name="comment" value = "<?php echo $data2;?>" placeholder = "コメント"></p>
	<p><input type="text" name="password" value = "<?php echo $data4;?>" placeholder = "パスワード"></p>
 	<p><input type="hidden" name="edithidden" value = "<?php echo $data0;?>"></p>
	<p><input type="submit" value="送信"></p>

	<p><input type="text" name="delete" placeholder = "削除対象番号"></p>
	<p><input type="text" name="deletePass" placeholder = "パスワード"></p>
	<p><input type="submit" value="削除"></p>

	<p><input type="text" name="edit" placeholder = "編集対象番号"></p>
	<p><input type="text" name="editPass" placeholder = "パスワード"></p>
	<p><input type="submit" value="編集"></p>

	</form>


<?php
//selectで表示(3-6)
$sql = 'SELECT*FROM table1';
$results = $pdo -> query($sql);
foreach ($results as $row){
	//$rowの中にはテーブルのカラム名が入る
	echo $row['id'].' ';
	echo $row['name'].' ';
	echo $row['comment'].' ';
	echo $row['date'].'<br>';
}

?>

</body>
</html>
