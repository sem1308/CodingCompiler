<?php
	$id = $_POST['id'];
	$pw = $_POST['pw'];
	
	$conn = new mysqli("localhost","root","123456","web_proj") or die("실패...");
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$conn -> query('set session character_set_connection=utf8');
	$conn -> query('set session character_set_results=utf8');
	$conn -> query('set session character_set_client=utf8');

	$return = array();

	$sql = "select * from users where id='$id'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		$row = mysqli_fetch_assoc($result);
		if($row['pw'] == $pw){
			$return['success'] = true;
			$return['token'] = $row['seq'];
			$return['id'] = $id;
		}else{
			$return['success'] = false;
			$return['error_msg'] = "비밀번호가 다릅니다.";	
		}
	} else {
		$return['success'] = false;
		$return['error_msg'] = "아이디가 없습니다.";
	}

	echo json_encode($return);
?>
