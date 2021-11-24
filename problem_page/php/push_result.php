<?php
	$id = $_POST['id'];
	$is_correct = $_POST['correct'];

	echo $id." ",$is_correct;
	
	/*
	$connect= mysqli_connect("localhost", "root", "123456");
	$connect -> query('set session character_set_connection=utf8');
	$connect -> query('set session character_set_results=utf8');
	$connect -> query('set session character_set_client=utf8');
    if($connect-> connect_errno) {
        die("Cannot connect! ". $connect-> connect_error);
    }
    $db_newsinformation= mysqli_select_db($connect, 'web_proj');
	$connect->close();
	*/
?>