<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/GalleryCt.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$notice = new GalleryCt($pageRows, $tablename, $category_tablename, $_REQUEST);
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
			<h2><?=$pageTitle?> 보기</h2>
		</div>
		<div class="write">
			<div class="wr_box">
				<h3>등록정보</h3>
				<table>
					<colgroup>
						<col width="8%">
						<col width="42%">
						<col width="8%"/>
						<col width="42%"/>
					</colgroup>
					<tbody>
						<tr>
							<th>작성일</th>
							<td colspan="3">
								<?=$data['registdate'] ?>
							</td>
						</tr>

						<tr>
							<th>카테고리</th>
							<td>
								<?=$data['category_title']?>
							</td>
							<th>진행 일시</th>
							<td><?=$data['stday']?><!-- ~ <?=$data['etday']?>--></td>
						</tr>
						
						<tr>
							<th>노출설정</th>
							<td colspan="3">
								<?=$data['display'] == 1 ? "노출" : "미노출"?>
								<?if($data['top'] == 1){?>탑공지 (상단노출) <?}?>
							</td>
						</tr>

					</tbody>
				</table>
			</div>
			<div class="wr_box">
				<h3>등록정보</h3>

				<table>
					<colgroup>
						<col width="8%">
						<col width="*">
					</colgroup>
					<tbody>
					<tr>
						<th>프로그램 제목</th>
						<td>
							<?=$data['title']?>
						</td>
					</tr>
					<tr>
						<th>장소</th>
						<td><?=$data['place']?></td>
					</tr>
					<tr>
						<th>출연(강사)</th>
						<td><?=$data['teacher']?></td>
					</tr>
					<tr>
						<th>보조출연(보조강사)</th>
						<td><?=$data['genre']?></td>
					</tr>
					<tr>
						<th>참여가능 연령</th>
						<td><?=$data['age']?></td>
					</tr>
					<tr>
						<th>모집 기간</th>
						<td>
							<?=$data['sday']?>
							~
							<?=$data['eday']?>
						</td>
					</tr>
					<tr>
						<th>시간</th>
						<td><?=$data['rtime']?></td>
					</tr>
					<tr>
						<th>참여비</th>
						<td><?=number_format($data['price'])?>원</td>
					</tr>
					<tr>
						<th>인원</th>
						<td><?=$data['amount']?></td>
					</tr>
					<tr>
						<th>동반인</th>
						<td>
							<?
								if($data['together'] == 3){ echo "선택 불가"; }
								else if($data['together'] == 1){ echo "1인"; }
								else if($data['together'] == 2){ echo "2인"; }
							?>
			
						</td>
					</tr>
					<tr>
						<th>내용</th>
						<td>
							<?=$data['contents']?>
						</td>
					</tr>
					<!--<tr>
						<th>유의사항</th>
						<td>
							<?=$data['contents2']?>
						</td>
					</tr>-->
					
					
					<tr>
						<th>이미지</th>
						<td>
							<? if ($data[imagename]) { ?>
							<img src="<?=$uploadPath?><?=$data[imagename]?>" alt="<?=$data[image_alt]?>" style="max-width:500px;"/><br/>
							<a href="/lib/download.php?path=<?=$uploadPath?>&vf=<?=$data['imagename']?>&af=<?=urlencode($data['imagename_org'])?>" target="_blank"><?=$data[imagename_org]?></a>
							<? } ?>
						</td>
					</tr>

					<tr>
						<th>관련링크</th>
						<td><?=$data['relation_url'] != "" ? "<a href='".$data['relation_url']."' target='_blank'>".$data['relation_url']."</a>" : ""?></td>
					</tr>
					
					</tbody>
				</table>
			</div>
			<!-- //wr_box -->
		</div>
		<!-- //write -->
		<div class="btnSet clear">
			<span class="left">
				<a href="<?=$notice->getQueryString('index.php', 0, $_REQUEST) ?>" class="btn list hoverbg">
					<span class="material-icons">reorder</span>목록
				</a>
			</span>
			<span class="right">
				<a href="<?=$notice->getQueryString('copy.php', $data['no'], $_REQUEST) ?>" class="btn hoverbg">복사</a>
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
