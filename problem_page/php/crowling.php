<?php
    ini_set('max_execution_time', '600'); // 60 seconds = 1 minutes
    set_time_limit(600);

    function getNums() {
        $url= "https://www.acmicpc.net/problemset?user=sem1308&user_solved=1&page=1";//Load the HTML page
        $html= file_get_contents($url);
        //Create a new DOM document
        $dom= new DOMDocument("1.0", "UTF-8");
        @$dom->loadHTML($html);
        $finder= new DomXPath($dom);
        // https://stackoverflow.com/a/20788609/7105963
        $classname_problem_id="list_problem_id";

        $problem_ids= $finder->query("//*[contains(@class, '$classname_problem_id')]"); 
        $problem_ids= iterator_to_array($problem_ids);
		$returnValue = array();
		foreach($problem_ids as $eachItem) {
            array_push($returnValue, $eachItem->nodeValue);
        }
        return $returnValue;
    }

	function getProInf($num) {
        $url= "https://www.acmicpc.net/problem/$num";//Load the HTML page
        $html= file_get_contents($url);
        //Create a new DOM document
        $dom= new DOMDocument("1.0", "UTF-8");
        @$dom->loadHTML("<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">".$html);
        $finder= new DomXPath($dom);
        // https://stackoverflow.com/a/20788609/7105963
        $classname_problem_info="table";
		$classname_problem_title="problem_title";
		$classname_problem_desc="problem_description";
		$classname_problem_input="problem_input";
		$classname_problem_ex="sampledata";
		
        $problem_ids= $finder->query("//*[contains(@class, '$classname_problem_info')]/tbody/tr/td"); 
		$problem_title= $finder->query("//*[contains(@id, '$classname_problem_title')]"); 
		$problem_desc= $finder->query("//*[contains(@id, '$classname_problem_desc')]"); 
		$problem_input= $finder->query("//*[contains(@id, '$classname_problem_input')]"); 
		$problem_ex= $finder->query("//*[contains(@class, '$classname_problem_ex')]"); 
		
        $problem_ids= iterator_to_array($problem_ids);
		$problem_title= iterator_to_array($problem_title);
		$problem_desc= iterator_to_array($problem_desc);
		$problem_input= iterator_to_array($problem_input);
		$problem_ex= iterator_to_array($problem_ex);
		
		$returnValue = array();
		$returnEx = array();
		array_push($returnValue,$problem_title[0]->nodeValue);
		foreach($problem_ids as $eachItem) {
            array_push($returnValue, $eachItem->nodeValue);
        }
		array_push($returnValue,$problem_desc[0]->nodeValue);
		array_push($returnValue,$problem_input[0]->nodeValue);
		foreach($problem_ex as $eachItem) {
            array_push($returnEx, $eachItem->nodeValue);
        }
        $returnValue['example']=$returnEx;
        return $returnValue;
    }

	$numbers = getNums();
	//$result = getProInf(1476);
	//$example = $result['example'];

	// Initialize Database
    $connect= mysqli_connect("localhost", "root", "");
	$connect -> query('set session character_set_connection=utf8');
	$connect -> query('set session character_set_results=utf8');
	$connect -> query('set session character_set_client=utf8');
    if($connect-> connect_errno) {
        die("Cannot connect! ". $connect-> connect_error);
    }
    $db_newsinformation= mysqli_select_db($connect, 'web');
    // For init
    if(!($results=$connect->query("TRUNCATE problem_info"))) {
        echo"Failed to TRUNCATE TABLE :: problem_info<br>";
	}
	if(!($results=$connect->query("TRUNCATE problem_example"))) {
        echo"Failed to TRUNCATE TABLE :: problem_example<br>";
	}
	if(!($results=$connect->query("TRUNCATE pro_example"))) {
        echo"Failed to TRUNCATE TABLE :: pro_example<br>";
	}
	if(!($results=$connect->query("TRUNCATE problem_detail"))) {
        echo"Failed to TRUNCATE TABLE :: pro_example<br>";
	}
	
	$id=1;
	$ex_id=1;
	foreach($numbers as $num){
		$result = getProInf($num);
		$example = $result['example'];
		$ratio = rtrim($result[6],'%');
		$time = $result[1][0];
		$insert_query= "INSERT INTO problem_info(id, title, ans_people, submits, ans_pro, time_restrict) VALUES($id,'$result[0]','$result[4]','$result[3]',$ratio,$time)";
		if($connect->query($insert_query) === TRUE) {
			// echo "New record created successfully";
		}else{
			echo"Error: ". $insert_query. "<br>". $connect->error;
		}
		$content = str_replace('"','\"',$result[7]);	
		$restrict = str_replace('"','\"',$result[8]);
		$insert_query= "INSERT INTO problem_detail(contents,restricts) VALUES(\"$content\",\"$restrict\")";
		if($connect->query($insert_query) === TRUE) {
			// echo "New record created successfully";
		}else{
			echo"Error: ". $insert_query. "<br>". $connect->error;
		}
		$idx=0;
		for($i=0; $i<count($example)/2; $i++){	
			$e_1 = $example[$idx++];	
			$e_2 = $example[$idx++];
			$insert_query= "INSERT INTO problem_example(ex_id, input, output) VALUES($ex_id, '$e_1', '$e_2')";
			if($connect->query($insert_query) === TRUE) {
				// echo "New record created successfully";
			}else{
				echo"Error: ". $insert_query. "<br>". $connect->error;
			}
			$insert_query= "INSERT INTO pro_example(pro_id, ex_id) VALUES($id,$ex_id)";
			if($connect->query($insert_query) === TRUE) {
				// echo "New record created successfully";
			}else{
				echo"Error: ". $insert_query. "<br>". $connect->error;
			}
			$ex_id++;
		}
		$id++;
	}

    $connect->close();
?>