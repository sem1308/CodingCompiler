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
	$title = $_POST['title'];
	$content = $_POST['content'];
	$date = date('Y-m-d');
  
  if($id && $title && $content){
      $sql = "insert into board(id,title,content,date) values('$id','$title','$content','$date')";
	  $result = mysqli_query($conn,$sql);
      echo "<script>
      alert('글쓰기가 완료되었습니다.');
      location.href='board?id=$id';</script>";
    }
    else{
      echo "<script>
      alert('글쓰기에 실패했습니다.');
      history.back();</script>";
    }
?>
