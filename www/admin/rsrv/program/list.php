<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Rsrv2.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$today = getToday();
$oneMonth = getMonthDateAdd(-1, $today);
$twoMonth = getMonthDateAdd(-2, $today);
$threeMonth = getMonthDateAdd(-3, $today);


$notice = new Rsrv2($pageRows, $tablename, $_REQUEST);
$rowPageCount = $notice->getCount($_REQUEST);
$result = $notice->getList($_REQUEST);
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<script>
//달력부분
$(window).load(function() {
	initCal({id:"sstartdate",type:"day",today:"y"});			
	initCal({id:"senddate",type:"day",today:"y"});
	
	$("input[type=text][name*=sval]").keypress(function(e){
		if(e.keyCode == 13){
			goSearch();
		}
	});
});
</script>
<script>
function groupDelete() {	
	if ( isSeleted(document.frm.no) ){
		if (confirm("선택한 항목을 삭제하시겠습니까?")) {
			document.frm.submit();
		}
	} else {
		alert("삭제할 항목을 하나 이상 선택해 주세요.");
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

<div class="contWrap">
	<div class="titWrap">
		<h2><?=$pageTitle ?></h2>
		<div class="sBtn reset">
			<span class="material-icons">restart_alt</span>
			<input type="button" value="검색초기화" class="reset" onclick="resetSearchForm();"/>
		</div>
	</div>
	<div class="searchWrap">
		<form method="get" name="searchForm" id="searchForm" action="list.php">
			<table class="searchTable">
				<caption> 게시글검색 </caption>
				<colgroup>
					<col width="8%" />
					<col width="40%" />
					<col width="8%" />
					<col width="8%" />
					<col width="8%" />
					<col width="30%" />
				</colgroup>
				<tbody>
					<tr>
						<th>
							신청일시
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
							<input type="button" value="3M" onClick="searchDate('<?=$threeMonth?>','<?=$today?>');"  class="hoverbg" />
						</td>
						<th>신청상태</th>
						<td>
							<span class="select">
								<select name="sstate" onchange="goSearch();">
									<option value="">선택</option>
									<?for($i = 1; $i <= 5; $i++){?>
									<option value="<?=$i?>" <?=getSelected($i, $_REQUEST['sstate'])?>><?=getStateName($i)?></option>
									<?}?>
								</select>
							</span>

						</td>
						<th>검색어</th>
						<td>
							<span class="select">
								<select name="stype" id="stype">
									<option value="all" <?=getSelected("all", $_REQUEST['stype']) ?>>전체</option>
									<option value="name" <?=getSelected("name", $_REQUEST['stype']) ?>>신청자명</option>
									<option value="cell" <?=getSelected("cell", $_REQUEST['stype']) ?>>연락처</option>
									<option value="program_title" <?=getSelected("program_title", $_REQUEST['stype'])?>>프로그램명</option>
								</select>
							</span>
							<input type="text" name="sval" id="sval" value="<?=$_REQUEST['sval'] ?>" />
							<input type="submit" value="검색" class="btn_search hoverbg" />
						</td>
					</tr>
				</tbody>
			</table>
			<input type="hidden" name="pageRows" id="pageRows" value="<?=$pageRows ?>"/>
			<input type="hidden" name="srdate" value="<?=$_REQUEST['srdate']?>"/>
			<input type="hidden" name="sprogram_fk" value="<?=$_REQUEST['sprogram_fk']?>"/>
		</form>
	</div>
	<!-- //search_warp -->
	<div class="list">
		<p class="list_tit clear">전체 <strong><?=$rowPageCount[0]?></strong>건 [<?=$notice->reqPageNo?>/<?=$rowPageCount[1]?>페이지] 
			<span class="select">
				<select name="pageRowsVal" id="pageRowsVal" onchange="$('#pageRows').val($('#pageRowsVal').val());goSearch();">
					<option value="10" <?=getSelected("10", $pageRows) ?>>10개씩</option>
					<option value="20" <?=getSelected("20", $pageRows) ?>>20개씩</option>
					<option value="50" <?=getSelected("50", $pageRows) ?>>50개씩</option>
				</select>
			</span>
		</p>
		<form name="frm" id="frm" action="process.php" method="post">
			<table>
				<caption> 목록 </caption>
					<colgroup>
						<col width="42px" />
						<col width="5%" />
						<col width="*" />
						<col width="15%" />
						<col width="10%" />
						<col width="10%" />
						<col width="10%"/>
						<col width="10%"/>
						<col width="10%"/>

					</colgroup>
				<thead>
					<tr>
						<th scope="col"><input type="checkbox" name="allChk" id="allChk" onClick="check(this, document.frm.no)"/></th>
						<th scope="col">번호</th>
						<th scope="col">프로그램명</th>
						<th scope="col">프로그램 신청일시</th>
						<th scope="col">신청자명</th>
						<th>연락처</th>
						<th>참여비</th>
						<th>신청 상태</th>
						<th scope="col">작성일</th>
					</tr>
				</thead>
				<tbody>
				<? if ($rowPageCount[0] == 0) { ?>
					<tr>
						<td colspan="9" align="center">등록된 데이터가 없습니다.</td>
					</tr>
				<?
					 } else {
						$targetUrl = "";
						$topClass = "";
						$i = 0;

						while ($row=mysql_fetch_assoc($result)) {
							$row = escape_html($row);


							$targetUrl = "style='cursor:pointer;' onclick=\"location.href='".$notice->getQueryString('view.php', $row[no], $_REQUEST)."'\"";
							if ($row[top] == '1') {
								$topClass = "class='topBg'";
							} else {
								$topClass = "";
							}
				?>
					<tr <?=$topClass?>>
						<td><input type="checkbox" value="<?=$row['no']?>" name="no[]" id="no"/></td>
						<td <?=$targetUrl ?>>
						<? if ($row[top] == "1") { ?>
							<img src="/img/ico_top.png" alt="TOP공지" />
						<? } else { ?>
							<?=$rowPageCount[0] - (($notice->reqPageNo-1)*$pageRows) - $i?>
						<? } ?>
						</td>
						<td class="txt_l" <?=$targetUrl ?>>
						<?=$row[title]?>
						</td>
						<td <?=$targetUrl ?>><?=$row[rdate]?><!-- (<?=substr($row['rtime'], 0, 5)?>)--></td>
						<td <?=$targetUrl ?>><?=$row[name]?></td>
						<td <?=$targetUrl ?>><?=$row[cell]?></td>
						<td <?=$targetUrl ?>><?=number_format($row[price])?>원</td>
						<td <?=$targetUrl?>><?=getStateName($row['state'])?></td>
						<td <?=$targetUrl ?>><?=$row[registdate]?></td>
					</tr>
					<?
							$i++;
							}
						 }
					?>
				</tbody>
			</table>
			<input type="hidden" name="cmd" id="cmd" value="groupDelete"/>
			<?=$notice->getQueryStringToHidden($_REQUEST) ?>
		</form>
	</div>
	<!-- //list -->
	<div class="btnSet clear">
		<div class="sBtn left">
			
			<input type="button" value="삭제" onclick="groupDelete();"/>
			
		</div>
		<div class="right">
			<!--
			<a href="write.php" class="btn edit hoverbg">
				<span class="material-icons">edit</span>글쓰기
			</a>
			-->
		</div>
	</div>
	<div class="pagenate">
		<?=pageList($notice->reqPageNo, $rowPageCount[1], $notice->getQueryString('list.php', 0, $_REQUEST))?>
	</div>
	<!-- //pagenate -->
</div>
<!-- //contWrap -->
</div>
<!-- //wrapeer --> 
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</body>
</html>
