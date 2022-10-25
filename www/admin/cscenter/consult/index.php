<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Consult.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$today = getToday();
$oneMonth = getMonthDateAdd(-1, $today);
$twoMonth = getMonthDateAdd(-2, $today);
$threeMonth = getMonthDateAdd(-3, $today);

$consult = new Consult($pageRows, $tablename, $_REQUEST);
$rowPageCount = $consult->getCount($_REQUEST);
$result = ($consult->getList($_REQUEST));
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<script>
$(window).load(function() {
	initCal({id:"sstartdate",type:"day",today:"y"});			
	initCal({id:"senddate",type:"day",today:"y"});
	
	$("input[type=text][name*=sval]").keypress(function(e){
		if(e.keyCode == 13){
			goSearch();
		}
	});
	
});

function groupDelete() {	
	if ( isSeleted(document.frm.no) ){
		
		if (confirm("선택한 항목을 삭제하시겠습니까?")) {
			$('input#cmd').val('groupDelete');
			document.frm.submit();
		}
	} else {
		alert("삭제할 항목을 하나 이상 선택해 주세요.");
	}
}

function groupUnSpam(){
	if ( isSeleted(document.frm.no) ){
		
		if (confirm("선택한 항목을 스팸해제 하시겠습니까?")) {
			$('input#cmd').val('groupUnSpam');
			document.frm.submit();
		}
	} else {
		alert("해제할 항목을 하나 이상 선택해 주세요.");
	}
	
}

function groupSpam(){
	if ( isSeleted(document.frm.no) ){
		
		if (confirm("선택한 항목을 스팸등록 하시겠습니까?")) {
			$('input#cmd').val('groupSpam');
			document.frm.submit();
		}
	} else {
		alert("등록할 항목을 하나 이상 선택해 주세요.");
	}
	
}
function searchDate(startDay, endDay) {
	var f = document.searchForm;
	f.sstartdate.value = startDay;
	f.senddate.value = endDay;

	goSearch();
}

function goSearch() {
	$("#searchForm").submit();
}

function resetSearchForm() {
	$(".searchWrap input[type='text']").val("");
	$(".searchWrap input[type='checkbox']").removeAttr("checked");
	$(".searchWrap input[type='radio']").removeAttr("checked");
	$(".searchWrap select").val("all");
	goSearch()();
}
</script>
</head>


