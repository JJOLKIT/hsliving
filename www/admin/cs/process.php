<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Cs.class.php";
include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$notice = new Cs($pageRows, $tablename, $_REQUEST);
?>
<!doctype html>
<html lang="ko">
<head>
<? include $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php" ?>
</head>
<body>
<?
if (checkReferer($_SERVER["HTTP_REFERER"])) {
	
	

	if ($_REQUEST['cmd'] == 'write') {
			$_POST = xss_clean($_POST);



			$_POST = fileupload('filename', $_SERVER['DOCUMENT_ROOT'].$uploadPath, $_POST, true, $maxSaveSize);		// 첨부파일
			$_POST = fileupload('filename2', $_SERVER['DOCUMENT_ROOT'].$uploadPath, $_POST, true, $maxSaveSize);		// 첨부파일
			$_POST = fileupload('filename3', $_SERVER['DOCUMENT_ROOT'].$uploadPath, $_POST, true, $maxSaveSize);		// 첨부파일
			
			$_POST['relation_url'] = COMPANY_URL;
			$_POST['company_name'] = COMPANY_NAME;
			$_POST['refer'] = REFERER_URL;


			$r = $notice->insert($_POST);

			
			if ($r > 0) {
				echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 저장되었습니다.');
			} else {
				echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
			}
		
		

	} else if ($_REQUEST['cmd'] == 'edit') {
		$_POST = xss_clean($_POST);


		$_POST = fileupload('filename', $_SERVER['DOCUMENT_ROOT'].$uploadPath, $_POST, true, $maxSaveSize);		// 첨부파일
		$_POST = fileupload('filename2', $_SERVER['DOCUMENT_ROOT'].$uploadPath, $_POST, true, $maxSaveSize);		// 첨부파일
		$_POST = fileupload('filename3', $_SERVER['DOCUMENT_ROOT'].$uploadPath, $_POST, true, $maxSaveSize);		// 첨부파일
		
		$_POST['relation_url'] = COMPANY_URL;
		$_POST['company_name'] = COMPANY_NAME;
		$_POST['refer'] = REFERER_URL;
		$r = $notice->update($_POST);

		if ($r > 0) {
			echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 수정되었습니다.');
		} else {
			echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
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

		if ($r > 0) {
			echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 삭제되었습니다.');
		} else {
			echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}
	}
	

} else {
	echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.1');
}
?>
</body>
</html>