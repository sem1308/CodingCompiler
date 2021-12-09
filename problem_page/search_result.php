<?php
	$conn = mysqli_connect("localhost","root","123456","web_proj") or die("실패...");
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$conn -> query('set session character_set_connection=utf8');
	$conn -> query('set session character_set_results=utf8');
	$conn -> query('set session character_set_client=utf8');

    $sql = "select * from problem_info where $catagory like '%$search_con%' order by idx desc";
	$result = mysqli_query($conn,$sql);

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CoCo</title>
	<link href="../css/problem_table.css" rel="stylesheet" type="text/css" />
    <script src="//code.jquery.com/jquery.min.js"></script>
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
						echo "<span class = 'id_label'>$id</span><a href='./problem_page' onclick='logout()' class='top_right_label'>로그아웃</a>";		
					}
				?>
			</div>
		</a>
	</div>
	<br>
		<div id="search_box" style="text-align: right;">
			<form action="search_result.php" method="get">
				<select name"category">
					<option value"title">문제 제목</option>
				</select>
				<input type"text" name"search" size="20" required="requird">
				<button class="btn btn-primary">
					검색
				</button>
			</form>
		</div>
	<?php
		$category = $_GET['category'];
	    $search_con = $_GET['search'];
	?>
	<div style = "display:flex; justify-content:center; flex-direction:column; align-items:center">
		<div style = " width:60.1%; display: inline-flex; box-shadow: 0 1px 4px 0px #b4b4b4;">
			<div class = "thead" style="width:5%">No.</div>
			<div class = "thead" style="width:65.1%">문제</div>
			<div class = "thead" style="width:10%">정답</div>
			<div class = "thead" style="width:10%">제출</div>
			<div class = "thead" style="width:10%">정답률</div>
		</div>
		<table style="width:60%">
			<tbody>
				<?php
				if(mysqli_num_rows($result)>0){
					while($row = mysqli_fetch_assoc($result)){
						$id = $row["id"];
						echo ("<tr><td style='width:5%'>" . $row["id"] . "</td><td style='width:65%'><a class=\"data\" href='/problem_page/pro_info.php?number=$id'>" . 
							  $row["title"] . "</a></td><td style='width:10%'>" . $row["ans_people"] . "</td><td style='width:10%'>" . $row["submits"] . "</td><td style='width:10%'>" . 
							  $row["ans_pro"] . "%</td></tr>");
					}
				}
				?>
			</tbody>
		</table>
	</div>
		
		
	<br><br><br>
</body>
<script>
	function logout(){
		let date = new Date();
		date.setDate(date.getDate() - 100);
		let Cookie = `token=;Expires=${date.toUTCString()}`+'domain=prog-coco.run.goorm.io;path=/;'
		document.cookie = Cookie;
		Cookie = `id=;Expires=${date.toUTCString()}`+'domain=prog-coco.run.goorm.io;path=/;'
		document.cookie = Cookie;
	}	
</script>
</html>
