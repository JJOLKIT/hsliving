<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

	include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/GalleryCt.class.php";

	include "config.php";
	$notice = new GalleryCt($pageRows, $tablename, $category_tablename, $_REQUEST);
	$data = ($notice->getData($_REQUEST[no], $userCon));
	$today = Date('Y-m-d');
?>
<?	
	$p = "program";
	$sp = 0;
	$spc= 0;
	$root = $_SERVER['DOCUMENT_ROOT'];
	include_once $root."/header.php";
?>

<script>
function goSave(){
	<?if(!$loginCheck){?>
		if(confirm('로그인이 필요한 서비스입니다.\n로그인 페이지로 이동하시겠습니까?')){
			location.href='/member/login.php?url='+encodeURIComponent(location.href);
		}else{
			return false;
		}
	<?}else{?>
	document.frm.submit();
	<?}?>
}
</script>
<form action="/apply/program/write.php" method="post" id="frm" name="frm">
	<input type="hidden" name="program_fk" value="<?=$data[no]?>"/>
</form>
<div id="sub" class="list_view apply_idx prg_view">
	<?include_once $root."/include/sub_visual.php";?>
	<div class="con_wrap">
		<div class="cont_top">
			<div class="size">
				<div class="t_wrap">
					<span>화성시 생활문화창작소</span>
					<b>프로그램</b>
				</div>
			</div>
		</div>
	<div class="con1 has_contit nbd">
		<div class="size clear">
			<!-- 여기서부터 게시판--->
			<div class="inf_wrap clear">
				<div class="pic_box">
					<div class="pic" style="background-image:url('<?=$uploadPath.$data['imagename']?>')"><img src="/img/prg_b_img.jpg"></div>
				</div>
				<div class="info_box">
					<div class="tb_wrap txt_l">
						<div class="tit"><?=$data['title']?></div>
						<table>
							<colgroup>
								<col width="184px"/>
								<col width="*"/>
							</colgroup>
							<tbody>
								<tr>
									<th>
										<p>행사(교육) 일시</p>
									</th>
									<td>
										<p><?=Date('Y. m. d', strtotime($data['stday']))?> (<?=substr($data['rtime'], 0, 5)?>)</p>
									</td>
								</tr>
								<tr>
									<th>
										<p>장소</p>
									</th>
									<td>
										<p><?=$data['place']?></p>
									</td>
								</tr>
								<tr>
									<th>
										<p>출연(강사)</p>
									</th>
									<td>
										<p><?=$data['teacher']?></p>
									</td>
								</tr>
								<tr>
									<th>
										<p>보조출연(보조강사)</p>
									</th>
									<td>
										<p><?=$data['genre']?></p>
									</td>
								</tr>
								<tr>
									<th>
										<p>참여 가능 연령</p>
									</th>
									<td>
										<p><?=$data['age']?></p>
									</td>
								</tr>
								<tr>
									<th>
										<p>신청 기간</p>
									</th>
									<td>
										<p><?=Date('Y. m. d', strtotime($data['sday']))?> ~ <?=Date('Y. m. d', strtotime($data['eday']))?></p>
									</td>
								</tr>
								<tr>
									<th>
										<p>가격(수강료)</p>
									</th>
									<td>
										<p><?=number_format($data['price'])?>원</p>
									</td>
								</tr>
								<tr>
									<th>
										<p>예약 현황</p>
									</th>
									<td>
										<p><b><?=number_format($data['count'] + $data['sum_together'])?>명</b> / <?=number_format($data['amount'])?>명</p>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<p class="p_st1">프로그램 내용</p>
					<div class="contents">
						<?=$data['contents']?>
					</div>
					<div class="rnd_btns mt50">
						<?	
							$tcount = $data['count'] + $data['sum_together'];
							if(strtotime($today) < strtotime($data['sday'])){
						?>
						<a href="javascript:;" ><span>오픈 예정</span></a>
						<?}else if( strtotime($today) > strtotime($data['eday'])) {?>
						<a href="javascript:;" ><span>신청 마감</span></a>
						<?}else if( $tcount >= $data['amount']) {?>
						<a href="javascript:;" ><span>신청 마감</span></a>
						<?}else{?>
						<a href="javascript:;" onclick="goSave();" ><span>프로그램 신청하기</span></a>
						<?}?>
					</div>
				</div>
			</div>
			<!-- //여기까지 게시판---> 
		</div>
	</div>
	<div class="con2  has_contit on">
		<div class="size clear">
			<div class="map_tit con_tit">
				<b>예약관련 유의사항</b>
			</div>
			<div class="con_info">
				<div class="info_wrap">
					<div class="boxes">
						<div class="tb_wrap2">
							<div class="wraps clear">
								<div class="l_nm">
									<span>프로그램 신청절차</span>
								</div>
								<div class="r_ct">
									<div class="ul_wrap2">
										<ul class="clear">
											<li>
												<div class="tb">
													<div class="tbc">
														<div class="pic" style="background-image:url('/img/step_ico01.png')"><img src="/img/step_ico01.png"></div>
														<div class="txt">
															<b>프로그램 확인</b>
															<span>(인원 및 일정 등)</span>
														</div>
													</div>
												</div>
												<div class="ab_img"><img src="/img/ar_bf.png"></div>
											</li>
											<li>
												<div class="tb">
													<div class="tbc">
														<div class="pic" style="background-image:url('/img/step_ico02.png')"><img src="/img/step_ico01.png"></div>
														<div class="txt">
															<b>신청서 작성·제출</b>
															<span>(홈페이지)</span>
														</div>
													</div>
												</div>
												<div class="ab_img"><img src="/img/ar_bf.png"></div>
											</li>
											<li>
												<div class="tb">
													<div class="tbc">
														<div class="pic" style="background-image:url('/img/step_ico03.png')"><img src="/img/step_ico01.png"></div>
														<div class="txt">
															<b>수강료 입금 및 확인</b>
														</div>
													</div>
												</div>
												<div class="ab_img"><img src="/img/ar_bf.png"></div>
											</li>
											<li>
												<div class="tb">
													<div class="tbc">
														<div class="pic" style="background-image:url('/img/step_ico04.png')"><img src="/img/step_ico01.png"></div>
														<div class="txt">
															<b>승인 통보</b>
														</div>
													</div>
												</div>
												<div class="ab_img"><img src="/img/ar_bf.png"></div>
											</li>
											<li class="last">
												<div class="tb">
													<div class="tbc">
														<div class="pic" style="background-image:url('/img/step_ico05.png')"><img src="/img/step_ico01.png"></div>
														<div class="txt">
															<b>프로그램 참가</b>
														</div>
													</div>
												</div>
												<div class="ab_img"><img src="/img/ar_bf_last.png"></div>
											</li>
										</ul>
									</div>
								</div>
							</div>
					
							<div class="wraps clear">
								<div class="l_nm">
									<span>문의</span>
								</div>
								<div class="r_ct">
									<p>화성시 생활문화창작소</p>
									<a href="tel:031-267-2050">031-267-2050, 2051</a>
								</div>
							</div>
						</div>
					</div>
					<div class="boxes">
						<div class="sm_tit">
							<b><em>수강료 반환기준</em></b>
						</div>
						<div class="tb_wrap txt_l has_scr">
							<div>
								<table>
									<colgroup>
										<col width="60%" />
										<col width="40%" />
									</colgroup>
									<thead>
										<tr class="txt_c">
											<th>반환기준 내용</th>
											<th>반환 금액</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td><p>프로그램 시작일 7일 전에 취소신청을 한 경우</p></td>
											<td><p>수강료, 재료·교재비 전액 반환</p></td>
										</tr>
										<tr>
											<td><p>프로그램 시작일 전일 18:00이전에 취소 신청을 한 경우</p></td>
											<td><p>수강료 미반환, 재료·교재비 전액 반환</p></td>
										</tr>
										<tr>
											<td><p>프로그램 시작일 전일 18:00이후에 취소 신청을 한 경우</p></td>
											<td><p>전액 미반환</p></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="boxes">
						<div class="sm_tit">
							<b><em>기타 안내</em></b>
						</div>
						<div class="has_bf_wrap mt20 b_txt">
							<p><span>프로그램  별 수강료가 다르며, 수강료 감면 내용은 <a href="/download/수강료감면기준.pdf"> 수강료 감면기준</a>을 클릭하시어 내용 확인 부탁드립니다.</span></p>
							<p><span>프로그램  수강료 감면 시 프로그램  참여일에 증빙 자료가 필요합니다.</span></p>
							<p><span>프로그램 별 참여 가능한 인원이 다르며, 인원 마감 시 프로그램 신청이 진행되지 않습니다.</span></p>
							<p><span>부주의로 인하여 시설물 또는 집기류를 손상한 경우에는 배상금이 부과됩니다.</span></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- //size--->
</div>
<!-- //sub--->
<?
	include_once $root."/footer.php";
?>
