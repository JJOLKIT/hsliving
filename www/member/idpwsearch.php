<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<? include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php"; ?>
<?	
	$p = "";
	$sp = 0;
	$spc= 0;
	$root = $_SERVER['DOCUMENT_ROOT'];
	include_once $root."/header.php";
?>
<?
	$type = $_GET['init'];
	if($type == ""){
		$type = "id";
	}
?>

<SCRIPT type="text/javascript">

function idCheck(frm){
	
	if(frm.name.value.trim()==""){
		alert('이름을 입력해주세요');
		frm.name.focus();
		return false;
	}

	if(frm.email.value.trim()==""){
		alert('이메일을 입력해주세요');
		frm.email.focus();
		return false;
	}
	return true;

}

function pwCheck(frm){
	
	if(frm.id.value.trim()==""){
		alert('아이디를 입력해주세요');
		frm.id.focus();
		return false;
	}

	if(frm.name.value.trim()==""){
		alert('이름을 입력해주세요');
		frm.name.focus();
		return false;
	}

	if(frm.email.value.trim()==""){
		alert('이메일을 입력해주세요');
		frm.email.focus();
		return false;
	}

	return true;
}
	$(function(){
		$('.find_wrap .find_form .find_'+'<?=$type?>').siblings().css('display','none');
		$('.find_wrap .find_form .find_'+'<?=$type?>').css('display','block');

		$('.find_wrap .find_tab ul li a').on('click',function(){
			var idx = $(this).parent().index();
			$(this).parent().siblings().children().removeClass('on');
			$(this).addClass('on');
			if(idx == 0 ){
				$('.find_wrap .find_form .find_id').siblings().css('display','none');
				$('.find_wrap .find_form .find_id').css('display','block');
			}else{
				$('.find_wrap .find_form .find_pw').siblings().css('display','none');
				$('.find_wrap .find_form .find_pw').css('display','block');
			}
		});
	});
</SCRIPT>
<div id="sub" class="member_login">
	<div class="con_wrap">
		<div class="size">
			<!-- 여기서부터 게시판--->
			<div class="bbs">
				<div class="login_form mb">
					<div class="login_txt">

						<h3>ID / Password 찾기</h3>
						<p>회원가입시 입력하신 메일주소를 입력하시면 이메일로 발송해드립니다.</p>
				 </div>
			 </div>
			 <div class="find_wrap mt30">
				<div class="find_tab">
						 <ul class="clear">
									<li>
											 <a href="javascript:;" <?if($type=="id"){ echo "class='on'";}?>>아이디 찾기</a>
									</li>
									<li>
											 <a href="javascript:;" <?if($type=="pw"){ echo "class='on'"; }?>>비밀번호 찾기</a>
									</li>
						 </ul>
				</div>
				<div class="find_form">
						 <div class="find_id">
									<form name="board" id="board" method="post" action="<?=getSslCheckUrl($_SERVER['REQUEST_URI'], 'process.php')?>" onsubmit="return idCheck(this);" >
											 <fieldset class="login_form mb">
														<input type="hidden" name="cmd" value="searchid"/>
														<input type="hidden" name="init" value="id"/>
														<div class="login_box">
															<div class="w_box">
																	 <p class="name">
																				<input type="text" name="name" placeholder="이름"/>
																	 </p>
																	 <p class="email">
																				<input type="text" name="email" placeholder="이메일"/>
																	 </p>
															</div>
															<div class="rnd_btns mt30">
																<div class="ip ful">
																	<input type="submit" value="ID 찾기" class=""/>
																</div>
															</div>
															<div class="login_util clear mt20">
																<div class="sm_tit">
																	<a href="write.php" ><b><em>회원가입</em></b></a>
																</div>
															</div>
														</div>
														
											 </fieldset>
									</form>
						 </div>
						 <div class="find_pw">
									<form name="board" id="board" method="post" action="<?=getSslCheckUrl($_SERVER['REQUEST_URI'], 'process.php')?>" onsubmit="return pwCheck(this);">
											 <fieldset class="login_form mb">
														<input type="hidden" name="cmd" id="cmd" value="searchpw"/>
														<input type="hidden" name="init" value="pw"/>
														<div class="login_box">
															<div class="w_box">
																	 <p class="id">
																				<input type="text" name="id" placeholder="아이디"/>
																	 </p>
																	 <p class="name">
																				<input type="text" name="name" placeholder="이름"/>
																	 </p>
																	 <p class="email">
																				<input type="text" name="email" placeholder="이메일"/>
																	 </p>
															</div>
															<div class="rnd_btns mt30">
																<div class="ip ful">
																	<input type="submit" value="Password 찾기" class=""/>
																</div>
															</div>
															<div class="login_util clear mt20">
																<div class="sm_tit">
																	<a href="write.php" ><b><em>회원가입</em></b></a>
																</div>
															</div>
														</div>
														
											 </fieldset>
									</form>
						 </div>
				</div>
			 </div>
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
