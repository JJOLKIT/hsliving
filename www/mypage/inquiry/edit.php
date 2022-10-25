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
	$data = escape_html($notice->getData($_REQUEST['no'], $userCon));
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
			frm.email.focus();
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
	
	if(frm.zsfCode.value.trim() == ""){
		alert('스팸방지코드를 입력해 주세요.');
		frm.zsfCode.focus();
		return false;
	}

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
										<div class="tb_wrap">
											<table class="write">
													 <caption>글수정</caption>
													 <colgroup>
																<col width="15%" />
																<col width="*" />
													 </colgroup>
													 <tbody>
																<tr>
																		 <th>이름 <span class="required">*<span>필수입력</span></span></th>
																		 <td><input type="text" name="name" id="name" value="<?=$data['name']?>" class="max200"/></td>
																</tr>
																<tr>
																		 <th>연락처 <span class="required">*<span>필수입력</span></span></th>
																		 <td><input type="text" name="cell" id="cell" value="<?=$data['cell']?>" maxlength="15" class="max300"/></td>
																</tr>
															 <tr>
															<th>이메일 <span class="required">*<span>필수입력</span></span></th>
															<td class="email">
																	 <input type="text" name="email1" id="email1" value="<?=explode("@", $data['email'])[0]?>" class="max200" autocomplete="off"/>
																	 <span class="at">@</span>
																	 <input type="text" name="email2" id="email2" value="<?=explode("@", $data['email'])[1]?>" class="max200" autocomplete="off"/>
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
																		 <th>제목 <span class="required">*<span>필수입력</span></span></th>
																		 <td><input type="text" name="title" id="title" value="<?=$data['title']?>"/></td>
																</tr>
																<tr class="txtarea">
																		 <th>내용 <span class="required">*<span>필수입력</span></span></th>
																		 <td><textarea name="contents" id="contents"><?=$data['contents']?></textarea></td>
																</tr>

																<tr>
																		 <th>첨부<br />파일</th>
																		 <td>
																					<?if($data['filename']){?>
																					<a href="/lib/download.php?path=<?=$uploadPath?>&vf=<?=$data['filename']?>&af=<?=urlencode($data['filename_org'])?>" target="_blank"><?=$data[filename_org]?></a>&nbsp;&nbsp;<input type="checkbox" name="filename_chk" value="1"/> 기존파일삭제</br>
																					<?}?>
																					<div class="fileBox">
																							 <div class="inputBox">
																										<input type="text" id="addFile" disabled="" value="">
																							 </div>
																							 <div class="fileBtn">
																										<label>찾아보기<input type="file" name="filename" onchange="document.getElementById('addFile').value = this.files[0].name;"></label>
																							 </div>
																					</div>
																					<p class="help">첨부파일은 5MB 이하의 문서 파일만 가능합니다.</p>
																		 </td>
																</tr>
												<tr class="pw">
													<th>비밀번호</th>
													<td colspan="3"><!-- 
														<div class="check_box">
															<p>
																<input type="checkbox" id="agr01" value="">
																<label for="agr01"><span>비밀글 설정</span></label>
															</p>
														</div> -->
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
											<input type="hidden" name="no" value="<?=$_REQUEST['no']?>"/>
											<input type="hidden" name="secret" value="1">
											<input type="hidden" name="cmd" value="edit" />
											<input type="hidden" name="member_fk" value="<?=$data['member_fk']?>" />
						<input type="hidden" name="email" value="<?=$data['email']?>" id="email"/>
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

				
