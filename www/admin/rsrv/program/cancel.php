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
	jQuery(window).load(function(){
		// 달력
		initCal({id:"refund_date",type:"day",today:"y",timeYN:"y"});
	});
function goDelete() {
	if (confirm("삭제하시겠습니까?")) {
		location.href="<?=$notice->getQueryString("process.php", $data['no'], $_REQUEST) ?>&cmd=delete";
	}
}


function goSave(){
	var frm = document.frm;
	
	/* if(frm.refund_option.value.trim() == ""){
		alert('반환 기준 내용을 선택해 주세요.');
		frm.refund_option.focus();
		return false;
	} */
	if(frm.refund_bank.value.trim() == ""){
		alert('은행명을 입력해 주세요.');
		frm.refund_bank.focus();
		return false;
	}
	if(frm.refund_name.value.trim() == ""){
		alert('예금주를 입력해 주세요.');
		frm.refund_name.focus();
		return false;
	}
	if(frm.refund_account.value.trim() == ""){
		alert('계좌번호를 입력해 주세요.');
		frm.refund_account.focus();
		return false;
	}
	if(frm.refund_reason.value.trim() == ""){
		alert('반환사유를 입력해 주세요.');
		frm.refund_reason.focus();
		return false;
	}

	if(confirm('저장하시겠습니까?')){
		frm.submit();
		return false;
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
		<div id="print">
			<form method="post" name="frm" id="frm" action="<?=getSslCheckUrl($_SERVER['REQUEST_URI'], 'process.php')?>" enctype="multipart/form-data">
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
								<th>신청일</th>
								<td colspan="3">
									<?=$data['registdate'] ?>
								</td>
								
							</tr>
							<tr>
								<th>프로그램명</th>
								<td><?=$data['title']?></td>
								<th>진행 일시</th>
								<td><?=$data['rdate']?> (<?=substr($data['rtime'], 0, 5)?>)</td>
							</tr>
							<tr>
								<th>신청 상태</th>
								<td colspan="3">
									<input type="radio" name="state" id="state3" value="3" <?=getChecked(3,$data['state'])?>/>
									<label for="state3" >취소 신청</label>
									<input type="radio" name="state" id="state4" value="4" <?=getChecked(4,$data['state'])?>/>
									<label for="state4" >취소 확정</label>
								</td>
							</tr>
							</tbody>
						</table>
					</div>
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
		




					<div class="wr_box">
						<h3>프로그램료 반환 정보</h3>
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
									<p class="calendar">
										<input type="text" name="refund_date" id="refund_date" value="<?=$data['refund_date']?>" class="dateTime" readonly autocomplete="off"/>
										<span id="Calrefund_dateIcon" style="height:22px; line-height:22px;vertical-align:middle;">
											<span class="material-icons">calendar_month</span>
											<!--<img src="/admin/img/ico_calendar.gif" id="CalregistdateIconImg" style="cursor:pointer;"/>-->
										</span>
									</p>
								</td>
							</tr>
							<tr>
								<th>반환 기준 내용</th>
								<td colspan="3">
									<span class="select">
									<select name="refund_option">
										<?=getCancelOption($data['refund_option'])?>
									</select>
									</span>
								</td>
							</tr>
							<tr>
								<th>반환액</th>
								<td colspan="3">
									<input type="text" name="refund_price" value="<?
									if($data['refund_price'] == ""){
										echo $data['price'];
									}else{
										echo $data['refund_price'];
									}
									?>" class="wid200"/>원
								</td>
							</tr>
							<tr>
								<th>반환 계좌 정보</th>
								<td colspan="3">
									
									<p>은행명 <input type="text" name="refund_bank" value="<?=$data['refund_bank']?>" class="wid200"/></p>
									<p style="margin-top:5px;">예금주 <input type="text" name="refund_name" value="<?=$data['refund_name']?>" class="wid200"/></p>
									<p style="margin-top:5px;">계좌번호 <input type="text" name="refund_account" value="<?=$data['refund_account']?>" class="wid200"/></p>

								</td>
							</tr>
							<tr>
								<th>반환 사유</th>
								<td colspan="3">
									<input type="text" name="refund_reason" value="<?=$data['refund_reason']?>"/>
								</td>
							</tr>
							</tbody>
						</table>
					</div>
					<!-- //wr_box -->

				</div>

				<input type="hidden" name="cmd" value="cancel"/>
				<input type="hidden" name="no" value="<?=$data['no']?>"/>
			</form>
		</div>
		<!-- //write -->
		<div class="btnSet clear">
			<span class="left">
				<a href="<?=$notice->getQueryString('list.php', 0, $_REQUEST) ?>" class="btn list hoverbg">
					<span class="material-icons">reorder</span>목록
				</a>
			</span>
			<span class="right">
				<a href="<?=$notice->getQueryString('view.php', $_REQUEST['no'], $_REQUEST) ?>" class="btn hoverbg">취소</a>
				<a href="javascript:;" onclick="goSave();" class="btn hoverbg">저장</a>
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
