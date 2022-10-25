<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Rsrv2.class.php";

include "config.php";

$notice = new Rsrv2($pageRows, $tablename, $_REQUEST);
$data = $notice->getData($_REQUEST[no], $userCon);
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta charset="utf-8">
		<script src="/admin/js/jquery-1.12.0.min.js"></script>
		<link rel="stylesheet" href="/css/reset.css?ver=1">
		<link rel="stylesheet" href="/css/common.css?ver=1">
		<link rel="stylesheet" href="/css/content.css?ver=1">
		<script>
		window.onload = function(){
			//window.print();
			setTimeout(function(){
			//	window.close();		
			},500);
		}
		</script>
	</head>
	<body id="pdf">
		<div class="page">
			<div class="certif">
				<div class="title_wrap">
					<div class="img"><img src="/admin/img/print_logo.png"/></div>
				</div>
				<div class="tb_wraps">
					<div class="tp">
						<div class="img"><img src="/admin/img/bf.png"/></div>
						<p>수강(참가)신청서</p>
					</div>
					<table>
						<colgroup>
							<col width="130px"/>
							<col width="80px"/>
							<col width="235px"/>
							<col width="130px"/>
							<col width="255px"/>
						</colgroup>
						<tbody>
							<tr>
								<th>접수번호</th>
								<td colspan="4">&nbsp;</td>
							</tr>
							<tr>
								<th>강좌명</th>
								<td colspan="2"><?=$data['title']?></td>
								<th>구분</th>
								<td>일반</td>
							</tr>
							<tr>
								<th>성명</th>
								<td colspan="2"><?=$data['name']?></td>
								<th>성별</th>
								<td><?=getGenderName($data['gender'])?></td>
							</tr>
							<tr>
								<th>연락처</th>
								<td colspan="2"><?=$data['cell']?></td>
								<th>생년월일</th>
								<td><?=$data['birthday']?></td>
							</tr>
							<tr>
								<th>주소</th>
								<td colspan="4"><?=$data['addr']?></td>
							</tr>
							<tr>
								<th rowspan="2">수강료</th>
								<td><?=number_format($data['price'])?>원</td>
							</tr>
							<tr>
								<th class="has_bd">입금계좌</th>
								<td colspan="3">신한은행 140-013-805900 (예금주 : 수원여자대학교산학협력단)</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="tb_wraps sm privacy">
					<div class="tp">
						<div class="img"><img src="/admin/img/bf.png"/></div>
						<p>개인정보 수집, 이용 동의</p>
					</div>
					<table>
						<colgroup>
							<col width="200px"/>
							<col width="*"/>
						</colgroup>
						<tbody>
							<tr>
								<th>수집, 이용하는 자</th>
								<td>화성시생활문화창작소</td>
							</tr>
							<tr>
								<th>수집하는 개인정보 항목</th>
								<td>[필수항목] 성명, 생년월일, 성별, 연락처, 주소, 프로그램 진행 중 촬영된 사진/영상</td>
							</tr>
							<tr>
								<th>수집, 이용 목적</th>
								<td>본인확인, 개인식별, 화성시민식별, 고지사항 전달(유선, 온라인), 홍보(SNS, 홈페이지, 기사, 책)</td>
							</tr>
							<tr>
								<th>보유 및 이용기간</th>
								<td>화성시 생활문화창작소가 유지되며 별도의 해지 요청이 있기 전까지 보관 <br/>(단, SNS 상의 이미지 또는 영상에 포함된 내용은 요청이 있을 시 해당 부분만 모자이크 처리함)</td>
							</tr>
						</tbody>
					</table>					
					<p>※ 귀하께서는 개인정보 제공에 대한 동의를 거부할 수 있으나, 동의 거부시 수강신청 및 시설이용이 제한될 수 있습니다.</p>
					<div class="chk_list">
						<div class="chk_inner">
							<div class="check_box">
								<input type="checkbox" id="agr1" name="agr" value="" checked onclick="return false;" />
								<label for="agr1">동의합니다</label>
							</div>
						</div>
					</div>
				</div>				
				<div class="tb_wraps sm sm2">
					<div class="tp">
						<div class="img"><img src="/admin/img/bf.png"/></div>
						<p>수강료(참가료) 반환기준</p>
					</div>
					<table>
						<colgroup>
							<col width="60%"/>
							<col width="40%"/>
						</colgroup>
						<thead>
							<tr>
								<th>반환기준내용</th>
								<th>반환금액</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="bdt no_bd">프로그램 시작일 7일 전에 취소신청을 한 경우</td>
								<td class="bdt">수강(참가)료, 재료·교재비 전액 반환</td>
							</tr>
							<tr>
								<td class="no_bd">프로그램 시작일 18:00까지 취소신청을 한 경우</td>
								<td>수강(참가)료 미반환, 재료·교재비 전액 반환</td>
							</tr>
							<tr>
								<td class="no_bd">프로그램 시작일 18:00이후 취소신청을 한 경우</td>
								<td>전액 미반환</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="tb_wraps sm">
					<div class="tp">
						<div class="img"><img src="/admin/img/bf.png"/></div>
						<p>수강자 준수사항</p>
					</div>
					<table>
						<colgroup>
							<col width="70px"/>
							<col width="*"/>
						</colgroup>
						<thead>
							<tr>
								<th>동의</th>
								<th>준수사항</th>
							</tr>
						</thead>
						<tbody >
							<tr>
								<td class="bdt no_bd ck">
									<div class="check_box">
										<input type="checkbox" id="agr2" name="agr" value="" checked onclick="return false;" />
										<label>동의 체크</label>
									</div>
								</td>
								<td class="bdt">생활문화창작소의 프로그램 운영 규칙을 준수하겠습니다.</td>
							</tr>
							<tr>
								<td class="bdt no_bd ck">
									<div class="check_box">
										<input type="checkbox" id="agr2" name="agr" value="" checked onclick="return false;" />
										<label>동의 체크</label>
									</div>
								</td>
								<td>프로그램 운영자 (관리자, 강사)의 지시를 잘 따르겠습니다.</td>
							</tr>
							<tr>
								<td class="bdt no_bd ck">
									<div class="check_box">
										<input type="checkbox" id="agr2" name="agr" value="" checked onclick="return false;" />
										<label>동의 체크</label>
									</div>
								</td>
								<td>부주의로 인하여 시설물 또는 집기류를 손상한 경우에는 배상할 것을 약속합니다. </td>
							</tr>
						</tbody>
					</table>
					<p>※ 원활한 프로그램 운영을 위해 시간을 준수해 주시기 바랍니다.</p>
				</div>
				<!-- <div class="tb_wraps sm">
					<div class="tp">
						<div class="img"><img src="/admin/img/bf.png"/></div>
						<p>개인정보 수집, 이용 동의</p>
					</div>
					<table>
						<colgroup>
							<col width="200px"/>
							<col width="*"/>
						</colgroup>
						<tbody>
							<tr>
								<th>수집, 이용하는 자</th>
								<td>화성시생활문화창작소</td>
							</tr>
							<tr>
								<th>수집하는 개인정보 항목</th>
								<td>[필수항목] 성명, 생년월일, 성별, 연락처, 주소, 프로그램 진행 중 촬영된 사진/영상</td>
							</tr>
							<tr>
								<th>수집, 이용 목적</th>
								<td>본인확인, 개인식별, 화성시민식별, 고지사항 전달(유선, 온라인), 홍보(SNS, 홈페이지, 기사, 책)</td>
							</tr>
							<tr>
								<th>보유 및 이용기간</th>
								<td>화성시 생활문화창작소가 유지되며 별도의 해지 요청이 있기 전까지 보관 <br/>(단, SNS 상의 이미지 또는 영상에 포함된 내용은 요청이 있을 시 해당 부분만 모자이크 처리함)</td>
							</tr>
						</tbody>
					</table>
				</div> -->
				<div class="btms">
					<p class="txt_c">본인은 위의 사항을 충분히 숙지하였으며, 화성시 생활문화창작소 프로그램 수강(참가)을 신청합니다.</p>
					<div class="wrp clear mt20">
						<div><p><?=Date('Y', strtotime($data['registdate']))?>년</p><p><?=Date('m', strtotime($data['registdate']))?>월</p><p><?=Date('d', strtotime($data['registdate']))?>일</p></div>
						<div class="last"><p>(본인) <?=$data['name']?></p><!--<p>(서명)</p>--></div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>