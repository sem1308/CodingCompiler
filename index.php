<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CoCo</title>
	<link href="css/main.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<div class="header">
		<a class = "main_top" href = "/">
			<span style="color:gray;">Co</span><span class="title_right">ding</span>.<span style="color:gray;">Co</span><span class="title_right">mpiler</span>
			<div class="top_right">
				<?php
					$id = $_COOKIE['id'];
					if($_COOKIE['token'] == null){				
						echo "<a href = '/login.php' class='top_right_label'>로그인</a><a href = '/register.php' class='top_right_label'>회원가입</a>";
					}else{
						echo "<span class = 'id_label'>$id</span><a href='./' onclick='logout()' class='top_right_label'>로그아웃</a>";						
					}
				?>
			</div>
		</a>
	</div>
	<div class = "main">
		<div class = "main_middle">
			<a class = "menu" href = "problem_page">
				<div>
					<img src="problem.png" class = "image"/>				
				</div>
				<span class="label" >문제</span>
			</a>
			<a class = "menu" href = "compiler">
				<div>
					<img src="compile.png" class = "image"/>				
				</div>
				<span class="label" >컴파일러</span>
			</a>
		</div>
	</div>
</body>
<script>
	function logout(){
		let date = new Date();
		date.setDate(date.getDate() - 100);
		let Cookie = `token=;Expires=${date.toUTCString()}`
		document.cookie = Cookie;
	}
</script>
</html>
