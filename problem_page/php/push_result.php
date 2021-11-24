<?php
	$id = $_POST['id'];
	$is_correct = $_POST['correct'];

	$connect= mysqli_connect("localhost", "root", "123456");
	$connect -> query('set session character_set_connection=utf8');
	$connect -> query('set session character_set_results=utf8');
	$connect -> query('set session character_set_client=utf8');
    if($connect-> connect_errno) {
        die("Cannot connect! ". $connect-> connect_error);
    }
    $db_newsinformation= mysqli_select_db($connect, 'web_proj');

	$insert_query= "SELECT ans_people,submits FROM problem_info WHERE id = $id";
	$result = $connect->query($insert_query);
	$data;
	if ($result->num_rows > 0) {
		$data = mysqli_fetch_row($result);
	} else {
		die("Database Error: " . $conn->connect_error);
	}

	$ans = $data[0]+$is_correct;
	$submit = $data[1]+1;
	$ans_rate = number_format($ans/$submit*100, 2);

	$insert_query= "UPDATE problem_info SET ans_people=$ans, submits=$submit, ans_pro=$ans_rate where id = $id";
	$result = $connect->query($insert_query);

	$connect->close();
?>