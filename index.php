<?php
 $arrs = array(
  	array("number"=>1 , "title" => "A+B", "태그"=> "수학", "제출"=>10, "정답률", "" ),
	array("number"=>1 , "title" => "A+B", "태그"=> "수학", "제출"=>10, "정답률", "" ),
 );
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>ComPiler</title>
</head>
<body>
	<table>
		<?php
			foreach($arrs as $arr){
				echo "<div>".$arr["number"]."<a href=\"compiler\">".$arr["title"]."</a></div>";
			}
		?>
	</table>
	
</body>
</html>