<body>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>
<!-- s:warp -->
	<div class="contWrap">
		<div class="titWrap">
			<h2><?=$pageTitle?></h2>
			<div class="sBtn reset">
				<span class="material-icons">restart_alt</span>
				<input type="button" value="검색초기화"  class="reset"  />
			</div>
		</div>
		<div class="searchWrap">
			<form method="get" name="searchForm" id="searchForm" action="index.php">
				<table class="searchTable">
					<caption> 게시글검색 </caption>
					<colgroup>
						<col width="10%" />
						<col width="40%" />
						<col width="10%" />
						<col width="40%" />
					</colgroup>
					<tbody>
						<tr>
							<th>
								날짜
							</th>
							<td>
								<p class="calendar">
									<input type="text" name="sstartdate" id="sstartdate"  value="<?=$_REQUEST['sstartdate']?>" class="date" onKeyUp="cvtDate(this);isNumberOrHyphen(this);" maxlength="10"/>
									<span id="CalsstartdateIcon">
										<span class="material-icons" id="CalsstartdateIconImg">calendar_month</span>
									</span>
								</p>
								<span>~</span>
								<p class="calendar">
									<input type="text" name="senddate"  id="senddate" value="<?=$_REQUEST['senddate']?>" class="date" onKeyUp="cvtDate(this);isNumberOrHyphen(this);" maxlength="10"/>
									<span id="CalsenddateIcon">
										<span class="material-icons" id="CalsenddateIconImg">calendar_month</span>
									</span>
								</p>
								<input type="button" value="검색" onclick="goSearch();" class="hoverbg" />
								<input type="button" value="1M" onClick="searchDate('<?=$oneMonth?>','<?=$today?>');" class="hoverbg" />
								<input type="button" value="2M" onClick="searchDate('<?=$twoMonth?>','<?=$today?>');" class="hoverbg" />
								<input type="button" value="3M" onClick="searchDate('<?=$threeMonth?>','<?=$today?>');" class="hoverbg" />
							</td>
							<th>검색어</th>
							<td>
								<span class="select">
									<select name="stype" id="stype">
										<option value="all" <?=getSelected("all", $_REQUEST['stype']) ?>>전체</option>
										<option value="title" <?=getSelected("title", $_REQUEST['stype']) ?>>제목</option>
										<option value="contents" <?=getSelected("contents", $_REQUEST['stype']) ?>>내용</option>
									</select>
								</span>
								<input type="text" name="sval" id="sval" value="<?=$_REQUEST['sval'] ?>" />
								<input type="submit" value="검색" class="btn_search hoverbg" />
							</td>
						</tr>
					</tbody>
				</table>
			<input type="hidden" name="pageRows" id="pageRows" value="<?=$pageRows ?>"/>
			</form>
		</div>
		<!-- //search_warp -->
		<div class="list">
			<p class="list_tit">전체 <strong><?=$rowPageCount[0]?></strong>건 [<?=$consult->reqPageNo?>/<?=$rowPageCount[1]?>페이지] </p>
			<form name="frm" id="frm" action="process.php" method="post">
			<table>
				<caption> 게시글 목록 </caption>
					<colgroup>
						<col width="50px" />
						<col width="5%" />
						<col width="5%"/>
						<col width="*" />
						<col width="7%" />
						<col width="10%" />
						<col width="10%" />
					</colgroup>
				<thead>
					<tr>
						<th scope="col"><input type="checkbox" name="allChk" id="allChk" onClick="check(this, document.frm.no)"/></th>
						<th scope="col">번호</th>
						<th scope="col">스팸여부</th>
						<th scope="col">제목</th>
						<th scope="col">답변</th>
						<th scope="col">작성자</th>
						<th scope="col">작성일</th>
					</tr>
				</thead>
				<tbody>
				<? if ($rowPageCount[0] == 0) { ?>
					<tr>
						<td colspan="6" align="center">등록된 데이터가 없습니다.</td>
					</tr>
				<?
					 } else {
						$topClass = "";
						$i = 0;
						while ($row=mysql_fetch_assoc($result)) {
							$row = escape_html($row);
							$targetUrl = "style='cursor:pointer;' onclick=\"location.href='".$consult->getQueryString('view.php', $row[no], $_REQUEST)."'\"";
				?>
					<tr>
						<td><input type="checkbox" value="<?=$row['no']?>" name="no[]" id="no" /></td>
						<td <?=$targetUrl?>><?=$rowPageCount[0] - (($consult->reqPageNo-1)*$pageRows) - $i?></td>
						<td <?=$targetUrl?>><?=getSpam($row['isspam'])?></td>
						<td <?=$targetUrl?> class="txt_l"><?=$row['title'] ?></td>
						<td <?=$targetUrl?>>
						<? if (!$row['answer'] || $row['answer'] == "<p>&nbsp;</p>") { ?>
							<span class="re_state bgGray">대기</span>
						<? } else { ?>
							<span class="re_state bgcolor">완료</span>
						<? } ?>
						</td>
						<td <?=$targetUrl?>><?=$row['name'] ?></td>
						<td <?=$targetUrl?>><?=$row['registdate'] ?></td>
					</tr>
				<?
						$i++;
						}
					 }
				?>
				</tbody>
			</table>
			<input type="hidden" name="cmd" id="cmd" value="groupDelete"/>
		
			<?=$consult->getQueryStringToHidden($_REQUEST) ?>
		</form>
		</div>
		<!-- //list -->
		<div class="btnSet clear">
			<div class="sBtn left">
				<input type="button" value="삭제" onclick="groupDelete();"/>
				<input type="button" value="스팸등록" onclick="groupSpam();"/>
				<input type="button" value="스팸해제" onclick="groupUnSpam();"/>
			</div>
		</div>
		<div class="pagenate">
			<?=pageList($consult->reqPageNo, $rowPageCount[1], $consult->getQueryString('index.php', 0, $_REQUEST))?>
		</div>
		<!-- //pagenate -->
	</div>
	<!-- //contents -->
</div>
<!-- e:warp --> 
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</body>
</html>
