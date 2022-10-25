<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Spam.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$today = getToday();
$oneMonth = getMonthDateAdd(-1, $today);
$twoMonth = getMonthDateAdd(-2, $today);
$threeMonth = getMonthDateAdd(-3, $today);

$notice = new Spam($pageRows, $tablename, $_REQUEST);
$rowPageCount = $notice->getCount($_REQUEST);
if($rowPageCount[0] != 0){
	$data = $notice->getContents();
	$cmd = "edit";

}else{
	$data['contents'] = '';
	$cmd = "write";

}
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
	$("#sstartdate").val("");
	$("#senddate").val("");
	$("#stype").val("all");
	$("#sval").val("");
	goSearch()();
}

function goSave(){
	$('#frm').submit();
}
</script>
</head>


<body>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>
<!-- s:warp -->
	<div class="contWrap">
		<div class="titWrap">
			<h2><?=$pageTitle ?></h2>
			<div class="sBtn reset">
				<span class="material-icons">restart_alt</span>
				<input type="button" value="검색초기화"  class="reset"  onclick="resetSearchForm();"/>
			</div>
		</div>
		<div class="information">
			<ul>
				<li>※ 쉼표(,)로 단어를 구분합니다. (단어1, 단어2, 단어3)</li>
				<li>※ 설정된 단어들은 사용자 게시판에서 입력이 제한됩니다.</li>
				<li>※ 관리자 페이지는 적용되지 않습니다.</li>
				<li>※ 띄어쓰기(공백)은 모두 무시하며, 공백 여부 관계없이 모든 사용자 입력 값에 대해 검사합니다.</li>
			</ul>
		</div>
		<!-- //search_warp -->
		<div class="list">
			
			<form name="frm" id="frm" action="process.php" method="post"  style="margin-top:40px;">
				<textarea name="contents" style="width:100%; height:200px; border-width:2px; font-size:15px; padding:10px; box-sizing:border-box;"><?=$data['contents']?></textarea>
				
			<input type="hidden" name="cmd" id="cmd" value="<?=$cmd?>"/>
			<?=$notice->getQueryStringToHidden($_REQUEST) ?>
		</form>
		</div>
		<!-- //list -->
		<div class="btnSet clear">
			<div class="right"><a href="javascript:;"  onclick = "goSave();" class="btn hoverbg">등록</a></div>
		</div>
		<div class="pagenate">
			<?=pageList($notice->reqPageNo, $rowPageCount[1], $notice->getQueryString('index.php', 0, $_REQUEST))?>
		</div>
		<!-- //pagenate -->
	</div>
	<!-- //contents -->
</div>
<!-- e:warp --> 
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</body>
</html>
