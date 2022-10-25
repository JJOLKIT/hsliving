<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Visual.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$notice = new Visual($pageRows, $tablename, $_REQUEST);
$data = $notice->getData($_REQUEST[no], $userCon);
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<script>
function goDelete() {
	if (confirm("삭제하시겠습니까?")) {
		location.href="<?=$notice->getQueryString("process.php", $data['no'], $_REQUEST) ?>&cmd=delete";
	}
}
</script>
</head>


<body>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>
<!-- s:warp -->
	<div class="contWrap">
		<div class="titWrap">
			<h2><?=$pageTitle ?> 글보기</h2>
		</div>
		<div class="write">
			<div class="wr_box">
				<h3>등록정보</h3>
				<table class="row_line">
					<colgroup>
						<col width="8%">
						<col width="42%">
						<col width="8%">
						<col width="42%">
					</colgroup>
					<tbody>
					<tr>
						<th>작성일</th>
						<td>
							<?=$data['registdate'] ?>
						</td>
						<th>작성자</th>
						<td>
							<?=$data['name'] ?>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			<!-- //wr_box -->
			<div class="wr_box">
				<h3>게시글</h3>
				<table>
					<colgroup>
						<col width="8%">
						<col width="*">
					</colgroup>
					<tbody>
					<tr>
						<th>노출여부</th>
						<td>
							<?=getDisplay($data['main']);?>
						</td>
					</tr>
					<tr>
						<th>서브 타이틀</th>
						<td>
							<?=escape_html($data['subtitle']) ?>
						</td>
					</tr>
					<tr>
						<th>타이틀</th>
						<td>
							<?=escape_html($data['title']) ?>
						</td>
					</tr>
					<tr>
						<th>신청 기간</th>
						<td>
							<?
								if(!$data['stday'] || $data['stday'] != '0000-00-00'){
							?>
							<?=escape_html($data['stday']) ?>
							~
							<?=escape_html($data['etday']) ?>
							<?}?>
						</td>
					</div>
					<tr>
						<th>PC 이미지</th>
						<td>
							<? if ($data[imagename]) { ?>
								<img src="<?=$uploadPath?><?=$data[imagename]?>" style="max-width:300px !important;"/>
							<? } ?>
						</td>
					</tr>
					<tr>
						<th>모바일 이미지</th>
						<td>
							<? if ($data[filename]) { ?>
								<img src="<?=$uploadPath?><?=$data[filename]?>" style="max-width:300px !important;"/>
							<? } ?>
						</td>
					</tr>
					<tr>
						<th>관련링크</th>
						<td>
							<a href="<?=escape_html($data['relation_url'])?>" title="새 창 열림" target="_blank"><?=escape_html($data['relation_url'])?></a>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			<!-- //wr_box -->
			<? if ($isComment) { ?>
				<? include $_SERVER['DOCUMENT_ROOT']."/admin/board/comment/comment.php" ?><!-- 댓글 -->
			<? } ?>
		</div>
		<!-- //write -->
		<div class="btnSet clear">
			<span class="left">
				<a href="<?=$notice->getQueryString('index.php', 0, $_REQUEST) ?>" class="btn list hoverbg">
					<span class="material-icons">reorder</span>목록
				</a>
			</span>
			<span class="right">
				<a href="<?=$notice->getQueryString('edit.php', $data['no'], $_REQUEST) ?>" class="btn hoverbg">수정</a>
				<a href="javascript:;" class="btn hoverbg" onclick="goDelete();">삭제</a>
			</span>
		</div>
		<!-- //btnSet -->
	</div>
	<!-- //contents -->
</div>
<!-- e:warp --> 
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</body>
</html>
