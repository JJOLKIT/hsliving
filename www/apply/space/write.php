<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

     include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Rsrv.class.php";

     include "config.php";

     $notice = new Rsrv($pageRows, $tablename, $_REQUEST);

?>
<?
	$p = "apply";
	$sp = 0;
	$spc= 0;
	$root = $_SERVER['DOCUMENT_ROOT'];
	include_once $root."/header.php";

	if(!$loginCheck){
		echo "<script>
			if(confirm('회원만 이용 가능합니다.\\n로그인 페이지로 이동하시겠습니까?')){
				location.href = '/member/login.php?url='+encodeURIComponent(location.href);
			}else{
				location.href = 'index.php';
			}
		</script>";
		exit;
	}
?>
<script>
	function checkAll(obj){
		if($(obj).is(':checked')){
			$('.agree').prop('checked', true);
		}else{
			$('.agree').prop('checked', false);
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
				//$('#price_html').html('0');
				setPrice();
				$('#price_html').next().show();

			}else{
				$('.dc_txt').show();
				$('#price_html').html('관리자 확인 후 유선 안내 드립니다.');
				$('#price_html').next().hide();

			}
		}
	}
	function goSave(){
		var frm = document.frm;
		
		if(!frm.agree2.checked){
			alert('개인정보 수집 이용에 동의해 주세요.');
			return false;
		}
		if(!frm.agree1.checked){
			alert('사용자 준수 서약서에 동의해 주세요.');
			return false;
		}

		var placeChk = 0;
		for(var i = 0; i < frm.place.length; i++){
			if(frm.place[i].checked){
				placeChk = frm.place[i].value; 
			}
		}

		if(placeChk == 0){
			alert('사용 시설을 선택해 주세요.');
			return false;
		}

		var calcnt = 0;		
		var selcnt = 0;
		var hourcnt = 0;
		var samecnt = 0;
		$('.cal_wrap .wraps').each(function(){
			if( $(this).find('.cals').find('input').val() == ""){
				calcnt ++;
			}
			if( $(this).find('.time_sel select').length > 0 ){
				if($(this).find('.time_sel select').val() == ""){
					selcnt++;
				}
			}

			if( $(this).find('.hour_sel select').length > 0){
				if( $(this).find('.hour_sel select').val() == ""){
					hourcnt ++;
				}
			}

		});
		

		




		if( calcnt > 0 ){
			alert('이용 일시를 선택해 주세요.');
			return false;
		}

		if(selcnt > 0){
			alert('이용 시작 시간을 선택해 주세요.');
			return false;
		}

		if(hourcnt > 0){
			alert('이용 시간을 선택해 주세요.');
			return false;
		}


		var times = [];
		var tcnt = 0;
		for( var i = 0 ; i < $('.cal_wrap .wraps').length; i ++ ){

			if( $('.cal_wrap .wraps').eq(i).find('.hour_sel select').length > 0){

				
				for(var k = 0; k < $('.cal_wrap .wraps').eq(i).find('.hour_sel select').val().trim() ; k ++){
					var ttime = $('.cal_wrap .wraps').eq(i).find('.time_sel select').val();
					ttime = Number(ttime.substr(0,2));
					console.log('ttime = '+ttime);


					times[tcnt] = $('.cal_wrap .wraps').eq(i).find('.cals input').val() + '/' + (ttime + k);
					tcnt++;
					
				}

			}
		
		}



		//중복검사
		var isdup = isDup(times);

		if(isdup){
			alert('동일한 일시/시간을 선택하셨습니다.\n다시 선택해 주세요.');
			return false;
		}



		var gbChk = 0;
		for(var i = 0; i < frm.gb.length; i++){
			if(frm.gb[i].checked){
				gbChk = frm.gb[i].value;
			}
		}



		if(gbChk == 0){
			alert('신청인 구분을 선택해 주세요.');
			return false;
		}
		//단체
		else if(gbChk == 1){
			if(frm.company.value.trim() == ""){
				alert('사업자/단체명을 입력해 주세요.');
				frm.company.focus();
				return false;
			}	
			if(frm.name.value.trim() == ""){
				alert('대표자명을 입력해 주세요.');
				frm.name.focus();
				return false;
			}
			/*
			if(frm.reg_number.value.trim() == ""){
				alert('사업자등록번호를 입력해 주세요.');
				frm.reg_number.focus();
				return false;
			}
			*/
			if(frm.cell.value.trim() == ""){
				alert('대표자 전화를 입력해 주세요.');
				frm.cell.focus();
				return false;
			}
			if(frm.addr.value.trim() == ""){
				alert('사업장 주소를 입력해 주세요.');
				frm.addr.focus();
				return false;
			}
		}
		//개인
		else if(gbChk == 2){
			if(frm.name.value.trim() == ""){
				alert('담당자/개인명을 입력해 주세요.');
				frm.name.focus();
				return false;
			}
			if(frm.birthday.value.trim() == ""){
				alert('생년월일을 입력해 주세요.');
				frm.birthday.focus();
				return false;
			}else{
				var reg = /^(19[0-9][0-9]|20\d{2})(0[0-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])$/;
				if(!reg.test(frm.birthday.value)){
					alert('올바른 생년월일이 아닙니다.\n다시 입력해 주세요.');
					frm.birthday.focus();
					return false;
				}
			}

			if(frm.cell.value.trim() == ""){
				alert('연락처를 입력해 주세요.');
				frm.cell.focus();
				return false;
			}
			if(frm.addr.value.trim() == ""){
				alert('주소(개인)를 입력해 주세요.');
				frm.addr.focus();
				return false;
			}
		}
		
		var purposeChk = 0;
		for(var i = 0; i < frm.purpose.length; i++){
			if(frm.purpose[i].checked){
				purposeChk = frm.purpose[i].value;
			}
		}

		if(purposeChk == 0){
			alert('사용 목적을 선택해 주세요.');
			return false;
		}

		if(frm.title.value.trim() == ""){
			alert('활동 명을 입력해 주세요.');
			frm.title.focus();
			return false;
		}

		if(frm.contents.value.trim() == ""){
			alert('활동 내용을 입력해 주세요.');
			frm.contents.focus();
			return false;
		}

		if(frm.amount.value.trim() == ""){
			alert('참가 인원을 입력해 주세요.');
			frm.amount.focus();
			return false;
		}
		
		var dcChk = 0;
		for(var i = 0; i < frm.dc.length; i ++){
			if(frm.dc[i].checked){
				dcChk = frm.dc[i].value;
			}
		}

		if(dcChk == 0){
			alert('대관료 감면 여부를 선택해 주세요.');
			return false;
		}else if(dcChk == 2){
			if( frm.dc_txt.value == "" ){
				alert('감면 대상을 선택해 주세요.');
				frm.dc_txt.focus();
				return false;
			}
		}

		if(confirm('작성하신 내용으로 대관 신청하시겠습니까?')){
			frm.submit();
		}else{
			return false;
		}

	}


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

	function checkCal2(obj){
		var v = obj.value;
		$('.cal_wrap > .wraps').each(function(){
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
						//console.log(r);
						if(r != "fail"){
							$target.find('.time_sel').children().html(r);
							$target.find('.hour_sel').hide();
							$target.find('.hour_sel').children().html('');
							$('#price').val(0);
							$('#price_html').html(0);


						}
						
					}
				});
			}
		});
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
					'place' : placeChk
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


	function isDup(arr){
		return arr.some(function(x){
			return arr.indexOf(x) !== arr.lastIndexOf(x);
		});
	}


	function setPrice(){
		var hours = 0 ;
		if( $('input[name="place"]:checked').length > 0 ){
			$('.cal_wrap > .wraps').each(function(){
				if($(this).find('.hour_sel').length > 0 && $('.hour_sel').length == $('.cal_wrap > .wraps').length ){
					hours += Number($(this).find('.hour_sel select').val());
				}
			});
			$.ajax({
				url : 'getPrice.php',
				data : {
					'place' : $('input[name="place"]:checked').val(),
					'hours' : hours
				},
				success : function(data){
					$('#price').val(data.trim());

					if( $('input[name="dc"]:checked').val() == 1 ) {
						$('#price_html').html( numberWithCommas(data.trim()));
					
						$('#price_html').next().show();
					}else{
						$('#price_html').html('관리자 확인 후 유선 안내 드립니다.');
						$('#price_html').next().hide();
					}
				}
			});
		}

	}
