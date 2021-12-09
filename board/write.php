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
	$number = $_GET['number'];
	$sql = "select title from problem_info where id=$number";
	$main = mysqli_query($conn, $sql);
	$main = mysqli_fetch_row($main)[0];
	
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CoCo</title>
	<link href="../css/board.css" rel="stylesheet" type="text/css" />
    <script src="//code.jquery.com/jquery.min.js"></script>
</head>
<body>
	<div class="header">
		<a class = "main_top" href = "/">
			<span style="color:gray;">Co</span><span class="title_right">ding</span>.<span style="color:gray;">Co</span><span class="title_right">mpiler</span>
			</a>
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
	</div>
	<div class="main">
		<div class="main">
		<div class="pro_button_block">
			<a href="/problem_page/index.php" class="pro_button">문제 목록</a>
			<a href="/problem_page/pro_info.php?number=<?php echo $number?>" class = "pro_button current">문제 정보 (<?php echo $main?>)</a>
			<a href="/problem_page/pro_submit.php?number=<?php echo $number?>" class="pro_button">컴파일 & 제출</a>
			<a href="/problem_page/pro_my_submit.php?number=<?php echo $number?>" class="pro_button">내 제출</a>
			<?php
				if($correct_user){
					echo '<a href="/problem_page/correct_answer.php?number='.$number.'" class="pro_button">정답자</a>';				
				}
			?>
			<a href="../board?number=<?php echo $number?>" class="pro_button">Q&A</a>
		</div>
	
    <div id="board_write">
            <div id="write_area">
				<div class = "write_block">
					<form enctype="multipart/form-data" action="/board/write_ok.php?id=<?php echo $id;?>&number=<?php echo $number;?>" method="post">
						<div id="in_title">
							<textarea name="title" id="utitle" rows="1" cols="55" placeholder="제목" maxlength="100" required></textarea>
						</div>
						<div class="wi_line"></div>
						<div id="in_content">
							<textarea name="content" id="ucontent" placeholder="내용" required></textarea>
						</div>
						<div class="bt_se">
							<button type="submit">글 작성</button>
						</div>
					</form>
				</div>
            </div>
		</div></div>
	</div>
	</body>
	</html>
