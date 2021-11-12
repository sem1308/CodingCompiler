<?php
	$root="../compiler/codemirror";
	$number = $_GET['number'];

	function get_result($sql){
	}

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
				<div style="width:60%;">
					<div style="width:100%; display:inline-flex">
						<div class = "label" >Input</div>
						<div class = "label">Result</div>
					</div>
					<div style="border: 1px solid #ddd; display:inline-flex; width:100%;">
						<textarea id = "input" style="border-right: 1px solid #ddd;width:50%; height:230px; padding:15px; font-size:13px; font-family:none;"></textarea>
						<div id="result" style="width:40%; border:none; height:200px;"></div>
					</div>				
				</div>
				<div class = "middle_right">
					<button class = "run_button" onclick="get_result()">Run</button>
					<button class = "run_button" onclick="submit()">Submit</button>
				</div>
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
	var result;
	editor.setSize(901, 400);	
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
		editor.setOption("mode",lan);
	}
	function get_result(){
		const obj = document.getElementById('result');
		obj.innerHTML = "<img src=\"../css/imgs/roading.gif\" width=\"20px\" height=\"20px\">";
		$.ajax({
			url: "../compiler/php/compile.php",
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
	categoryChange();
</script>	
	
	
	
</html>
