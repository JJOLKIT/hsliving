<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

	include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Reply2.class.php";

	include "config.php";

	$notice = new Reply2($pageRows, $tablename, $_REQUEST);
?>
<?
	$p = "mypage";
	$sp = 2;
	$spc= 0;
	$root = $_SERVER['DOCUMENT_ROOT'];
	include_once $root."/header.php";
?>
<script type="text/javascript">
var oEditors; // 에디터 객체 담을 곳
$(document).ready(function(e){
	oEditors = setEditor("contents"); // 에디터 셋팅
});

function goSave(frm) {
	<?if(!$loginCheck){?>
	if(frm.agree_2.checked == false){
		alert('개인정보 수집방침에 동의해 주세요.');
		frm.agree_2.focus();
		return false;
	}
	
	
	<?}?>
	
	if(frm.name.value.trim() == ""){
		alert('이름을 입력해 주세요.');
		frm.name.focus();
		return false;
	}
	if(frm.cell.value.trim() == ""){
		alert('연락처를 입력해 주세요.');
		frm.cell.focus();
		return false;
	}

	if(frm.email1.value.trim() == ""){
		alert('이메일을 입력해 주세요.');
		frm.email1.focus();
		return false;
	}
	if(frm.email2.value.trim() == ""){
		alert('이메일을 입력해 주세요.');
		frm.email2.focus();
		return false;
	}

	

	frm.email.value = frm.email1.value.trim() + '@' + frm.email2.value.trim();
	
	if (frm.email.value.trim() != "") {
		if(!isValidEmail(getObject("email"))) {
			alert("잘못된 이메일 형식입니다.\\n올바로 입력해 주세요.\\n ex)abcdef@naver.com");
			frm.email2.focus();
			return false;
		}
	}



	if(frm.title.value.trim() == ""){
		alert('제목을 입력해 주세요.');
		frm.title.focus();
		return false;
	}

	var sHTML = oEditors.getById["contents"].getIR();
	if (sHTML.replace("<p><br></p>","") == "") {
		alert('내용을 입력해 주세요.');
		frm.contents.focus();
		return false;
	} else {
		oEditors.getById["contents"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.
	}
	

	<?if(!$loginCheck){?>
	if(frm.password.value.trim() == ""){
		alert('비밀번호를 입력해 주세요.');
		frm.password.focus();
		return false;
	}
	<?}?>
	
	return true;
}


</script>

<div id="sub" class="list_view apply_write">
	<?include_once $root."/include/sub_visual.php";?>
	<div class="con_wrap">
		<div class="cont_top">
			<div class="size">
				<div class="t_wrap">
					<span>화성시 생활문화창작소</span>
					<b>문의</b>
				</div>
			</div>
		</div>
	<div class="has_contit nbd">
		<div class="size clear">
		<!-- 여기서부터 게시판--->
          <div class="bbs con_info">
                <form method="post" name="frm" id="frm" action="<?=getSslCheckUrl($_SERVER['REQUEST_URI'], 'process.php')?>" enctype="multipart/form-data" onsubmit="return goSave(this);">

					<?
						if(!$loginCheck){
					?>
									<div class="agree">
											<div class="agree_con"> 
													<strong>개인정보수집방침</strong>
													<? include_once $_SERVER['DOCUMENT_ROOT']."/member/policy.html"; ?>
											</div>
											<p class="check_box">
													<input type="checkbox" name="agree_2" id="agree_2"/>
													<label for="agree_2"> 개인정보수집방침에 동의합니다.</label>
											</p>
									</div>
					<?}?>
									<!-- //agree--->
					<div class="tb_wrap">
					<table class="write">
										<caption>글쓰기</caption>
										<colgroup>
												<col width="200px" />
												<col width="*" />
										</colgroup>
										<tbody>
												<tr>
														<th>작성자</th>
														<td><div class="md_ip"><input type="text" name="name" id="name" value="<?=$_SESSION['member_name']?>"/></div></td>
												</tr>
												<tr>
														<th>연락처 </th>
														<td>
														<div class="md_ip"><input type="tel"  name="cell" id="cell" value="<?=$_SESSION['member_cell']?>" maxlength="15" onkeyup="isNumberOrHyphen(this);cvtPhoneNumber(this);" placeholder="숫자만 입력해주세요."/>
														</div></td>
												</tr>
												<tr>
														<th>이메일 <span class="required">*<span>필수입력</span></span></th>
														<td class="email">
																 <input type="text" name="email1" id="email1" value="<?=explode("@", $_SESSION['member_email'])[0]?>" class="max200" autocomplete="off"/>
																 <span class="at">@</span>
																 <input type="text" name="email2" id="email2" value="<?=explode("@", $_SESSION['member_email'])[1]?>" class="max200" autocomplete="off"/>
																 <span class="select max200">
																						 <select onchange="document.getElementById('email2').value = this.value;" class="max200">
												<option value="">직접입력</option>
												<option value="naver.com">naver.com</option>
												<option value="gmail.com">gmail.com</option>
												<option value="nate.com">nate.com</option>
												<option value="daum.net">daum.net</option>
															</select>
																	 </span>
														 </td>
												</tr>
												<tr>
														<th>제목</th>
														<td><div class="fl_ip"><input type="text" name="title" id="title" /></div></td>
												</tr>
												<tr class="txtarea">
														<th>내용</th>
														<td><textarea name="contents" id="contents"></textarea></td>
												</tr>

										
												 <tr class="pw">
														<th>비밀번호</th>
														<td colspan="3">
															<div class="sm_ip">
															<input type="password" name="password" id="password" class="max200" placeholder=""/>
															</div>
															<p class="bf_p">게시글 수정 및 삭제 시 확인이 필요하니, 꼭 기억해주시기 바랍니다.</p>
														</td>
												</tr>
												
												

										</tbody>
								</table>
								</div>
									<!-- //write--->
									<div class="rnd_btns mt70 clear">
											<p class="ip"><input type="submit" value="저장"/></p> <a href="javascript:;" onclick="history.back();" class="gr"><span>취소</span></a>
									</div>
									 <input type="hidden" name="secret" value="1">
									 <input type="hidden" name="cmd" value="write" />
									 <input type="hidden" name="member_fk" value="<?=$_SESSION['member_no']?>" />
					 <input type="hidden" name="email" id="email" value="<?=$_SESSION['member_email']?>"/>
									 <?=$notice->getQueryStringToHidden($_REQUEST) ?>
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

				
