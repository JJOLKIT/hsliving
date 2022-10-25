<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Rsrv2.class.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/email/SendMail.class.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/GalleryCt.class.php";
$program = new GalleryCt(1, 'program', 'program_category', $_REQUEST);
$today = Date('Y-m-d');

include "config.php";
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
	if ($_REQUEST['cmd'] == 'write') {

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

		$req['sprogram_fk'] = $_POST['program_fk'];
		$req['srdate'] = $_POST['rdate'];
		$req['smember_fk'] = $SESSION['member_no'];
		$rCnt = $notice->getCheckCount($req);

		
		if($rCnt['cnt'] > 0){
			echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '이미 해당 프로그램을 신청하셨습니다.');
			exit;
		} else {
			$_POST['state'] = 1;
			$_POST['pay_state'] = 1;
			$_POST['member_fk'] = $_SESSION['member_no'];

			$r = $notice->insert($_POST);


			if ($r > 0) {
				echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 신청되었습니다.\n감사합니다.');

			} else {
				echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
				exit;
			}
		}




		

	}


} else {
	echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
	exit;
}
?>
</body>
</html>