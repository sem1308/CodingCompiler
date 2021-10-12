<?php
	$root="comfiler/codemirror";
	$base_root="comfiler";
	#$theme="xq-light";
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>ComFiler</title>
	<link href="<?php echo $base_root?>/css/comfiler.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="<?php echo $root?>/lib/codemirror.css">
	<link rel=stylesheet href="<?php echo $root?>/doc/docs.css">
	<!--<link rel=stylesheet href="<?php echo $root?>/theme/<?php echo $theme?>.css"> -->
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
	<style>
	  .CodeMirror { width:50%; height: auto; border: 1px solid #ddd; }
	  .CodeMirror-scroll { max-height: 300px;}
	  .CodeMirror pre { line-height: 1.25; }
	</style>
</head>
<body>
	<div>
		<div id="main_block">
			<div id = "code_block">
				<div>
					<select id = "language" style="display : block" onchange='categoryChange()'>
						<option value="cpp">C++</option>
						<option value="c">C</option>
						<option value="java">JAVA</option>
						<option value="js">JavaScript</option>
						<option value="py">Python</option>
						<option value="php">PHP</option>
					</select>
					<div id = "code_box">
						<textarea id = "code" rows="45" cols="73">
						</textarea>
						<input type = "button" value = "RUN" style="padding:5px; margin:0px 10px;" onclick="get_result()">
						<textarea id="result" rows="45" cols="73" readonly style="resize: none; padding: 15px; font-size: 16px; border: 1px solid #ddd;"></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
		  	lineNumbers: true,
			mode: document.getElementById('language').valaue,
		  	matchBrackets: true,
			//theme: "<?php echo $theme?>",
		});
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
                }
            }).done(function(data){
                editor.setValue(data);
				editor.setOption("mode",lan);
            });
        }
		function get_result(){
			$.ajax({
                url: "<?php echo $base_root?>/php/comfile.php",
                type: "GET",
                data: {
                    language: $('#language').val(),
					value: editor.getValue(),
                }
            }).done(function(data){
				$('#result').val(data);
            });
		}
		categoryChange();
    </script>
</body>
</html>
