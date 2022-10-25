<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
     include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

     include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Rsrv.class.php";

	if($_REQUEST['place'] != "" && $_REQUEST['hours'] != ""){
		echo getPlacePrice($_REQUEST['place'], $_REQUEST['hours']);
	}
?>