<?php
	$langauge = $_POST["language"];
	$link = "../default_codes";
	$code = fopen("$link/default_code.$langauge","r") or die("Unable to open file!");
	$fr = fread($code, filesize("$link/default_code.$langauge"));
	echo $fr;
	fclose($code);
?>