<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Rsrv.class.php";
include $_SERVER['DOCUMENT_ROOT']."/include/loginCheck.php";
include "config.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Spam.class.php";
$spam = new Spam(999, 'spam', $_REQUEST);
$notice = new Rsrv($pageRows, $tablename, $_REQUEST);
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

		
		$category = $_POST['category'];
		$gb = $_POST['gb'];
		$purpose = $_POST['purpose'];
		$dc = $_POST['dc'];
		$amount = $_POST['amount'];


		if(!is_numeric($category) || !is_numeric($gb) || !is_numeric($purpose) || !is_numeric($dc) || !is_numeric($amount)){
			echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '잘못된 접근입니다.');
			exit;
		}

		$_POST['state'] = 5;
		$_POST['member_fk'] = $_SESSION['member_no'];

		$r = $notice->userUpdate($_POST);


		if ($r > 0) {
			$notice->deleteDetail($_POST['no']);

			$arr['rsrv_fk'] = $_POST['no'];
			for($i = 0; $i < count($_POST['rdates']); $i++){
				$arr['rdate'] = $_POST['rdates'][$i];
				$arr['rtime'] = $_POST['rtimes'][$i];
				$arr['rhour'] = $_POST['rhours'][$i];

				$r2 += $notice->insertDetail($arr);
			}	


			if($r2 > 0){
				echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 수정되었습니다.');
				exit;
			}else{

				$notice->delete($r);
				echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
				exit;
			}
			
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