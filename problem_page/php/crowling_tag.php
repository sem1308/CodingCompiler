<?php
    ini_set('max_execution_time', '600'); // 60 seconds = 1 minutes
    set_time_limit(600);

    function getTags() {
        $url= "https://www.acmicpc.net/problem/tags";//Load the HTML page
        $html= file_get_contents($url);
        //Create a new DOM document
        $dom= new DOMDocument("1.0", "UTF-8");
        @$dom->loadHTML("<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">".$html);
        $finder= new DomXPath($dom);
        // https://stackoverflow.com/a/20788609/7105963
        $problem_ids= $finder->query("//td/a"); 
        $problem_ids= iterator_to_array($problem_ids);
		$returnValue = array();
		foreach($problem_ids as $eachItem) {
            array_push($returnValue, $eachItem->nodeValue);
        }
        return $returnValue;
    }

	$tags = getTags();
	print_r($tags);

	// Initialize Database
    $connect= new mysqli("localhost", "root", "123456", "web_proj");
	$connect -> query('set session character_set_connection=utf8');
	$connect -> query('set session character_set_results=utf8');
	$connect -> query('set session character_set_client=utf8');
    if($connect-> connect_errno) {
        die("Cannot connect! ". $connect-> connect_error);
    }
    // For init
    if(!($results=$connect->query("TRUNCATE problem_tag"))) {
        echo"Failed to TRUNCATE TABLE :: problem_tag<br>";
	}
	if(!($results=$connect->query("TRUNCATE pro_tag"))) {
        echo"Failed to TRUNCATE TABLE :: pro_tag<br>";
	}

	for($i=0; $i<count($tags); $i=$i+2){
		$id = (int)($i/2)+1;
		$insert_query= "INSERT INTO problem_tag(tag_id, tag) VALUES($id,'$tags[$i]')";
		if($connect->query($insert_query) === TRUE) {
			// echo "New record created successfully";
		}else{
			echo"Error: ". $insert_query. "<br>". $connect->error;
		}
	}
	
	$max = count($tags)/2;
	for($i=1; $i<100; $i++){
		for($j=0; $j<3; $j++){
			$tag_num = rand(1,$max);
			$insert_query= "INSERT INTO pro_tag(pro_id, tag_id) VALUES($i,$tag_num)";
			if($connect->query($insert_query) === TRUE) {
			// echo "New record created successfully";
			}else{
				echo"Error: ". $insert_query. "<br>". $connect->error;
			}
		}
	}

    $connect->close();
?>