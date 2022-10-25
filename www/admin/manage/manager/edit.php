<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/environment/Admin.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$admin = new Admin($pageRows, "admin", $_REQUEST);
$data = $admin->getData($_REQUEST);
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<script>
var oEditors; // 에디터 객체 담을 곳
$(window).load(function() {
	oEditors = setEditor("memo"); // 에디터 셋팅
});

function goSave() {
	if ($("#grade").val() == "0") {
		alert("등급을 선택하세요");
		$("#grade").focus();
		return false;
	}
	if ($("#name").val() == "") {
		alert("이름을 입력하세요");
		$("#name").focus();
		return false;
	}
	
	if ($("#password").val() != "") {
		// 비밀번호 유효성체크
		if(!validPassword($("#password"))) return false;
		if ($("#password").val() != $("#password2").val()) {
			alert("비밀번호를 확인해주세요");
			$("#password2").val("");
			$("#password2").focus();
			return false;
		}
	}
	
	oEditors.getById["memo"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.
	$("#frm").submit();
}

</script>
</head>


<body>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>
<!-- s:warp -->
	<div class="contWrap">
		<div class="titWrap">
			<h2><?=$pageTitle ?></h2>
		</div>
		<div class="write">
		<form name="frm" id="frm" action="<?=getSslCheckUrl($_SERVER['REQUEST_URI'], 'process.php')?>" method="post">
			<div class="wr_box">
				<h3>개인정보 (필수항목)</h3>
				<table class="row_line">
					<colgroup>
						<col width="8%">
						<col width="42%">
						<col width="8%">
						<col width="42%">
					</colgroup>
					<tbody>
					<tr>
						<th>권한등급</th>
						<td colspan="3">
							<?
								$adminResult = $admin->getGradeList($_REQUEST);
							?>
							<span class="select">
								<select name="grade" id="grade">
								<? while ($row = mysql_fetch_assoc($adminResult)) { ?>
									<option value="<?=$row['no']?>" <?=getSelected($row['no'], $data['grade'])?>><?=$row['grade_name']?></option>
								<? } ?>
								</select>
							</span>
						</td>
					</tr>
					<tr>
						<th>아이디</th>
						<td><?=$data['id'] ?></td>
						<th>이름</th>
						<td><input type="text" name="name" id="name" value="<?=$data['name'] ?>" class="wid200" /></td>
					</tr>
					<tr>
						<th>비밀번호</th>
						<td><input type="password" name="password" id="password" value=""  class="wid200"/></td>
						<th>비밀번호 확인</th>
						<td><input type="password" name="password2" id="password2" value=""  class="wid200"/></td>
					</tr>
					<tr>
						<th>이메일</th>
						<td><input type="text" name="email" id="email" value="<?=$data['email'] ?>" /></td>
						<th>휴대전화</th>
						<td><input type="text" name="tel" id="tel" value="<?=$data['tel'] ?>" class="wid200"/></td>
					</tr>
					<tr>
						<th>메모</th>
						<td colspan="3" class="inbr">
							<textarea id="memo" name="memo" title="내용을 입력해주세요" style="width:100%" ><?=$data['memo'] ?></textarea>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
		<input type="hidden" name="cmd" value="edit">
		<input type="hidden" name="ip" id="ip" value="<?=$_SERVER['REMOTE_ADDR']?>"/>
		<input type="hidden" name="stype" id="stype" value="<?=$_GET['stype']?>"/>
		<input type="hidden" name="sval" id="sval" value="<?=$_GET['sval']?>"/>
		<input type="hidden" name="sgrade" id="sgrade" value="<?=$_GET['sgrade']?>"/>
		<input type="hidden" name="reqPageNo" id="reqPageNo" value="<?=$_GET['reqPageNo']?>"/>
		<input type="hidden" name="no" id="no" value="<?=$data['no'] ?>"/>
		</form>
		</div>
		<!-- //write -->
		<div class="btnSet clear">
			<a href="javascript:;" class="btn hoverbg save" onclick="goSave();">저장</a>
			<a href="index.php" class="btn hoverbg">취소</a>
		</div>
		<!-- //btnSet -->
	</div>
	<!-- //contents -->
</div>
<!-- e:warp --> 
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</body>
</html>
