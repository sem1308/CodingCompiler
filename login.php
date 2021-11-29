<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CoCo</title>
	<link href="../css/main.css" rel="stylesheet" type="text/css" />
	<link href="../css/register.css" rel="stylesheet" type="text/css" />
    <script src="//code.jquery.com/jquery.min.js"></script>
</head>
<body>
	<div class="header">
		<a class = "main_top" href = "/">
			<span style="color:gray;">Co</span><span class="title_right">ding</span>.<span style="color:gray;">Co</span><span class="title_right">mpiler</span>
			<div class="top_right">			
				<a href = 'register.php' class='top_right_label'>회원가입</a>
			</div>
		</a>
	</div>
	<div class = "main">
		<div class = "register_block">
			<div class="register_input">
				<label>아이디</label>
				<input id='id' placeholder="아이디" type="text">			
			</div>
			<div class="register_input">
				<label>비밀번호</label>
				<input id='pw' placeholder="비밀번호" type="password">	
			</div>
			<button onclick = "login()">로그인</button>
			<div id="error" style="display:none"></div>
		</div>
	</div>
</body>
<script>
	function setCookie(cookie_name, value, days) {
	  var date = new Date();
		date.setTime(date.getTime() + days*24*60*60*1000);
		document.cookie = cookie_name + '=' + value + ';expires=' + date.toUTCString() + ';path=/';
	}
	function login(){
		$.ajax({
			url: "login_submit.php",
			type: "POST",
			data: {
				id: $('#id').val(),
				pw: $('#pw').val(),
			},
			success: function(data){
				const result = JSON.parse(data);
				console.log(result);
				if(result.success){
					setCookie('token', result.token, 1);
					setCookie('id', result.id, 1);
					location.replace(document.referrer);
				}else{
					const e = document.getElementById('error');
					e.setAttribute('style',"display:block;font-size:16px;width:35%; padding-bottom:20px;display:flex;align-items:center;justify-content:center; border-bottom:1px solid rgba(0, 100, 0,0.2)");
					e.innerText = result.error_msg;
				}
			}
		});
	}	
</script>
</html>
