<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Rsrv2.class.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/GalleryCt.class.php";
include "config.php";

$notice = new Rsrv2($pageRows, $tablename, $_REQUEST);


$program = new GalleryCt(1, 'program', 'program_category', $_REQUEST);
if( !empty($_REQUEST['program_fk']) && is_numeric($_REQUEST['program_fk']) ){

	$data = $program->getData($_REQUEST['program_fk'], false);

	

	$pcount = $data['count'] + $data['sum_together'];
	$pamount = $data['amount'];
	$plimit = $pamount - $pcount;
	
	echo $plimit;

	/* if($pcount >= $pamount){
		echo $plimit;
	} else if ($pcount < $pamount) {
		echo "ok";
	} */
	
	
}




?>