<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Rsrv2.class.php";
include "config.php";

$today = getToday();
$oneMonth = getMonthDateAdd(-1, $today);
$twoMonth = getMonthDateAdd(-2, $today);
$threeMonth = getMonthDateAdd(-3, $today);


$_REQUEST['smember_fk'] = $_SESSION['member_no'];
$notice = new Rsrv2($pageRows, $tablename, $_REQUEST);
$rowPageCount = $notice->getCount($_REQUEST);
$result = $notice->getList($_REQUEST);
?>
<?
	$p = "mypage";
	$sp = 1;
	$spc= 0;
	$root = $_SERVER['DOCUMENT_ROOT'];
	include_once $root."/header.php";

	if(!$loginCheck){
		echo "
			<script>
			if(confirm('로그인이 필요한 서비스입니다.\\n로그인 페이지로 이동하시겠습니까?')){
				location.href = '/member/login.php?url='+encodeURIComponent(location.href);
			}else{
				location.href = '/';
			}
			</script>
		";
		exit;
	}
?>

<script>
//달력부분
$(window).load(function() {
	
	$("input[type=text][name*=sval]").keypress(function(e){
		if(e.keyCode == 13){
			goSearch();
		}
	});
	
});

function goSearch() {
	$("#searchForm").submit();
}

</script>
<div id="sub" class="list_idx">
  <?include_once $root."/include/sub_visual.php";?>
  <div class="con_wrap">
    <div class="cont_top">
      <div class="size">
        <div class="t_wrap">
          <span>화성시 생활문화창작소</span>
          <b>신청현황</b>
        </div>
      </div>
    </div>
    <!-- 여기서부터 게시판--->
    <div class="has_contit nbd">
      <div class="size clear">
        <div class="bbs con_info">
          <div class="bbsSearch">
            <form method="get" name="searchForm" id="searchForm" action="index.php">
				<span class="select srchSelect">
					<select id="stype" name="stype" class="dSelect" title="검색분류 선택">
						<option value="program_title" <?=getSelected("program_title", $_REQUEST['stype'])?>>프로그램명</option>
					</select>
				</span>
				<span class="searchWord">
					<input type="text" id="sval" name="sval" placeholder="검색어를 입력해주세요." value="" title="검색어 입력" onKeypress="">
					<input type="button" id="" value="검색" title="검색" onclick="goSearch();">
				</span>
            </form>
          </div>
          <div class="bbs_list">
            <table class="list">
              <caption>게시판 목록</caption>
              <colgroup>
                <col width="100px" />
                <col width="140px" />
                <col width="*" />
                <col width="110px" />
                <col width="130px" />
				<col width="130px" />
              </colgroup>
              <thead>
                <tr>
                  <th>번호</th>
                  <th>구분</th>
                  <th>프로그램명</th>
                  <th>신청상태</th>
                  <th>이용 신청일</th>
				  <th>접수일</th>
                </tr>
              </thead>
              <tbody>
				<?if($rowPageCount[0] == 0){?>
				<tr>
					<td colspan="6">신청하신 내역이 없습니다.</td>
				</tr>
				<?}else{
				$i = 0;
					while($row = mysql_fetch_assoc($result)){
						$targetUrl = "style='cursor:pointer;' onclick=\"location.href='".$notice->getQueryString('view.php', $row[no], $_REQUEST)."'\"";
				?>

				<tr <?=$targetUrl?>>
					<td class="no"><?=$rowPageCount[0] - (($notice->reqPageNo-1)*$pageRows) - $i?></td>
					<td class="catg txt_c">프로그램</td>
					<td class="txt_l title">
						<p><?=$row['title']?></p>
					</td>
					<td class="name txt_c"><?=getStateName($row['state'])?></td>
					<td class="date txt_c"><?=$row['rdate']?></td>
					<td class="date txt_c"><?=getYMD($row['registdate'])?></td>

				</tr>
				<? $i++;}}?>
              </tbody>
            </table>
          </div>

		<div class="pagenate clear">
			<?=pageList($notice->reqPageNo, $rowPageCount[1], $notice->getQueryString('index.php', 0, $_REQUEST))?>
		</div>
		<!-- //pagenate -->




        <!-- //여기까지 게시판--->
      </div>
    </div>
    <!-- //size--->
  </div>
<?
	include_once $root."/footer.php";
?>