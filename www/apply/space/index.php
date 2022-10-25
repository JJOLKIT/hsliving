<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Rsrv.class.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Schedule.class.php";
include "config.php";


if(!isset($_REQUEST['smonth']) || $_REQUEST['smonth'] == ""){
	$_REQUEST['smonth'] = Date('Y-m');
}

$smonth = $_REQUEST['smonth'];
$preYear = getYearMonth($smonth, -12);
$preMonth = getYearMonth($smonth, -1);
$curMonth = getYearMonth($smonth, 0);
$nexMonth = getYearMonth($smonth, 1);
$nexYear = getYearMonth($smonth, 12);

$s = new Schedule(99, 'holiday', $_REQUEST);
$notice = new Rsrv($pageRows, $tablename, $_REQUEST);

$result = rstToArray($s->getCalendar($curMonth));



	$p = "apply";
	$sp = 0;
	$spc= 0;
	$root = $_SERVER['DOCUMENT_ROOT'];
	include_once $root."/header.php";
?>
<script>
	function openPopup2(no){
		$.ajax({
			url : 'getPopup.php',
			data : {
				'no' : no
			},
			success : function(data){
				var r = data.trim();
				if(r != "fail"){
					$('#popup').html(r).stop().fadeIn(400);
				}
			}
		});
		
	}
