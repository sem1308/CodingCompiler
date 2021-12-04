<?php
	$conn = mysqli_connect("localhost","root","123456","web_proj") or die("실패...");
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$conn -> query('set session character_set_connection=utf8');
	$conn -> query('set session character_set_results=utf8');
	$conn -> query('set session character_set_client=utf8');

    $sql = "SELECT * FROM problem_info"; 
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
						echo "<span class = 'id_label'>$id</span><a href='./' onclick='logout()' class='top_right_label'>로그아웃</a>";		
					}
				?>
			</div>
		</a>
	</div>
	<br>
		<div id="search_box" style="text-align: right; padding-bottom:15px;">
			<form action="search_result.php" method="get">
				<select name"category">
					<option value"title">문제 제목</option>
					<option value"ans_pro">정답률</option>
				</select>
				<input type"text" name"search" size="20" required="requird">
				<button class="btn btn-primary">
					검색
				</button>
			</form>
		</div>
		<table style="width:70%">
            <thead><tr><th style="width:10%">문제</th>
				<th style="width:30%">문제 제목</th>
				<th style="width:10%">맞힌 사람</th>
				<th style="width:5%">제출</th>
				<th style="width:5%">정답률</th></tr></thead>
			<tbody>
				<div class="data">
				<?php
					$i = 0;
				if(mysqli_num_rows($result)>0){
					while($row = mysqli_fetch_assoc($result)){
						$id = $row["id"];
						if($i % 2 == 0)
						echo ("<tr class=\"color\"><td>" . $row["id"] . "</td><td><a class=\"data\" href='/problem_page/pro_info.php?number=$id'>" . $row["title"] . "</a></td><td>" . $row["ans_people"] . "</td><td>" . $row["submits"] . "</td><td>" . $row["ans_pro"] . "</td></tr>");
						else echo ("<tr><td>" . $row["id"] . "</td><td><a class=\"data\" href='/problem_page/pro_info.php?number=$id'>" . $row["title"] . "</a></td><td>" . $row["ans_people"] . "</td><td>" . $row["submits"] . "</td><td>" . $row["ans_pro"] . "</td></tr>");
						$i ++;
					}
				}
				?>
				</div></tbody></table>
		
	<br><br><br>
</body>
</html>
