<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Schedule.class.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Rsrv2.class.php";
include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php"; 

$smonth = $_REQUEST['smonth'];
$preYear = getYearMonth($smonth, -12);
$preMonth = getYearMonth($smonth, -1);
$curMonth = getYearMonth($smonth, 0);
$nexMonth = getYearMonth($smonth, 1);
$nexYear = getYearMonth($smonth, 12);

$s = new Schedule(99, 'holiday', $_REQUEST);
$notice = new Rsrv2($pageRows, $tablename, $_REQUEST);

$result = rstToArray($s->getCalendar($curMonth));
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>

<script>
function goSave(v) {
	if ($("#title"+v).val() == "") {
		alert("내용을 입력해 주세요");
		$("#title"+v).focus();
		return false;
	}
	$.ajax({
		type : "POST",
		url: "process.php",
		async: false,
		data: {
			startday : $("#startday"+v).val(),
			type : $("#type"+v+":checked").val(),
			title : $("#title"+v).val(),
			cmd : "write"
		},
		success: function( data ) {
			var r = data.trim();
			//$("#close"+v).trigger('click');
			location.reload(true);
		},
		error:function(e) {
			alert(e.responseText);
		}
	});
}

function goEdit(v) {
	if ($("#title"+v).val() == "") {
		alert("내용을 입력해 주세요");
		$("#title"+v).focus();
		return false;
	}
	$.ajax({
		type : "POST",
		url: "process.php",
		async: false,
		data: {
			startday : $("#startday"+v).val(),
			type : $("#type"+v+":checked").val(),
			title : $("#title"+v).val(),
			cmd : $("#cmd"+v).val(),
			no : $("#no"+v).val()
		},
		success: function( data ) {
			var r = data.trim();
			//$("#close"+v).trigger('click');
			location.reload(true);
		},
		error:function(e) {
			alert(e.responseText);
		}
	});
}

function goDel(v) {
	if (confirm("삭제하시겠습니까?")) {
		$.ajax({
			type : "POST",
			url: "process.php",
			async: false,
			data: {
				cmd : "delete",
				no : v
			},
			success: function( data ) {
				var r = data.trim();
				location.reload(true);
			},
			error:function(e) {
				alert(e.responseText);
			}
		});
	}
}
</script>
<style>
	a.tcname {background:#ddd; display:block; border-radius:5px; margin-top:5px; font-size:1.3rem; padding:1px 5px; opacity:0.8; }
	a.tcname.tc2 {background:#ffd247;}
	a.tcname.state1 {color:green;}
	a.tcname.state2 {background:#ffd247; opacity:1;}
	a.tcname.state3 {color:red;}
	a.tcname.state4 {color:gray;}





	.diaryList {margin-top:20px;}
	.diary li{}
</style>
</head>


<body>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>
<!-- s:warp -->
	<div class="contWrap"> 
		<div class="titWrap">
			<h2><?=$pageTitle ?></h2>
		</div>
		<div class="diary btnSet clear">
			<ul style="display:inline-block;">
				<li><a href="index.php?smonth=<?=$preYear?>"class="board first" title="이전년도">이전년도</a></li>
				<li><a href="index.php?smonth=<?=$preMonth?>"class="board prev" title="이전달">이전달</a></li>
				<li class="month"><?=substr($curMonth, 0, 4)?>년 <?=substr($curMonth, 5)?>월</li>
				<li><a href="index.php?smonth=<?=$nexMonth?>"class="board next" title="다음달">다음달</a></li>
				<li><a href="index.php?smonth=<?=$nexYear?>"class="board last" title="다음년도">다음년도</a></li>
			</ul>
		</div>
		<!-- //pagenate -->
		<div class="diaryList">
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
						<td ></td>
				<?
								}
							}
				?>
						<td>
			
							<span class="date <?=$dateStyle?>"><?=$date?></span>
				

							<? 
								if ($row['cnt'] > 0) {
									$result2 = rstToArray($s->getTodayList($row['today']));
									for ($m=0; $m<count($result2); $m++) {
										$row2 = $result2[$m];
							?>
										<span style="color:red;"><?=$row2['title']?></span>
							<? 
									} 
								}else{
									$req['srdate'] = $row['today'];
									//$list = rstToArray($notice->getDetailList($req));
									$list = rstToArray($notice->getProgramList($req));

									for($v = 0; $v < count($list); $v++){
										if(  strtotime(Date('Y-m-d'))  < strtotime($list[$v]['sday']))  {
											$dstate = 1;
										}else if( strtotime(Date('Y-m-d')) >= strtotime($list[$v]['sday']) && strtotime(Date('Y-m-d')) <= strtotime($list[$v]['eday'])  ){
											$dstate = 2;
										}else if( strtotime(Date('Y-m-d')) > strtotime($list[$v]['eday']) ){
											$dstate = 3;
										}

										if($list[$v]['amount'] == $list[$v]['cnt'] + $list[$v]['together']){
											$state = 2;
										}else{
											$state = 1;
										}
										?>
										<a href="list.php?sprogram_fk=<?=$list[$v]['program_fk']?>&srdate=<?=$row['today']?>" class="tcname tc1 clear state<?=$state?> dstate<?=$dstate?>"><span class="fl_l"><?=$list[$v]['title']?> (<?=substr($list[$v]['rtime'], 0, 5)?>)</span><span class="fl_r"><?=number_format($list[$v]['cnt'] + $list[$v]['together'])?>명 / <?=number_format($list[$v]['amount'])?>명</span></a>
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
		</div>
		<!-- //list -->
		<div class="caption mt20">
			<ul>
				<li>
					<span style="color:green; background:#ddd; padding:1px 5px; display:inline-block; opacity:0.8;">정원미달</span> <span style="opacity:1; background:#ffd247; color:black; padding:1px 5px; display:inline-block;">정원</span>
				
				</li>
			</ul>
		</div>
	</div>
	<!-- //contents -->
</div>
<!-- e:warp --> 
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>

</body>
</html>
