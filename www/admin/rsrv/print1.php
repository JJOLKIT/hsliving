<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Rsrv.class.php";

include "config.php";

$notice = new Rsrv($pageRows, $tablename, $_REQUEST);
$data = $notice->getDataDetail($_REQUEST[no], $userCon);

$list = rstToArray($notice->getGroupList($data['rsrv_fk'], 0));

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
			window.print();
			setTimeout(function(){
				window.close();		
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
						<p>대관신청서</p>
					</div>
					<table class="nrp">
						<colgroup>
							<col width="100px"/>
							<col width="130px"/>
							<col width="235px"/>
							<col width="130px"/>
							<col width="255px"/>
						</colgroup>
						<tbody>
							<tr>
								<th rowspan="8">신청인</th>
								<th>구분</th>
								<td colspan="4"><?=getGbName($data['gb'])?></td>
							</tr>
							<tr>
								<th class="has_bd" rowspan="3">사업자/단체명</th>
								<td rowspan="3"><?=$data['company']?></td>
								<th class="has_bd">대표자명</th>
								<td><?=$data['gb'] == 1 ? $data['name'] : "&nbsp;"?></td>
							</tr>
							<tr>
								<th class="has_bd">사업자등록번호</th>
								<td><?=$data['reg_number']?></td>
							</tr>
							<tr>
								<th class="has_bd">대표자 전화</th>
								<td><?=$data['gb'] == 1 ? $data['cell'] : "&nbsp;"?></td>
							</tr>
							<tr>
								<th class="has_bd">사업장 주소</th>
								<td colspan="4"><?=$data['gb'] == 1 ? $data['addr'] : "&nbsp;"?></td>
							</tr>
							<tr>
								<th class="has_bd" rowspan="2">담당자/개인명</th>
								<td rowspan="2"><?=$data['gb'] == 2 ? $data['name'] : "&nbsp;"?></td>
								<th class="has_bd">생년월일</th>
								<td><?=$data['birthday']?></td>
							</tr>
							<tr>
								<th class="has_bd">연락처</th>
								<td><?=$data['gb'] == 2 ? $data['cell'] : "&nbsp;"?></td>
							</tr>
							<tr>
								<th class="has_bd">주소(개인)</th>
								<td colspan="4"><?=$data['gb'] == 2 ? $data['addr'] : "&nbsp;"?></td>
							</tr>
							<tr>
								<th rowspan="3">사용목적</th>
								<th class="has_bd">활동명</th>
								<td colspan="4"><?=$data['title']?></td>
							</tr>
							<tr>
								<th class="has_bd">활동내용</th>
								<td colspan="4"><?=$data['contents']?></td>
							</tr>
							<tr>
								<th class="has_bd">참가인원</th>
								<td><?=number_format($data['amount'])?></td>
								<th class="has_bd">구분</th>
								<td><?=getPurposeName($data['purpose']) ?></td>
							</tr>
							<tr>
								<th rowspan="5">대관내용</th>
								<th>사용시설</th>
								<td colspan="3"><?=getPlaceName($data['place']) ?></td>
							</tr>
							<tr>
								<th rowspan="4" class="has_bd">사용일시</th>
								<td colspan="3">
									<?
										if($list[0] != ""){
											$lt = substr($list[0]['rtime'],0,2) + $list[0]['rhour'];
									?>
									<?=$list[0]['rdate']?> <?=substr($list[0]['rtime'],0,5)?>~<?=$lt?>:00 (<?=$list[0]['rhour']?>시간)
									<?}else{?>
									&nbsp;
									<?}?>
								</td>
							</tr>
							
							<tr>
								<td colspan="3">
									<?
										if($list[1] != ""){
											$lt = substr($list[1]['rtime'],0,2) + $list[1]['rhour'];
									?>
									<?=$list[1]['rdate']?> <?=substr($list[1]['rtime'],0,5)?>~<?=$lt?>:00 (<?=$list[1]['rhour']?>시간)
									<?}else{?>
									&nbsp;
									<?}?>
								</td>
							</tr>
							<tr>
								<td colspan="3">
									<?
										if($list[2] != ""){
											$lt = substr($list[0]['rtime'],0,2) + $list[2]['rhour'];
									?>
									<?=$list[2]['rdate']?> <?=substr($list[2]['rtime'],0,5)?>~<?=$lt?>:00 (<?=$list[2]['rhour']?>시간)
									<?}else{?>
									&nbsp;
									<?}?>
								</td>
							</tr>
							<tr>
								<td colspan="3">
									<?
										if($list[3] != ""){
											$lt = substr($list[3]['rtime'],0,2) + $list[3]['rhour'];
									?>
									<?=$list[3]['rdate']?> <?=substr($list[3]['rtime'],0,5)?>~<?=$lt?>:00 (<?=$list[3]['rhour']?>시간)
									<?}else{?>
									&nbsp;
									<?}?>
								</td>
							</tr>
							<tr>
								<th rowspan="2">대관료</th>
								<th class="has_bd">대관료</th>
								<td  colspan="3"><?=number_format($data['price'])?>원</td>
							</tr>
							<tr>
								<th class="has_bd">입금계좌</th>
								<td colspan="3">신한은행 140-013-805900 (예금주 : 수원여자대학교산학협력단)</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="tb_wraps sm">
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
				</div>
				<div class="btms mt20">
					<p class="txt_c">본인은 위의 내용에 동의하며, 화성시 생활문화창작소 시설 대관을 신청합니다.</p>
					<div class="wrp clear mt20">
						<div><p><?=Date('Y', strtotime($data['registdate']))?>년</p><p><?=Date('m', strtotime($data['registdate']))?>월</p><p><?=Date('d', strtotime($data['registdate']))?>일</p></div>
						<div class="last"><p>(본인) <?=$data['name']?></p><!--<p>(서명)</p>--></div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>