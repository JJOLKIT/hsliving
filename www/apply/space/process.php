<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Rsrv.class.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/email/SendMail.class.php";

include "config.php";
$notice = new Rsrv($pageRows, $tablename, $_REQUEST);
?>
<!doctype html>
<html lang="ko">
<head>
<? include $_SERVER['DOCUMENT_ROOT']."/include/headHtml.php" ?>
</head>
<body>
<?
if (checkReferer($_SERVER["HTTP_REFERER"])) {
	if ($_REQUEST['cmd'] == 'write') {

				$_POST = xss_clean($_POST);

				
				$category = $_POST['category'];
				$gb = $_POST['gb'];
				$purpose = $_POST['purpose'];
				$dc = $_POST['dc'];
				$amount = $_POST['amount'];


				if(!is_numeric($category) || !is_numeric($gb) || !is_numeric($purpose) || !is_numeric($dc) || !is_numeric($amount)){
					echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '잘못된 접근입니다.');
					exit;
				}

				$req['smember_fk'] = $SESSION['member_no'];
				$req['suser'] = 1;
				$ckCnt = rstToArray($notice->getDetailList($req));

				$_POST['state'] = 1;
				$_POST['member_fk'] = $_SESSION['member_no'];

				$r = $notice->insert($_POST);


				if ($r > 0) {
					
					$arr['rsrv_fk'] = $r;
					for($i = 0; $i < count($_POST['rdates']); $i++){
						$arr['rdate'] = $_POST['rdates'][$i];
						$arr['rtime'] = $_POST['rtimes'][$i];
						$arr['rhour'] = $_POST['rhours'][$i];

						$r2 += $notice->insertDetail($arr);
					}	


					if($r2 > 0){
						echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '정상적으로 신청되었습니다.\\n감사합니다.');
						exit;
					}else{

						$notice->delete($r);
						echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
						exit;
					}


					



					/*
					$title = "폼메일";
					$contents = "
							<table border='0' cellpadding='0' cellspacing='0' width='100%' style='font-family:굴림; font-size: 12px; color: #4b4b4b; border:#CCCCCC 1px solid;'>
							<tr height='30'>
								<td width=90 style='font-weight:bold; padding-left:5px; border-right:#CCCCCC 1px solid; border-bottom:#CCCCCC 1px solid; background-color: #f4f8fd;'>회사명</td>
								<td style='padding-left:5px; border-bottom:#CCCCCC 1px solid;'>".$_REQUEST['company']."</td>
							</tr>
							<tr height='30'>
								<td width=90 style='font-weight:bold; padding-left:5px; border-right:#CCCCCC 1px solid; border-bottom:#CCCCCC 1px solid; background-color: #f4f8fd;'>담당자명</td>
								<td style='padding-left:5px; border-bottom:#CCCCCC 1px solid;'>".$_REQUEST['name']."</td>
							</tr>
							<tr height='30'>
								<td width=90 style='font-weight:bold; padding-left:5px; border-right:#CCCCCC 1px solid; border-bottom:#CCCCCC 1px solid; background-color: #f4f8fd;'>연락처</td>
								<td style='padding-left:5px; border-bottom:#CCCCCC 1px solid;'>".$_REQUEST['cell']."</td>
							</tr>
							<tr height='30'>
								<td width=90 style='font-weight:bold; padding-left:5px; border-right:#CCCCCC 1px solid; border-bottom:#CCCCCC 1px solid; background-color: #f4f8fd;'>이메일</td>
								<td style='padding-left:5px; border-bottom:#CCCCCC 1px solid;'>".$_REQUEST['email']."</td>
							</tr>
							<tr height='30'>
								<td style='font-weight:bold; padding-left:5px; border-right:#CCCCCC 1px solid; border-bottom:#CCCCCC 1px solid; background-color: #f4f8fd;'>내용</td>
								<td style='padding-left:5px; border-bottom:#CCCCCC 1px solid;'>".$_REQUEST['contents']."</td>
							</tr>
							</table>";
						
					//$mailForm = getURLMakeMailForm(COMPANY_URL, EMAIL_FORM);
					$mailForm = file_get_contents_curl(COMPANY_URL.EMAIL_FORM);
					$mailForm = str_replace(":SUBJECT", $title, $mailForm);
					$mailForm = str_replace(":CONTENT", $contents, $mailForm);
						
					$sendmail = new SendMail();
					$sendmail->send(COMPANY_EMAIL, COMPANY_NAME, $_REQUEST['email'], $title, $mailForm);
					*/
					
				} else {
					echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
					exit;
				}

	}


} else {
	echo returnURLMsg($notice->getQueryString(getRemoviSslUrl($_SERVER["REQUEST_URI"], 'index.php'), 0, $_REQUEST), '요청처리중 장애가 발생하였습니다.');
	exit;
}
?>
</body>
</html>