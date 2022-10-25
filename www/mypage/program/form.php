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

	if($data['state'] == 3){
		echo "
			<script>
				alert('이미 취소 신청 진행중입니다.');
				history.back();
			</script>
		";
		exit;
	}
?>
<script>
function goSave(){
	var frm = document.frm;

	/* if( frm.refund_option.value == "" ){
		alert('반환 기준을 선택해 주세요.');
		frm.refund_option.focus();
		return false;
	} */

	if( frm.refund_bank.value.trim() == ""){
		alert('은행명을 입력해 주세요.');
		frm.refund_bank.focus();
		return false;
	}

	if( frm.refund_account.value.trim() == "" ){
		alert('은행계좌를 입력해 주세요.');
		frm.refund_account.focus();
		return false;
	}

	if( frm.refund_name.value.trim() == "" ){
		alert('예금주를 입력해 주세요.');
		frm.refund_name.focus();
		return false;
	}

	if( frm.refund_reason.value.trim() == "" ){
		alert('반환 사유를 입력해 주세요.');
		frm.refund_reason.focus();
		return false;
	}

	if(confirm('작성하신 내용으로 반환신청 하시겠습니까?\n신청 후에는 수정할 수 없습니다.')){
		frm.submit();
	}else{
		return false;
	}

	

}
</script>
<div id="sub" class="apply_idx apply_write">
	<?include_once $root."/include/sub_visual.php";?>
	<div class="con_wrap">
		<div class="cont_top">
			<div class="size">
				<div class="t_wrap">
					<span>화성시 생활문화창작소</span>
					<b>신청현황</b>
				</div>
			</div>
		</div>
		<div class="con1  has_contit nbd">
			<div class="size clear">
				<div class="con_info">
					<form name="frm" id="frm" action="<?=getSslCheckUrl($_SERVER['REQUEST_URI'], 'process.php')?>" method="post" >
						<div class="info_wrap">
							<div class="boxes">
								<div class="sm_tit">
									<b><em>프로그램 내용</em></b>
								</div>
								<div class="tb_wrap txt_l bd_th">
									<table>
										<colgroup>
											<col width="200px" />
											<col width="*" />
										</colgroup>
										<tbody>
											<tr>
												<th><p>프로그램명</p></th>
												<td><p><?=$data['title']?></p></td>
											</tr>
											<tr>
												<th><p>사용 일시</p></th>
												<td><p><?=$data['rdate']?> <?=substr($data['rtime'],0,5)?></p></td>
											</tr>
											<tr>
												<th><p>신청 상태</p></th>
												<td><p><?=getStateName($data['state'])?></p></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="boxes">
								<div class="sm_tit">
									<b><em>신청인 정보</em></b>
								</div>
								<div class="tb_wrap txt_l bd_th">
									<table>
										<colgroup>
											<col width="200px" />
											<col width="*" />
										</colgroup>
										<tbody>
											<tr>
												<th><p>성명</p></th>
												<td><p><?=$data['name']?></p></td>
											</tr>
											<tr>
												<th><p>성별</p></th>
												<td><p><?=getGenderName($data['gender'])?></p></td>
											</tr>
											<tr>
												<th><p>연락처</p></th>
												<td><p><?=$data['cell']?></p></td>
											</tr>
											<tr>
												<th><p>생년월일</p></th>
												<td><p><?=$data['birthday']?></p></td>
											</tr>
											<tr>
												<th><p>주소</p></th>
												<td><p><?=$data['addr']?></p></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="boxes">
								<div class="sm_tit">
									<b><em>반환 정보</em></b>
								</div>
								<div class="tb_wrap mt20">
									<table>
										<colgroup>
											<col width="200px">
											<col width="*">
										</colgroup>
										<tbody>
											<!-- <tr>
												<th>
													<p>반환 기준 내용</p>
												</th>
												<td>
													<div class="md_ip">
														<select name="refund_option">
															<?=getCancelOption()?>
														</select>
													</div>
												</td>
											</tr> -->
											<tr>
												<th>
													<p>반환금액</p>
												</th>
												<td>
													<p class="yl"><b><?=$data['price']?></b>원</p>
													<p> 취소 시점에 따라 반환금액이 상이하며, 관리자 별도 연락 예정입니다.</p>
												</td>
											</tr>
											<tr>
												<th>
													<p>입금정보</p>
												</th>
												<td>
													<div class="md_ip has_ips">
														<input type="text" name="refund_bank" placeholder="은행명을 입력해주세요"/>
														<input type="text" name="refund_name" placeholder="예금주를 입력해주세요"/>
														<input type="text" name="refund_account" placeholder="계좌번호를 숫자만 입력해주세요"  onkeyup="onlyNumber(this);"/>
													</div>
												</td>
											</tr>
											<tr>
												<th>
													<p>반환사유</p>
												</th>
												<td>
													<div class="fl_ip">
														<input type="text" name="refund_reason" id="refund_reason">
													</div>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>

							<div class="rnd_btns mt50">
								<a href="javascript:;" onclick="goSave();"><span>반환신청</span></a>
								<a href="<?=$notice->getQueryString('view.php', $data[no], $_REQUEST)?>" class="gr"><span>취소</span></a>
							</div>
						</div>

						<input type="hidden" name="cmd" value="cancel"/>
						<input type="hidden" name="no" value="<?=$data['no']?>"/>
						<input type="hidden" name="state" value="3"/>
						<input type="hidden" name="refund_price" value="<?=$data['price']?>"/>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>


<?
	include_once $root."/footer.php";
?>