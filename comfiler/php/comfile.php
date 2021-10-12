<?php
    $langauge = $_GET["language"];
    $value = $_GET["value"];
	$link = "../files";
    $test_file = fopen("$link/newfile.$langauge","w") or die("Unable to open file!");
    fwrite($test_file,$value);
    switch($langauge){
        case "java":
			system("cd $link; javac newfile.java");
			$result = shell_exec("cd $link; java newfile");
            break;
        case "js":
			$result = shell_exec("node $link/newfile.js");
            break;
        case "php":
			$result = shell_exec("php $link/newfile.php");
            break;
		case "py":
			system("chmod u+x $link/newfile.py ");
			$result = shell_exec("python3 $link/newfile.py");
			break;
		default:
			$comfile_type;
			if($langauge == "c") $comfile_type = "gcc";
			else $comfile_type = "g++";
			system("$comfile_type $link/newfile.$langauge -o $link/newfile");
			$result = shell_exec("./$link/newfile");
			break;
    }
    echo $result;
    fclose($test_file);
?>