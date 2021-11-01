<?php
    $langauge = $_GET["language"];
    $value = $_GET["value"];
	$input = $_GET["input"];
	$link = "../files";
    $test_file = fopen("$link/newfile.$langauge","w") or die("Unable to open file!");
    fwrite($test_file,$value);
	$results = array();
    switch($langauge){
        case "java":
			$error = shell_exec("cd $link; javac newfile.java 2>&1");
			if($error){
				$result = $error;
			}else{
				$start = microtime(true);
				$result = shell_exec("echo $input|cd $link; java newfile 2>&1");
				$end = microtime(true);
			}
            break;
        case "js":
			$start = microtime(true);
			$result = shell_exec("echo $input|node $link/newfile.js 2>&1");
			$end = microtime(true);
            break;
        case "php":
			$start = microtime(true);
			$result = shell_exec("echo $input|php $link/newfile.php 2>&1");
			$end = microtime(true);
            break;
		case "py":
			$result = shell_exec("echo $input|python3 $link/newfile.py 2>&1");
			break;
		default:
			$compile_type;
			if($langauge == "c") $compile_type = "gcc";
			else $compile_type = "g++";
			$error = shell_exec("$compile_type $link/newfile.$langauge -o $link/newfile 2>&1");
			if($error){
				$result = $error;
			}else{
				$start = microtime(true);
				$result = shell_exec("echo $input|./$link/newfile");	
				$end = microtime(true);
			}
			break;
    }
	if(!$result){		
		$result = "";
	}
	$results["result"] = $result;
	$results["runtime"] = number_format($end-$start,3);
	system("cd $link; rm *");
	echo json_encode($results);	
    fclose($test_file);
?>