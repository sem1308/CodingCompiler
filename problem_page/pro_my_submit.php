<?php
	if($_COOKIE['token']==null){
		$prevPage = "../login.php";
		header('location:'.$prevPage);
	}

	$root="../compiler/codemirror";
	$base_root="../compiler";

	$id = $_COOKIE['id'];
	$number = $_GET['number'];
	$conn = new mysqli("localhost","root","123456","web_proj") or die("실패...");
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$conn -> query('set session character_set_connection=utf8');
	$conn -> query('set session character_set_results=utf8');
	$conn -> query('set session character_set_client=utf8');

	function get_result($sql){
		global $conn;
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			$return = array();
			while($row = mysqli_fetch_assoc($result)) {
			  array_push($return,$row);
			}
			return $return;
		} else {
			die("Database Error: " . $conn->connect_error);
		}
	}
	
	# 문제 기본 정보 가져오기
	$sql = "SELECT * FROM users_submits WHERE id = \"$id\" AND pro_id = $number "; 
	$result = get_result($sql);
	function show_table(){
		global $result;
		static $i=1;
		foreach($result as $row){
			if($row['is_correct'] == 0){
				$is_correct = 'X';			
			}else{
				$is_correct = 'O';			
			}
			echo "<tr><th>문제 번호</th><td>".$row['pro_id']."</td><th>유저</th><td>".$row['id']."</td><th>컴파일 시간</th><td>".$row['compile_time']."</td><th>정답</th><td>$is_correct</td></tr>";
			echo "<tr><td colspan='8'><textarea id='code_".$i++."'>".$row['code']."</textarea></td></tr>";
		}
	}
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CoCo</title>
	<link href="../css/main.css" rel="stylesheet" type="text/css" />
	<link href="../css/my_submit.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="<?php echo $root?>/lib/codemirror.css">
	<link rel=stylesheet href="<?php echo $root?>/doc/docs.css">
	<link rel="stylesheet" href="<?php echo $root?>/addon/hint/show-hint.css">
	<script src="<?php echo $root?>/lib/codemirror.js"></script>
	<script src="<?php echo $root?>/addon/hint/show-hint.js"></script>
	<script src="<?php echo $root?>/addon/hint/xml-hint.js"></script>
	<script src="<?php echo $root?>/addon/hint/html-hint.js"></script>
	<script src="<?php echo $root?>/mode/javascript/javascript.js"></script>
	<script src="<?php echo $root?>/mode/css/css.js"></script>
	<script src="<?php echo $root?>/mode/python/python.js"></script>
	<script src="<?php echo $root?>/mode/php/php.js"></script>
	<script src="<?php echo $root?>/mode/xml/xml.js"></script>
	<script src="<?php echo $root?>/mode/clike/clike.js"></script>
	<script src="<?php echo $root?>/mode/htmlmixed/htmlmixed.js"></script>
	<script src="<?php echo $root?>/addon/edit/matchbrackets.js"></script>
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
		<div class = "table_block">
			<table>
				<?php show_table(); ?>
			</table>
		</div>
	</div>
</body>
<script>
	let len = '<?php echo count($result);?>';
	for(let i=1; i<=len; i++){
		var editor = CodeMirror.fromTextArea(document.getElementById("code_"+i), {
			lineNumbers: true,
			indentUnit: 4,
			matchBrackets: true,
			spellcheck: true,
				autocorrect: true
		});
		editor.setSize('100%', '100%');
	}
	
	function logout(){
		let date = new Date();
		date.setDate(date.getDate() - 100);
		let Cookie = `token=;Expires=${date.toUTCString()}`
		document.cookie = Cookie;
	}
</script>
</html>
