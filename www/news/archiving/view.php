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
	$data = ($notice->getData($_REQUEST[no], $userCon));
?>
<?	
	$p = "news";
	$sp = 3;
	$spc= 0;
	$root = $_SERVER['DOCUMENT_ROOT'];
	include_once $root."/header.php";
?>
<div id="sub" class="list_view">
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
					<div class="bbs con_info">
							<div class="view">
									<div class="title">
											<dl>
													<dt><?=$data['title']?></dt>
													<dd><span class="name">작성자</span><?=escape_html($data['name'])?></dd>
							<dd><span class="date">날짜</span><?=getYMD($data['registdate'])?></dd>
							<dd><span class="hit">조회수</span><?=$data[readno]?></dd>
											</dl>
									</div>
									<!-- //title---> 
									<div class="cont"> <?=$data['contents']?> </div>
									<!-- //cont--->
					<div class="link">
						<? if ($data['filename']) { ?>
						<dl>
							<dt class="file">첨부파일</dt>
							<dd><a href="/lib/download.php?path=<?=$uploadPath?>&vf=<?=$data['filename']?>&af=<?=urlencode($data['filename_org'])?>" target="_blank"><?=$data[filename_org]?></a></dd>
						</dl>
						<? } ?>
						<!-- //file--->
						<?if($data['relation_url']){?>
						<dl>
							<dt class="url">관련링크</dt>
							<dd><a href="<?=$data['relation_url']?>" target="_blank" title="새 창 열림"><?=$data['relation_url']?></a></dd>
						</dl>
						<?}?>
						<!-- //link--->
					</div>
									<? if ($isComment) { ?>
									<? include_once $_SERVER['DOCUMENT_ROOT']."/include/comment/comment.php" ?>
									<? } ?>
					<?
						$rownum = $notice->getRowNum($_REQUEST);
						$next = $notice->getNextRowNum($_REQUEST, $rownum);
						$prev = $notice->getPrevRowNum($_REQUEST, $rownum);

						///////////////////////////////////////////////////////////////////////////
						/*

							이전글 다음글만 prev || next에 걸리도록 
							목록버튼이 안에 들어가면 안나옴.
							주의

						*/
						///////////////////////////////////////////////////////////////////////////
						if($prev || $next){
					?>
					<div>
						<ul class="viewNavi">
							<?if($next){?>
							<li  class="next"><a href="<?=$notice->getQueryString('view.php', $next[next_no], $_REQUEST)?>"><?=$next['next_title']?></a></li>
							<?}?>
							<?if($prev){?>
							<li  class="prev"><a href="<?=$notice->getQueryString('view.php', $prev[prev_no], $_REQUEST)?>"><?=$prev['prev_title']?></a></li>
							<?}?>
						</ul>
					</div>
					<?}?>
						<div class="rnd_btns mt50">
							<a href="<?=$notice->getQueryString('index.php', 0, $_REQUEST) ?>" ><span>목록으로</span></a>
						</div>
							</div>
							<!-- //view---> 
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
