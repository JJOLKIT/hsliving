<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Visual.class.php";




include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";


$notice = new Visual($pageRows, $tablename, $_REQUEST);

if($_REQUEST['type'] == "up"){
	$r = $notice->upSeq($_REQUEST['seq'], $_REQUEST['no']);
	if($r > 0){
		echo "success";
	}else{
		echo "fail";
	}
}else if($_REQUEST['type'] == "down"){
	$r = $notice->downSeq($_REQUEST['seq'], $_REQUEST['no']);
	if($r > 0){
		echo "success";
	}else {
		echo "fail";
	}
}

?>
