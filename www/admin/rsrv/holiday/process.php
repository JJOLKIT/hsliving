<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Schedule.class.php";

include "config.php";

$s = new Schedule($pageRows, $tablename, $_REQUEST);
?>
<?
//if (checkReferer($_SERVER["HTTP_REFERER"])) {

	if ($_REQUEST['cmd'] == 'write') {
		//$_REQUEST = fileupload('filename', $_SERVER['DOCUMENT_ROOT'].$uploadPath, $_REQUEST, true, $maxSaveSize);		// 첨부파일

		$r = $s->insert($_REQUEST);

	} else if ($_REQUEST['cmd'] == 'edit') {

		//$_REQUEST = fileupload('filename', $_SERVER['DOCUMENT_ROOT'].$uploadPath, $_REQUEST, true, $maxSaveSize);		// 첨부파일

		$r = $s->update($_REQUEST);

	} else if ($_REQUEST['cmd'] == 'delete') {

		$no = $_REQUEST['no'];
		
		$r = $s->delete($no);

	}


//} else {
//}
?>
<?=$r ?>