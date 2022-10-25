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
if (checkReferer($_SERVER["HTTP_REFERER"])) {
	$data = $notice->getDataDetail($_REQUEST[no], $userCon);
?>

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
										<td><p><?=getPlaceName($data['place'])?></p></td>
									</tr>
									<tr>
										<th>
											<p>대관 시간</p>
										</td>
										<td>
											<?
												$lastTime = substr($data['rtime'], 0, 2) + $data['rhour'] ;										
											?>
										<p><?=Date('Y.m.d', strtotime($data['rdate']))?> <?=substr($data['rtime'],0,2).":00"?> - <?=$lastTime.":00"?></p></td>
									</tr>
									<tr>
										<th>
											<p>대관 확정 유무</p>
										</td>
										<td><p>
											<?if($data['state'] == 1){ echo "대관 접수"; }else if($data['state'] == 2){ echo "대관 확정"; }?></p></td>
									</tr>

								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?}else{
echo "fail";
exit;
}?>