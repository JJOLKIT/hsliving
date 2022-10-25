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
											<td><p>키친랩</p></td>
										</tr>
										<tr>
											<th><p>사용 일시</p></th>
											<td><p>2022. 06. 01. (수)  11:00 ~14:00</p></td>
										</tr>
										<tr>
											<th><p>대관 상태</p></th>
											<td><p>대관 접수</p></td>
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
											<td><p>단체</p></td>
										</tr>
										<tr>
											<th><p>사업자/단체명</p></th>
											<td><p>OO어린이집</p></td>
										</tr>
										<tr>
											<th><p>대표자명</p></th>
											<td><p>이제노</p></td>
										</tr>
										<tr>
											<th><p>사업자등록번호</p></th>
											<td><p>123-45-67890</p></td>
										</tr>
										<tr>
											<th><p>대표자 전화</p></th>
											<td><p>010-1234-5678</p></td>
										</tr>
										<tr>
											<th><p>사업장 주소</p></th>
											<td><p>경기도 화성시 효행로</p></td>
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
												<p>반환 기준 내용</p>
											</th>
											<td>
												<div class="md_ip">
													<select>
														<option value="반환기준내용 선택">반환기준내용 선택</option>
														<option value="사용 예정일 7일 전에 취소신청">사용 예정일 7일 전에 취소신청</option>
														<option value="사용 예정일 전일 18:00 이전 취소신청">사용 예정일 전일 18:00 이전 취소신청</option>
														<option value="사용 예정일 전일 18:00 이후 취소신청">사용 예정일 전일 18:00 이후 취소신청</option>
														<option value="화성시의 행사 또는 생활문화창작소의 특별한 사정에 의해 취소">화성시의 행사 또는 생활문화창작소의 특별한 사정에 의해 취소</option>
														<option value="천재지변 또는 기타 불가항력의 사유">천재지변 또는 기타 불가항력의 사유</option>
													</select>
												</div>
											</td>
										</tr>
										<tr>
											<th>
												<p>반환료</p>
											</th>
											<td>
												<p class="yl"><b>40,000</b>원</p>
											</td>
										</tr>
										<tr>
											<th>
												<p>입금정보</p>
											</th>
											<td>
												<div class="md_ip has_ips">
													<input type="text" placeholder="은행명을 입력해주세요"/>
													<input type="text" placeholder="예금주를 입력해주세요"/>
													<input type="text" placeholder="계좌번호를 숫자만 입력해주세요"  onkeyup="onlyNumber(this);"/>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>

						<div class="rnd_btns mt50">
							<a href="javascript:;"><span>반환신청</span></a>
							<a href="index.php" class="gr"><span>취소</span></a>
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