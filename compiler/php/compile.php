<?php
	$file_name = "newfile";
    $language = $_GET["language"];
    $value = $_GET["value"];
	$input = $_GET["input"];
	if($_COOKIE['token'] == null){
		session_start();
		$id = session_id();
		$file_link = "../files/others";
	}else{
		$id = $_COOKIE['token'];
		$file_link = "../files";
	}
	$user_file_link = "$file_link/$id";
	if(!is_dir($user_file_link)){
		system("mkdir $user_file_link");
	}
	$cat = "cat ./$user_file_link/input.txt |";
	$input_file = fopen("$user_file_link/input.txt","w") or die("Unable to open file!");
	fwrite($input_file,$input);
	fclose($input_file);
	$end=0;
	$start=0;
	
    $test_file = fopen("$user_file_link/$file_name.$language","w") or die("Unable to open file!");
    fwrite($test_file,$value);
    fclose($test_file);
	$results = array();
    switch($language){
        case "java":
			$error = shell_exec("cd $user_file_link; javac $file_name.java 2>&1");
			if($error){
				$result = $error;
			}else{
				$start = microtime(true);
				$result = shell_exec("cd $user_file_link;$cat java $file_name 2>&1");
				$end = microtime(true);
			}
            break;
        case "js":
			$start = microtime(true);
			$result = shell_exec("$cat node $user_file_link/$file_name.js 2>&1");
			$end = microtime(true);
            break;
        case "php":
			$start = microtime(true);
			$result = shell_exec("$cat php $user_file_link/$file_name.php 2>&1");
			$end = microtime(true);
            break;
		case "py":
			$start = microtime(true);
			$result = shell_exec("$cat python3 $user_file_link/$file_name.py 2>&1");
			$end = microtime(true);
			break;
		default:
			$compile_type;
			if($language == "c") $compile_type = "gcc";
			else $compile_type = "g++";
			$error = shell_exec("$compile_type $user_file_link/$file_name.$language -o $user_file_link/$file_name 2>&1");
			if($error){
				$result = $error;
			}else{
				$start = microtime(true);
				$result = shell_exec("$cat./$user_file_link/$file_name 2>&1");	
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