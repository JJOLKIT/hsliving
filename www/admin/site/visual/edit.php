<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Visual.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$notice = new Visual($pageRows, $tablename, $_REQUEST);
$data = escape_html($notice->getData($_REQUEST[no], $userCon));
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<script>
	jQuery(window).load(function(){
		
		// 달력
		initCal({id:"registdate",type:"day",today:"y",timeYN:"y"});
		initCal({id:"stday",type:"day",today:"y"});
		initCal({id:"etday",type:"day",today:"y"});
	});
	
	function goSave() {
		var regex=/^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/i;
		if ($("#name").val() == "") {
			alert('작성자를 입력해 주세요.');
			$("#name").focus();
			return false;
		}
		if ($("#registdate").val() != "") {
			var regex2=/[0-9]{4}[\-][0-1][0-9][\-][0-3][0-9]\s[0-2][0-9]:[0-6][0-9]:[0-6][0-9]$/i; 
			if(!regex2.test($("#registdate").val())){
				alert('잘못된 날짜 형식입니다.\\n올바로 입력해 주세요.\\n ex)2013-02-14 03:28:85');
				$("#registdate").focus();
				return false;
			} 
		}
		if ($("#stday").val() != "") {
			var regex2=/[0-9]{4}[\-][0-1][0-9][\-][0-3][0-9]$/i; 
			if(!regex2.test($("#stday").val())){
				alert('잘못된 날짜 형식입니다.\\n올바로 입력해 주세요.\\n ex)2013-02-14 03:28:85');
				$("#stday").focus();
				return false;
			} 
		}
		if ($("#etday").val() != "") {
			var regex2=/[0-9]{4}[\-][0-1][0-9][\-][0-3][0-9]$/i; 
			if(!regex2.test($("#etday").val())){
				alert('잘못된 날짜 형식입니다.\\n올바로 입력해 주세요.\\n ex)2013-02-14 03:28:85');
				$("#etday").focus();
				return false;
			} 
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
			<h2><?=$pageTitle ?> 수정</h2>
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
							<p class="calendar">
								<input type="text" name="registdate" id="registdate"  value="<?=$data['registdate'] ?>" class="dateTime" />
								<span id="CalregistdateIcon" style="height:22px; line-height:22px;vertical-align:middle;">
									<span class="material-icons">calendar_month</span>
									<!--<img src="/admin/img/ico_calendar.gif" id="CalregistdateIconImg" style="cursor:pointer;"/>-->
								</span>
							</p>
						</td>
						<th>작성자</th>
						<td>
							<input type="text" name="name" id="name"  value="<?=$data['name'] ?>" class="wid200"/>
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
						<th>노출여부</th>
						<td>
							<input type="radio" name="main" id="main1" value="1" <?=getChecked(1, $data['main'])?>/>
							<label for="main1">노출</label>&emsp;
							<input type="radio" name="main" id="main2" value="0" <?=getChecked(0, $data['main'])?>/>
							<label for="main2">미노출</label>
						</td>
					</tr>
					<tr>
						<th>서브 타이틀</th>
						<td>
							<input type="text" name="subtitle" id="subtitle"  value="<?=$data['subtitle'] ?>" />
						</td>
					</tr>
					<tr>
						<th>타이틀</th>
						<td>
							<input type="text" name="title" id="title"  value="<?=$data['title'] ?>" />
						</td>
					</tr>
					<tr>
							<th>신청 기간</th>
							<td>
								<p class="calendar">
									<input type="text" name="stday" id="stday" value="<?if(!$data['stday'] || $data['stday'] != '0000-00-00'){ echo $data['stday'];}?>" class="dateTime" readonly autocomplete="off"/>
									<span id="CalstdayIcon" style="height:22px; line-height:22px;vertical-align:middle;">
										<span class="material-icons">calendar_month</span>
										<!--<img src="/admin/img/ico_calendar.gif" id="CalregistdateIconImg" style="cursor:pointer;"/>-->
									</span>
								</p>
								~
								<p class="calendar">
									<input type="text" name="etday" id="etday"  value="<?if(!$data['etday'] || $data['etday'] != '0000-00-00'){ echo $data['etday'];}?>" class="dateTime" readonly autocomplete="off"/>
									<span id="CaletdayIcon" style="height:22px; line-height:22px;vertical-align:middle;">
										<span class="material-icons">calendar_month</span>
										<!--<img src="/admin/img/ico_calendar.gif" id="CalregistdateIconImg" style="cursor:pointer;"/>-->
									</span>
								</p>
							</td>
						</div>
					<tr>
						<th>PC 이미지</th>
						<td>
							<? if ($data['imagename']) { ?>
								<input type="checkbox" name="imagename_chk" value="1"/> 기존파일삭제</br/>
								<a href="/lib/download.php?path=<?=$uploadPath?>&vf=<?=$data['imagename']?>&af=<?=$data['imagename_org']?>" target="_blank"><?=$data[imagename_org]?></a><br/>
							<? } ?>
							<input  type="file" name="imagename" id="imagename" />
						</td>
					</tr>
					<tr>
						<th>모바일 이미지</th>
						<td>
							<? if ($data['filename']) { ?>
								<input type="checkbox" name="filename_chk" value="1"/> 기존파일삭제</br/>
								<a href="/lib/download.php?path=<?=$uploadPath?>&vf=<?=$data['filename']?>&af=<?=$data['filename_org']?>" target="_blank"><?=$data[filename_org]?></a><br/>
							<? } ?>
							<input  type="file" name="filename" id="filename" />
						</td>
					</tr>
					<tr>
						<th>관련링크</th>
						<td>
							<input type="text" name="relation_url" id="relation_url"  value="<?=$data['relation_url']?>" />
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			<!-- //wr_box -->
		<input type="hidden" name="cmd" value="edit" />
		<input type="hidden" name="no" value="<?=$data['no'] ?>" />
		<?=$notice->getQueryStringToHidden($_REQUEST) ?>
		</form>
		</div>
		<!-- //write -->
		<div class="btnSet clear">
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
