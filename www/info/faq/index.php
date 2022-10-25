<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

	include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Faq.class.php";

	include "config.php";



	$faq = new Faq($pageRows, $tablename, $category_tablename, $_REQUEST);
	$rowPageCount = $faq->getCount($_REQUEST);
	$result = ($faq->getList($_REQUEST));
	$category_result = $faq->getCategoryList($_REQUEST);
	$category_result2 = $faq->getCategoryList($_REQUEST);
	$cate_total = $faq->getCategoryCount($category_result);
	
	if($_REQUEST['scategory_fk'] != ""){
		$cateData = $faq->getCategoryData($_REQUEST['scategory_fk']);
	}
	

	$p = "info";
	$sp = 0;
	$spc= 0;
	$root = $_SERVER['DOCUMENT_ROOT'];
	include_once $root."/header.php";
?>



<script src="/js/jquery.carouFredSel-6.2.1-packed.js"></script>
<script type="text/javascript">
	$(window).load(function(){ 
		$("input[type=text][name*=sval]").keypress(function(e){
			if(e.keyCode == 13){
				goSearch();
			}
		});
	});

	function goSearch() {
		$("#searchForm").submit();
	}

	function show(o) {
		$(".faqA").removeClass('on');
		$(".faqQ").removeClass('on');
		$("."+o).addClass('on');
		$("#"+o).addClass('on');

	}

	$(function(){
		$('.faqTab_m > a').on('click',function(){
			if($(this).hasClass('on')){
				$(this).removeClass('on');
				$(this).next().stop().slideUp(400);
			}else{
				$(this).addClass('on');
				$(this).next().stop().slideDown(400);
			}
		});

		$('.faqTab_m > a').html( $('.faqTab_m ul li a.on').text() );
	});
</script>
<div id="sub" class="list_idx">
	<?include_once $root."/include/sub_visual.php";?>
	<div class="con_wrap">
		<div class="cont_top">
			<div class="size">
				<div class="t_wrap">
					<span>화성시 생활문화창작소</span>
					<b>FAQ</b>
				</div>
			</div>
		</div>
	<div class="has_contit nbd">
		<div class="size clear">
			<!-- 여기서부터 게시판--->
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

							<?

				if($category_use){
							if($category_result > 0){ 
							?>
							<div class="faqTab">
									<ul class="clear itemList<?=$cate_total[0]?>">
											<li><a href="index.php" <?if($_REQUEST['scategory_fk'] == "") { echo "class='on'"; }?>>전체</a></li>
											<?
													$i = 1; 
													while($row2 = mysql_fetch_assoc($category_result)){	
											?>
											<li <?if($i < 5) { echo "class='lineclear'";}?>><a href="index.php?scategory_fk=<?=$row2['no']?>" <? if($_REQUEST['scategory_fk'] == $row2['no']) {  echo "class='on'"; }?>><?=$row2['name']?></a></li>
											<? $i++; }?>
									</ul>
							</div>
							<div class="faqTab_m">
									<a href="javascript:;">
						<?
							if($_REQUEST['scategory_fk'] != ""){
								echo $cateData['name'];
							}else{
								echo "전체";
							}
						?>
					</a>
									<ul>
											<li><a href="index.php" <?if($_REQUEST['scategory_fk'] == ""){ echo "class='on'"; }?>>전체</a></li>
											 <?
											while($row3 = mysql_fetch_assoc($category_result2)){
													?>
											<li><a href="index.php?scategory_fk=<?=$row3['no']?>"><?=$row3['name']?></a></li>
											<?}?>
									</ul>
							</div>
							<?}}?>
							<div class="bbs_list faqs">
							<table class="list faq">
									<caption>게시판 목록</caption>
									<colgroup>
											<col width="100px" />
						<?if($category_use){?>
											<col width="180px" />
						<?}?>
											<col width="*" />
									</colgroup>
									<thead>
											<tr>
												<th>번호</th>
							<?if($category_use){?>
													<th>구분</th>
							<?}?>
													<th>제목</th>
											</tr>
									</thead>
									<tbody>
									<? if ($rowPageCount[0] == 0) { ?>
											<tr>
													<td colspan="<?=$category_use == true ? "2" : "1" ?>" align="center">등록된 글이 없습니다.</td>
											</tr>
									<?
											 } else {
													$targetUrl = "";
													$topClass = "";
													$i = 0;
													while ($row=mysql_fetch_assoc($result)) { 
			
															$targetUrl = "style='cursor:pointer;' onclick=\"location.href='".$faq->getQueryString('view.php', $row[no], $_REQUEST)."'\"";
															if ($row[top] == '1') { 
																	$topClass = "class='topBg'";
															} else {
																	$topClass = "";
															}
									?>
											<tr class="faqQ q<?=$i?>" onclick="show('q<?=$i?>');" style="cursor:pointer;">
												<td> <? if ($row[top] == "1") { ?>
                                <img src="/img/ico_top.png" alt="TOP공지" />
                            <? } else { ?>
                                <?=$rowPageCount[0] - (($notice->reqPageNo-1)*$pageRows) - $i?>
                            <? } ?></td>
							<?if($category_use){?>
													<td class="part txt_c"><span class="blind">Q</span> <?=$row[category_name]?></td>
							<?}?>
													<td class="title"><?=escape_html($row[title])?></td>
											</tr>
											<tr id="q<?=$i?>" class="faqA" style="display:none;" >
											<td ></td>
											<td ></td>
													<td ><span class="blind">A</span>
															<?=$row[contents]?><br/>
															<? if ($row['filename']) { ?>
															<a href="/lib/download.php?path=<?=$uploadPath?>&vf=<?=$row['filename']?>&af=<?=urlencode($row['filename_org'])?>" target="_blank"><?=$row[filename_org]?></a>
															<? } ?>
													</td>
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
									<?=pageList($faq->reqPageNo, $rowPageCount[1], $faq->getQueryString('index.php', 0, $_REQUEST))?>
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



