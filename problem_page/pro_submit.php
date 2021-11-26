<?php
	$root="../compiler/codemirror";
	$base_root="../compiler";
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
	
	# 문제 제목 및 시간제한 가져오기
	$sql = "SELECT title,time_restrict FROM problem_info WHERE id = \"$number\""; 
	$result = get_result($sql)[0];
	$title = $result['title'];
	$time_rest = $result['time_restrict'];

	# 문제 예제 가져오기
	$sql = "SELECT * FROM pro_example WHERE pro_id = \"$number\""; 
	$result = get_result($sql);
	$example = array();
	$idx = 1;
	foreach($result as $row){
		$ex_id = $row['ex_id'];
		$sql = "SELECT * FROM problem_example WHERE ex_id = \"$ex_id\"";
		$example[$idx] = array_slice(get_result($sql)[0],1,2);
		$idx++;
	}

	$submit_table_init = "<thead><tr><th class=\"submit_table\" style=\"width:35%;\">테스트 No.</th><th class=\"submit_table\" style=\"width:30%;\">결과</th><th class=\"submit_table\">소요 시간</th></tr></thead>";
	
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CoCo</title>
	<link href="../css/problem_submit.css" rel="stylesheet" type="text/css" />
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
    <script src="//code.jquery.com/jquery.min.js"></script>
</head>
<body style="background:white;">
	<div class="header">
		<a class = "main_top" href = "/">
			<span style="color:gray;">Co</span><span class="title_right">ding</span><span style="color:black;">.</span><span style="color:gray;">Co</span><span class="title_right">mpiler</span>
		</a>
	</div>
	<div class ="main">
		<div class="pro_button_block">
			<a href="./" class="pro_button">문제 목록</a>
			<a href="./pro_info.php?number=<?php echo $number?>" class = "pro_button">문제로 돌아가기 (<?php echo $title?>)</a>
			<a class="pro_button">내 제출</a>
			<a class="pro_button">정답자</a>
			<a class="pro_button">Q&A</a>
		</div>
		<div class="pro_main">
			<div class = "code_submit_block">
				<div id = "code_block">
					<div class = "code_box">
						<select id = "language" onchange='categoryChange()'>
							<option value="cpp">C++</option>
							<option value="c">C</option>
							<option value="java">JAVA</option>
							<option value="js">JavaScript</option>
							<option value="py">Python</option>
							<option value="php">PHP</option>
						</select>
						<textarea id = "code" autofocus></textarea>
					</div>
				</div>
				<div class = "return_box">
					<div style="width:100%;">
						<div style="width:100%;">
							<div class = "label" >입력</div>
							<textarea id = "input"></textarea>
							<div class = "arrow_img"><img src="../css/imgs/start.png" class = "run_button" onclick="get_result()"></div>
							<div class = "label">출력</div>
							<div id="result"></div>
						</div>			
					</div>
				</div>
				<div class = "submit_box">
					<button class = "submit_button" onclick="submit()">제출</button>
					<table id = "submit_res" class="submit_res_box">
						<?php echo $submit_table_init?>
					</table>
					<div id = "ans_block">
						<div class = "sub_ans_box">
							<div class = "show_ans top">
								<span class = "ans_pro">정답률:</span><span id = "ans_pro" class="ans_pro">-</span>							
							</div>
							<div id = "correct">
								correct
							</div>
							<div id = "incorrect">
								incorrect
							</div>
							<div class = "show_ans">
								<span class = "ans_label">정답</span><span class = "ans_label">전체</span>								
							</div>
							<div class = "show_ans">
								<span id = "y_cnt" class = "ans_label">-</span> / <span id = "w_cnt" class = "ans_label">-</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class = "case_block">

			</div>
		</div>
	</div>
