<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

	include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Rsrv.class.php";
	include $_SERVER['DOCUMENT_ROOT']."/include/loginCheck.php";

	include "config.php"; 


	
	$_REQUEST['smember_fk'] = $_SESSION['member_no'];

	$notice = new Rsrv($pageRows, $tablename, $_REQUEST);
	$rowPageCount = $notice->getCount($_REQUEST);
	$result = $notice->getList($_REQUEST);


?>
<?
	$p = "mypage";
	$sp = 0;
	$spc= 0;
	$root = $_SERVER['DOCUMENT_ROOT'];
	include_once $root."/header.php";
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
						<option value="title" <?=getSelected("title", $_REQUEST['stype'])?>>활동명</option>
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
                <col width="140px" />
                <col width="*" />
                <col width="130px" />
                <col width="130px" />
                <col width="130px" />
              </colgroup>
              <thead>
                <tr>
                  <th>번호</th>
                  <th>사용시설</th>
                  <th>구분</th>
                  <th>활동명</th>
                  <th>참가인원</th>
                  <th>신청상태</th>
                  <th>신청일</th>
                </tr>
              </thead>
              <tbody>
				<? if ($rowPageCount[0] == 0) { ?>
                <tr>
					등록된 신청현황이 없습니다.
                </tr>
				<?
					} else {
                        $targetUrl = "";
                        $topClass = "";
                        $i = 0;
                        while ($row=mysql_fetch_assoc($result)) {
							$row = escape_html($row);
                            $targetUrl = "style='cursor:pointer;' onclick=\"location.href='".$notice->getQueryString('view.php', $row[no], $_REQUEST)."'\"";
				?>

				<?
					if($row['member_fk'] > 0 && $row['member_fk'] == $_SESSION['member_no']){
				?>
				<tr <?=$targetUrl?>>
				<?}else{?>
				<tr onclick="getPass('<?=$row[no]?>');" style="cursor:pointer;" >
				<?}?>
					<td class="no"><?=$rowPageCount[0] - (($notice->reqPageNo-1)*$pageRows) - $i?></td>
					<td class="catg txt_c"><?=getPlaceName($row['place']) ?></td>
					<td class="catg txt_c"><?=getPurposeName($row['purpose']) ?></td>
					<td class="txt_l title">
						<p><?=$row['title']?></p>
					</td>
					<td class="name txt_c"><?=number_format($row['amount'])?></td>
					<td class="date txt_c"><?=getStateName($row['state'])?></td>
					<td class="date txt_c"><?=getYMD($row['registdate'])?></td>
					<!-- <td class="form_btn">
						<a href="view.php">취소신청</a>
					</td> -->
                </tr>
				<?$i++;}}?>
              </tbody>
            </table>
          </div>

          <div class="pagenate clear">
            <ul class="paging">
              <li><a href="javascript:;" class="current">1</a></li>
            </ul>
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