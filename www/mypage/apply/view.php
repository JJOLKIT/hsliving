<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

	include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Rsrv.class.php";
	include $_SERVER['DOCUMENT_ROOT']."/include/loginCheck.php";

	include "config.php"; 

	$notice = new Rsrv($pageRows, $tablename, $_REQUEST);
	$data = $notice->getData($_REQUEST[no], $userCon);
	$rdata = rstToArray($notice->getRdate($_REQUEST[no], $userCon));
?>
<?
	$p = "mypage";
	$sp = 0;
	$spc= 0;
	$root = $_SERVER['DOCUMENT_ROOT'];
	include_once $root."/header.php";
?>

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
					<div class="info_wrap">
						<div class="boxes">
							<div class="sm_tit">
								<b><em>대관 내용</em></b>
							</div>
							<div class="tb_wrap txt_l bd_th">
								<table>
									<colgroup>
										<col width="200px" />
										<col width="*" />
									</colgroup>
									<tbody>
										<tr>
											<th><p>사용 시설</p></th>
											<td><p><?=getPlaceName($data['place']) ?></p></td>
										</tr>
										<tr>
											<th><p>사용 일시</p></th>
											<td>
												<?
													for($i=0; $i<count($rdata); $i++){
												?>
													<p>
													<?=$rdata[$i]['rdate'] ?> <?=substr($rdata[$i]['rtime'],0,5)?> 
													<?$lastTime = substr($rdata[$i]['rtime'],0,2) + $rdata[$i]['rhour'];?>
													~
													<?=$lastTime.":00"?> (<?=$rdata[$i]['rhour']?>시간)
													</p>
												<?}?>
												<!-- <p>2022. 06. 01. (수)  11:00 ~14:00</p> -->
											</td>
										</tr>
										<tr>
											<th><p>대관 상태</p></th>
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
											<th><p>구분</p></th>
											<td><p><?=getGbName($data['gb'])?></p></td>
										</tr>
										<?
											if($data['gb'] == 1){
										?>
										<tr>
											<th><p>사업자/단체명</p></th>
											<td><p><?=$data['company']?></p></td>
										</tr>
										<?}?>
										<tr>
											<th><p><?=$data['gb'] == 1 ? "대표자명" : "담당자/개인명"?></p></th>
											<td><p><?=$data['name']?></p></td>
										</tr>
										<?
											if($data['gb'] == 1){
										?>
										<tr>
											<th><p>사업자등록번호</p></th>
											<td><p><?=$data['reg_number']?></p></td>
										</tr>
										<?}else if($data['gb'] == 2){?>
										<tr>
											<th><p>생년월일</p></th>
											<td><p><?=$data['birthday']?></p></td>
										</tr>
										<?}?>
										<tr>
											<th><p><?=$data['gb'] == 1 ? "대표자 전화" : "연락처"?></p></th>
											<td><p><?=$data['cell']?></p></td>
										</tr>
										<tr>
											<th><p><?=$data['gb'] == 1 ? "사업장 주소" : "주소(개인)"?></p></th>
											<td><p><?=$data['addr']?></p></td>
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
										<col width="200px">
										<col width="*">
									</colgroup>
									<tbody>
										<tr>
											<th>
												<p>대관료 감면 여부</p>
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
												<p>대관료</p>
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
							<a href="<?=$notice->getQueryString('index.php', 0, $_REQUEST)?>"><span>목록으로</span></a>
							<a href="<?=$notice->getQueryString('edit.php', $data[no], $_REQUEST)?>"><span>수정하기</span></a>
							<a href="<?=$notice->getQueryString('form.php', $data[no], $_REQUEST)?>" class="gr"><span>반환신청</span></a>
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