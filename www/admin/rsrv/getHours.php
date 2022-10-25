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
	//if (checkReferer($_SERVER["HTTP_REFERER"])) {
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

		$hourArr = array();
		$useArr = array();
		for($t = 9; $t <= 17; $t++){
			if(!in_array($t, $timeArr)){
				array_push($hourArr, $t);
			}else{
				array_push($useArr, $t);
			}


		}


		//print_r($hourArr);
		//echo "<br/><br/>";
		//print_r($useArr);


		$selTime = substr($_REQUEST['rtime'], 0, 2);
//		echo $selTime;

		if($selTime < 10){
			$selTime = substr($_REQUEST['rtime'], 1,1);
		}

	
		//선택시간이 가능 시간일 때 
		//선택 가능한 이용시간 추출
		if( in_array($selTime, $hourArr) ){
			
			$cnt = 0; 
			$h = 0; 
			for($i = 0; $i < count($hourArr); $i++){
				if($hourArr[$i] == $selTime + $cnt){
					$cnt ++;
					$h ++;
				}
			}
		
		}


?>
<select name="rhour" onchange="setPrice();">
	<option value="">이용시간</option>
	<?

		for($t = 1; $t <= $h; $t++){


	?>
	<option value="<?=$t?>" <?=getSelected($t, $_REQUEST['srhour'])?>><?=$t?>시간</option>
	<?
		}
	?>

	
</select>
<?/*
}else{
	echo "fail";
	exit;
}*/
?>