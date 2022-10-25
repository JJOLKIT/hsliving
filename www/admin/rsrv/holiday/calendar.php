<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/orderform/Schedule.class.php";

$smonth = $_REQUEST['smonth'];

$s = new Schedule(9999, "orderform_schedule", $_REQUEST);
$result = rstToArray($s->getCalendar($smonth));
?>
				<table>
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
							
							$styleMouse = "";
							$dateStyle = "";
							
							if ($name == 1) {
							} else if ($name == 7) {
								$dateStyle = "blue";
							}
							
							if ($holiday != '0') {
								$dateStyle = "red";
							}
							
							if ($holiday == '0') {
								if ($name == 1) {
									$dateStyle = "red";
								}
							}
							
							$date = "<span class='".$dateStyle."'>".substr($today,8)."</span>";
							
							if ($i == 0 || 1 == $name) { 
				?>
					<tr>
				<?
							}
							if ($i == 0) {
								for ($j=0; $j<$name-1; $j++) {
				?>
						<td></td>
				<?
								}
							}
				?>
						<td>
							<a href="javascript:;" class="popupTrigger" data-popup-id="pop_date<?=$i?>"><span class="date <?=$dateStyle?>"><?=$date?></span></a>
							<? 
								if ($row['cnt'] > 0) {
									$result2 = rstToArray($s->getTodayList($row['today']));
									for ($m=0; $m<count($result2); $m++) {
										$row2 = $result2[$m];
							?>
										<span><a href="#" class="popupTrigger <? if ($row['type']==1) echo "red"; ?>" data-popup-id="pop_date<?=$i?><?=$m?>" title="수정하기"><?=$row2['title']?></a> <input type="button" value="x" title="삭제" class="del_btn" onclick="goDel('<?=$row2['no']?>');"/></span>
							<? 
									} 
								}		
							?>
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
		
		<!-- s:일정등록팝업-->
		<? 
			for ($i=0; $i<count($result); $i++) { 
				$row = $result[$i];	
		?>
		<div class="popup" data-popup-id="pop_date<?=$i?>">
			<div class="diary_wr popupContent">
				<div class="diary_top">
					<span class="title">상담신청내용등록</span>
					<p class="close" id="close<?=$i?>"></p>
				</div>
				<div class="box">
					<div class="write">
						<div class="wr_box">
						<form name="frm<?=$i?>" id="frm<?=$i?>">
							<table>
								<colgroup>
									<col width="20%">
									<col width="*">
								</colgroup>
								<tbody>
								<tr>
									<th>날짜</th>
									<td>
										<input type="text" name="startday" id="startday<?=$i?>"  value="<?=$row['today']?>" class="dateTime" readonly />
									</td>
								</tr>
								<tr>
									<th>예약</th>
									<td colspan="3">
										<input type="checkbox" name="type" id="type<?=$i?>" value="1" /> 예약불가
									</td>
								</tr>
								<tr>
									<th>내용</th>
									<td>
										<input type="text" name="title" id="title<?=$i?>"  value=""/>
									</td>
								</tr>
								</tbody>
							</table>
						<input type="hidden" name="cmd" id="cmd<?=$i?>" value="write"/>
						</form>	
						</div>
						<!-- //wr_box -->
						<div class="btnSet">
							<a href="javascript:;" class="btn" onclick="goSave('<?=$i?>');">저장</a>
						</div>
						<!-- //btnSet -->
					</div>
				</div>
			</div>
		</div>
		<? 
			if ($row['cnt'] > 0) {
				$result2 = rstToArray($s->getTodayList($row['today']));
				for ($m=0; $m<count($result2); $m++) {
					$row2 = $result2[$m];
		?>
		<div class="popup" data-popup-id="pop_date<?=$i?><?=$m?>">
			<div class="diary_wr popupContent">
				<div class="diary_top">
					<span class="title">상담신청내용등록</span>
					<p class="close" id="close<?=$i?><?=$m?>"></p>
				</div>
				<div class="box">
					<div class="write">
						<div class="wr_box">
						<form name="frm<?=$i?><?=$m?>" id="frm<?=$i?><?=$m?>">
							<table>
								<colgroup>
									<col width="20%">
									<col width="*">
								</colgroup>
								<tbody>
								<tr>
									<th>날짜</th>
									<td>
										<input type="text" name="startday" id="startday<?=$i?><?=$m?>"  value="<?=$row['today']?>" class="dateTime" readonly/>
									</td>
								</tr>
								<tr>
									<th>예약</th>
									<td colspan="3">
										<input type="checkbox" name="type" id="type<?=$i?><?=$m?>" value="1" <?=getChecked(1, $row2['type'])?>/> 예약불가
									</td>
								</tr>
								<tr>
									<th>내용</th>
									<td>
										<input type="text" name="title" id="title<?=$i?><?=$m?>"  value="<?=$row2['title']?>"/>
									</td>
								</tr>
								</tbody>
							</table>
						<input type="hidden" name="cmd" id="cmd<?=$i?><?=$m?>" value="edit"/>
						<input type="hidden" name="no" id="no<?=$i?><?=$m?>" value="<?=$row2['no']?>"/>
						</form>		
						</div>
						<!-- //wr_box -->
						<div class="btnSet">
							<a href="javascript:;" class="btn" onclick="goEdit('<?=$i?><?=$m?>');">수정</a>
						</div>
						<!-- //btnSet -->
					</div>
				</div>
			</div>
		</div>
		<?
				}
			}
		?>
		<? } ?>
		<!-- e:일정등록팝업-->