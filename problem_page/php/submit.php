<?php
	$number = $_POST['number'];
	$link = "../test_cases";
	$results = array();

	$inputs = array();
	if ($handle = opendir("$link/input/$number")) {
		while (false !== ($entry = readdir($handle))) {
			if ($entry != "." && $entry != "..") {
				$file = fopen("$link/input/$number/$entry","r");
				$input="";
				while (($buffer = fgets($file, 4096)) !== false) {
					$input = $input.$buffer;
				}
				fclose($file);
				array_push($inputs,$input);
			}
    	}
		closedir($handle);
	}
	
	$outputs = array();
	if ($handle = opendir("$link/output/$number")) {
		while (false !== ($entry = readdir($handle))) {
			if ($entry != "." && $entry != "..") {
				$file = fopen("$link/output/$number/$entry","r");
				$output="";
				while (($buffer = fgets($file, 4096)) !== false) {
					$output = $output.$buffer;
				}
				fclose($file);
				array_push($outputs,$output);
			}
		}
		closedir($handle);
	}

	$results['inputs'] = $inputs;
	$results['outputs'] = $outputs;
	echo json_encode($results);	
?>