</script>
<div id="sub" class="apply_idx apply_space">
	<?include_once $root."/include/sub_visual.php";?>
	<div class="con_wrap">
		<div class="cont_top">
			<div class="size">
				<div class="t_wrap">
					<span>화성시 생활문화창작소</span>
					<b>공간대관 신청 현황</b>
				</div>
			</div>
		</div>
		<div class="con1 ">
			<div class="size clear">
				<div class="cal_wrap con_info">



						<div class="diary_box box">
							<div class="diary btn_wrap clear">
								<ul>
									<!--<li><a href="index.php?smonth=2021-05"class="board first" title="이전년도">이전년도</a></li>-->
									<li class="prev "><a href="index.php?smonth=<?=$preMonth?>" class="pic" ><img src="/img/cal_left.png"/></a></li>
									<li class="cur"><div class="n_wrap"><b><?=substr($curMonth, 0, 4)?></b><b><?=substr($curMonth, 5)?></b></div></li>
									<li class="next active"><a href="index.php?smonth=<?=$nexMonth?>" class="pic"><img src="/img/cal_right.png"/></a></li>
									<!--<li><a href="index.php?smonth=2023-05"class="board last" title="다음년도">다음년도</a></li>-->
								</ul>
							</div>

							<div class="diaryList">
								<div class="" >
									<table class="calendar">
										<caption> 달력 목록 </caption>
											<colgroup>
												<col width="14.2%" />
												<col width="14.3%" />
												<col width="14.3%" />
												<col width="14.3%" />
												<col width="14.3%" />
												<col width="14.3%" />
												<col width="14.3%" />
											</colgroup>
										<thead>
												<tr>
													<th>일</th>
													<th>월</th>
													<th>화</th>
													<th>수</th>
													<th>목</th>
													<th>금</th>
													<th>토</th>
												</tr>
										</thead>
										<tbody>
										<?
											$tot = count($result);
											if ($tot == 0) {
										?>
											<tr>
												<td>달력이 존재하지 않습니다.</td>
											</tr>
										<?
											} else {
												for ($i=0; $i<count($result); $i++) {
													$row = $result[$i];
													
													$name = $row[name];			// 요일
													$today = $row[today];		// 날짜
													$holiday = $row[holiday];	// 공휴일 여부(공휴일인 경우 공휴일명)
													$holiday2 = rstToArray($s->getTodayList($today)); //관리자 휴일 체크


													$styleMouse = "";
													$dateStyle = "";
													
													if ($name == 1) {
													} else if ($name == 7) {
														$dateStyle = "blue";
													}
													
													if ($holiday != '0' || count($holiday2) > 0) {
														$dateStyle = "red";
													}
													
													if ($holiday == '0') {
														if ($name == 1) {
															$dateStyle = "red";
														}
													}
													
													$date = substr($today,8);
													
													if ($i == 0 || 1 == $name) { 
										?>
											<tr>
										<?
													}
													if ($i == 0) {
														for ($j=0; $j<$name-1; $j++) {
										?>
												<td class='bftd'></td>
										<?
														}
													}
													$result2 = "";

													
		

										?>
												<td <?=$targetUrl?> align="center" <?if($row['today'] == getToday()){ echo "class='active'"; } else if (strtotime($row['today']) < strtotime(Date('Y-m-d')) ){ echo "class='bftd'"; } ?>>
													<span class="date <?=$dateStyle?>"><?=$date?> 
													<? 
													if($row['holiday'] != '0' ) { echo $row['holiday']; 
													}else{
														if( count($holiday2) > 0){ echo $holiday2[0]['title']; }
													}?>
													
													</span>
													
														
													<?
														$req['srdate'] = $row['today'];
														$req['suser'] = 1;
														$list = rstToArray($notice->getDetailList($req));
														for($v = 0; $v < count($list); $v++){
															$lastTime = substr($list[$v]['rtime'], 0, 2) + $list[$v]['rhour'] ;
			
													?>

															<a href="javascript:; " onclick="openPopup2('<?=$list[$v]['no']?>');" <?if($list[$v]['state'] == 2){ echo "class='confirm'"; }?>>
																<b><?=getPlaceName($list[$v]['place'])?></b>
																<p><?=substr($list[$v]['rtime'], 0, 5)?> ~ <?=$lastTime.":00"?></p>
															</a>
													<?}?>
			
														
	
												</td>
										<?
													if ($i == $tot-1) {
														for ($k=0; $k<7-$name; $k++) {
										?>
												<td></td>
										<?
														}
													}
										?>
										<?
													if ($i == $tot-1 || 7 == $name) {
										?>
											</tr>
										<?
													}
												}
											}
										?>
											
										</tbody>
									</table>

							
								</div>
							</div>
						</div>



					<?
					/*
					<div class="diary_box">
						<div class="diary btn_wrap clear">
							<ul>
								<!--<li><a href="index.php?smonth=2021-05"class="board first" title="이전년도">이전년도</a></li>-->
								<li class="prev "><a href="index.php?smonth=2022-04" class=" pic" title="이전달"><img src="/img/cal_left.png"></a></li>
								<li class="cur"><b>2022</b><b>05</b></li>
								<li class="next active"><a href="index.php?smonth=2022-06" class=" pic" title="다음달"><img src="/img/cal_right.png"></a></li>
								<!--<li><a href="index.php?smonth=2023-05"class="board last" title="다음년도">다음년도</a></li>-->
							</ul>
						</div>
						<!-- //pagenate -->
						<div class="diaryList">
							<table>
								<caption> 달력 목록 </caption>
								<colgroup>
									<col width="14.2%">
									<col width="14.3%">
									<col width="14.3%">
									<col width="14.3%">
									<col width="14.3%">
									<col width="14.3%">
									<col width="14.3%">
								</colgroup>
								<thead>
									<tr>
										<th>일</th>
										<th>월</th>
										<th>화</th>
										<th>수</th>
										<th>목</th>
										<th>금</th>
										<th>토</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>
											<span class="date red">01 </span>
										</td>
										<td>
											<span class="date ">02 </span>
										</td>
										<td>
											<span class="date ">03 </span>
										</td>
										<td>
											<span class="date ">04 </span>
										</td>
										<td>
											<span class="date red">05 어린이날</span>
											<a href="javascript:; " onclick="openPopup('popup');">
												<b>리빙랩</b>
												<p>11:00~14:00</p>
											</a>
										</td>
										<td>
											<span class="date ">06 </span>
										</td>
										<td>
											<span class="date blue">07 </span>
										</td>
									</tr>
									<tr>
										<td>
											<span class="date red">08 </span>
											<a href="javascript:;" class="confirm" onclick="openPopup('popup');">
												<b>리빙랩</b>
												<p>11:00~14:00</p>
											</a>
											<a href="javascript:;" onclick="openPopup('popup');">
												<b>리빙랩</b>
												<p>11:00~14:00</p>
											</a>
										</td>
										<td>
											<span class="date ">09 </span>
										</td>
										<td>
											<span class="date ">10 </span>
										</td>
										<td>
											<span class="date ">11 </span>
										</td>
										<td>
											<span class="date ">12 </span>
										</td>
										<td>
											<span class="date ">13 </span>
										</td>
										<td>
											<span class="date blue">14 </span>
											<a href="javascript:;" class="confirm" onclick="openPopup('popup');">
												<b>커뮤니티라운지</b>
												<p>11:00~14:00</p>
											</a>
										</td>
									</tr>
									<tr>
										<td>
											<span class="date red">15 </span>
										</td>
										<td>
											<span class="date ">16 </span>
										</td>
										<td>
											<span class="date ">17 </span>
										</td>
										<td>
											<span class="date ">18 </span>
										</td>
										<td>
											<span class="date ">19 </span>
										</td>
										<td>
											<span class="date ">20 </span>
										</td>
										<td>
											<span class="date blue">21 </span>
										</td>
									</tr>
									<tr>
										<td>
											<span class="date red">22 </span>
										</td>
										<td>
											<span class="date ">23 </span>
										</td>
										<td>
											<span class="date ">24 </span>
										</td>
										<td>
											<span class="date ">25 </span>
										</td>
										<td align="center" class="todayTd">
											<span class="date ">26 </span>
										</td>
										<td align="center">
											<span class="date ">27 </span>
										</td>
										<td align="center">
											<span class="date blue">28 </span>
										</td>
									</tr>
									<tr>
										<td align="center">
											<span class="date red">29 </span>
										</td>
										<td align="center">
											<span class="date ">30 </span>
										</td>
										<td align="center">
											<span class="date ">31 </span>
										</td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
									</tr>

								</tbody>
							</table>
						</div>
					</div>
					*/
					?>
					<div class="btm_wrap clear">
						<div class="has_bf_wrap">
							<p><span>대관 신청 후 대관료 입금 전인 경우 대관 예정 상태이며, 대관료 입금 확인 후 대관 확정 상태로 변경됩니다.</span></p>
						</div>
						<div class="span_wrap">
							<span>대관접수</span>
							<span>대관확정</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="con2 on has_contit">
			<div class="size clear">
				<div class="con_tit">
					<b>대관안내</b>
				</div>
				<div class="con_info">
					<div class="info_wrap">
						<div class="boxes">
							<div class="sm_tit">
								<b><em>개요</em></b>
							</div>
							<div class="tb_wrap2">
								<div class="wraps clear">
									<div class="l_nm">
										<span>대관 목적</span>
									</div>
									<div class="r_ct">
										<p>화성시 시민들의 생활문화 활동을 위한 공간 제공</p>
									</div>
								</div>
								<div class="wraps clear">
									<div class="l_nm">
										<span>자격</span>
									</div>
									<div class="r_ct">
										<p>화성 시민, 화성시 관내 단체 또는 사업자</p>
									</div>
								</div>
								<div class="wraps clear">
									<div class="l_nm">
										<span>대관 내용</span>
									</div>
									<div class="r_ct">
										<div class="ul_wrap">
											<ul class="clear">
												<li>
													<div class="tb " style="background-image:url('/img/app1_li01.png')">
														<div class="tbc">
															<b>커뮤니티라운지</b>
														</div>
													</div>
												</li>
												<li>
													<div class="tb" style="background-image:url('/img/app1_li02.png')">
														<div class="tbc">
															<b>키친랩</b>
														</div>
													</div>
												</li>
												<li>
													<div class="tb" style="background-image:url('/img/app1_li03.png')">
														<div class="tbc">
															<b>커뮤니티룸</b>
															<span>(교육실)</span>
														</div>
													</div>
												</li>
												<li>
													<div class="tb" style="background-image:url('/img/app1_li04.png')">
														<div class="tbc">
															<b>리빙랩1<br/>리빙랩2</b>
														</div>
													</div>
												</li>
											</ul>
										</div>
										<p>단, 생활문화창작소 주관 사업이 진행될 경우 대관 불가함</p>
									</div>
								</div>
								<div class="wraps clear">
									<div class="l_nm">
										<span>대관 신청절차</span>
									</div>
									<div class="r_ct">
										<div class="ul_wrap2">
											<ul class="clear">
												<li>
													<div class="tb">
														<div class="tbc">
															<div class="pic" style="background-image:url('/img/step_ico01.png')"><img src="/img/step_ico01.png"></div>
															<div class="txt">
																<b>일정협의</b>
																<span>(전화 및 방문)</span>
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
																<span>(홈페이지 및 방문)</span>
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
																<b>대관료 입금 및 확인</b>
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
																<b>사용 및 정리</b>
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
										<span>신청기한</span>
									</div>
									<div class="r_ct">
										<p>대관 이용 희망일 4주 전부터 3일 전까지 대관신청서 제출 및 입금 처리 완료</p>
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
								<b><em>부대시설</em></b>
							</div>
							<div class="tb_wrap txt_c has_scr">
								<div>
									<table>
										<colgroup>
											<col width="40%" />
											<col width="60%" />
										</colgroup>
										<thead>
											<tr>
												<th>구분</th>
												<th>시설 / 장비</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td><p>커뮤니티라운지</p></td>
												<td><p>간이무대, 오디오시스템</p></td>
											</tr>
											<tr>
												<td><p>키친랩</p></td>
												<td><p>아일랜드 주방, 각종 요리가전, 식기류, 오디오시스템, 촬영시스템, 테이블, 의자</p></td>
											</tr>
											<tr>
												<td><p>커뮤니티룸(교육실)</p></td>
												<td><p>빔프로젝터, 오디오시스템, 테이블, 의자</p></td>
											</tr>
											<tr>
												<td><p>리빙랩 1 / 리빙랩 2</p></td>
												<td><p>TV, 테이블, 의자, 진열장</p></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="has_bf_wrap mt20">
								<p><span>부대시설은 공간대관과 함께 대여해야 사용 가능하며, 공간대관 시간을 초과해서 사용하실 수 없습니다.</span></p>
								<p><span>키친랩의 식기 등 기물 파손 시 구입비용에 근거하여 배상금이 부과됩니다.</span></p>
							</div>
						</div>
						<div class="boxes">
							<div class="sm_tit">
								<b><em>대관료</em></b>
							</div>
							<div class="tb_wrap txt_c has_scr">
								<div>
									<table>
										<colgroup>
											<col width="24%" />
											<col width="18%" />
											<col width="18%" />
											<col width="40%" />
										</colgroup>
										<thead>
											<tr>
												<th>구분</th>
												<th>최대수용인원</th>
												<th>대관료(원)</th>
												<th>비고</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td><p>커뮤니티라운지</p></td>
												<td><p>50명</p></td>
												<td><p>20,000</p></td>
												<td class="txt_l" rowspan="5">
													<div class="has_bf_wrap">
														<p><span>대관료는 1시간 기준 금액입니다. <br/>(1시간 미만 시 1시간으로 산정되어 정산됩니다.)</span></p>
														<p><span>대관 이용 시간은 1일 최대 4시간으로 제한됩니다.</span></p>
														<p><span>대관은 개관 시간 내에만 가능합니다. <br/>(개관시간 : 월~토 10:00-18:00)</span></p>
													</div>
												</td>
											</tr>
											<tr>
												<td><p>키친랩</p></td>
												<td><p>9명</p></td>
												<td><p>10,000</p></td>
											</tr>
											<tr>
												<td><p>커뮤니티룸(교육실)</p></td>
												<td><p>20명</p></td>
												<td><p>10,000</p></td>
											</tr>
											<tr>
												<td><p>리빙랩_1</p></td>
												<td><p>6명</p></td>
												<td><p>5,000</p></td>
											</tr>
											<tr>
												<td><p>리빙랩_2</p></td>
												<td><p>6명</p></td>
												<td><p>5,000</p></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="has_bf_wrap mt20">
								<p><span>최대수용인원보다 적은 인원이 사용해도 사용료는 동일합니다.</span></p>
								<p>
									<span>대관료 감면 내용은</span>
									<a href="/download/대관료감면기준.pdf"> 대관료 감면기준</a>
									<span>을 클릭하시어, 내용 확인 부탁드립니다. (대관료 감면 시 대관일에 증빙 자료가 필요합니다.)</span>
								</p>
							</div>
						</div>
						<div class="boxes">
							<div class="sm_tit">
								<b><em>대관료 반환기준</em></b>
							</div>
							<div class="tb_wrap txt_c has_scr">
								<div>
								<table>
										<colgroup>
											<col width="60%" />
											<col width="40%" />
										</colgroup>
										<thead>
											<tr>
												<th>반환기준 내용</th>
												<th>반환 금액</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td class="txt_l">
													<p>- 화성시의 행사 또는 생활문화창작소의 특별한 사정에 따라 사용을 못하게 된 경우 </p>
													<p>- 천재지변 또는 기타 불가항력의 사유로 사용이 불가능할 경우</p>
													<p>- 사용 예정일 7일 전에 취소신청을 한 경우</p>
												</td>
												<td><p>전액</p></td>
											</tr>
											<tr>
												<td class="txt_l"><p>- 사용 예정일 전일 18:00이전에 취소 신청을 한 경우</p></td>
												<td><p>납부금액의 100분의 10을 공제한 금액</p></td>
											</tr>
											<tr>
												<td class="txt_l"><p>- 사용 예정일 전일 18:00이후에 취소 신청을 한 경우</p></td>
												<td><p>전액 미반환</p></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="rnd_btns mt50">
							<a href="write.php"><span>대관 신청</span></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="popup" class="popup nr_pop">
	<div class="pop_size">
		<div class="pop_wrap ">
			<div class="pop ">
				<div class="wrap">
					<a class="pop_close" href="javascript:;" onclick="closePopup('popup');"><img src="/img/pop_close.png"/></a>
					<div class="pop_info">
						<div class="sm_tit">
							<b><em>대관 신청 현황</em></b>
						</div>
						<div class="tb_wrap mt20">
							<table>
								<colgroup>
									<col width="30%" />
									<col width="70%" />
								</colgroup>
								<tbody>
									<tr>
										<th>
											<p>대관장소</p>
										</td>
										<td><p>키친랩</p></td>
									</tr>
									<tr>
										<th>
											<p>대관 시간</p>
										</td>
										<td><p>2022.05.10 11:00 - 14:00</p></td>
									</tr>
									<tr>
										<th>
											<p>대관 확정 유무</p>
										</td>
										<td><p>대관 확정</p></td>
									</tr>

								</tbody>
							</table>
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