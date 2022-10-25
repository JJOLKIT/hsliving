<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Reply2.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$notice = new Reply2($pageRows, $tablename, $_REQUEST);
$data = $notice->getData($_REQUEST[no], $userCon);
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<script>
$(window).load(function() {
	
	
});
	var oEditors; // 에디터 객체 담을 곳
	jQuery(window).load(function(){
		oEditors = setEditor("an_contents"); // 에디터 셋팅
		
		// 달력
		//initCal({id:"registdate",type:"day",today:"y",timeYN:"y"});
	});
	
	function goSave() {
		var regex=/^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/i;
		
		if ($("#an_title").val() == "") {
			alert('답변 제목을 입력해 주세요.');
			$("#title").focus();
			return false;
		}
		var sHTML = oEditors.getById["an_contents"].getIR();
		if (sHTML == "") {
			alert('답변 내용을 입력해 주세요.');
			$("#an_contents").focus();
			return false;
		} else {
			oEditors.getById["an_contents"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.
		}
		$('#frm').submit();
	}

</script>
</head>


<body>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>
<!-- s:warp -->
	<div class="contWrap">
		<div class="titWrap">
			<h2><?=$pageTitle?> 수정</h2>
		</div>
		<div class="write">
		<form method="post" name="frm" id="frm" action="<?=getSslCheckUrl($_SERVER['REQUEST_URI'], 'process.php')?>" enctype="multipart/form-data">
			<div class="wr_box">
				<h3>등록정보</h3>
				<table class="row_line">
					<colgroup>
						<col width="8%">
						<col width="42%">
						<col width="8%">
						<col width="42%">
					</colgroup>
					<tbody>
					<tr>
						<th>작성일</th>
						<td>
							<?=$data['registdate'] ?>
						</td>
						<th>작성자</th>
						<td <?=$memberUrl ?>>
							<?=escape_html($data['name']) ?>
						</td>
					</tr>
					<tr>
						<th>연락처</th>
						<td><?=escape_html($data['cell'])?></td>
						<th>이메일</th>
						<td><?=escape_html($data['email'])?></td>
					</tr>
					<!--
					<tr>
						<th>노출설정</th>
						<td colspan="3">
							<span class="h_line"></span> <img src="/img/ico_new.png" alt="새글" /> <?=getNewIcon($data['newicon']) ?> <span class="h_line"></span>
							<img src="/img/secret.gif" alt="비밀글" /> <?=getSecretName($data['secret']) ?>
						</td>
					</tr>
					-->
					</tbody>
				</table>
			</div>
			<!-- //wr_box -->
			<div class="wr_box">
				<h3>문의글</h3>
				<table>
					<colgroup>
						<col width="8%">
						<col width="*">
					</colgroup>
					<tbody>
					<tr>
						<th>제목</th>
						<td>
							<?=escape_html($data['title']) ?>
						</td>
					</tr>
					<tr>
						<th>내용</th>
						<td>
							<?=$data['contents'] ?>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			<!-- //wr_box -->
			<div class="wr_box">
				<h3>답변글</h3>
				<table>
					<colgroup>
						<col width="8%">
						<col width="*">
					</colgroup>
					<tbody>
					<!-- <tr>
						<th>제목</th>
						<td>
							<input type="text" name="an_title" id="an_title" value="<?=escape_html($data['an_title']) ?>" />
						</td>
					</tr> -->
					<tr>
						<th>내용</th>
						<td>
							<textarea name="an_contents" id="an_contents" rows="10"><?=$data['an_contents'] ?></textarea>
						</td>
					</tr>
					<!-- <tr>
						<th>첨부파일</th>
						<td>
							<? if ($data['filename']) { ?>
								<input type="checkbox" name="filename_chk" value="1"/> 기존파일삭제</br/>
								<a href="/lib/download.php?path=/upload/business/&vf=<?=$data['filename']?>&af=<?=$data['filename_org']?>" target="_blank"><?=$data[filename_org]?></a><br/>
							<? } ?>
							<input  type="file" name="filename" id="filename" />
						</td>
					</tr> -->
					</tbody>
				</table>
			</div>
			<!-- //wr_box -->
		<input type="hidden" name="cmd" value="edit" />
		<input type="hidden" name="no" value="<?=$data['no'] ?>" />
		<input type="hidden" name="secret" value="1"/>
		<?=$notice->getQueryStringToHidden($_REQUEST) ?>
		</form>
		</div>
		<!-- //write -->
		<div class="btnSet clear">
			<a href="javascript:;" class="btn hoverbg save" onclick="goSave();">답변 저장</a>
			<a href="<?=$notice->getQueryString('index.php', 0, $_REQUEST)?>" class="btn hoverbg">취소</a>
		</div>
		<!-- //btnSet -->
	</div>
	<!-- //contents -->
</div>
<!-- e:warp --> 
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</body>
</html>
