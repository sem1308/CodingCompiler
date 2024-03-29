<?php
	# number를 가지고 database에서 문제 정보를 끌어와야함
	$number = $_GET["number"];
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

	# 정답자인지 확인
	$id = $_COOKIE['id'];
	$correct_user=false;
	$sql = "SELECT is_correct FROM users_submits WHERE id = \"$id\" AND pro_id = $number AND is_correct=1"; 
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		$correct_user = true;
	}
	
	# 문제 기본 정보 가져오기
	$sql = "SELECT * FROM problem_info WHERE id = \"$number\""; 
	$result = get_result($sql)[0];
	$title = $result['title'];
	$submits = $result['submits'];
	$ans_people = $result['ans_people'];
	$ans_pro = $result['ans_pro']."%";
	$time_restrict = $result['time_restrict'];

	# 문제 세부사항 가져오기
	$sql = "SELECT * FROM problem_detail WHERE id = \"$number\""; 
	$result = get_result($sql)[0];
	$contents = $result['contents'];
	$restricts = $result['restricts'];
	$output = $result['output'];
	
	# 문제 예제 가져오기
	$sql = "SELECT * FROM pro_example WHERE pro_id = \"$number\""; 
	$result = get_result($sql);
	$example = array();
	$idx = 1;
	foreach($result as $row){
		$ex_id = $row['ex_id'];
		$sql = "SELECT * FROM problem_example WHERE ex_id = \"$ex_id\"";
		$example['case_'.$idx] = array_slice(get_result($sql)[0],1,2);
		$idx++;
	}

	# 문제 태그 가져오기
	$sql = "SELECT * FROM pro_tag WHERE pro_id = \"$number\""; 
	$result = get_result($sql);
	$tags = array();
	foreach($result as $row){
		$tag_id = $row['tag_id'];
		$sql = "SELECT * FROM problem_tag WHERE tag_id = \"$tag_id\"";
		array_push($tags,get_result($sql)[0]['tag']);
	}

	#문제 기본 내용 저장
	$problem = array(
		"문제 내용" => $contents,
		"입력 형식" => $restricts,
		"출력 형식" => $output,
	);

	#제한 및 정보 저장
	$restrict_info = array(
		"시간 제한" => $time_restrict."s",
		"제출" => $submits,
		"정답" => $ans_people,
		"정답률" => $ans_pro,
	);

	#상세 정보 출력
	function show($title,$contents){
		static $case_cnt=1;	
		echo"<div class = \"wrapper\"><div class=\"pro_info_title\">$title</div>
		<div class=\"pro_info_contents\">$contents</div></div>";
	}

	function show_case(){
		global $example;
		$num = 0;
		echo "<div class='wrapper'><div class=\"pro_info_title\">예제</div>";
		foreach($example as $ex){
			$input = $ex['input'];
			$output = $ex['output'];
			++$num;
			echo "<div class='wrapper'><div class=\"pro_info_title\"><span id = ".$num." class=\"case_label\"></span><div class=\"pro_info_title case\" onMouseOver=\"show_copy_msg(".$num.")\" onMouseLeave=\"init_copy_msg(".$num.")\" onclick= \"copy(".$num.")\">#</div></div><div class = 'pro_info_case'><div class = 'wrap'><div class='result'>input</div><pre id=input_".$num." class=\"ex_result\">$input</pre></div><div class = 'wrap'><div class='result'>output</div><pre class=\"ex_result\">$output</pre></div></div></div>";
		}		
		echo "</div>";
	}

	function show_tag(){
		global $tags;
		echo "<div class='wrapper'><div class=\"pro_info_title\">태그</div>";
		foreach($tags as $tag){
			echo "<div class='wrapper'><div class = 'pro_info_contents'>$tag</div></div>";
		}	
		echo "</div>";
	}

	function show_tr(){
		global $restrict_info;
		echo "<span class='pro_title_right'>시간제한: ".$restrict_info['시간 제한']."</span><span class='pro_title_right'>정답률: ".$restrict_info['정답률']."</span>";
	}

	$conn->close();
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CoCo</title>
	<link href="../css/problem_info.css" rel="stylesheet" type="text/css"/>
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
					echo "<span class = 'id_label'>$id</span><a href='./pro_info.php?number=$number' onclick='logout()' class='top_right_label'>로그아웃</a>";						
				}
			?>
		</div>
	</div>
	<div class="main">
		<div class="pro_button_block">
			<a href="./" class="pro_button">문제 목록</a>
			<div href="./pro_info.php?number=<?php echo $number?>" class = "pro_button current">문제 정보 (<?php echo $title?>)</div>
			<a href="./pro_submit.php?number=<?php echo $number?>" class="pro_button">컴파일 & 제출</a>
			<a href="./pro_my_submit.php?number=<?php echo $number?>" class="pro_button">내 제출</a>
			<?php
				if($correct_user){
					echo '<a href="./correct_answer.php?number='.$number.'" class="pro_button">정답자</a>';				
				}
			?>
			<a href="../board?number=<?php echo $number?>" class="pro_button">Q&A</a>
		</div>
		<div class="pro_main">
			<div class = "main_middle">
				<div class="pro_info_top">
					<div class = "pro_title">
						<?php echo $title?>
						<?php show_tr()?>
					</div>
				</div>
				<div class="pro_info_middle">
					<div class="pro_info">
						<?php
							foreach($problem as $key => $value){
								show($key,$value);
							}
			   				show_case();
						   	show_tag();
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
<script>
	function copy(num) {
		var copyText = document.getElementById("input_"+num).innerText;
		const textArea = document.createElement('textarea');
		document.body.appendChild(textArea);
		textArea.value = copyText;
		textArea.select();
		document.execCommand('copy');
		document.body.removeChild(textArea);
		
		const msg = document.getElementById(num)
		msg.innerText="복사 완료";
		msg.setAttribute("style","display:block;");
	}
	function show_copy_msg(num) {
		const msg = document.getElementById(num)
		msg.innerText="입력 복사";
		msg.setAttribute("style","display:block;");
	}
	function init_copy_msg(num) {
		const msg = document.getElementById(num)
		msg.setAttribute("style","display:none");
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