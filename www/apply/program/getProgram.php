<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/GalleryCt.class.php";

include "config.php";
 
$today = getToday();
$oneMonth = getMonthDateAdd(-1, $today);
$twoMonth = getMonthDateAdd(-2, $today);
$threeMonth = getMonthDateAdd(-3, $today);

$_REQUEST['sdisplay'] = 1;
$_REQUEST['stoday'] = Date('Y-m-d');


$notice = new GalleryCt(20, 'program', 'program_category', $_REQUEST);
$rowPageCount = $notice->getCount($_REQUEST);
$result = $notice->getList($_REQUEST);
$cresult = $notice->getCategoryList(array());

if (checkReferer($_SERVER["HTTP_REFERER"])) {



if($rowPageCount[0] == 0){ ?>
<tr>
	<td colspan ="5">프로그램이 없습니다.</td>
</tr>
<?}
else{
	while($row = mysql_fetch_assoc($result)){
		$arr['program_fk'] = $row['no'];
		$arr['rdate'] = $row['stday'];
		$arr['rtime'] = $row['rtime'];
		$count = $notice->getAmount($arr);
		if($count < $row['amount']){
?>

<tr>
	<td>
		<div class="radio_box">
			<p>
				<input type="radio" name="pg" class="program_fks" id="p<?=$row['no']?>"  value="<?=$row['no']?>" data-rtime="<?=substr($row['rtime'],0,5)?>" data-rdate="<?=$row['stday']?>" data-title="<?=$row['title']?>">
				<label for="p<?=$row['no']?>"><span>&nbsp;</span></label>
			</p>
		</div>
	</td>
	<td><p><?=$row['category_title']?></p></td>
	<td><p><?=$row['title']?></p></td>
	<td><p><?=$row['stday']?> <?=substr($row['rtime'], 0, 5)?></p></td>
	<td><p><?=number_format($count)?> / <?=$row['amount']?></p></td>
</tr>
<?}?>

<?}}


}else{
	echo "fail";
	exit;
}
?>