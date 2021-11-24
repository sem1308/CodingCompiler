<?php
	# $number = $_GET["number"];
	# number를 가지고 database에서 문제 정보를 끌어와야함
	$number = 10;
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
	$sql = "SELECT * FROM problem_info WHERE id = \"$number\""; 
	$result = get_result($sql)[0];
	$title = $result['title'];
	$submits = $result['submits'];
	$ans_people = $result['ans_people'];
	$ans_pro = $result['ans_pro'];
	$time_restrict = $result['time_restrict'];

	# 문제 세부사항 가져오기
	$sql = "SELECT * FROM problem_detail WHERE id = \"$number\""; 
	$result = get_result($sql)[0];
	$contents = $result['contents'];
	$restricts = $result['restricts'];
	
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
		"제한 사항" => $restricts,
		"예제" => $example,
		"tag" => $tags,
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
		$is_case = strpos($title, "case");
		$is_result = ($title==="input" || $title==="output");
		$w_case=null;
		$case=null;
		if($is_case !== false){
			$case='case';
			$title = '#';
		}else if($is_result !== false){
			$case='result';
			$w_case = "wrap";
		}
		if(!is_int($title)){
			$title = "<div class=\"pro_info_title $case\">$title</div>";
		}else{
			$title = null;
		}
		echo"<div class = \"wrapper $w_case\">$title";	
		if($is_case !== false){
			echo"<div class=\"pro_info_case\">";		
		}
		if(is_array($contents)){
			foreach($contents as $key => $value){
				show($key,$value);
			}
		}else{
			if($is_result !== false){
				echo "<pre class=\"ex_result\">$contents</pre>";
			}else{
				echo "<div class=\"pro_info_contents\">$contents</div>";
			}
		}
		echo "</div>";
		if(strpos($title, "case") !== false){
			echo "</div>";
		}
	}

	#기본 정보 출력
	function show_res_info(){
		global $restrict_info;
		$titles = "";
		$contents = "";
		foreach($restrict_info as $key => $item){
			$titles = $titles."<th style=\"width:25%;\">".$key."</th>";
			$contents = $contents."<td style=\"width:25%;\">".$item."</td>";
		}
		echo "<table>";
		echo "<tr>".$titles."</tr>";
		echo "<tr>".$contents."</tr>";
		echo "</table>";
	}
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CoCo</title>
	<link href="../css/problem_info.css" rel="stylesheet" type="text/css" />
    <script src="//code.jquery.com/jquery.min.js"></script>
</head>
<body>
	<div class="pro_main">
		<a class = "main_top" href = "/">
			<span style="color:gray;">Co</span><span class="title_right">ding</span>.<span style="color:gray;">Co</span><span class="title_right">mpiler</span>
		</a>
		<hr>
		<div class = "main_middle">
			<div class = "pro_title">
				<?php echo $title?>
			</div>
			<div class="pro_restrict_info">
				<?php show_res_info() ?>
			</div>
			<div class="pro_info_middle">
				<div class="pro_info">
					<?php
						foreach($problem as $key => $value){
							show($key,$value);
						}
					?>
				</div>
				<div class="pro_button_block">
					<a href="./pro_submit.php?number=<?php echo $number?>" class="pro_button">컴파일 & 제출</a>
					<a class="pro_button">내 제출</a>
					<a class="pro_button">정답자</a>
					<a class="pro_button">Q&A</a>
				</div>
			</div>
		</div>
	</div>
</body>
</html>

<?php
	$conn->close();
?>

