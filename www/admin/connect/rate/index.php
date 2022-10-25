<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/weblog/pageLog.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";

$pageRows = 9999;
$weblog = new pageLog($pageRows, $_REQUEST);

$today = getToday();
//if ($_REQUEST['edate']) $today = $_REQUEST['edate'];
$pageTitle = "접속율 내역";

$tm = Date('Y-m');
$ty = Date('Y');
if(isset($_REQUEST['sdate']) && $_REQUEST['sdate'] != "" ){
	$today = getDayDateAdd(+3, $_REQUEST['sdate']);
	$tm = Date('Y-m', strtotime($_REQUEST['sdate']));
	$ty = Date('Y-m', strtotime($_REQUEST['sdate']));
}



$yesterday = getDayDateAdd(-1, $today);
$oneWeek = getDayDateAdd(-7, $today);
$oneMonth = getMonthDateAdd(-1, $today);
$twoMonth = getMonthDateAdd(-2, $today);
$threeMonth = getMonthDateAdd(-3, $today);


if(!isset($_REQUEST['stype'])){
	$_REQUEST['stype'] = "daily";
}

$total = $weblog->getChartCount($_REQUEST);
$btotal = $weblog->getChartBrowserCount($_REQUEST);

if($_REQUEST['stype'] == "daily"){
	$title = "일간 접속율";
}else if($_REQUEST['stype'] == "monthly"){
	$title = "월간 접속율";
}else if($_REQUEST['stype'] == "yearly"){
	$title = "연간 접속율";
}

?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<script>
//달력부분
$(window).load(function() {
	initCal({id:"sdate",type:"day",today:"y"});			
	initCal({id:"edate",type:"day",today:"y"});
	
	$("input[type=text][name*=sval]").keypress(function(e){
		if(e.keyCode == 13){
			goSearch();
		}
	});
	
	
});

function goSearch() {
	$("#search").attr("action","index.php");
	$("#search").submit();
}

function resetSearchForm() {
	$(".searchWrap input[type='text']").val("");
	$(".searchWrap input[type='checkbox']").removeAttr("checked");
	$(".searchWrap input[type='radio']").removeAttr("checked");
	$(".searchWrap select").val("all");
	goSearch()();
}

function searchDate(startDay, endDay) {
	var f = document.search;
	f.sdate.value = startDay;
	f.edate.value = endDay;

	goSearch();
}



