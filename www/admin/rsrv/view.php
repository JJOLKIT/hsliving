<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Rsrv.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$notice = new Rsrv($pageRows, $tablename, $_REQUEST);
$data = $notice->getDataDetail($_REQUEST[no], $userCon);


//같이 등록한 다른 시간 구함

$list = rstToArray($notice->getGroupList($data['rsrv_fk'], $_REQUEST['no']));


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
	var popWindow = window.open("print1.php?no=<?=$_REQUEST[no]?>", "신청서", "width=827, height=1169, toolbar=no, menubar=no, status=no");
}
function goPrint2(){
	var popWindow = window.open("print2.php?no=<?=$_REQUEST[no]?>", "신청서", "width=827, height=1169, toolbar=no, menubar=no, status=no");
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
		<div id="print">
		<div class="write">

			<div class="wr_box">
				<h3>대관 내용</h3>
				<table class="row_line">
					<colgroup>
						<col width="8%">
						<col width="42%">
						<col width="8%">
						<col width="42%">
					</colgroup>
					<tbody>
					<tr>
						<th>사용시설</th>
						<td colspan="3">
							<?=getPlaceName($data['place']) ?>
						</td>
					</tr>
					<tr>
						<th>대관 일시</th>
						<td>
							<b>
							<?=$data['rdate'] ?> <?=substr($data['rtime'],0,5)?> 
							<?
								$lastTime = substr($data['rtime'],0,2) + $data['rhour'];
								
							?>
							~
							<?=$lastTime.":00"?> (<?=$data['rhour']?>시간)
							</b>

						</td>
						<th>신청 일시</th>
						<td><?=$data['registdate']?></td>
					</tr>
					<tr>
						<th>함께 신청한<br/>대관 일시</th>
						<td colspan="3">
							
			
							<?
								for($i = 0; $i < count($list); $i++){
								?>
								<p>
								<?
									$lt = substr($list[$i]['rtime'],0,2) + $list[$i]['rhour'];
									
									echo $list[$i]['rdate']." ".substr($list[$i]['rtime'],0,5)." ~ ".$lt.":00 (".$list[$i]['rhour']."시간)";
								?>
								</p>
								<?
								}
							?>
						</td>
					</tr>
					<tr>
						<th>신청 상태</th>
						<td colspan="3"><?=getStateName($data['state'])?></td>
					</tr>
					</tbody>
				</table>
			</div>
			<div class="wr_box">
				<h3>신청인 정보</h3>
				<table class="row_line">
					<colgroup>
						<col width="8%">
						<col width="42%">
						<col width="8%">
						<col width="42%">
					</colgroup>
					<tbody>
					<tr>
						<th>구분</th>
						<td colspan="3">
							<?=getGbName($data['gb'])?>
						</td>
					</tr>
					<?
						if($data['gb'] == 1){
					?>
					<tr>
						<th>사업자/단체명</th>
						<td colspan="3"><?=$data['company']?></td>
					</tr>
					<?}?>
					
					<tr>
						<th><?=$data['gb'] == 1 ? "대표자명" : "담당자/개인명"?></th>
						<td><?=$data['name']?></td>
					</tr>
					<?
						if($data['gb'] == 1){
					?>
					<tr>
						<th>사업자등록번호</th>
						<td colspan="3"><?=$data['reg_number']?></td>
					</tr>
					<?}else if($data['gb'] == 2){?>
					<tr>
						<th>생년월일</th>
						<td colspan="3"><?=$data['birthday']?></td>
					</tr>
					<?}?>

					<tr>
						<th><?=$data['gb'] == 1 ? "대표자 전화" : "연락처"?></th>
						<td colspan="3"><?=$data['cell']?></td>
					</tr>
					<tr>
						<th><?=$data['gb'] == 1 ? "사업장 주소" : "주소(개인)"?></th>
						<td colspan="3"><?=$data['addr']?></td>
					</tr>

					</tbody>
				</table>
			</div>
			<div class="wr_box">
				<h3>사용 목적</h3>
				<table class="row_line">
					<colgroup>
						<col width="8%">
						<col width="42%">
						<col width="8%">
						<col width="42%">
					</colgroup>
					<tbody>
					<tr>
						<th>구분</th>
						<td colspan="3">
							<?=getPurposeName($data['purpose']) ?>
						</td>
					</tr>
					<tr>
						<th>활동 명</th>
						<td colspan="3">
							<?=$data['title']?>
						</td>
					
					</tr>
					<tr>
						<th>활동 내용</th>
						<td colspan="3"><?=$data['contents']?></td>
					</tr>
					<tr>
						<th>참가 인원</th>
						<td colspan="3"><?=number_format($data['amount'])?></td>
					</tr>
					</tbody>
				</table>
			</div>
			<div class="wr_box">
				<h3>대관료</h3>
				<table class="row_line">
					<colgroup>
						<col width="8%">
						<col width="42%">
						<col width="8%">
						<col width="42%">
					</colgroup>
					<tbody>
					<tr>
						<th>대관료 감면여부</th>
						<td colspan="3">
							<?=getDcName($data['dc']) ?>

							<?if($data['dc'] == 2){?>
							(<?=getDcTxt($data['dc_txt'])?>)
							<?}?>
						</td>
					</tr>
					<tr>
						<th>대관료</th>
						<td colspan="3">
							<?=number_format($data['price'])?>원
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			
			<?if($data['state'] >= 3){?>
			<div class="wr_box">
				<h3>대관료 반환 정보</h3>
				<table class="row_line">
					<colgroup>
						<col width="8%">
						<col width="42%">
						<col width="8%">
						<col width="42%">
					</colgroup>
					<tbody>
					<tr>
						<th>반환신청일시</th>
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
						<td colspan="3">
							
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
			
		</div>
		<!-- //write -->
		<div class="btnSet clear">
			<span class="left">
				<a href="<?=$notice->getQueryString('index.php', 0, $_REQUEST) ?>" class="btn list hoverbg">
					<span class="material-icons">reorder</span>목록
				</a>
			</span>
			<span class="right">
				<?if($data['state'] == 4){?>
				<a href="javascript:;" onclick="goPrint2();" class="btn hoverbg">반환 신청서 출력</a>
				<?}?>
				<a href="javascript:;" onclick="goPrint();" class="btn hoverbg">신청서 출력</a>
				<a href="<?=$notice->getQueryString('edit.php', $_REQUEST['no'], $_REQUEST) ?>" class="btn hoverbg">수정</a>
				<a href="<?=$notice->getQueryString('cancel.php', $_REQUEST['no'], $_REQUEST) ?>" class="btn hoverbg">취소 신청</a>
				<a href="javascript:;" class="btn" onclick="goDelete();">삭제</a>
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
