<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

	include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Notice.class.php";

	include "config.php";



	$notice = new Notice($pageRows, $tablename, $_REQUEST);
	$rowPageCount = $notice->getCount($_REQUEST);
	$result = ($notice->getList($_REQUEST));



?>
<?	
	$p = "news";
	$sp = 1;
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
					<b>보도자료</b>
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
									<option value="all" <?=getSelected("all", $_REQUEST['stype']) ?>>전체</option>
									<option value="title" <?=getSelected("title", $_REQUEST['stype']) ?>>제목</option>
									<option value="contents" <?=getSelected("contents", $_REQUEST['stype']) ?>>내용</option>
								</select>
							</span>
							<span class="searchWord">
								<input type="text" id="sval" name="sval" placeholder="검색어를 입력해주세요." value="<?=$_REQUEST['sval'] ?>" title="검색어 입력" onKeypress="">
								<input type="button" id="" value="검색" title="검색" onclick="goSearch();">
							</span>
						</form>
					</div>
					<div class="bbs_list">
						<table class="list">
							<caption>게시판 목록</caption>
							<colgroup>
								<col width="110px" />
								<col width="*" />
								<col width="100px"  class="admin"/>
								<col width="170px" />
								<col width="130px" />
							</colgroup>
							<thead>
								<tr>
									<th>번호</th>
									<th>제목</th>
									<th class="admin">작성자</th>
									<th>작성일</th>
									<th>조회수</th>
								</tr>
							</thead>
							<tbody>
							<? if ($rowPageCount[0] == 0) { ?>
								<tr>
									<td colspan="5" align="center">등록된 데이터가 없습니다.</td>
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
											$topClass = "class='notice'";
										} else {
											$topClass = "";
										}
							?>
								<tr <?=$topClass?> <?=$targetUrl?>>
									<td class="no">
									<? if ($row[top] == "1") { ?>
										<span class="notice_ico">공지</span>
									<? } else { ?>
										<?=$rowPageCount[0] - (($notice->reqPageNo-1)*$pageRows) - $i?>
									<? } ?>
									</td>
									<td class="title txt_l">
										<?=$row[title]?>
										<? if ($isComment) { ?>
											<span class="reNum">[<strong><?=$row[comment_count]?></strong>]</span>
										<? } ?>
										<? if (checkNewIcon($row['registdate'], $row['newicon'], 1)) { ?>
											<img src="/img/ico_new.png" alt="새글" />
										<? } ?>
										<?=$row['age']?>
									</td>
									<td class="name admin"><?=$row[name]?></td>
									<td class="date"><?=getYMD($row[registdate])?></td>
									<td class="hit"><?=$row[readno]?></td>
								</tr>
							<?
									$i++;
									}
								 }
							?>
							</tbody>
						</table>
					</div>
					<div class="pagenate clear">
						<?=pageList($notice->reqPageNo, $rowPageCount[1], $notice->getQueryString('index.php', 0, $_REQUEST))?>
					</div>
					<!-- //pagenate -->
					
				</div>
			</div>
		</div>
		<!-- //여기까지 게시판--->
	<!-- //size--->
	</div>
</div>
<!-- //sub--->
<?
	include_once $root."/footer.php";
?>