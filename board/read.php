<?php
   	$conn = mysqli_connect("localhost", "root", "123456", "web_proj");
	$conn -> query('set session character_set_connection=utf8');
	$conn -> query('set session character_set_results=utf8');
	$conn -> query('set session character_set_client=utf8');
	
	if($_COOKIE['token']==null){
		$prevPage = "../login.php";
		header('location:'.$prevPage);
	}
	
	$number = $_GET['number'];
	$sql = "select title from problem_info where id=$number";
	$main = mysqli_query($conn, $sql);
	$main = mysqli_fetch_row($main)[0];

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CoCo</title>
	<link href="../css/board.css" rel="stylesheet" type="text/css" />
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
					$number = $_GET['number'];
					if($_COOKIE['token'] == null){				
						echo "<a href = '/login.php' class='top_right_label'>로그인</a><a href = '/register.php' class='top_right_label'>회원가입</a>";
					}else{
						echo "<span class = 'id_label'>$id</span><a href='./' onclick='logout()' class='top_right_label'>로그아웃</a>";		
					}
				?>
			</div>
	</div>
	<div class="main">
		<div class="pro_button_block">
			<a href="/problem_page/index.php" class="pro_button">문제 목록</a>
			<a href="/problem_page/pro_info.php?number=<?php echo $number?>" class = "pro_button">문제 정보 (<?php echo $main?>)</a>
			<a href="/problem_page/pro_submit.php?number=<?php echo $number?>" class="pro_button">컴파일 & 제출</a>
			<a href="/problem_page/pro_my_submit.php?number=<?php echo $number?>" class="pro_button">내 제출</a>
			<?php
				if($correct_user){
					echo '<a href="/problem_page/correct_answer.php?number='.$number.'" class="pro_button">정답자</a>';				
				}
			?>
			<a href="../board?number=<?php echo $number?>" class="pro_button current">Q&A</a>
		</div>
    <?php
    	
	
	
        $bno = $_GET['idx']; /* bno함수에 idx값을 받아와 넣음*/
		$sqlhit = "select * from board where idx ='$bno'";
		$resulthit = mysqli_query($conn, $sqlhit);
        $hit = mysqli_fetch_array($resulthit);
        $hit = $hit['hit'] + 1;
		
		$sqlfet = "update board set hit = \"$hit\" where idx = \"$bno\"";
		$resultfet = mysqli_query($conn, $sqlfet);
        
        $sql = "select * from board where idx=\"$bno\"";
		$result = mysqli_query($conn, $sql); /* 받아온 idx값을 선택 */
        $row = mysqli_fetch_assoc($result)
    ?>
		<div class="board_place">
			<div id="board_read">
       <h2><?php echo $row['title']; ?></h2>
           <div id="user_info" align=right>
                  <?php echo $row['id']; ?> <?php echo $row['date']; ?> 조회:<?php echo $row['hit']; ?>
                    <div id="bo_line"></div>
            </div>
            <div id="bo_content">
                <?php echo nl2br($row['content']); ?>
            </div>
 
    <!-- 목록, 수정, 삭제 -->
 
       <div>
             <ul>
                    <?php echo "<li><a href=\"board?number=$number\">[목록으로]</a></li>" ?>
              <!-- <? if ($row['id']==$_COOKIE['id']){?>
                    <li><a href="/board/modify.php?idx=<?php echo $row['idx']; ?>">[수정]</a></li>
                    <li><a href="/board/delete.php?idx=<?php echo $row['idx']; ?>">[삭제]</a></li>
              <? } ?> --> 
            </ul>
      </div>
 
  </div>
  <!--- 댓글 불러오기 -->
<div class="reply_view">
    <h3>댓글목록</h3>
        <?php
		$sqlrep = "select * from reply where con_num='$bno' order by idx desc";
		$result = mysqli_query($conn,$sqlrep);
	
    	if(mysqli_num_rows($result)>0){
					while($row = mysqli_fetch_assoc($result)){
						
        ?>
        <div class="dap_lo">
            <div><b><?php echo $row['id'];?></b></div>
            <?php echo "<div class=\"dap_to comt_edit\">" . nl2br($row['content']) . "</div>" ?>
            <div class="rep_me dap_to"><?php echo $row['date']; ?></div>
 
        </div>
    <?php }} ?>
 
    <!--- 댓글 입력 폼 -->
    <div class="dap_ins">
        <?php echo "<form action=\"/board/read_ok.php?id=$id&idx=$bno\" method=\"post\"" ?>
            <input type="hidden" name="dat_user" id="dat_user" class="dat_user" size="15" placeholder="아이디" value=<?php $_COOKIE['id']?>>
            <div style="margin-top:10px;">
                <textarea name="content" class="reply_content" id="re_content" ></textarea>
                <button id="rep_bt" class="re_bt">확인</button>
		</div>
        </form>
    </div>
</div><!--- 댓글 불러오기 끝 -->
	<div id="foot_box"></div>
		</div>
<!-- 글 불러오기 -->
		
  
</body>
</html>