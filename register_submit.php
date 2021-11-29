<?php
	$id = $_POST['id'];
	$pw = $_POST['pw'];
	$email = $_POST['email'];
	
	$conn = new mysqli("localhost","root","123456","web_proj") or die("실패...");
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$conn -> query('set session character_set_connection=utf8');
	$conn -> query('set session character_set_results=utf8');
	$conn -> query('set session character_set_client=utf8');
	
	$sql = "INSERT INTO users(id,pw,address) values('$id','$pw','$email')"; 
	$result = $conn->query($sql);
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CoCo</title>
	<link href="../css/register.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<div class = "reg_main">
		<div class = "ok_block">
			회원가입 완료
			<a href="/" class="button_block">
				로그인
			</a>
		</div>
	</div>
</body>
</html>
