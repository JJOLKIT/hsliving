<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/member/Member.class.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/order/Point.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$member = new Member($pageRows, "member", $_REQUEST);

$point = new Point($pageRows, $_REQUEST);
$rowPageCount = $point->getCount($_REQUEST);
$result = $point->getList($_REQUEST);
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<script>
//달력부분
$(window).load(function() {
	
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

function goSearch() {
	$("#searchForm").attr("action","index.php");
	$("#searchForm").submit();
}

function goSave() {
	if ($("#point").val() == '') {
		alert("포인트를 입력하세요");
		$("#point").focus();
		return false;
	}
	$("#wri").submit();
}
</script>
</head>


<body>
<!-- s:warp -->
	<div class="contWrap">
		<div class="titWrap">
			<h2>포인트 내역</h2>
		</div>
		<div class="search_Warp" style="width:670px">
			<table class="searchTable">
				<caption> 카테고리 등록 </caption>
					<colgroup>
						<col width="10%" />
						<col width="15%" />
						<col width="10%" />
						<col width="20%" />
						<col width="10%">
						<col width="30%" />
						<col width="10%">
					</colgroup>
				<tbody>
				<form name="wri" id="wri" action="<?=getSslCheckUrl($_SERVER['REQUEST_URI'], 'process.php')?>" method="post" enctype="multipart/form-data" >
					<tr>
						<th>구분</th>
						<td>
							<input type="radio" name="type" id="type1" value="1" checked/> <label for="type1">적립</label>
							<input type="radio" name="type" id="type2" value="2"/> <label for="type2">사용</label>
						</td>
						<th>포인트</th>
						<td>
							<input type="text" name="point" id="point" value="" maxlength="5" onkeyup="onlyNumber(this);"/>
						</td>
						<th>내용</th>
						<td>
							<input type="text" name="name" id="name" value=""/>
						</td>
						<td><input type="button" value="등록" id="" onclick="goSave();"/>
						</td>
					</tr>
					<input type="hidden" name="cmd" value="writePoint"/>
					<input type="hidden" name="member_fk" value="<?=$_REQUEST['smember_fk'] ?>"/>
					<input type="hidden" name="smember_fk" value="<?=$_REQUEST['smember_fk'] ?>"/>
				</form>
				</tbody>
			</table>
		</div>
		<br/>
		<div class="list" style="width:670px">
			<form name="frm" id="frm" action="process.php" method="post">
			<table>
				<colgroup>
					<col width="10%">
					<col width="30%">
					<col width="25%">
					<col width="35%">
				</colgroup>
				<tbody>
				<tr>
					<th>구분</th>
					<th>일자</th>
					<th>포인트</th>
					<th>내용</th>
				</tr>
				<? while ($row=mysql_fetch_assoc($result)) {  ?>
				<tr>
					<td><?=getPointTypeName($row['type']) ?></td>
					<td><?=getYMD($row['registdate']) ?></td>
					<td>
						<? if ($row['type']==2) echo"-";?><?=number_format($row['point']) ?>
					</td>
					<td><?=$row['name'] ?></td>
				</tr>
				<? } ?>
				</tbody>
			</table>
			<input type="hidden" name="cmd" id="cmd" value="groupDeletePoint"/>
			<?=$member->getQueryStringToHidden($_REQUEST) ?>
			</form>
		</div>
		<!-- //list -->
		<div class="pagenate" style="width:670px">
			<?=pageList($point->reqPageNo, $rowPageCount[1], $point->getQueryString('pointList.php', 0, $_REQUEST))?>
		</div>
		<!-- //pagenate -->
	</div>
	<!-- //contents -->
</div>
<!-- e:warp --> 
</body>
</html>
