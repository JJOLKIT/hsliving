<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Post.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$post = new Post($pageRows, $tablename, $_REQUEST);
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

		$_REQUEST = fileupload('filename', $_SERVER['DOCUMENT_ROOT'].$uploadPath, $_REQUEST, true, $maxSaveSize);		// 첨부파일
		$_REQUEST = fileupload('imagename', $_SERVER['DOCUMENT_ROOT'].$uploadPath, $_REQUEST, false, $maxSaveSize);	// 목록이미지

		$r = $post->insert($_REQUEST);
		if ($r > 0) {
			echo returnURLMsg($post->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 저장되었습니다.');
		} else {
			echo returnURLMsg($post->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}
		

	} else if ($_REQUEST['cmd'] == 'edit') {
	
		$_REQUEST = fileupload('filename', $_SERVER['DOCUMENT_ROOT'].$uploadPath, $_REQUEST, true, $maxSaveSize);		// 첨부파일
		$_REQUEST = fileupload('imagename', $_SERVER['DOCUMENT_ROOT'].$uploadPath, $_REQUEST, false, $maxSaveSize);	// 목록이미지

		$r = $post->update($_REQUEST);

		if ($r > 0) {
			echo returnURLMsg($post->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 수정되었습니다.');
		} else {
			echo returnURLMsg($post->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}
		
	} else if ($_REQUEST['cmd'] == 'groupDelete') {

		$no = $_REQUEST['no'];
		
		$r = 0;
		for ($i=0; $i<count($no); $i++) {
			$r += $post->delete($no[$i]);
		}

		if ($r > 0) {
			echo returnURLMsg($post->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '총 '.$r.'건이 삭제되었습니다.');
		} else {
			echo returnURLMsg($post->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}

	} else if ($_REQUEST['cmd'] == 'delete') {

		$no = $_REQUEST['no'];
		
		$r = $post->delete($no);

		if ($r > 0) {
			echo returnURLMsg($post->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 삭제되었습니다.');
		} else {
			echo returnURLMsg($post->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
		}
	}


} else {
	echo returnURLMsg($post->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.1');
}
?>
</body>
</html>