</script>

<div id="sub" class="apply_write">
	<?include_once $root."/include/sub_visual.php";?>
	<div class="con_wrap">
		<div class="cont_top">
			<div class="size">
				<div class="t_wrap">
					<span>화성시 생활문화창작소</span>
					<b>공간 대관 신청</b>
				</div>
			</div>
		</div>
		<div class="con1  has_contit nbd">
			<div class="size clear">
				<div class="con_info">
					<form name="frm" id="frm" action="<?=getSslCheckUrl($_SERVER['REQUEST_URI'], 'process.php')?>" method="post" >
						<div class="check_box txt_r">
							<p>
								<input type="checkbox" id="chk_all" onclick="checkAll(this);">
								<label for="chk_all"><span>전체동의</span></label>
							</p>
						</div>
						<div class="agr_wrap">
							<div class="agr_box">
								<b>개인정보 수집·이용에 대한 동의</b>
								<div class="sc_box"><?include_once $root."/member/policy2.php";?></div>
								<div class="check_box">
									<p>
										<input type="checkbox" id="agr02" name="agree2" value="1" class="agree">
										<label for="agr02"><span>본 단체(또는 개인, 법인)는 화성시 생활문화창작소 시설을 대관함에 있어 위의 사항을 준수하겠습니다.</span></label>
									</p>
								</div>
							</div>
							<div class="agr_box mt20">
								<b>화성시 생활문화창작소 시설 사용자 준수 서약서</b>
								<div class="sc_box">
									<?include_once $root."/member/policy1.php";?>
								</div>
								<div class="check_box">
									<p>
										<input type="checkbox" id="agr01" name="agree1" value="1" class="agree">
										<label for="agr01"><span>위와 같이 개인정보를 수집·이용하는데 동의합니다.</span></label>
									</p>
								</div>
							</div>
						</div>
						<div class="boxes">
							<div class="sm_tit">
								<b><em>대관 내용</em></b>
							</div>
							<div class="tb_wrap mt20">
								<table>
									<colgroup>
										<col width="200px" />
										<col width="*" />
									</colgroup>
									<tbody>
										<tr>
											<th>
												<p>사용시설</p>
											</th>
											<td>
												<div class="radio_box rd_st1">
													<?
														for($i = 1; $i <= 5; $i++){
													?>
													<p>
														<input type="radio" name="place" id="pl0<?=$i?>" value="<?=$i?>" onclick="checkCal2(this);">
														<label for="pl0<?=$i?>"><span><?=getPlaceName($i)?></span></label>
													</p>
													<?}?>
												</div>
											</td>
										</tr>
										<tr>
											<th>
												<p>사용일</p>
											</th>
											<td>
												<div class="cal_wrap">
													<div class="wraps">
														<div class="cals sel_st"><input type="text" placeholder="이용 일시" name="rdates[]" id="datapicker1" readonly autocomplete="off" onchange="checkCal(this);"/></div>
														<div class="time_sel sel_st" style="display:none;">
															<div class="select">
															</div>
														</div>
														<div class="hour_sel sel_st" style="display:none;">
															<div class="select">
															</div>
														</div>
														<div class="btns">
															<a href="javascript:;" class="add_btn" onclick="addOption('cal_wrap');"></a>
														</div>
													</div>
													<!--
													<div class="wraps">
														<div class="cals"><input type="text" value="이용 일시" id="datapicker2" readonly/></div>
														<div class="select">
															<select>
																<option>이용시간</option>
																<option>09:00 - 10:00</option>
															</select>
														</div>
														<div class="btns">
															<a href="javascript:;" class="add_btn" onclick="addOption('cal_wrap');"></a>
															<a href="javascript:;" class="rmv_btn"  onclick="delOption(this);"></a>
														</div>
													</div>
													-->
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="boxes">
							<div class="sm_tit">
								<b><em>신청인 정보</em></b>
							</div>
							<div class="tb_wrap mt20">
								<table>
									<colgroup>
										<col width="200px" />
										<col width="*" />
									</colgroup>
									<tbody>
										<tr>
											<th>
												<p>구분</p>
											</th>
											<td>
												<div class="radio_box rd_st1">
													<?for($i = 1; $i<= 2; $i++){?>
													<p>
														<input type="radio" name="gb" id="if0<?=$i?>" value="<?=$i?>" onclick="setGb(this);">
														<label for="if0<?=$i?>"><span><?=getGbName($i)?></span></label>
													</p>
													<?}?>
													<!--
													<p>
														<input type="radio" name="info" id="if02" value="">
														<label for="if02"><span>개인</span></label>
													</p>
													-->
												</div>
											</td>
										</tr>
										<tr class="gb1_tr">
											<th>
												<p>사업자/단체명</p>
											</th>
											<td>
												<div class="md_ip"><input type="text" name="company"/></div>
											</td>
										</tr>
										<tr>
											<th>
												<p id="name_txt">대표자명</p>
											</th>
											<td>
												<div class="md_ip"><input type="text" name="name"/></div>
											</td>
										</tr>
										<tr class="gb2_tr" style="display:none;">
											<th><p>생년월일</p></th>
											<td>
												<div class="md_ip"><input type="text" name="birthday" value="" placeholder="생년월일 8자리" maxlength = "8" onkeyup="onlyNumber(this);"/><div>
											</td>
										</tr>
										<tr class="gb1_tr">
											<th>
												<p>사업자등록번호</p>
											</th>
											<td>
												<div class="md_ip"><input type="text" name="reg_number" placeholder="숫자만 입력해주세요" onkeyup="onlyNumber(this);"/></div>
											</td>
										</tr>
										<tr>
											<th>
												<p id="cell_txt">대표자 전화</p>
											</th>
											<td>
												<div class="md_ip"><input type="text" name="cell" placeholder="숫자만 입력해주세요" maxlength="15" onkeyup="isNumberOrHyphen(this);cvtPhoneNumber(this);"/></div>
											</td>
										</tr>
										<tr>
											<th>
												<p id="addr_txt">사업장 주소</p>
											</th>
											<td>
												<div class="md_ip"><input type="text" name="addr" placeholder="동 또는 도로명까지만 기입 예)화성시 봉담읍, 화성시 효행로" /></div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="boxes">
							<div class="sm_tit">
								<b><em>사용 목적</em></b>
							</div>
							<div class="tb_wrap mt20">
								<table>
									<colgroup>
										<col width="200px" />
										<col width="*" />
									</colgroup>
									<tbody>
										<tr>
											<th>
												<p>구분</p>
											</th>
											<td>
												<div class="radio_box rd_st2">
													<?
													for($i = 1; $i <= 5; $i++){
													?>
													<p>
														<input type="radio" name="purpose" id="pr0<?=$i?>" value="<?=$i?>">
														<label for="pr0<?=$i?>"><span><?=getPurposeName($i)?></span></label>
													</p>
													<?}?>
													<!--
													<p>
														<input type="radio" name="purpose" id="pr02" value="">
														<label for="pr02"><span>행사</span></label>
													</p>
													<p>
														<input type="radio" name="purpose" id="pr03" value="">
														<label for="pr03"><span>전시</span></label>
													</p>
													<p>
														<input type="radio" name="purpose" id="pr04" value="">
														<label for="pr04"><span>동호회</span></label>
													</p>
													<p>
														<input type="radio" name="purpose" id="pr05" value="">
														<label for="pr05"><span>기타</span></label>
													</p>
													-->
												</div>
											</td>
										</tr>
										<tr>
											<th>
												<p>활동 명</p>
											</th>
											<td>
												<div class="fl_ip"><input type="text" name="title"/></div>
											</td>
										</tr>
										<tr>
											<th>
												<p>활동 내용</p>
											</th>
											<td>
												<div class="fl_ip"><input type="text" name="contents"/></div>
											</td>
										</tr>
										<tr>
											<th>
												<p>참가 인원</p>
											</th>
											<td>
												<div class="sm_ip"><input type="text" name="amount" placeholder="숫자만 입력해주세요" onkeyup="onlyNumber(this);" maxlength="2"/></div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="boxes">
							<div class="sm_tit">
								<b><em>대관료</em></b>
							</div>
							<div class="tb_wrap mt20">
								<table>
									<colgroup>
										<col width="200px" />
										<col width="*" />
									</colgroup>
									<tbody>
										<tr>
											<th>
												<p>대관료 감면 여부</p>
											</th>
											<td>
												<div class="radio_box rd_st1">
													<?
														for($i = 1; $i<=2; $i++){
													?>
													<p>
														<input type="radio" name="dc" id="dc<?=$i?>" value="<?=$i?>" onclick="dcCheck(this);" <?=$i == 1 ? "checked" : ""?>>
														<label for="dc<?=$i?>"><span><?=getDcName($i)?></span></label>
													</p>
													<?}?>
													<!--
													<p>
														<input type="radio" name="place" id="pl02" value="">
														<label for="pl02"><span>감면대상</span></label>
													</p>
													-->
													<p class="md_ip select dc_txt" style="display:none;" >
														<select name="dc_txt">
															<?=getDcTxtOption()?>
														</select>
													</p>
												</div>
											</td>
										</tr>
										<tr>
											<th>
												<p>대관료</p>
											</th>
											<td>
												<p class="yl"><b id="price_html">0</b><span>원</span></p>
											</td>
										</tr>
										<tr>
											<th>
												<p>입금계좌</p>
											</th>
											<td>
											    <p><b> ※ 전화로 대관 관련 문의 후, 담당자의 확인을 받으시고 아래 계좌로 입금하시기 바랍니다.</b> </p>
												<p>신한은행 140-013-805900  (예금주 : 수원여자대학교산학협력단)</p>
												<p class="bf_p">대관 문의 및 신청자명과 입금자명이 다른 경우 <b>031-267-2050/2051</b> 로 연락 부탁드립니다.</p>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="rnd_btns mt50">
							<a href="javascript:;" onclick="goSave();"><span>대관 신청</span></a>
							<a href="index.php" class="gr"><span>취소</span></a>
						</div>


						<input type="hidden" name="cmd" value="write"/>
						<input type="hidden" name="category" value="1"/>
						<input type="hidden" name="price" id="price" value="0"/>
						<input type="hidden" id="hideprice" value="0"/>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>