</script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Day', 'PC', 'MOBILE'],
		<?
			if($_REQUEST['stype'] == "daily"){	

			for($i = 0; $i <= 7; $i++){
				if($i > 0){ echo ","; }
				echo "['". getDayDateAdd(-(7-$i), $today)."', ".$total['p'.(7-$i)].", ".$total['m'.(7-$i)]."]" ;

			}}
			else if($_REQUEST['stype'] == "monthly"){
				for($i = 0; $i <= 7; $i++){
					if($i > 0){ echo ","; }
					echo "['".substr(getMonthDateAdd(-(6-$i), $tm), 0, 7)."', ".$total['p'.(7-$i)].", ".$total['m'.(7-$i)]."]";
				}
			}
			else if($_REQUEST['stype'] == "yearly"){
				for($i = 0; $i <= 7; $i++){
					if($i > 0 ) { echo ","; }
					echo "['".substr(getYearDateAdd(-(6-$i), $ty), 0, 4)."', ".$total['p'.(7-$i)].", ".$total['m'.(7-$i)]."]";
				}
			}
			?>


        ]);

        var options = {
          title: '<?=$title?>',
          curveType: 'line',
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
	  function goSaerchAll(){
		$('#sdate').val('');
		goSearch();
	  }

</script>

</head>

<body>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>
<!-- s:warp -->
	<div class="contWrap">
		<div class="titWrap">
			<h2>접속율 통계</h2>
			<div class="sBtn reset">
				<span class="material-icons">restart_alt</span>
				<input type="button" value="검색초기화"  class="reset"  onclick="resetSearchForm();"/>
			</div>
		</div>
		<div class="searchWrap">
			<form method="get" name="search" id="search" action="index.php">
				<table class="searchTable">
					<caption> 검색 </caption>
					<colgroup>
						<col width="10%" />
						<col width="40%" />
						<col width="10%" />
						<col width="40%" />
					</colgroup>
					<tbody>
						<tr>
							<th>검색일</th>
							<td>
								<p class="calendar">
									<input type="text" name="sdate" id="sdate"  value="<?=$_REQUEST['sdate']?>" class="date" onKeyUp="cvtDate(this);isNumberOrHyphen(this);" maxlength="10"/>
									<span id="CalsdateIcon">
										<span class="material-icons" id="CalsdateIconImg">calendar_month</span>
									</span>
								</p>
								<input type="button" value="전체" onclick="goSaerchAll();" class="hoverbg" />
								<input type="button" value="검색" onclick="goSearch();" class="hoverbg" />
							</td>
							<th>검색구분</th>
							<td>
								<span class="select">
									<select name="stype" onchange="goSearch();">
										<option value="">선택</option>
										<option value="daily" <?=getSelected("daily", $_REQUEST['stype'])?>>일간</option>
										<option value="monthly" <?=getSelected("monthly", $_REQUEST['stype'])?>>월간</option>
										<option value="yearly" <?=getSelected("yearly", $_REQUEST['stype'])?>>연간</option>
									</select>
								</span>
							</td>
						</tr>
					</tbody>
				</table>
			</form>
		</div>
		
		<!-- //search -->

		<div id="curve_chart" class="chart_rate"></div>
		<div class="list">
			<form>
				<table>
					<colgroup>
						<col width="33%"/>
						<col width="33%"/>
						<col width="33%"/>
					</colgroup>
					<thead>
						<tr>
							<th>일자</th>
							<th>PC</th>
							<th>MOBILE</th>
						</tr>
					</thead>
					<tbody>
						<?if($_REQUEST['stype'] == "daily"){
						$dif = "";
							for($i = 7; $i  >= 0; $i --){
						?>
						<tr>
							<td><?=getDayDateAdd(-(7-$i), $today)?> <?if(getDayDateAdd(-(7-$i), $today) == Date('Y-m-d')){ echo "(당일)"; }?></td>
							<td><?=number_format($total['p'.(7-$i)])?> 
								<?if($i != 0 ){
									$dif = $total['p'.(7-$i)] - $total['p'.(7-($i-1))] ;
									if($dif > 0){
										echo "<font color='green'>↑".number_format($dif)."</font>";
									}else if($dif < 0){
										echo "<font color='red'>↓".number_format($dif)."</font>";
									}else{
										echo "";
									}
								}?>
							</td>
							<td><?=number_format($total['m'.(7-$i)])?>
								<?if($i != 0 ){
									$dif = $total['m'.(7-$i)] - $total['m'.(7-($i-1))] ;
									if($dif > 0){
										echo "<font color='green'>↑".number_format($dif)."</font>";
									}else if($dif < 0){
										echo "<font color='red'>↓".number_format($dif)."</font>";
									}else{
										echo "";
									}
			

								}?>
							</td>
						</tr>
						<?
							}
						
						}else if($_REQUEST['stype'] == "monthly"){
							$dif = "";
							 for($i = 7; $i >= 0; $i--){
							 ?>
						<tr>
							<td><?=substr(getMonthDateAdd(-(7-$i), $tm), 0, 7)?> <?if(substr(getMonthDateAdd(-(7-$i), $tm), 0, 7) == Date('Y-m')){ echo "(당월)";}?></td>
							<td><?=number_format($total['p'.(7-$i)])?>
								<?if($i != 0 ){
									$dif = $total['p'.(7-$i)] - $total['p'.(7-($i-1))] ;
								
									if($dif > 0){
										echo "<font color='green'>↑".number_format($dif)."</font>";
									}else if($dif < 0){
										echo "<font color='red'>↓".number_format($dif)."</font>";
									}else{
										echo "";
									}
								}?>
							</td>
							<td><?=number_format($total['m'.(7-$i)])?>
								<?if($i != 0 ){
									$dif = $total['m'.(7-$i)] - $total['m'.(7-($i-1))] ;
									if($dif > 0){
										echo "<font color='green'>↑".number_format($dif)."</font>";
									}else if($dif < 0){
										echo "<font color='red'>↓".number_format($dif)."</font>";
									}else{
										echo "";
									}
								}?>
							</td>
						</tr>
							 <?
							 }
						}else if($_REQUEST['stype'] == "yearly"){
							$dif = "";
							for($i = 7; $i>= 0; $i--){
							?>
						<tr>
							<td><?=substr(getYearDateAdd(-(7-$i), $ty), 0, 4)?> <?if(substr(getYearDateAdd(-(7-$i), $ty), 0, 4) == Date('Y')){ echo "(금년)";}?></td>
							<td><?=number_format($total['p'.(7-$i)])?>
								<?if($i != 0 ){
									$dif = $total['p'.(7-$i)] - $total['p'.(7-($i-1))] ;
									if($dif > 0){
										echo "<font color='green'>↑".number_format($dif)."</font>";
									}else if($dif < 0){
										echo "<font color='red'>↓".number_format($dif)."</font>";
									}else{
										echo "";
									}
								}?>
							</td>
							<td><?=number_format($total['m'.(7-$i)])?>
								<?if($i != 0 ){
									$dif = $total['m'.(7-$i)] - $total['m'.(7-($i-1))] ;
									if($dif > 0){
										echo "<font color='green'>↑".number_format($dif)."</font>";
									}else if($dif < 0){
										echo "<font color='red'>↓".number_format($dif)."</font>";
									}else{
										echo "";
									}
								}?>
							</td>
						</tr>
							<?
							}
						}?>

					</tbody>
				</table>
			</form>
		</div>
	</div>
</div>
<!-- e:warp --> 
<? include $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>

</body>
</html>
