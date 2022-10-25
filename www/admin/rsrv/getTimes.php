<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
	include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

    include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Rsrv.class.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Schedule.class.php";

    include "config.php";

    $notice = new Rsrv($pageRows, $tablename, $_REQUEST);
	$sc = new Schedule(99, 'holiday', array());
	if (checkReferer($_SERVER["HTTP_REFERER"])) {

		$check = $sc->chkHoliday($_REQUEST['rdate']);
		if($check > 0){
			echo "holiday";
			exit;
		}

	


	$result = rstToArray($notice->getTimeListEdit($_REQUEST['place'], $_REQUEST['rdate'], $_REQUEST['no']));

	$timeArr = array();

	
	//시작시간 체크 설정
	for($j = 0; $j < count($result); $j++){

		$rtime = substr($result[$j]['rtime'], 0, 2);
		if($rtime < 10){
			$rtime = substr($result[$j]['rtime'], 1, 1);
		}
		$resultTime = 0;
		for($k = 0; $k < $result[$j]['rhour']; $k++){
			$resultTime = $rtime + $k;
			array_push($timeArr, $resultTime);
		}
	}
	

?>
<select name="rtime" onchange="checkTimes(this);">
	<option value="">시작시간</option>
	<?
	$time = "";
		for($t = 9; $t <= 17; $t++){
			if($t < 10){
				$time = "0".$t;
			}else{
				$time = $t;
			}



			if(!in_array($t, $timeArr)){



	?>
	<option value="<?=$time?>:00:00" <?=getSelected($time.":00:00", $_REQUEST['srtime'])?>><?=$time?>:00</option>
		
	<?		}
		}
	?>

	
</select>
<?
	
}else{
	echo "fail";
	exit;
}
?>