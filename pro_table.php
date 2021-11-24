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
	<div class="pro_main">
		<a class = "main_top" href = "/">
			<span style="color:gray;">Co</span><span class="title_right">ding</span>.<span style="color:gray;">Co</span><span class="title_right">mpiler</span>
		</a>
		<hr>
		<table style="width:100%">
            <thead><tr><th style="width:10%">문제</th>
				<th style="width:55%">문제 제목</th>
				<th style="width:11%">맞힌 사람</th>
				<th style="width:12%">제출</th>
				<th style="width:15%">정답률</th></tr></thead>
			<tbody>
				<?php
				if(mysqli_num_rows($result)>0){
					while($row = mysqli_fetch_assoc($result)){
						echo ("<tr><td>" . $row["id"] . "</td><td>" . $row["title"] . "</td><td>" . $row["ans_people"] . "</td><td>" . $row["submits"] . "</td><td>" . $row["ans_pro"] . "</td></tr>");
					}
				}
				?>
			</tbody></table>
		</div>
</body>
</html>
