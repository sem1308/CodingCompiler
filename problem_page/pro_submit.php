<?php
	$root="../compiler/codemirror";
	$base_root="../compiler";
	$number = $_GET['number'];
	$submit_table_init = "<tr><th class=\"submit_table\" style=\"width:35%;\">테스트 No.</th><th class=\"submit_table\" style=\"width:30%;\">결과</th><th class=\"submit_table\">소요 시간</th></tr>";
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CoCo</title>
	<link href="../css/problem_submit.css" rel="stylesheet" type="text/css" />
	<link href="../css/compiler.css" rel="stylesheet" type="text/css" />
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
	<div class="pro_main">
		<a class = "main_top" href = "/">
			<span style="color:gray;">Co</span><span class="title_right">ding</span><span style="color:black;">.</span><span style="color:gray;">Co</span><span class="title_right">mpiler</span>
		</a>
		<hr style="margin-bottom:30px;">
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
				<div class = "return_box">
					<div style="width:100%;">
						<div style="width:100%; display:inline-flex">
							<div class = "label" >입력</div>
							<div class = "label">출력</div>
						</div>
						<div style="border: 1px solid #ddd; display:inline-flex; width:100%;">
							<div style="width:50%;">
								<textarea id = "input" style="border-right: 1px solid #ddd; height:200px; width:100%; padding:15px; font-size:13px; font-family:none;"></textarea>
								<button class = "run_button" onclick="get_result()">실행</button>
							</div>
							<div id="result" style="width:50%; border:none; height:200px;"></div>
						</div>				
					</div>
				</div>
			</div>
			<div class = "submit_box">
				<button class = "submit_button" onclick="submit()">제출</button>
				<table id = "submit_res" class="submit_res_box">
					<?php echo $submit_table_init?>
				</table>
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
	editor.setSize(601, 400);
	var result;	
	let is_made = false;
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
	
	function submit(){
		$.ajax({
			url: "./php/submit.php",
			type: "POST",
			data: {
				number: <?php echo $number?>,
			},
			success: function(data){
				let result = JSON.parse(data);
				show_sub_res(result.inputs,result.outputs);
			}
		});
	}
	
	function make_sub_board(len){
		const obj = document.getElementById('submit_res');
		for(let i=0; i<len; i++){
			let val = obj.innerHTML;
			let case_num = i+1;
			obj.innerHTML = val+"<tr><th class=\"submit_table\" style=\"width:35%;\">"+case_num+"</th><th id=case_"+case_num+" class=\"submit_table\" style=\"width:30%;\"><img src=\"../css/imgs/roading.gif\" width=\"20px\" height=\"20px\"></th><th id=case_time_"+case_num+" class=\"submit_table\">-</th></tr>";
		}
	}
	
	function change_sub_board(len){
		for(let i=0; i<len; i++){
			let case_num = i+1;
			const c = document.getElementById('case_'+case_num);
			const t = document.getElementById('case_time_'+case_num);
			c.innerHTML = "<img src=\"../css/imgs/roading.gif\" width=\"20px\" height=\"20px\">";
			t.innerHTML = '-';
		}
	}
	
	function show_sub_res(inputs,outputs){
		if(!is_made){
			make_sub_board(inputs.length);
			is_made = true;
		}else{
			change_sub_board(inputs.length);
		}
		for(let i=0; i<inputs.length; i++){
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
					result = JSON.parse(data);
					const t = document.getElementById('case_time_'+case_num);
					const c = document.getElementById('case_'+case_num);
					if(outputs[i] == result.result){
						c.innerHTML = '<span style="color: blue; ">O</span>';
					}else{
						c.innerHTML = '<span style="color:red;">X</span>';
					}
					t.innerHTML = result.runtime;					
				}
			});
		}
	}
		
	categoryChange();
</script>	
</html>