</body>
<script>
	var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
		lineNumbers: true,
		indentUnit: 4,
		matchBrackets: true,
		spellcheck: true,
		autocorrect: true
	});
	editor.setSize('100%', 600);
	var result;	
	let is_made = false;
	let inputs;
	let outputs;
	let len;
	let board="<tbody id = \"made_board\">";
	function categoryChange(){
		let default_code;
		let lan = $('#language').val();
		switch(lan){
			case "cpp":
				lan = "text/x-c++src";
				break;
			case "c":
				lan = "text/x-csrc";
				break;
			case "java":
				lan = "text/x-java";
				break;
			case "js":
				lan = "javascript";
				break;
			case "py":
				lan = "python";
				break;
		}
		$.ajax({
			url: "<?php echo $base_root?>/php/get_default_code.php",
			type: "POST",
			data: {
				language: $('#language').val(),
			},
			success: function(data){
				editor.setValue(data);
				editor.setOption("mode",lan);
				$('#result').html("");
			}
		});
	}
	function get_result(){
		const obj = document.getElementById('result');
		obj.innerHTML = "<img src=\"../css/imgs/roading.gif\" width=\"20px\" height=\"20px\">";
		$.ajax({
			url: "<?php echo $base_root?>/php/compile.php",
			type: "GET",
			data: {
				language: $('#language').val(),
				input: $('#input').val(),
				value: editor.getValue(),
			},
			success: function(data){
				result = JSON.parse(data);
				show_result('result');
			}
		});
	}
	function show_result(name){
		const obj = document.getElementById(name);
		obj.innerHTML = "<div>소요시간: "+result.runtime+"s</div>"+"<pre class='result_text''>"+result.result+"</pre>";
	}
	
	async function submit(){
		const cor = document.getElementById('correct');
		const incor = document.getElementById('incorrect');
		cor.setAttribute('style', 'display:none');
		incor.setAttribute('style', 'display:none');
		const is_correct = await show_sub_res();
		$.ajax({
			url: "./php/push_result.php",
			type: "POST",
			data: {
				id: <?php echo $number?>,
				correct: is_correct,
			},
			success: function(data){
			}
		});
	}
	
	function get_io_data(){
		$.ajax({
			url: "./php/submit.php",
			type: "POST",
			async: false,
			data: {
				number: <?php echo $number?>,
			},
			success: function(data){
				let result = JSON.parse(data);
				inputs = result.inputs;
				outputs = result.outputs;
				len = inputs.length;
			}
		});
	}
	
	function make_sub_board(){
		const obj = document.getElementById('submit_res');
		let case_num;
		for(let i=0; i<len; i++){
			case_num = i+1;
			board = board+"<tr><td class=\"submit_table\" style=\"width:35%;\">"+case_num+"</td><td class=\"submit_table\" style=\"width:30%;\"><img id='roading_img_"+case_num+"' src=\"../css/imgs/roading.gif\" width=\"20px\" height=\"20px\"><span id=case_"+case_num+"></span></td><td id=case_time_"+case_num+" class=\"submit_table\">-</td></tr>";
		}
		board = board+"</tbody>";
		obj.innerHTML = obj.innerHTML+board;
	}
	
	function change_sub_board(){
		for(let i=0; i<len; i++){
			let case_num = i+1;
			const img = document.getElementById('roading_img_'+case_num);
			const t = document.getElementById('case_time_'+case_num);
			const c = document.getElementById('case_'+case_num);
			img.setAttribute('style','display:block; margin-left:21px;');
			t.innerHTML = '-';
			c.innerHTML = null;
		}
		const a = document.getElementById('ans_pro');
		a.innerHTML = "-";
		const y = document.getElementById('y_cnt');
		y.innerHTML = "-";
	}
	
	function show_sub_res(){
		return new Promise((resolve, reject)=>{
			if(!is_made){
				const b = document.getElementById("made_board");
				b.setAttribute('style','display:table-row-group;');
				is_made = true;
			}else{
				change_sub_board();
			}
			let ans_cnt=0;
			let end_cnt=0;
			let is_correct=0;
			for(let i=0; i<len; i++){
				let case_num = i+1;
				$.ajax({
					url: "<?php echo $base_root?>/php/compile.php",
					type: "GET",
					data: {
						language: $('#language').val(),
						input: inputs[i],
						value: editor.getValue(),
					},
					success: function(data){
						results = JSON.parse(data);
						let result = (results.result).trim();
						let output = outputs[i].trim();
						output = output.replace(/\r/g, "");
						result = result.replace(/\r/g, "");
						console.log(output);
						console.log(result);
						time = results.runtime;
						if(result[result.length-1] == '\n'){
							result = result.slice(0,result.length-1);
						}
						const img = document.getElementById('roading_img_'+case_num);
						img.setAttribute('style','display:none');
						const t = document.getElementById('case_time_'+case_num);
						const c = document.getElementById('case_'+case_num);
						const time_rest = <?php echo $time_rest?>;
						end_cnt += 1;
						if(time > time_rest){
							c.innerHTML = '<span style="color:#9400D3;">시간초과</span>';
						}else{
							if((output == result) || (parseFloat(output) == parseFloat(result))){
								ans_cnt += 1;
								c.innerHTML = '<span style="color: blue; ">O</span>';	
							}else{
								c.innerHTML = '<span style="color:red;">X</span>';
							}
						}
						t.innerHTML = time+'s';
						if(end_cnt == len){
							const b = document.getElementById('ans_block');
							b.setAttribute('style', 'display:block');
							let res = ans_cnt/len*100;
							const a = document.getElementById('ans_pro');
							a.innerHTML = res.toFixed(1)+"%";
							const y = document.getElementById('y_cnt');
							y.innerHTML = ans_cnt;
							const w = document.getElementById('w_cnt');
							w.innerHTML = len;
							const cor = document.getElementById('correct');
							const incor = document.getElementById('incorrect');
							if(ans_cnt == len){
								is_correct = 1;
								cor.setAttribute('style', 'display:block');
								incor.setAttribute('style', 'display:none');
							}else{
								cor.setAttribute('style', 'display:none');
								incor.setAttribute('style', 'display:block');
							}
							resolve(is_correct);
						}
					}
				});
			}
		});
	}
	
	get_io_data();
	make_sub_board();
	categoryChange();
</script>	
</html>
