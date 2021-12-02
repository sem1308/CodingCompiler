<?php
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
		}
	}


	# 문제 제목 가져오기
	$sql = "SELECT title FROM problem_info WHERE id = \"$number\"";
	$result = get_result($sql)[0];
	$title = $result['title'];

	# 문제 기본 정보 가져오기
	$sql = "SELECT * FROM users_submits WHERE pro_id = $number AND is_correct=1"; 
	$result = get_result($sql);
	$language = array();
	foreach($result as $row){
		array_push($language,$row['language']);
	}

	function show_table(){
		global $result;
		static $i=1;
		foreach($result as $row){
			if($row['is_correct'] == 0){
				$is_correct = 'incorrect';
				$color='red';
			}else{
				$is_correct = 'correct';		
				$color='blue';
			}
			echo "<div class='submit_box'><span class = 'ex_box'><span class = 'lan'>".$row['id']."</span><span class = 'lan'>".$row['language']."</span><span style='color:$color' class='cor'>$is_correct</span><span class = 'time'>".$row['compile_time']."s</span></span>";
			echo "<div><textarea id='code_".$i++."'>".$row['code']."</textarea></div></div>";
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
<body style="background-color:white">
	<div class="header">
		<a class = "main_top" href = "/">
			<span style="color:gray;">Co</span><span class="title_right">ding</span><span style="color:black;">.</span><span style="color:gray;">Co</span><span class="title_right">mpiler</span>
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
		<div class="pro_button_block">
			<a href="./" class="pro_button">문제 목록</a>
			<a href="./pro_info.php?number=<?php echo $number?>" class = "pro_button">문제 정보 (<?php echo $title?>)</a>
			<a href="./pro_submit.php?number=<?php echo $number?>" class="pro_button">컴파일 & 제출</a>
			<a href="./pro_my_submit.php?number=<?php echo $number?>" class="pro_button">내 제출</a>
			<a href="./correct_answer.php?number=<?php echo $number?>" class="pro_button current">정답자</a>
			<a class="pro_button">Q&A</a>
		</div>
		<div class = "table_block">
			<div class = "pro_title"><?php echo $title?></div>
			<?php show_table(); ?>
		</div>
	</div>
</body>
<script>
	let len = '<?php echo count($result);?>';
	let language = <?php echo json_encode($language)?>;
	
	for(let i=0; i<len; i++){
		let lan = language[i];
		switch(language[i]){
			case "cpp":
				lan = "text/x-c++src";
				break;
			case "c":
				lan = "text/x-csrc";
				break;
			case "java":
				lan = "text/x-java";
				break;
			default:
				break;
		}
		var editor = CodeMirror.fromTextArea(document.getElementById("code_"+(i+1)), {
			lineNumbers: true,
			indentUnit: 4,
			matchBrackets: true,
			spellcheck: true,
			autocorrect: true,
			mode: lan,
		});
		editor.setSize('100%', '100%');
	}
	
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
