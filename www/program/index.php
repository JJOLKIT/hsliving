<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

	include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/GalleryCt.class.php";

	include "config.php";
	$_REQUEST['sdisplay'] = 1;

	$notice = new GalleryCt($pageRows, $tablename, $category_tablename, $_REQUEST);
	$rowPageCount = $notice->getCount($_REQUEST);
	$result = ($notice->getList($_REQUEST));
?>

<?	
	$p = "program";
	$sp = 0;
	$spc= 0;
	$root = $_SERVER['DOCUMENT_ROOT'];
	include_once $root."/header.php";
?>
<script>
	function setState(obj, i){
		if(obj.checked){
			$('#sstate'+i).val(1);
		}else{
			$('#sstate'+i).val('');
		}

		goSearch();
	}
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

<div id="sub" class="list_idx prg_idx">
	<?include_once $root."/include/sub_visual.php";?>
	<div class="con_wrap">
		<div class="cont_top">
			<div class="size">
				<div class="t_wrap">
					<span>화성시 생활문화창작소</span>
					<b>프로그램</b>
				</div>
			</div>
		</div>
		<div class="has_contit nbd">
			<div class="size clear">
				<!-- 여기서부터 게시판--->
					<div class="bbs con_info clear">
						<div class="ct_wrap check_box">
							<p class="ck1">
								<input type="checkbox" id="ong" value="1" onclick="setState(this, 2);" <?=getChecked(1, $_REQUEST['sstate2'])?>>
								<label for="ong"><span>진행중</span></label>
							</p>
							<p  class="ck2">
								<input type="checkbox" id="pre" value="1" onclick="setState(this, 1);" <?=getChecked(1, $_REQUEST['sstate1'])?>>
								<label for="pre"><span>예정</span></label>
							</p>
							<p class="ck3">
								<input type="checkbox" id="end" value="1" onclick="setState(this, 3);" <?=getChecked(1, $_REQUEST['sstate3'])?>>
								<label for="end"><span>종료</span></label>
							</p>
						</div>
						<div class="bbsSearch">
							<form method="get" name="searchForm" id="searchForm" action="index.php">
								<span class="select srchSelect">
									<select id="stype" name="stype" class="dSelect" title="검색분류 선택">
										<option value="all" <?=getSelected("all", $_REQUEST['stype']) ?>>전체</option>
										<option value="title" <?=getSelected("title", $_REQUEST['stype']) ?>>프로그램 제목</option>
										<option value="place" <?=getSelected("place", $_REQUEST['stype']) ?>>장소</option>
										<option value="teacher" <?=getSelected("teacher", $_REQUEST['stype']) ?>>출연(강사)</option>
										<option value="genre" <?=getSelected("genre", $_REQUEST['stype']) ?>>장르</option>
										

									</select>
								</span>
								<span class="searchWord">
									<input type="text" id="sval" name="sval" placeholder="검색어를 입력해주세요." value="<?=$_REQUEST['sval'] ?>" title="검색어 입력" onKeypress="">
									<input type="button" id="" value="검색" title="검색" onclick="goSearch();">
								</span>
								<input type="hidden" name="sstate1" id="sstate1" value="<?=$_REQUEST['sstate1']?>"/>
								<input type="hidden" name="sstate2" id="sstate2" value="<?=$_REQUEST['sstate2']?>"/>
								<input type="hidden" name="sstate3" id="sstate3" value="<?=$_REQUEST['sstate3']?>"/>

							</form>
						</div>
						<div class="bbs_list">
							<div class="gallery">
									<ul class="clear">
									<? if($rowPageCount[0] == 0) { ?>
											<li>등록된 글이 없습니다.</li>
									<? } else { ?>
											<?
											$state = "";
											$today = Date('Y-m-d');

											while ($row=mysql_fetch_assoc($result)) {
												$cls = "";
												if( strtotime($today) < strtotime($row['sday']) ){
													$state = "bf";
													$cls = "stt pr";
												}
												else if( strtotime($today) >= strtotime($row['sday']) && strtotime($today) <= strtotime($row['eday']) ){
													$state = "ing";
													$cls = "stt";
												}
												else if( strtotime($today) > strtotime($row['eday'])) {
													$state = "af";
													$cls = "stt ed";
												}else{
													$cls = "";
													$state = "";
												}
											?>
											<li class="clear">
												<a href="<?=$notice->getQueryString('view.php', $row[no], $_REQUEST)?>">
													<!--예정 : pr 종료 : ed-->
													<div class="<?=$cls?>" >
														<span>
															<?
																if($state == "bf"){ echo "예정"; }
																else if($state == "ing"){ echo "진행중"; }
																else if($state == "af") { echo "종료"; }
															?>


														</span>
													</div>
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
																	<span><?=$row['category_title']?></span>
																	<b><?=$row[title]?></b>
																</dd>
																<dd class="info">
																	<?=$row['sday']?> ~ <?=$row['eday']?>
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
