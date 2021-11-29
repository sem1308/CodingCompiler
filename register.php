<?php
	if($_COOKIE['token']!=null){
		header('location:/');
	}
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CoCo</title>
	<link href="../css/main.css" rel="stylesheet" type="text/css" />
	<link href="../css/register.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<div class="header">
		<a class = "main_top" href = "/">
			<span style="color:gray;">Co</span><span class="title_right">ding</span>.<span style="color:gray;">Co</span><span class="title_right">mpiler</span>
			<div class="top_right">			
				<a href = '/login.php' class='top_right_label'>로그인</a>
			</div>
		</a>
	</div>
	<div class = "main">
		<form action="./register_submit.php" method="POST" class = "register_block">
			<div class="register_input">
				<label>아이디</label>
				<input name='id' placeholder="아이디" type="text">			
			</div>
			<div class="register_input">
				<label>비밀번호</label>
				<input name='pw' placeholder="비밀번호" type="password">	
			</div>
			<div class="register_input">
				<label>이메일</label>
				<input name='email' placeholder="이메일" type="text">	
			</div>
			<button>회원가입</button>
		</form>
	</div>
</body>
</html>
