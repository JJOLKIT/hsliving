<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Rsrv2.class.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/GalleryCt.class.php";

include $_SERVER['DOCUMENT_ROOT']."/include/loginCheck.php";
include "config.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Spam.class.php";
$spam = new Spam(999, 'spam', $_REQUEST);

$program = new GalleryCt(1, 'program', 'program_category', $_REQUEST);
$today = Date('Y-m-d');

$notice = new Rsrv2($pageRows, $tablename, $_REQUEST);
?>
<!doctype html>
<html lang="ko">
<head>
<? include $_SERVER['DOCUMENT_ROOT']."/include/headHtml.php" ?>
</head>
<body>
<?
if (checkReferer($_SERVER["HTTP_REFERER"])) {
	if ($_REQUEST['cmd'] == 'edit') {

		$_POST = xss_clean($_POST);

		$dc = $_POST['dc'];
		$program_fk = $_POST['program_fk'];


		$data = $program->getData($_POST['program_fk'], false);

		if( strtotime($today) > strtotime($data['eday'])){
			echo "
				<script>
					alert('신청 기간이 지났습니다.');
					history.back();
				</script>
			";
			exit;
		}

		if( strtotime($today) < strtotime($data['sday']) ){
			echo "
				<script>
					alert('신청 기간이 아닙니다.');
					history.back();
				</script>
			";
			exit;
		}




		if(!is_numeric($dc) || !is_numeric($program_fk) ){
			echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '잘못된 접근입니다.');
			exit;
		}

		$_POST['state'] = 5;
		//$_POST['pay_state'] = 1;
		$_POST['member_fk'] = $_SESSION['member_no'];

		$r = $notice->userUpdate($_POST);

		if ($r > 0) {
			echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 변경되었습니다.');

		} else {
			echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
			exit;
		}

	} else if($_REQUEST['cmd'] == "cancel"){
		$_REQUEST['refund_date'] = Date('Y-m-d H:i:s');
		$r = $notice->cancel($_REQUEST);
		
		if($r > 0){
			echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'view.php'), $_REQUEST['no'], $_REQUEST), '정상적으로 저장되었습니다.');
		}else{
			echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'view.php'), $_REQUEST['no'], $_REQUEST), '요청처리중 장애가 발생하였습니다.');	
		}
	
	}
	

} else {
	echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.1');
}
?>
</body>
</html>