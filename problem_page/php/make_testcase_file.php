<?php
	$connect= new mysqli("localhost", "root", "123456", "web_proj");
	$connect -> query('set session character_set_connection=utf8');
	$connect -> query('set session character_set_results=utf8');
	$connect -> query('set session character_set_client=utf8');
    if($connect-> connect_errno) {
        die("Cannot connect! ". $connect-> connect_error);
    }
	function get_result($sql){
		global $connect;
		$result = $connect->query($sql);
		if ($result->num_rows > 0) {
			$return = array();
			while($row = mysqli_fetch_assoc($result)) {
			  array_push($return,$row);
			}
			return $return;
		} else {
			die("Database Error: " . $conn->connect_error);
		}
	}

	for($i=1;$i<=100;$i++){
		$sql = "SELECT * FROM pro_example WHERE pro_id = $i"; 
		$result = get_result($sql);
		$user_input_link = "../test_cases/input/$i";
		if(!is_dir($user_input_link)){
			system("mkdir $user_input_link");
		}
		$user_output_link = "../test_cases/output/$i";
		if(!is_dir($user_output_link)){
			system("mkdir $user_output_link");
		}
		$idx=1;
		foreach($result as $row){
			$ex_id = $row['ex_id'];
			$sql = "SELECT * FROM problem_example WHERE ex_id = \"$ex_id\"";
			$example = array_slice(get_result($sql)[0],1,2);
			$input = $example['input'];
			$output = $example['output'];

			$input_file = fopen("$user_input_link/$idx.txt","w") or die("Unable to open file!");
			fwrite($input_file,$input);
			fclose($input_file);
			
			$output_file = fopen("$user_output_link/$idx.txt","w") or die("Unable to open file!");
			fwrite($output_file,$output);
			fclose($output_file);
			
			$idx++;
		}
	}
	

	
	
	
	

?>