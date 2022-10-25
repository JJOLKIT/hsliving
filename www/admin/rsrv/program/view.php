<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Rsrv2.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$notice = new Rsrv2($pageRows, $tablename, $_REQUEST);
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

function goPrint(){
	var popWindow = window.open("print1.php?no=<?=$data[no]?>", "신청서", "width=827, height=1169, toolbar=no, menubar=no, status=no");
}
function goPrint2(){
	var popWindow = window.open("print2.php?no=<?=$data[no]?>", "신청서", "width=827, height=1169, toolbar=no, menubar=no, status=no");
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
						<th>신청일</th>
						<td colspan="3">
							<?=$data['registdate'] ?>
						</td>
						
					</tr>
					<tr>
						<th>프로그램명</th>
						<td><?=$data['title']?></td>
						<th>진행 일시</th>
						<td><?=$data['stday']?> (<?=substr($data['rtime'], 0, 5)?>)</td>
					</tr>
					<tr>
						<th>신청 상태</th>
						<td colspan="3">
							<?=getStateName($data['state'])?>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			<!-- //wr_box -->
			<div class="wr_box">
				<h3>신청인 정보</h3>
				<table>
					<colgroup>
						<col width="8%">
						<col width="*">
					</colgroup>
					<tbody>
					<tr>
						<th>성명</th>
						<td>
							<?=$data['name']?>
						</td>
					</tr>
					<tr>
						<th>성별</th>
						<td><?=getGenderName($data['gender'])?></td>
					</tr>
					<tr>
						<th>연락처</th>
						<td><?=$data['cell']?></td>
					</tr>
					<tr>
						<th>생년월일</th>
						<td><?=$data['birthday']?></td>
					</tr>
					<tr>
						<th>주소</th>
						<td><?=$data['addr']?></td>
					</tr>
					<tr>
						<th>동반인</th>
						<td>
							<?
								if($data['together'] == 0){
									echo "동반인 없음";
								}
								else if($data['together'] == 1){
									echo "1인";
								}else if($data['together'] == 2){
									echo "2인";
								}
							?>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			<div class="wr_box">
				<h3>프로그램료</h3>
				<table class="row_line">
					<colgroup>
						<col width="8%">
						<col width="42%">
						<col width="8%">
						<col width="42%">
					</colgroup>
					<tbody>
					<tr>
						<th>프로그램료 감면여부</th>
						<td colspan="3">
							<?=getDcName($data['dc']) ?>

							<?if($data['dc'] == 2){?>
							(<?=getDcTxt($data['dc_txt'])?>)
							<?}?>
						</td>
					</tr>
					<tr>
						<th>프로그램료</th>
						<td colspan="3">
							<?=number_format($data['price'])?>원
						</td>
					</tr>
					</tbody>
				</table>
			</div>
		
			<?if( $data['state'] >= 3 ){?>
			<div class="wr_box">
				<h3>취소 반환 정보</h3>
				<table class="row_line">
					<colgroup>
						<col width="8%">
						<col width="42%">
						<col width="8%">
						<col width="42%">
					</colgroup>
					<tbody>
					<tr>
						<th>반환일</th>
						<td colspan="3">
							<?=$data['refund_date']?>
						</td>
					</tr>
					<tr>
						<th>반환 기준 내용</th>
						<td colspan="3">
							<?=getCancelName($data['refund_option'])?>
						</td>
					</tr>
					<tr>
						<th>반환액</th>
						<td colspan="3">
							<?if($data['refund_price'] == ""){
								echo number_format($data['price']);
							}else{
								echo number_format($data['refund_price']);
							}?>원
						</td>
					</tr>
					<tr>
						<th>반환 계좌 정보</th>
						<td>
							<p>은행명 <?=$data['refund_bank']?></p>
							<p style="margin-top:5px;">예금주 <?=$data['refund_name']?></p>
							<p style="margin-top:5px;">계좌번호 <?=$data['refund_account']?></p>
						</td>
					</tr>
					<tr>
						<th>반환 사유</th>
						<td>
							<?=$data['refund_reason']?>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			<?}?>
			<!-- //wr_box -->
			<? if ($isComment) { ?>
				<? include $_SERVER['DOCUMENT_ROOT']."/admin/board/comment/comment.php" ?><!-- 댓글 -->
			<? } ?>
		</div>
		<!-- //write -->
		<div class="btnSet clear">
			<span class="left">
				<a href="<?=$notice->getQueryString('list.php', 0, $_REQUEST) ?>" class="btn list hoverbg">
					<span class="material-icons">reorder</span>목록
				</a>
			</span>
			<span class="right">
				<?if( $data['state'] == 4 ){?>
				<a href="javascript:;" onclick="goPrint2();" class="btn hoverbg">반환신청서 출력</a>
				<?}?>
				
				<a href="javascript:;" onclick="goPrint();" class="btn hoverbg">수강신청서 출력</a>
			
				<a href="<?=$notice->getQueryString('edit.php', $data['no'], $_REQUEST) ?>" class="btn hoverbg">수정</a>
				<a href="<?=$notice->getQueryString('cancel.php', $_REQUEST['no'], $_REQUEST) ?>" class="btn hoverbg">취소 신청</a>
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
