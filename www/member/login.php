<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";


     $url = $_REQUEST[url] == '' ? LOGIN_AFTER_PAGE : $_REQUEST[url];
     $param = $_REQUEST[param];
?>
<?	
	$p = "member";
	$sp = 0;
	$spc= 0;
	$root = $_SERVER['DOCUMENT_ROOT'];
	include_once $root."/header.php";
?>
<SCRIPT type="text/javascript">
$(document).ready(function(e){

	$("#id").focus();
    
	$("#password").bind("keydown", function(e) {
		if (e.keyCode == 13) { // enter key
			$("#board").submit();
			return false
		}
	});
});

function loginCheck(){

	if ( getObject("id").value.length < 1 ) {
		alert("아이디를 입력해주세요.");
		getObject("id").focus();
		return false;
	}

	if ( getObject("password").value.length < 1 ) {
		alert("비밀번호를 입력해주세요.");
		getObject("password").focus();
		return false;
	}

	var f = document.board;
	if (f.reg.checked==true) {
	   document.cookie = "cookie_userid=" + f.id.value + ";path=/;expires=Sat, 31 Dec 2050 23:59:59 GMT;";
	} else {
	   var now = new Date();	
	   document.cookie = "cookie_userid=null;path=/;expires="+now.getTime();
	}

	return true;
}

function userid_chk() {
	var f=document.board;
	var useridname = CookieVal("cookie_userid");
	console.log("useridname"+useridname);
	if (useridname=="null"){	
		f.id.focus();
		f.id.value="";
	} else {
		f.password.focus();
		f.id.value=useridname;
		f.reg.checked=true;
	}
}

function CookieVal(cookieName) {
	thisCookie = document.cookie.split("; ");
	for (var i = 0; i < thisCookie.length;i++) {
		if (cookieName == thisCookie[i].split("=")[0]) {
			return thisCookie[i].split("=")[1];
		}
	}
	return "null" ;
}

$(function(){
	userid_chk();
});
</SCRIPT>
<div id="sub" class="member_login">
	<div class="con_wrap">
		<div class="size">
			<!-- 여기서부터 게시판--->
			<div class="bbs">
								 <form name="board" id="board" method="post" action="/include/login.php" onsubmit="return loginCheck();">
											<fieldset class="login_form mb">
													 <input type="hidden" name="url" id="url" value="<?=$url?>"/>
													 <input type="hidden" name="param" id="param" value="<?=$param?>"/>
													 <div class="login_txt">
																<h3>로그인</h3>
																<p>공간 대관, 프로그램 신청을 위해서는 회원 로그인이 필요합니다.
																</p>
													 </div>
													 <div class="login_box ">
													 	<div class="w_box">
															<p class="id">
																<input type="text" name="id" id="id" placeholder="아이디를 입력해주세요"/>
															</p>
															<p class="password">
																<input type="password" name="password" id="password" placeholder="비밀번호를 입력해주세요"/>
															</p>
														</div>
														<div class="rnd_btns mt30">
															<div class="ip ful">
																<input type="submit" value="Login" class=""/>
															</div>
														 </div>
														 <div class="login_util clear mt20">
																<div class="sm_tit">
																	<a href="write.php" ><b><em>회원가입</em></b></a>
																</div>
																<div>
																	 <a href="idpwsearch.php?init=id">ID/PASSWORD 찾기</a>
																</div>
														 </div>
													 </div>
													 
											</fieldset>
								 </form>
						</div>
			<!-- //여기까지 게시판--->
		</div>
	</div>
	<!-- //size--->
</div>
<!-- //sub--->
<?
	include_once $root."/footer.php";
?>
