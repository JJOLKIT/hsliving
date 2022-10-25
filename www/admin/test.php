<?session_start();?>
<? include $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<? include $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<?
$d = date("ymd");
$data = 12;
?>
<?=$d ?><br/>
<?=str_pad($data,"5","0",STR_PAD_LEFT)?><br/>
<?=date('w', strtotime($d))?>