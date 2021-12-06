<?php
	$conn = mysqli_connect("localhost", "root", "123456", "web_proj");
	$conn -> query('set session character_set_connection=utf8');
	$conn -> query('set session character_set_results=utf8');
	$conn -> query('set session character_set_client=utf8');

	$id = $_COOKIE['id'];
	$number = $_GET['number'];

	$sql = "select title from problem_info where id=$number";
	$main = mysqli_query($conn, $sql);
	$main = mysqli_fetch_row($main)[0];
	
	
?>	
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CoCo</title>
	<link href="../css/problem_table.css" rel="stylesheet" type="text/css" />
    <script src="//code.jquery.com/jquery.min.js"></script>
</head>
<body>
	<div class="header">
		<a class = "main_top" href = "/">
			<span style="color:gray;">Co</span><span class="title_right">ding</span>.<span style="color:gray;">Co</span><span class="title_right">mpiler</span>
			</a>
		<div class="top_right">
				<?php
				
					$id = $_COOKIE['id'];
					if($_COOKIE['token'] == null){				
						echo "<a href = '/login.php' class='top_right_label'>로그인</a><a href = '/register.php' class='top_right_label'>회원가입</a>";
					}else{
						echo "<span class = 'id_label'>$id</span><a href='./board/index.php' onclick='logout()' class='top_right_label'>로그아웃</a>";		
					}
				?>
			</div>
	</div>
	<div class="main">
		<div class="pro_button_block">
			<a href="/problem_page" class="pro_button">문제 목록</a>
		</div>
	
	<div id="board_area">
    
      <h1>Q&A for no.<?php echo $number; ?></h1>
      <h4><?php echo $main; ?> 문제에 대해 질문하고 답하는 게시판입니다.</h4>
      <div id="search_box">
        <form action="search_result.php" method="get">
          <select name="catgo">
            <option value="title">제목</option>
            <option value="name">글쓴이</option>
            <option value="content">내용</option>
          </select>
          <input type="text" name="search" size="40" required="required" /> <button>검색</button>
        </form>
      </div>
      
 
        <table class="list-table">
          <thead>
              <tr>
                  <th width="70">번호</th>
                    <th width="500">제목</th>
                    <th width="120">글쓴이</th>
				  	<th width="100">작성일</th>
                    <th width="100">조회수</th>
                </tr>
            </thead>
            <?php
             
              $sql = "select * from board where pro_id=$number";
              $result = mysqli_query($conn,$sql);

          
					
				if(mysqli_num_rows($result)>0){
					while($row = mysqli_fetch_assoc($result)){
						$idx = $row["idx"];
						echo ("<tr class=\"color\"><td>" . $row["idx"] . "</td><td>
						<a href=\"/board/read.php?id=" . $id . "&idx=" . $row["idx"] . "&number=" . $number . "\">" . $row["title"] . "</a></td><td>"
							  . $row["id"] . "</td><td>" . $row["date"] . "</td><td>" . $row["hit"] . "</td></tr>");
						
						 $title=$row["title"];
                  if(strlen($title)>30)
                  {
                    //title이 30을 넘어서면 ...표시
                    $title=str_replace($row["title"],mb_substr($row["title"],0,30,"utf-8")."...",$row["title"]);
                  }
					}
				}
			
				?>
                
		</table>
		</div>
	<div id="write_btn">
                <?php echo "<a href=\"/board/write.php?id=$id&number=$number" . "\"><button>글쓰기</button></a>"?>
              </div>
      </div>
    </body>
	<script>
	function logout(){
		let date = new Date();
		date.setDate(date.getDate() - 100);
		let Cookie = `token=;Expires=${date.toUTCString()}`+'domain=prog-coco.run.goorm.io;path=/;'
		document.cookie = Cookie;
		Cookie = `id=;Expires=${date.toUTCString()}`+'domain=prog-coco.run.goorm.io;path=/;'
		document.cookie = Cookie;
	}</script>
</html>