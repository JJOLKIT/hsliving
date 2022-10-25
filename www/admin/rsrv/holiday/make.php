<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/orderform/Schedule.class.php";

include "config.php";

$s = new Schedule($pageRows, $tablename, $_REQUEST);




 $dbconn = new DBConnection();
 $conn = $dbconn->getConnection();


$type = 5;
for($i = 1; $i <= 31; $i ++){
	
	$type ++;
	if($type > 7){ $type = 1; }

	if($i < 10){
		$i = "0".$i;
	}

	$sql = "
		INSERT INTO calender (today, name, registerDate) VALUES (
			'2020-01-".$i."' , $type, NOW()
		)
	";
}

mysql_close($conn);




?>



