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

<div id="sub" class="apply_idx apply_write">
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
											<th><p>진행 일시</p></th>
											<td><p><?=$data['stday']?> <?=substr($data['rtime'],0,5)?></p></td>
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
								<b><em>프로그램료</em></b>
							</div>
							<div class="tb_wrap mt20">
								<table>
									<colgroup>
										<col width="200px">
										<col width="*">
									</colgroup>
									<tbody>
										<tr>
											<th>
												<p>프로그램료 감면 여부</p>
											</th>
											<td>
												<div class="radio_box">
													<p>
														<?=getDcName($data['dc']) ?>
														<?if($data['dc'] == 2){?>
														(<?=getDcTxt($data['dc_txt'])?>)
														<?}?>
													</p>
												</div>
											</td>
										</tr>
										<tr>
											<th>
												<p>프로그램료</p>
											</th>
											<td>
												<p class="yl"><b><?=number_format($data['price'])?></b>원</p>
											</td>
										</tr>
										<tr>
											<th>
												<p>입금계좌</p>
											</th>
											<td>
												<p><b>신한은행 140-013-805900</b> (예금주 : 수원여자대학교산학협력단)</p>
												<p class="bf_p">신청자명과 입금자명이 다를 경우 031-267-2050/2051 로 연락 부탁드립니다.</p>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="boxes">
							<div class="sm_tit org">
								<b><em>확인해주세요!</em></b>
							</div>
							<div class="has_bf_wrap mt20 b_txt">
								<p><span>대관 신청을 완료하셨더라도 대관 확정 상태가 아니므로, 대관 상태를 확인 부탁드립니다.</span></p>
								<p><span>대관 상태의 경우 대관비 입금 확인 후 관리자의 승인에 따라 대관 확정 상태로 변경됩니다.</span></p>
								<p><span>반환 신청 시, 반환 신청 일시에 따라 대관료 반환율이 달라지니, 이점 참고 부탁드립니다.</span></p>
							</div>
						</div>
						<div class="rnd_btns mt50">
							<a href="<?=$notice->getQueryString('index.php', 0, $_REQUEST)?>"  ><span>목록으로</span></a>
							<?if($data['state'] == 1 || $data['state'] == 2 || $data['state'] == 5){?>
							<a href="<?=$notice->getQueryString('edit.php', $data[no], $_REQUEST)?>"><span>수정하기</span></a>
							<?}else{?>
							<a href="javascript: alert('접수, 접수 변경 상태일 때만 수정 가능합니다.');"><span>수정하기</span></a>
							<?}?>
							<?if($data['state'] == 1 || $data['state'] == 2 || $data['state'] == 5){?>
							<a href="<?=$notice->getQueryString('form.php', $data[no], $_REQUEST)?>" class="gr"><span>반환신청<?=$data['state'] ?></span></a>
							<?}?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<?
	include_once $root."/footer.php";
?>