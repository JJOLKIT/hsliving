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
$cresult = $notice->getCategoryList($_REQUEST);
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<script>
$(window).load(function() {
	
	
});
	var oEditors, oEditors2; // 에디터 객체 담을 곳
	jQuery(window).load(function(){
		oEditors = setEditor("contents"); // 에디터 셋팅
		//oEditors2 = setEditor("contents2"); // 에디터 셋팅
		
		// 달력
		initCal({id:"sday",type:"day",today:"y"});
		initCal({id:"eday",type:"day",today:"y"});
		initCal({id:"stday",type:"day",today:"y"});
		//initCal({id:"etday",type:"day",today:"y"});
	});
	
	function goSave() {
		//if($('#stday').val() == "" || $('#etday').val() == ""){
		if($('#stday').val() == ""){
			alert('진행 일시를 설정해 주세요.');
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
	
		var sHTML = oEditors.getById["contents"].getIR();
		if (sHTML == "") {
			alert('내용을 입력해 주세요.');
			$("#contents").focus();
			return false;
		} else {
			oEditors.getById["contents"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.
		}
		
		/*
		var sHTML2 = oEditors2.getById["contents2"].getIR();
		oEditors2.getById["contents2"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.
		*/

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
			<h2><?=$pageTitle?> 쓰기</h2>
		</div>
		<div class="information">
			<ul style="display:inline-block;">
				<li>※ 첨부파일 및 이미지 파일은 개당 <b>10mb 이하</b>로 업로드 가능하며, <b>가로사이즈 1200px 이하, 1mb 이하</b>를 권장합니다.</li>
				<li>※ 이미지 사이즈 수정은 그림판, 포토샵 등 여러 그래픽 프로그램을 통해 수정 가능합니다.</li>
				<li>※ 이미지 용량이 너무 클 경우, 사이트 느려짐 현상과 트래픽 초과, 하드 용량 초과의 주 원인이 될 수 있습니다.</li>
				<li>※ 잘못 업로드 하신 파일로 인해 발생한 홈페이지 문제에 대해서는 유지보수 비용이 청구될 수 있습니다.</li>
			</ul>
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
										<option value="<?=$row['no']?>"><?=$row['title']?></option>
										<?}?>
									</select>
								</span>
							</td>
							<th>진행 일시</th>
							<td>
								<p class="calendar">
									<input type="text" name="stday" id="stday"  value="" class="dateTime" readonly autocomplete="off"/>
									<span id="CalstdayIcon" style="height:22px; line-height:22px;vertical-align:middle;">
										<span class="material-icons">calendar_month</span>
										<!--<img src="/admin/img/ico_calendar.gif" id="CalregistdateIconImg" style="cursor:pointer;"/>-->
									</span>
								</p>
								<?
								/*
								~
								<p class="calendar">
									<input type="text" name="etday" id="etday"  value="" class="dateTime" readonly autocomplete="off"/>
									<span id="CaletdayIcon" style="height:22px; line-height:22px;vertical-align:middle;">
										<span class="material-icons">calendar_month</span>
										<!--<img src="/admin/img/ico_calendar.gif" id="CalregistdateIconImg" style="cursor:pointer;"/>-->
									</span>
								</p>
								*/
								?>
							</td>
						</tr>
						<tr>
							<th>노출설정</th>
							<td colspan="3">
								<input type="radio" name="display" value="1" id="d1" checked><label for="d1">노출</label>
								<input type="radio" name="display" value="2" id="d2"><label for="d2">미노출</label>

								<input type="checkbox" name="top" value="1" /> 탑공지 (상단노출) 
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
							<input type="text" name="title" id="title"  value="" />
						</td>
					</tr>
					
					<tr>
						<th>장소</th>
						<td><input type="text" name="place" id="place" value=""/></td>
					</tr>
					<tr>
						<th>출연(강사)</th>
						<td><input type="text" name="teacher" id="teacher"></td>
					</tr>
					<tr>
						<th>보조출연(보조강사)</th>
						<td><input type="text" name="genre" id="genre" value=""/></td>
					</tr>
					<tr>
						<th>참여가는 연령</th>
						<td><input type="text" name="age" id="age" value="" class="wid200"/></td>
					</tr>
					<tr>
						<th>모집 기간</th>
						<td>
							<p class="calendar">
								<input type="text" name="sday" id="sday"  value="" class="dateTime" readonly autocomplete="off"/>
								<span id="CalsdayIcon" style="height:22px; line-height:22px;vertical-align:middle;">
									<span class="material-icons">calendar_month</span>
									<!--<img src="/admin/img/ico_calendar.gif" id="CalregistdateIconImg" style="cursor:pointer;"/>-->
								</span>
							</p>
							~
							<p class="calendar">
								<input type="text" name="eday" id="eday"  value="" class="dateTime" readonly autocomplete="off"/>
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
							<input type="time" value="" id="rtime" name="rtime" autocomplete="off"/>
						</td>
					</tr>
			
					<tr>
						<th>참여비</th>
						<td><input type="text" name="price" id="price" value="" onkeyup="onlyNumber(this);" class="wid200"/>원</td>
					</tr>
					<tr>
						<th>인원</th>
						<td><input type="text" name="amount" id="amount" value="" onkeyup="onlyNumber(this);" class="wid200"/></td>
					</tr>
					<tr>
						<th>동반인</th>
						<td>
							<span class="select">
							<select name="together">
								<option value="">선택</option>
								<option value="3">선택 불가</option>
								<option value="1">1인</option>
								<option value="2">2인</option>
							</select>
							</span>
						</td>
					</tr>
					<tr>
						<th>내용</th>
						<td>
							<textarea name="contents" id="contents" rows="10"></textarea>
						</td>
					</tr>
					<!--<tr>
						<th>유의사항</th>
						<td>
							<textarea name="contents2" id="contents2" rows="10"></textarea>
						</td>
					</tr>-->
					
					
					<tr>
						<th>이미지</th>
						<td>
							<input type="file" id="imagename" name="imagename" title="이미지를 업로드 해주세요."/>	
						</td>
					</tr>

					<tr>
						<th>관련링크</th>
						<td><input type="text" name="relation_url" id="relation_url"/></td>
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
