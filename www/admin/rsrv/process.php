<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Rsrv.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$notice = new Rsrv($pageRows, $tablename, $_REQUEST);
?>
<!doctype html>
<html lang="ko">
<head>
<? include $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php" ?>
</head>
<body>
<?
if (checkReferer($_SERVER["HTTP_REFERER"])) {
	$_REQUEST = xss_clean($_REQUEST);
	if ($_REQUEST['cmd'] == 'write') {
		
	
		$r = $notice->insert($_REQUEST);
		if ($r > 0) {
			echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 저장되었습니다.');
		} else {
			echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}
		

	} else if ($_REQUEST['cmd'] == 'edit') {

		$r = $notice->update($_REQUEST);

		if ($r > 0) {
			$arr['no'] = $_REQUEST['detail_no'];
			$arr['rdate'] = $_REQUEST['rdate'];
			$arr['rtime'] = $_REQUEST['rtime'];
			$arr['rhour'] = $_REQUEST['rhour'];

			$r2 = $notice->updateDetail($arr);

			if($r2 > 0){
				echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'view.php'), $_REQUEST['detail_no'], $_REQUEST), '정상적으로 수정되었습니다.');
			}else{
				echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'edit.php'), $_REQUEST['detail_no'], $_REQUEST), '요청처리중 장애가 발생하였습니다.');	
			}
		} else {
			echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'view.php'), $_REQUEST['detail_no'], $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}
		
	} else if ($_REQUEST['cmd'] == 'groupDelete') {

		$no = $_REQUEST['no'];
		
		$r = 0;
		for ($i=0; $i<count($no); $i++) {
			$r += $notice->delete($no[$i]);
		}

		if ($r > 0) {
			echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '총 '.$r.'건이 삭제되었습니다.');
		} else {
			echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}

	} else if ($_REQUEST['cmd'] == 'delete') {

		$no = $_REQUEST['no'];
		
		$r = $notice->delete($no);

		$r = $notice->detailDelete($no);

		if ($r > 0) {
			echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 삭제되었습니다.');
		} else {
			echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}
	} else if($_REQUEST['cmd'] == "cancel"){

		$r = $notice->cancel($_REQUEST);
		
		if($r > 0){
			echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'view.php'), $_REQUEST['detail_no'], $_REQUEST), '정상적으로 저장되었습니다.');
		}else{
			echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'view.php'), $_REQUEST['detail_no'], $_REQUEST), '요청처리중 장애가 발생하였습니다.');	
		}
	
	}


} else {
	echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.1');
}
?>
</body>
</html>