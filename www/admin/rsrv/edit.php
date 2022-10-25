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
	var $container = $('#print').clone()[0].innerHTML;
	var cssText = "";
	
	var popWindow = window.open("", "_blank", "width=700, height=800");
	popWindow.document.write("<!DOCTYPE HTML><html><head><link rel='stylesheet' href='/admin/css/reset.css'/><link href='/admin/css/content.css' rel='stylesheet'/></head><body><div class='contWrap'>"+$container+"</div></body></html>");


	popWindow.document.close();
	popWindow.focus();

	setTimeout(function(){
		popWindow.print();
		popWindow.close();
	}, 500);
}

$(function(){
	
	$("input[id^='datapicker']").each(function() {
		var _this = this.id;
		$('#'+_this).datepicker({
			dateFormat: 'yy-mm-dd',
			buttonText: "달력",
			closeText: '닫기',
			prevText: '이전달',
			nextText: '다음달',
			currentText: '오늘',
			monthNames: ['1월(JAN)','2월(FEB)','3월(MAR)','4월(APR)','5월(MAY)','6월(JUN)',
			'7월(JUL)','8월(AUG)','9월(SEP)','10월(OCT)','11월(NOV)','12월(DEC)'],
			monthNamesShort: ['1월','2월','3월','4월','5월','6월',
			'7월','8월','9월','10월','11월','12월'],
			dayNames: ['일','월','화','수','목','금','토'],
			dayNamesShort: ['일','월','화','수','목','금','토'],
			dayNamesMin: ['일','월','화','수','목','금','토'],

			minDate: '+1d',
				/*

			beforeShowDay: function(day) {
				var result;
				// 포맷에 대해선 다음 참조(http://docs.jquery.com/UI/Datepicker/formatDate)
				var holiday = holidays[$.datepicker.formatDate("yy-mm-dd",day )];
				var thisYear = $.datepicker.formatDate("yy", day);

				// exist holiday?
				if (holiday) {
				if(thisYear == holiday.year || holiday.year == "") {
				result =  [false, "date-holiday", holiday.title];
				}
				}

				if(!result) {
				switch (day.getDay()) {
				case 0: // is sunday?
				   //result = [false, "date-sunday"];
				  // break;
				case 6: // is saturday?
				   result = [true, "date-saturday"];
				   break;
				default:
				   result = [true, ""];
				   break;
				}
				}

				return result;
			}
			*/
		});
	});
});
function checkCal(obj){
		var placeChk = 0;
		for(var i = 0; i < document.frm.place.length; i++){
			if( document.frm.place[i].checked ) {
				placeChk = document.frm.place[i].value;
			}
		}

		if(placeChk == 0){
			alert('사용 시설을 먼저 선택해 주세요.');
			obj.value = '';
			return false;
		}

		if($(obj).val().trim() != ""){
			$.ajax({
				url : 'getTimes.php',
				data : {
					'rdate' : obj.value,
					'place' : placeChk,
					'no' : '<?=$_REQUEST[no]?>'
				},
				success : function(data){
					var r = data.trim();
					console.log(r);
					if(r != "fail" && r != "holiday"){
						$(obj).parent().next().children().html(r);
						$(obj).parent().next().show();
						$(obj).parent().next().next().hide();
					}else if(r == "holiday"){
						alert('대관 불가 일정입니다.\n다른 날짜를 선택해 주세요.');
						return false;
					}
					
				}
			});
		}
	}

	function checkTimes(obj){
		var placeChk = 0;
		for(var i = 0; i < document.frm.place.length; i++){
			if( document.frm.place[i].checked ) {
				placeChk = document.frm.place[i].value;
			}
		}

		if(placeChk == 0){
			alert('사용 시설을 먼저 선택해 주세요.');
			obj.value = '';
			return false;
		}

		if( $(obj).val().trim() != "" ){
			$.ajax({
				url : 'getHours.php',
				data : {
					'rtime' : obj.value,
					'rdate' : $(obj).parent().parent().prev().find('input').val().trim(),
					'place' : placeChk,
					'no' : '<?=$_REQUEST[no]?>'
				},
				success : function(data){
					var r = data.trim();
					console.log(r);
					if(r != "fail"){
						$(obj).parent().parent().next().children().html(r);
						$(obj).parent().parent().next().show();
					}
				}
			});
		}
	}
	
	$(function(){
			$.ajax({
				url : 'getTimes.php',
				data : {
					'rdate' : '<?=$data[rdate]?>',
					'place' : '<?=$data[place]?>',
					'no' : '<?=$_REQUEST[no]?>',
					'srtime' : '<?=$data[rtime]?>'
 				},
				success : function(data){
					var r = data.trim();
					console.log(r);
					if(r != "fail" && r != "holiday"){
						$('.time_sel').children().html(r);
						$('.time_sel').show();
					}else if(r == "holiday"){
						alert('대관 불가 일정입니다.\n다른 날짜를 선택해 주세요.');
						return false;
					}
					
				}
			});

			$.ajax({
				url : 'getHours.php',
				data : {
					'rtime' : '<?=$data[rtime]?>',
					'rdate' : '<?=$data[rdate]?>',
					'place' : '<?=$data[place]?>',
					'srhour' : '<?=$data[rhour]?>',
					'no' : '<?=$_REQUEST[no]?>'
				},
				success : function(data){
					var r = data.trim();
					console.log(r);
					if(r != "fail"){
						$('.hour_sel').children().html(r);
						$('.hour_sel').show();
					}
				}
			});
		
	});


	function goSave(){
		var frm = document.frm;


		var reg = /^(19[0-9][0-9]|20\d{2})(0[0-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])$/;
		if(frm.birthday.value != ""){
			if(!reg.test(frm.birthday.value)){
				alert('올바른 생년월일이 아닙니다.\n다시 입력해 주세요.');
				frm.birthday.focus();
				return false;
			}
		}

		if(confirm('작성하신 내용으로 수정하시겠습니까')){
			frm.submit();
		}else{
			return false;
		}


	}



	function setGb(obj){
		//단체
		var i = $(obj).val();
		if(i == 1){
			$('.gb2_tr').find('input').val('');
			$('.gb2_tr').hide();
			$('#name_txt').html('대표자명');
			$('#cell_txt').html('대표자 전화');
			$('#addr_txt').html('사업장 주소');
			$('.gb1_tr').css('display','table-row');
		}else{
			$('.gb1_tr').find('input').val('');
			$('.gb1_tr').hide();
			$('#name_txt').html('담당자/개인명');
			$('#cell_txt').html('연락처');
			$('#addr_txt').html('주소(개인)');
			$('.gb2_tr').css('display','table-row');
		}
	}
	function dcCheck(obj){
		if( $(obj).is(':checked') ){
			if($(obj).val() == 1){
				$('.dc_txt').hide();
				$('.dc_txt').find('select').val('');
			}else{
				$('.dc_txt').show();

			}
		}
	}

	function checkCal2(obj){
		var v = obj.value;
		$('.wraps').each(function(){
			var $target = $(this);
			if( $(this).find('.cals input').val() != "" ){
				$.ajax({
					url : 'getTimes.php',
					data : {
						'rdate' : $target.find('.cals input').val(),
						'place' : v,
					},
					success : function(data){
						var r = data.trim();
						console.log(r);
						if(r != "fail"){
							$target.find('.time_sel').children().html(r);
							$target.find('.hour_sel').hide();
							$target.find('.hour_sel').children().html('');
							$('input[name="price"]').val(0);
							//$('#price_html').html(0);


						}
						
					}
				});
			}
		});
	}

	function setPrice(){
		var hours = 0 ;
		if( $('input[name="place"]:checked').length > 0 ){
			var hours = $('select[name="rhour"]').val(); 
			$.ajax({
				url : 'getPrice.php',
				data : {
					'place' : $('input[name="place"]:checked').val(),
					'hours' : hours
				},
				success : function(data){
						console.log(data);
					$('input[name="price"]').val( data.trim() );
				}
			});
		}


		


	}
