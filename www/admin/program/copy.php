<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/GalleryCt.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$notice = new GalleryCt($pageRows, $tablename, $category_tablename, $_REQUEST);

$data = $notice->getData($_REQUEST[no], $userCon);
$cresult = $notice->getCategoryList($_REQUEST);
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<script>
var oEditors, oEditors2; // 에디터 객체 담을 곳
jQuery(window).load(function(){
	oEditors = setEditor("contents"); // 에디터 셋팅
	oEditors2 = setEditor("contents2"); // 에디터 셋팅
	
	// 달력
	initCal({id:"sday",type:"day",today:"y"});
		initCal({id:"eday",type:"day",today:"y"});
		initCal({id:"stday",type:"day",today:"y"});
		initCal({id:"etday",type:"day",today:"y"});
});
	
	function goSave() {
		if($('#stday').val() == "" || $('#etday').val() == ""){
			alert('진행기간을 설정해 주세요.');
			return false;
		}

		if($('#sday').val() == "" || $('#eday').val() == ""){
			alert('모집기간을 설정해 주세요.');
			return false;
		}

		if($('#category').val() == ""){
			alert('카테고리를 선택해 주세요.');
			$('#category').focus();
			return false;
		}
		if ($("#title").val() == "") {
			alert('프로그램 제목을 입력해 주세요.');
			$("#title").focus();
			return false;
		}

		if( $('#rtime').val() == "" ){
			alert('진행 시간을 설정해 주세요.');
			$('#rtime').focus();
			return false;
		}
		if ($("#title").val() == "") {
			alert('프로그램 제목을 입력해 주세요.');
			$("#title").focus();
			return false;
		}
	
		var sHTML = oEditors.getById["contents"].getIR();
		if (sHTML == "") {
			alert('내용을 입력해 주세요.');
			$("#contents").focus();
			return false;
		} else {
			oEditors.getById["contents"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.
		}

		var sHTML2 = oEditors2.getById["contents2"].getIR();
		oEditors2.getById["contents2"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.
		/*
		if (sHTML2 == "") {
			alert('내용을 입력해 주세요.');
			$("#contents2").focus();
			return false;
		} else {
			oEditors2.getById["contents2"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.
		}
		*/
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
				<table>
					<colgroup>
						<col width="8%">
						<col width="42%">
						<col width="8%"/>
						<col width="42%"/>
					</colgroup>
					<tbody>

						<tr>
							<th>카테고리</th>
							<td>
								<span class="select">
									<select name="category" id="category">
										<option value="">선택</option>
										<?while($row = mysql_fetch_assoc($cresult)){?>
										<option value="<?=$row['no']?>" <?=getSelected($row['no'], $data['category'])?>><?=$row['title']?></option>
										<?}?>
									</select>
								</span>
							</td>
							<th>진행 일시</th>
							<td>
								<p class="calendar">
									<input type="text" name="stday" id="stday"  value="<?=$data['stday']?>" class="dateTime" readonly autocomplete="off"/>
									<span id="CalstdayIcon" style="height:22px; line-height:22px;vertical-align:middle;">
										<span class="material-icons">calendar_month</span>
										<!--<img src="/admin/img/ico_calendar.gif" id="CalregistdateIconImg" style="cursor:pointer;"/>-->
									</span>
								</p>
								<!--~
								<p class="calendar">
									<input type="text" name="etday" id="etday"  value="<?=$data['etday']?>" class="dateTime" readonly autocomplete="off"/>
									<span id="CaletdayIcon" style="height:22px; line-height:22px;vertical-align:middle;">
										<span class="material-icons">calendar_month</span>
										<!--<img src="/admin/img/ico_calendar.gif" id="CalregistdateIconImg" style="cursor:pointer;"/>-->
									</span>
								</p>
							</td>
						</tr>
						<tr>
							<th>노출설정</th>
							<td colspan="3">
								<input type="radio" name="display" value="1" id="d1" <?=getChecked(1, $data['display'])?>><label for="d1">노출</label>
								<input type="radio" name="display" value="2" id="d2" <?=getChecked(2, $data['display'])?>><label for="d2">미노출</label>
								<input type="checkbox" name="top" value="1" <?=getChecked(1, $data['top'])?>/> 탑공지 (상단노출) 
							</td>
						</tr>

					</tbody>
				</table>
			</div>
			<div class="wr_box">
				<h3>공연정보</h3>
				<table>
					<colgroup>
						<col width="8%">
						<col width="*">
					</colgroup>
					<tbody>
					<tr>
						<th>프로그램 제목</th>
						<td>
							<input type="text" name="title" id="title"  value="<?=$data['title']?>" />
						</td>
					</tr>
					<tr>
						<th>장소</th>
						<td><input type="text" name="place" id="place" value="<?=$data['place']?>"/></td>
					</tr>
					<tr>
						<th>출연(강사)</th>
						<td><input type="text" name="teacher" id="teacher" value="<?=$data['teacher']?>"></td>
					</tr>
					<tr>
						<th>장르</th>
						<td><input type="text" name="genre" id="genre" value="<?=$data['genre']?>"/></td>
					</tr>
					<tr>
						<th>참여가는 연령</th>
						<td><input type="text" name="age" id="age" value="<?=$data['age']?>" class="wid200"/></td>
					</tr>
					<tr>
						<th>모집 기간</th>
						<td>
							<p class="calendar">
								<input type="text" name="sday" id="sday"  value="<?=$data['sday']?>" class="dateTime" readonly autocomplete="off"/>
								<span id="CalsdayIcon" style="height:22px; line-height:22px;vertical-align:middle;">
									<span class="material-icons">calendar_month</span>
									<!--<img src="/admin/img/ico_calendar.gif" id="CalregistdateIconImg" style="cursor:pointer;"/>-->
								</span>
							</p>
							~
							<p class="calendar">
								<input type="text" name="eday" id="eday"  value="<?=$data['eday']?>" class="dateTime" readonly autocomplete="off"/>
								<span id="CaledayIcon" style="height:22px; line-height:22px;vertical-align:middle;">
									<span class="material-icons">calendar_month</span>
									<!--<img src="/admin/img/ico_calendar.gif" id="CalregistdateIconImg" style="cursor:pointer;"/>-->
								</span>
							</p>
						</td>
					</tr>
					<tr>
						<th>시간 </th>
						<td id="timeTable">
							<input type="time" value="<?=$data['rtime']?>" id="rtime" name="rtime" autocomplete="off"/>

						</td>
					</tr>
					<tr>
						<th>참여비</th>
						<td><input type="text" name="price" id="price" value="<?=$data['price']?>" onkeyup="onlyNumber(this);" class="wid200"/>원</td>
					</tr>
					<tr>
						<th>인원</th>
						<td><input type="text" name="amount" id="amount" value="<?=$data['amount']?>" onkeyup="onlyNumber(this);" class="wid200"/></td>
					</tr>
					<tr>
						<th>동반인</th>
						<td>
							<span class="select">
							<select name="together">
								<option value="">선택</option>
								<option value="3" <?=getSelected(3, $data['together'])?>>선택 불가</option>
								<option value="1" <?=getSelected(1, $data['together'])?>>1인</option>
								<option value="2" <?=getSelected(2, $data['together'])?>>2인</option>
							</select>
							</span>
						</td>
					</tr>
					<tr>
						<th>내용</th>
						<td>
							<textarea name="contents" id="contents" rows="10"><?=$data['contents']?></textarea>
						</td>
					</tr>
					<tr>
						<th>유의사항</th>
						<td>
							<textarea name="contents2" id="contents2" rows="10"><?=$data['contents2']?></textarea>
						</td>
					</tr>
					
					
					<tr>
						<th>이미지</th>
						<td>
							<? if ($data['imagename']) { ?>
								<input type="checkbox" name="imagename_chk" value="1"/> 기존파일삭제</br/>
								<a href="/lib/download.php?path=<?=$uploadPath?>&vf=<?=$data['imagename']?>&af=<?=$data['imagename_org']?>" target="_blank"><?=$data[imagename_org]?></a><br/>
								<input type="hidden" id="imagename" name="imagename" title="이미지를 업로드 해주세요." value="<?=$data['imagename']?>"/>
								<input type="hidden" name="imagename_org" value="<?=$data['imagename_org']?>"/>
							<? } ?>
							<br/>
							신규이미지 등록
							<input type="file" id="imagename_new" name="imagename_new" title="이미지를 업로드 해주세요." value=""/>	
						</td>
					</tr>

					<tr>
						<th>관련링크</th>
						<td><input type="text" name="relation_url" id="relation_url" value="<?=$data['relation_url']?>"/></td>
					</tr>
					</tbody>
				</table>
			</div>
			<!-- //wr_box -->
		<input type="hidden" name="cmd" value="copy" />
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
