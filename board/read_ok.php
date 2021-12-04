<?php
   	$conn = mysqli_connect("localhost", "root", "123456", "web_proj");
	$conn -> query('set session character_set_connection=utf8');
	$conn -> query('set session character_set_results=utf8');
	$conn -> query('set session character_set_client=utf8');
	
	if($_COOKIE['token']==null){
		$prevPage = "../login.php";
		header('location:'.$prevPage);
	}

    $id = $_COOKIE['id'];
    $bno = $_GET['idx'];
	$content = $_POST['content'];
        $date = date("Y-m-d H:i:s");
    if($bno && $id && $content){
		$sql = "insert into reply(con_num,id,content,date) values ('$bno','$id','$content','$date')";
		$result = mysqli_query($conn, $sql);
        
        echo "<script>alert('댓글이 작성되었습니다.');
        location.href='read.php?id=$id&idx=$bno';</script>";
    }else{
        echo "<script>alert('댓글 작성에 실패했습니다.');
        history.back();</script>";
    }
 
?>