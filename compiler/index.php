<?php
	$root="compiler/codemirror";
	$base_root="compiler";
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CoCo</title>
	<link href="css/compiler.css" rel="stylesheet" type="text/css" />
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
<body>
	<div class="header">
		<a class = "main_top" href = "/">
			<span style="color:gray;">Co</span><span class="title_right">ding</span><span style="color:black;">.</span><span style="color:gray;">Co</span><span class="title_right">mpiler</span>
			<div class="top_right">
				<?php
					$id = $_COOKIE['id'];
					if($_COOKIE['token'] == null){				
						echo "<a href = 'login.php' class='top_right_label'>로그인</a><a href = 'register.php' class='top_right_label'>회원가입</a>";
					}else{
						echo "<span class = 'id_label'>$id</span><a href='/compiler' onclick='logout()' class='top_right_label'>로그아웃</a>";						
					}
				?>
			</div>
		</a>
	</div>
	<div class = "main">
		<div id="main_block">
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
					<textarea id = "code" autofocus>
					</textarea>
				</div>
				<div class = "return">
					<div class = "result_box">
						<div class = "label">Result</div>
						<img id="roading_img" class="init_img" src="css/imgs/roading.gif" width="20px" height="20px">
						<div id="result"></div>
					</div>
					<div class = "input_box">
						<div class = "label" style = "border-bottom: 1px solid #ddd;">Input</div>
						<textarea id = "input"></textarea>
						<button type = "button" class = "run_button" onclick="get_result()">RUN</button>
					</div>
					<div class = "result_box">
						<div class = "label">History</div>
						<div id="history"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
		  	lineNumbers: true,
			indentUnit: 4,
		  	matchBrackets: true,
			spellcheck: true,
			autocorrect: true
		});
		editor.setSize(901, 400);
		var result;
		var histories = [];
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
			const res = document.getElementById('result');
			res.innerHTML="";
			const obj = document.getElementById('roading_img');
			obj.setAttribute('class','roading_img');
			$.ajax({
                url: "<?php echo $base_root?>/php/compile.php",
                type: "GET",
                data: {
                    language: $('#language').val(),
					input: $('#input').val(),
					value: editor.getValue(),
                },
				success: function(data){
					obj.setAttribute('class','init_img');
					result = JSON.parse(data);
					show_result('result');
					histories.unshift(result);
					show_history('history');
				}
            });
		}
		function show_result(name){
			const obj = document.getElementById(name);
			obj.innerHTML = "<div>소요시간: "+result.runtime+"s</div>"+"<pre class='result_text''>"+result.result+"</pre>";
		}
		function show_history(name){
			let value = "";
			const obj = document.getElementById(name);
			histories.forEach(function(history){
				value = value+"<div>소요시간: "+history.runtime+"s</div>"+"<pre class='result_text'>"+history.result+"</pre><br><br>";
			})
			obj.innerHTML=value;
		}
		function logout(){
			let date = new Date();
			date.setDate(date.getDate() - 100);
			let Cookie = `token=;Expires=${date.toUTCString()}`
			document.cookie = Cookie;
		}
		categoryChange();
    </script>
</body>
</html>
