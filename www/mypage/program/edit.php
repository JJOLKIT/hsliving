<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Rsrv2.class.php";
include "config.php";

$today = getToday();
$oneMonth = getMonthDateAdd(-1, $today);
$twoMonth = getMonthDateAdd(-2, $today);
$threeMonth = getMonthDateAdd(-3, $today);


$notice = new Rsrv2($pageRows, $tablename, $_REQUEST);
$data = $notice->getData($_REQUEST['no'], false);

?>
<?
	$p = "mypage";
	$sp = 1;
	$spc= 0;
	$root = $_SERVER['DOCUMENT_ROOT'];
	include_once $root."/header.php";
	if(!$loginCheck){
		echo "
			<script>
			if(confirm('로그인이 필요한 서비스입니다.\\n로그인 페이지로 이동하시겠습니까?')){
				location.href = '/member/login.php?url='+encodeURIComponent(location.href);
			}else{
				location.href = '/';
			}
			</script>
		";
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

	function checkPop(){
		if( $('#rdate').val() == ""){
			alert('날짜를 먼저 선택해 주세요.');
			return false;
		}else{
				<?if($data['no']){?>
					setProgram();
				<?}?>
			 openPopup('popup');
		}
	}

	function goSave(){
		var frm = document.frm;

		/* if(!frm.agree1.checked){
			alert('개인정보 수집 이용에 동의해 주세요.');
			return false;
		}
		if(!frm.agree2.checked){
			alert('수강자 준수사항에 동의해 주세요.');
			return false;
		}

		if(!frm.agree3.checked){
			alert('수강자 준수사항에 동의해 주세요.');
			return false;
		}
		if(!frm.agree4.checked){
			alert('수강자 준수사항에 동의해 주세요.');
			return false;
		} */
		
		if( frm.rdate.value.trim() == "" ){
			alert('날짜를 선택해 주세요.');
			frm.rdate.focus();
			return false;
		}

		if( frm.program_fk.value.trim() == "" || frm.rtime.value == "" ){
			alert('프로그램을 선택해 주세요.');
			return false;
		}

		if(frm.name.value.trim() == ""){
			alert('성명을 입력해 주세요.');
			frm.name.focus();
			return false;
		}

		var genderchk = 0;
		for(var i = 0; i < frm.gender.length; i ++){
			if(frm.gender[i].checked){
				genderchk = frm.gender[i].value;
			}
		}

		if(genderchk == 0){
			alert('성별을 선택해 주세요.');
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
			alert('주소를 입력해 주세요.');
			frm.addr.focus();
			return false;
		}


		if( $('#together_tr').data('show') == 1 ){
			if( frm.together.value == "" ){
				alert('동반인 여부를 선택해 주세요.');
				frm.together.focus();
				return false;
			}
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

		if(confirm('작성하신 내용으로 신청하시겠습니까?')){
			frm.submit();
		}else{
			return false;
		}

	}

	function setProgram(){
		var rdate = '';
		var title = '';
		if($('#rdate').val() != "" || $('#sval').val() != ""){
			rdate = $('#rdate').val();
			title = $('#sval').val();



			$.ajax({
				url : 'getProgram.php',
				data : {
					'srdate' : rdate,
					'stitle' : title
				},
				success : function(data){
					var r = data.trim();
					$('#program_list').html(r);
					$('#rtitle').val('');
					$('#program_fk').val('');
				}
			});
		}
	}

	function setFk(){
		if( $('.program_fks:checked').length == 0){
			alert('프로그램을 선택해 주세요.');
			return false;
		}else{
			var title = $('.program_fks:checked').data('title');
			var date = $('.program_fks:checked').data('rdate');
			var time = $('.program_fks:checked').data('rtime');
			if(confirm(title + ' ' +date + ' ' +time + ' 선택하시겠습니까?')){
				$('#rtime').val(time);
				$('#program_fk').val( $('.program_fks:checked').val() );
				$('#rtitle').val( title );
				closePopup('popup');


				$.ajax({
					url : 'getTogether.php',
					data : {
						'no' : $('#program_fk').val()
					},
					success : function(data){
						var r = data.trim();
						r = JSON.parse(r);

						console.log(r);
						if(r.together == "no"){
							$('#together_tr').hide();
							$('#together_tr').attr('data-show','0');
						}else{
							$('#together_tr select').html(r.together);
							$('#together_tr').show();
							$('#together_tr').attr('data-show','1');
						}
						
						
						$('#price').val( r.price );

						if( $('input[name="dc"]:checked').val() == 2){
							$('#price_html').html('관리자 확인 후 유선 안내 드립니다.');
							$('#price_html').next().hide();
						}else{
							$('#price_html').html( numberWithCommas( r.price ) );
							$('#hideprice').val( r.price );
							$('#price_html').next().show();
						}

					}
				});
			}
		}
	}




	function dcCheck(obj){
		if( $(obj).is(':checked') ){
			if($(obj).val() == 1){
				$('.dc_txt').hide();
				$('.dc_txt').find('select').val('');
				<?if($pbool){?>
					$('#price_html').html('<?=number_format($data[price])?>');
				<?}else{?>
				//$('#price_html').html('0');
				$('#price_html').html( numberWithCommas( Number($('#hideprice').val()) ) );
				
				<?}?>
				$('#price_html').next().show();
			}else{
				$('.dc_txt').show();
				$('#price_html').html('관리자 확인 후 유선 안내 드립니다.');
				
				$('#price_html').next().hide();



			}
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
					<b>프로그램 신청</b>
				</div>
			</div>
		</div>
		<div class="con1  has_contit nbd">
			<div class="size clear">
				<div class="con_info">
					<form name="frm" id="frm" action="<?=getSslCheckUrl($_SERVER['REQUEST_URI'], 'process.php')?>" method="post" >
						<!-- <div class="check_box txt_r">
							<p>
								<input type="checkbox" id="chk_all" onclick="checkAll(this);">
								<label for="chk_all"><span>전체동의</span></label>
							</p>
						</div>
						<div class="agr_wrap">
							<div class="agr_box">
								<b>개인정보 수집·이용에 대한 동의</b>
								<div class="sc_box"><?include_once $root."/member/policy1.php";?></div>
								<div class="check_box">
									<p>
										<input type="checkbox" id="agr01" name="agree1" class="agree">
										<label for="agr01"><span>위와 같이 개인정보를 수집·이용하는데 동의합니다.</span></label>
									</p>
								</div>
							</div>
							<div class="agr_box mt20">
								<b>수강자 준수사항</b>
								<div class="check_box">
									<p>
										<input type="checkbox" id="agr02" name="agree2" class="agree">
										<label for="agr02"><span>생활문화창작소의 프로그램 운영 규칙을 준수하겠습니다.</span></label>
									</p>
									<p>
										<input type="checkbox" id="agr03" name="agree3" class="agree">
										<label for="agr03"><span>프로그램 운영자 (관리자, 강사)의 지시를 잘 따르겠습니다.</span></label>
									</p>
									<p>
										<input type="checkbox" id="agr04" name="agree4" class="agree">
										<label for="agr04"><span>부주의로 인하여 시설물 또는 집기류를 손상한 경우에는 배상할 것을 약속합니다.</span></label>
									</p>
								</div>
							</div>
						</div> -->
						<div class="boxes">
							<div class="sm_tit">
								<b><em>프로그램 내용</em></b>
							</div>
							<div class="tb_wrap mt20">
								<table>
									<colgroup>
										<col width="200px" />
										<col width="*" />
									</colgroup>
									<tbody>
										<tr>
											<th><p>날짜</p></th>
											<td>
												<div class="md_ip datepicks"><input type="text" name="rdate" value="<?=$data['stday']?>" placeholder="날짜 선택" id="rdate" readonly autocomplete="off" onchange="setProgram();"/></div>
											</td>
										</tr>
										<tr>
											<th>
												<p>프로그램</p>
											</th>
											<td>
												<div class="md_box has_btn">
													<div class="md_ip"><input type="text" id="rtitle" readonly autocomplete="off" value="<?=$data['title']?>"/></div>
													<?if(!$pbool){?>
													<a href="javascript:;" onclick="checkPop();">프로그램 검색</a>
													<?}?>
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
												<p>성명</p>
											</th>
											<td>
												<div class="md_ip"><input type="text" name="name" value="<?=$data['name']?>"/></div>
											</td>
										</tr>
										<tr>
											<th>
												<p>성별</p>
											</th>
											<td>
												<div class="radio_box">
													<p>
														<input type="radio" name="gender" id="gd01" value="1" <?=getChecked(1,$data['gender'])?>>
														<label for="gd01"><span>남</span></label>
													</p>
													<p>
														<input type="radio" name="gender" id="gd02" value="2" <?=getChecked(2,$data['gender'])?>>
														<label for="gd02"><span>여</span></label>
													</p>
												</div>
											</td>
										</tr>
										
										<tr>
											<th>
												<p>연락처</p>
											</th>
											<td>
												<div class="md_ip"><input type="text" name="cell" placeholder="숫자만 입력해주세요" maxlength="15" onkeyup="isNumberOrHyphen(this);cvtPhoneNumber(this);" value="<?=$data['cell']?>"/></div>
											</td>
										</tr>
										<tr>
											<th>
												<p>생년월일</p>
											</th>
											<td>
												<div class="md_ip"><input type="text" name="birthday" placeholder="20200101" maxlength = "8" onkeyup="onlyNumber(this);" value="<?=$data['birthday']?>"/></div>
											</td>
										</tr>
										<tr>
											<th>
												<p>주소</p>
											</th>
											<td>
												<div class="md_ip"><input type="text" name="addr" placeholder="동 또는 도로명까지만 기입 예)화성시 봉담읍, 화성시 효행로" value="<?=$data['addr']?>"/></div>
											</td>
										</tr>
										
										<tr id="together_tr" style="display:none;" data-show="0">
											<th>
												<p>동반인 신청</p>
											</th>
											<td>
												<div class="sm_ip">
													<select name="together">
														<option value="0" <?=getSelected(0,$data['together'])?>>동반인 없음</option>
														<option value="1" <?=getSelected(1,$data['together'])?>>1인</option>
														<option value="2" <?=getSelected(2,$data['together'])?>>2인</option>
													</select>
												</div>
											</td>
										</tr>
											
										<?if( !empty($_POST['program_fk']) && is_numeric($_POST['program_fk']) ){?>
										<tr style="<?if($data['together'] == 3){ echo "display: none;";}?>">
											<th>
												<p>동반인 신청</p>
											</th>
											<td>
												<div class="sm_ip">
													<select name="together">
														<?if($data['together']==1){?>
														<option value="0" <?=getChecked(0, $data['together'])?>>동반인 없음</option>
														<option value="1" <?=getChecked(1, $data['together'])?>>1인</option>
														<?}?>

														<?if($data['together']==2){?>
														<option value="0" <?=getChecked(0, $data['together'])?>>동반인 없음</option>
														<option value="1" <?=getChecked(1, $data['together'])?>>1인</option>
														<option value="2" <?=getChecked(2, $data['together'])?>>2인</option>
														<?}?>
													</select>
												</div>
											</td>
										</tr>
										<?}?>

									</tbody>
								</table>
							</div>
						</div>
						
						<div class="boxes">
							<div class="sm_tit">
								<b><em>프로그램료</em></b>
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
												<p>프로그램료 감면 여부</p>
											</th>
											<td>
												<div class="radio_box">
													<?
														for($i = 1; $i<=2; $i++){
													?>
													<p>
														<input type="radio" name="dc" id="dc<?=$i?>" value="<?=$i?>" onclick="dcCheck(this);" <?=getChecked($i, $data['dc'])?>>
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
															<?=getDcTxtOption($data['dc_txt'])?>
														</select>
													</p>
												</div>
											</td>
										</tr>
										<tr>
											<th>
												<p>프로그램료</p>
											</th>
											<td>
												<p class="yl"><b id="price_html"><?=number_format($data['price'])?></b><span>원</span></p>
											</td>
										</tr>
										<tr>
											<th>
												<p>입금계좌</p>
											</th>
											<td>
												<p><b> ※ 신청 다음 날 까지 입금해 주셔야 최종 확정됩니다.</b> </p>
												<p>신한은행 140-013-805900 (예금주 : 수원여자대학교산학협력단)</p>
												<p class="bf_p">신청자명과 입금자명이 다를 경우 <b>031-267-2050/2051</b> 로 연락 부탁드립니다.</p>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="rnd_btns mt50">
							<a href="javascript:;" onclick="goSave();"><span>프로그램 수정</span></a>
							<a href="index.php" class="gr"><span>취소</span></a>
						</div>


						<input type="hidden" name="cmd" value="edit"/>
						<input type="hidden" name="no" value="<?=$data['no']?>"/>
						<input type="hidden" name="program_fk" id="program_fk" value="<?=$data['program_fk']?>" id="program_fk"/>
						<input type="hidden" name="rtime" value="<?=$data['rtime']?>" id="rtime"/>
						<input type="hidden" name="price" id="price" value="<?=$data['price']?>"/>
						<input type="hidden" id="hideprice" value="<?=$data['price']?>"/>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="popup" class="popup ">
	<div class="pop_size">
		<div class="pop_wrap ">
			<div class="pop ">
				<div class="wrap">
					<a class="pop_close" href="javascript:;" onclick="closePopup('popup');"><img src="/img/pop_close.png"/></a>
					<div class="pop_info">
						<div class="sm_tit">
							<b><em>프로그램 검색</em></b>
						</div>
						
						<div class="tb_wrap mt20 cal_wrap">
							<div class="bbs_box ">
								<form method="POST" id="pfrm" name="pfrm">
								
								<div class="bbsSearch ">
									<span class="searchWord">
										<input type="text" id="sval" name="sval" value="" title="검색어 입력" onkeypress="">
										<input type="hidden" name="stype" value="all">
										<input type="button"  value="검색" title="검색" onclick="setProgram();">
										<input type="hidden" name="" value="search">
									</span>
								</div>
								<div class="wraps">
									<!--<div class="cals"><input type="text" value="" placeholder="날짜 선택" id="datapicker" readonly autocomplete="off" onchange="setProgram();"/></div>-->
								</div>
								
								</form>
							</div>
								
							<div class="tb_scroll">
								<table class="mt20 txt_c has_radio">
									<colgroup>
										<col width="3%" />
										<col width="20%" />
										<col width="30%" />
										<col width="27%" />
										<col width="20%" />
									</colgroup>
									<thead>
										<tr>
											<th></th>
											<th>구분</th>
											<th>프로그램</th>
											<th>일시</th>
											<th>예약현황</th>
										</tr>
									</thead>
									<tbody id="program_list">
										<!--
										<tr>
											<td>
												<div class="radio_box">
													<p>
														<input type="radio" name="pg" id="pg01" value="">
														<label for="pg01"><span>&nbsp;</span></label>
													</p>
												</div>
											</td>
											<td><p>공연</p></td>
											<td><p>매직쇼</p></td>
											<td><p>2022-05-10 14 : 00</p></td>
											<td><p>60 / 70</p></td>
										</tr>
										<tr>
											<td>
												<div class="radio_box">
													<p>
														<input type="radio" name="pg" id="pg02" value="">
														<label for="pg02"><span>&nbsp;</span></label>
													</p>
												</div>
											</td>
											<td><p>공연</p></td>
											<td><p>매직쇼</p></td>
											<td><p>2022-05-10 14 : 00</p></td>
											<td><p>60 / 70</p></td>
										</tr>
										-->
									</tbody>
								</table>
							</div>
							<div class="rg_btn">
								<a href="javascript:;" onclick="setFk();">선택 프로그램 등록</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
$(function(){
	<?if(!$pbool){?>
	$( "#rdate").datepicker({
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
	<?}?>
});
</script>
<?
	include_once $root."/footer.php";
?>