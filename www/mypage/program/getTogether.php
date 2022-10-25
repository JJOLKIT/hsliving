<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/GalleryCt.class.php";

include "config.php";
 
$today = getToday();
$oneMonth = getMonthDateAdd(-1, $today);
$twoMonth = getMonthDateAdd(-2, $today);
$threeMonth = getMonthDateAdd(-3, $today);

$notice = new GalleryCt(20, 'program', 'program_category', $_REQUEST);
	if (checkReferer($_SERVER["HTTP_REFERER"])) {
		
		$data = $notice->getData($_REQUEST['no'], false);


		if($data['together'] == 3){
			$arr['together'] = "no" ;
			
		}
		else if($data['together'] == 1){
			$arr['together'] = "<option value=''>선택</option>";
			$arr['together'] .= "<option value='0'>동반인 없음</option>";
			$arr['together'] .= "<option value='1'>1인</option>";
			

		}else if($data['together'] == 2 ){

				$arr['together'] = "<option value=''>선택</option>";
				$arr['together'] .= "<option value='0'>동반인 없음</option>";
				$arr['together'] .= "<option value='1'>1인</option>";
				$arr['together'] .= "<option value='2'>2인</option>";
			
		}

		$arr['price'] = $data['price'];

		echo json_encode($arr);



	}else{
		echo "fail";
		exit;
	}
?>