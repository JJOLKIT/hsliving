<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Sns.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$notice = new Sns($pageRows, $tablename, $_REQUEST);
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<script>
	jQuery(window).load(function(){
		
		// 달력
		initCal({id:"registdate",type:"day",today:"y",timeYN:"y"});
	});
	
	function goSave() {
		var regex=/^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/i;

		if ($("#registdate").val() != "") {
			var regex2=/[0-9]{4}[\-][0-1][0-9][\-][0-3][0-9]\s[0-2][0-9]:[0-6][0-9]:[0-6][0-9]$/i; 
			if(!regex2.test($("#registdate").val())){
				alert('잘못된 날짜 형식입니다.\\n올바로 입력해 주세요.\\n ex)2013-02-14 03:28:85');
				$("#registdate").focus();
				return false;
			} 
		}
		if ($("#title").val() == "") {
			alert('제목을 입력해 주세요.');
			$("#title").focus();
			return false;
		}
		if ($("#imagename").val() == "") {
			alert('썸네일 이미지를 등록해 주세요.');
			$("#imagename").focus();
			return false;
		}
		if ($("#relation_url").val() == "") {
			alert('링크를 입력해 주세요.');
			$("#relation_url").focus();
			return false;
		}

		


		$('#frm').submit();
	}
	
	
</script>
<?
/*
<?include_once $_SERVER['DOCUMENT_ROOT']."/summernote/note.html";?>
<script>

	$(document).ready(function() {
		$('#contents').summernote(editorOptions);
	});
</script>
*/
?>
</head>


<body>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>
<!-- s:warp -->
	<div class="contWrap">
		<div class="titWrap">
			<h2><?=$pageTitle ?> 글쓰기</h2>
		</div>
		<div class="write">
			<form method="post" name="frm" id="frm" action="<?=getSslCheckUrl($_SERVER['REQUEST_URI'], 'process.php')?>" enctype="multipart/form-data">
				<div class="wr_box">
					<h3>등록정보</h3>
					<table class="row_line">
						<colgroup>
							<col width="8%">
							<col width="*">
						</colgroup>
						<tbody>
						<tr>
							<th>작성일</th>
							<td>
								<p class="calendar">
									<input type="text" name="registdate" id="registdate"  value="" class="dateTime" />
									<span id="CalregistdateIcon" style="height:22px; line-height:22px;vertical-align:middle;">
										<span class="material-icons">calendar_month</span>
										<!--<img src="/admin/img/ico_calendar.gif" id="CalregistdateIconImg" style="cursor:pointer;"/>-->
									</span>
								</p>
							</td>
						</tr>
						<tr>
							<th>노출설정</th>
							<td>
								<input type="checkbox" name="top" value="1" /> 탑공지 (상단노출)
							</td>
						</tr>
						</tbody>
					</table>
				</div>
				<!-- //wr_box -->
				<div class="wr_box">
					<h3>게시글</h3>
					<table>
						<colgroup>
							<col width="8%">
							<col width="*">
						</colgroup>
						<tbody>
						<tr>
							<th>제목</th>
							<td>
								<input type="text" name="title" id="title"  value="" />
							</td>
						</tr>
						
						<tr>
							<th>썸네일 이미지</th>
							<td>
								이미지 사이즈 : 360 * 230 px
								<input  type="file" name="imagename" id="imagename" />
							</td>
						</tr>
						<tr>
							<th>링크</th>
							<td>
								<input type="text" name="relation_url" id="relation_url"  value="" />
							</td>
						</tr>
						</tbody>
					</table>
				</div>
				<!-- //wr_box -->
				<input type="hidden" name="cmd" value="write" />
				<?=$notice->getQueryStringToHidden($_REQUEST) ?>
			</form>
		</div>
		<!-- //write -->
		<div class="btnSet">
			<a href="javascript:;" class="btn hoverbg save" onclick="goSave();">저장</a>
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
