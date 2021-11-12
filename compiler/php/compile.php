<?php
	session_start();
	$id = session_id();
	$file_name = "newfile";
    $langauge = $_GET["language"];
    $value = $_GET["value"];
	$input = $_GET["input"];
	$file_link = "../files";
	$user_file_link = "$file_link/$id";
	$end=0;
	$start=0;
	if(!is_dir($user_file_link)){
		system("mkdir $user_file_link");
	}
    $test_file = fopen("$user_file_link/$file_name.$langauge","w") or die("Unable to open file!");
	$input_file = fopen("$user_file_link/input.txt","w") or die("Unable to open file!");
    fwrite($test_file,$value);
	fwrite($input_file,$input);
    fclose($test_file);
	fclose($input_file);
	$results = array();
    switch($langauge){
        case "java":
			$error = shell_exec("cd $user_file_link; javac $file_name.java 2>&1");
			if($error){
				$result = $error;
			}else{
				$start = microtime(true);
				$result = shell_exec("cd $user_file_link; cat ./$user_file_link/input.txt|java $file_name 2>&1");
				$end = microtime(true);
			}
            break;
        case "js":
			$start = microtime(true);
			$result = shell_exec("cat ./$user_file_link/input.txt|node $user_file_link/$file_name.js 2>&1");
			$end = microtime(true);
            break;
        case "php":
			$start = microtime(true);
			$result = shell_exec("cat ./$user_file_link/input.txt|php $user_file_link/$file_name.php 2>&1");
			$end = microtime(true);
            break;
		case "py":
			$result = shell_exec("cat ./$user_file_link/input.txt |python3 $user_file_link/$file_name.py 2>&1");
			break;
		default:
			$compile_type;
			if($langauge == "c") $compile_type = "gcc";
			else $compile_type = "g++";
			$error = shell_exec("$compile_type $user_file_link/$file_name.$langauge -o $user_file_link/$file_name 2>&1");
			if($error){
				$result = $error;
			}else{
				$start = microtime(true);
				$result = shell_exec("cat ./$user_file_link/input.txt |./$user_file_link/$file_name 2>&1");	
				$end = microtime(true);
			}
			break;
    }
	if(!$result){		
		$result = "";
	}
	$results["result"] = $result;
	$results["runtime"] = number_format($end-$start,3);
	#system("cd $user_file_link; rm *");
	echo json_encode($results);	
?>