</script>
<style>
.time_sel, .hour_sel {display:inline-block;}

<?if($data['gb'] == 1){?>
.contWrap .write .wr_box table .gb1_tr{display:table-row ;}
.contWrap .write .wr_box table .gb2_tr {display:none ;}
<?}else if($data['gb'] == 2){?>
.contWrap .write .wr_box table  .gb1_tr {display:none;}
.contWrap .write .wr_box  table .gb2_tr{display:table-row;}
<?}?>
</style>
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
			<?
			//대관
				if($data['category'] == 1){
			?>
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
							<?
								for($i = 1; $i<=5; $i++){
							?>
							<input type="radio" name="place" id="pl<?=$i?>" <?=getChecked($i, $data['place'])?> value="<?=$i?>" onclick="checkCal2(this);"/><label for="pl<?=$i?>"><?=getPlaceName($i)?></label>
							<?}?>
						</td>
					</tr>
					<tr>
						<th>대관 일시</th>
						<td>
							<div class="wraps">
								<div class="cals wid200" style="display:inline-block;"><input type="text" placeholder="이용 일시" value="<?=$data['rdate']?>" name="rdate" id="datapicker1" readonly autocomplete="off" onchange="checkCal(this);"/></div>
								<div class="time_sel" style="display:none; ">
									<div class="select">
										
									</div>
								</div>
								<div class="hour_sel" style="display:none;">
									<div class="select">
									</div>
								</div>
		
							</div>
						</td>
						<th>신청 일시</th>
						<td><?=$data['registdate']?></td>
					</tr>
					<tr>
						<th>신청 상태</th>
						<td colspan="3">
							<input type="radio" name="state" id="state1" value="1" <?=getChecked(1, $data['state'])?>><label for="state1"><?=getStateName(1)?></label>
							<input type="radio" name="state" id="state5" value="5" <?=getChecked(5, $data['state'])?>><label for="state5"><?=getStateName(5)?></label>
							<input type="radio" name="state" id="state2" value="2" <?=getChecked(2, $data['state'])?>><label for="state2"><?=getStateName(2)?></label>
						</td>
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
							<?for($i = 1; $i<=2; $i++){?>
							<input type="radio" name="gb" id="gb<?=$i?>" value="<?=$i?>" <?=getChecked($i, $data['gb'])?> onclick="setGb(this);"/><label for="gb<?=$i?>"><?=getGbName($i)?></label>
							<?}?>
						</td>
					</tr>
	
					<tr class="gb1_tr">
						<th>사업자/단체명</th>
						<td colspan="3"><input type="text" name="company" id="company" value="<?=$data['company']?>" class="wid200"/></td>
					</tr>
					<tr>
						<th id="name_txt"><?=$data['gb'] == 1 ? "대표자명" : "담당자/개인명"?></th>
						<td colspan="3"><input type="text" name="name" value="<?=$data['name']?>" id="name" class="wid200"/></td>
					</tr>
					
					<tr class="gb1_tr">
						<th>사업자등록번호</th>
						<td colspan="3"><input type="text" name="reg_number" id="reg_number" value="<?=$data['reg_number']?>" class="wid200"/></td>
					</tr>
					<tr class="gb2_tr">
						<th>생년월일</th>
						<td colspan="3"><input type="text" name="birthday" value="<?=$data['birthday']?>" onkeyup="onlyNumber(this);" class="wid200" maxlength="8"/></td>
					</tr>

					<tr>
						<th id="cell_txt"><?=$data['gb'] == 1 ? "대표자 전화" : "연락처"?></th>
						<td colspan="3"><input type="text" name="cell" value="<?=$data['cell']?>" class="wid300" maxlength="13" onkeyup="isNumberOrHyphen(this);cvtPhoneNumber(this);"/></td>
					</tr>
					<tr>
						<th id="addr_txt"><?=$data['gb'] == 1 ? "사업장 주소" : "주소(개인)"?></th>
						<td colspan="3"><input type="text" name="addr" value="<?=$data['addr']?>"/></td>
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
							<?for($i = 1; $i <= 5; $i ++){?>
								<input type="radio" name="purpose" value="<?=$i?>" id="pr<?=$i?>" <?=getChecked($i, $data['purpose'])?>/><label for="pr<?=$i?>"><?=getPurposeName($i) ?></label>
							<?}?>
						</td>
					</tr>
					<tr>
						<th>활동 명</th>
						<td colspan="3">
							<input type="text" name="title" value="<?=$data['title']?>"/>
						</td>
					
					</tr>
					<tr>
						<th>활동 내용</th>
						<td colspan="3"><input type="text" name="contents" value="<?=$data['contents']?>"/></td>
					</tr>
					<tr>
						<th>참가 인원</th>
						<td colspan="3"><input type="text" name="amount" value="<?=($data['amount'])?>" onkeyup="onlyNumber(this);" class="wid200"/></td>
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
							<?for($i = 1; $i<= 2; $i++){?>
								<input type="radio" name="dc" value="<?=$i?>" id="dc<?=$i?>" <?=getChecked($i, $data['dc'])?> onclick="dcCheck(this);"/><label for="dc<?=$i?>"><?=getDcName($i) ?></label>
							<?}?>
							<span class="select dc_txt" <?if($data['dc'] == 1 ) { echo "style='display:none;'";}?>>
								<select name="dc_txt">
									<?=getDcTxtOption($data['dc_txt'])?>
								</select>
							</span>
					
						</td>
					</tr>
					<tr>
						<th>대관료</th>
						<td colspan="3">
							<input type="text" name="price" value="<?=($data['price'])?>" class="wid200" onkeyup="onlyNumber(this);"/>원
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			<?}?>
			<!-- //wr_box -->

		</div>

			<input type="hidden" name="cmd" value="edit" />
			<input type="hidden" name="no" value="<?=$data['rsrv_fk'] ?>" />
			<input type="hidden" name="detail_no" value="<?=$_REQUEST['no']?>"/>
			<?=$notice->getQueryStringToHidden($_REQUEST) ?>

		</form>
		</div>
		<!-- //write -->
		<div class="btnSet clear">
			<span class="left">
				<a href="<?=$notice->getQueryString('index.php', 0, $_REQUEST) ?>" class="btn list hoverbg">
					<span class="material-icons">reorder</span>목록
				</a>
			</span>
			<span class="right">
				<a href="javascript:;" onclick="goSave();" class="btn hoverbg">수정</a>
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
