<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Rsrv2.class.php";
include "config.php";

$rsrv = new Rsrv2(99, 'rsrv2', $_REQUEST);


if( !empty($_REQUEST['sprogram_fk']) && is_numeric($_REQUEST['sprogram_fk']) ){

	$chkCnt = $rsrv->getCount($_REQUEST);
	
	if($chkCnt[0] == 0){
		echo "ok";
		exit;
	} else {
		echo "over";
		exit;
	}
		
	
}




?>