<?
$holidays = array();
		for($i = 0; $i < count($result); $i++){	
			$result2 = rstToArray($s->getTodayList($result[$i]['today']));
			for($k = 0; $k < count($result2); $k++){
				array_push($holidays, $result2[$k]['startday']);
			}
		}

?>

<script>
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


var cnt = 1; 
function addOption(key){
			
			var dlen = $('.cal_wrap .wraps').length;
			//var schoolgrade = $('.cal_wrap .wraps').length;
			if(dlen > 3){
				alert('더 이상 추가할 수 없습니다.');
				return false;
			}
			dlen ++;
			//schoolgrade ++;
			
			
			/*
			var Dates ='<div class="wraps">';
			Dates +='<div class="cals">';
			Dates +='<input type="text" value="이용 일시" id="datapicker'+dlen+'" readonly/>';
			Dates +='</div>';
			Dates +='<div class="select">';
			Dates +='<select>';
			Dates +='<option>이용시간</option>';
			Dates +='<option>1시간</option>';
			Dates +='<option>2시간</option>';
			Dates +='<option>3시간</option>';
			Dates +='<option>4시간</option>';
			Dates +='</select>';
			Dates +='</div>';
			Dates +='<div class="btns">';
			Dates +='<a href="javascript:;" class="add_btn" onclick="addOption(\'cal_wrap\');"></a>';
			Dates +='<a href="javascript:;" class="rmv_btn" onclick="delOption(this);"></a>';
			Dates +='</div>';
			Dates +='</div>';
			*/

			
			Dates = '<div class="wraps">'
			+	'<div class="cals"><input type="text" placeholder="이용 일시" name="rdates[]" id="datapicker'+dlen+'" readonly autocomplete="off" onchange="checkCal(this);"/></div>'
			+	'<div class="time_sel" style="display:none;">'
			+		'<div class="select">'
			+		'</div>'
			+	'</div>'
			+	'<div class="hour_sel" style="display:none;">'
			+		'<div class="select">'
			+		'</div>'
			+	'</div>'
			+	'<div class="btns">'
			+		'<a href="javascript:;" class="add_btn" onclick="addOption(\'cal_wrap\');"></a>'
			+		'<a href="javascript:;" class="rmv_btn" onclick="delOption(this);"></a>';
			+	'</div>'
			+'</div>';
			


			$('.'+key).append(Dates);
			$( "#datapicker"+dlen).datepicker({
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
			});

			cnt++;

	}
	function delOption(obj){
		var idx = $(obj).parent().parent().index();
		$('.cal_wrap .wraps').eq(idx).remove();
	}
</script>
<?
	include_once $root."/footer.php";
?>