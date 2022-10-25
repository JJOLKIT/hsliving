<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

	include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Gallery.class.php";

	include "config.php";

	$notice = new Gallery($pageRows, $tablename, $_REQUEST);
	$rowPageCount = $notice->getCount($_REQUEST);
	$result = $notice->getList($_REQUEST);
?>

<?	
	$p = "news";
	$sp = 3;
	$spc= 0;
	$root = $_SERVER['DOCUMENT_ROOT'];
	include_once $root."/header.php";
?>

<div id="sub" class="list_idx prg_idx">
	<?include_once $root."/include/sub_visual.php";?>
	<div class="con_wrap">
		<div class="cont_top">
			<div class="size">
				<div class="t_wrap">
					<span>화성시 생활문화창작소</span>
					<b>아카이빙</b>
				</div>
			</div>
		</div>
		<div class="has_contit nbd">
			<div class="size clear">
				<!-- 여기서부터 게시판--->
					<div class="bbs con_info clear">
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
							<div class="gallery">
									<ul class="clear">
									<? if($rowPageCount[0] == 0) { ?>
											<li>등록된 글이 없습니다.</li>
									<? } else { ?>
											<?
											while ($row=mysql_fetch_assoc($result)) {
											?>
											<li>
													<a href="<?=$notice->getQueryString('view.php', $row[no], $_REQUEST)?>">
													<dl>
															
																<? if ($row['imagename']) { ?>
																<dt class="imgs" style="background-image:url('<?=$uploadPath?><?=$row['imagename']?>');">
																		 <? if ($row[top] == "1") { ?>
																					<span class="notice_ico">공지</span>
																		 <? } ?>
																		 <img src="/img/prg_img.jpg" alt="<?=$row[image_alt]?>"/>
																</dt>
																<?}else{?>
																<dt class="imgs noimgs" style="background-image:url('/admin/img/no_image.jpg');">
																		 <? if ($row[top] == "1") { ?>
																					<span class="notice_ico">공지</span>
																		 <? } ?>
																		 <img src="/img/prg_img.jpg" alt="<?=$row[image_alt]?>"/>
																</dt>
																<?}?>
																<dd class="title">
																	<b><?=$row[title]?></b>
																</dd>
																<dd class="info">
																	<?=getYmd($row['registdate']);?>
																</dd>
													</dl>
													</a>
											</li>
									<?
													}
											} 
									?>
									</ul>
							</div>
						</div>
						<!-- //galley -->
						<div class="pagenate clear">
								<?=pageList($notice->reqPageNo, $rowPageCount[1], $notice->getQueryString('index.php', 0, $_REQUEST))?>
						</div>
						<!-- //pagenate -->
					
				</div>
				<!-- //여기까지 게시판--->
			</div>
	</div>
	<!-- //size--->
</div>
<!-- //sub--->
<?
	include_once $root."/footer.php";
?>
