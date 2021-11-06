<?php
	# $problem = $_GET["problem"];

	# number를 가지고 database에서 문제 정보를 끌어와야함
	$number = 1;
	$title = "A+B";
	$problem = array(
		"문제 내용" => "A와 B를 더한 값을 출력하시오",
		"제한 사항" => "0 < A,B <100000",
		"예제" => array( "case_1" => array("input" => "3 4", "output" => "7"), "case_2" => array("input" => "5 10", "output" => "15")),
		"tag" => "수학",
	);

	function show($title,$contents){
		echo"<div class = \"wrapper\"><div class=\"pro_info_title\">$title</div>";	
		if(strpos($title, "case") !== false){
			echo"<div class=\"pro_info_case\">";		
		}
		if(is_array($contents)){
			foreach($contents as $key => $value){
				show($key,$value);
			}
		}else{
			echo "<div class=\"pro_info_contents\">$contents</div></div>";
		}
		if(strpos($title, "case") !== false){
			echo "</div>";
		}
	}
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CoCo</title>
	<link href="../css/problem_info.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<div class="pro_main">
		<div class = "main_top">
			<span style="color:gray;">Co</span><span class="title_right">ding</span>.<span style="color:gray;">Co</span><span class="title_right">mpiler</span>
		</div>
		<hr>
		<div class = "main_middle">
			<div class = "pro_title">
				<?php echo $title?>
			</div>
			<div class="pro_restrictions">
				<?php?>
			</div>
			<div class="pro_info">
				<?php
					foreach($problem as $key => $value){
						show($key,$value);
					}
				?>
			</div>
		</div>
	</div>
</body>
</html>