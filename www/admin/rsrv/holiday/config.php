<?
	$pageTitle			= "휴일관리";
	$tablename			= "holiday";
	$pageRows			= 10;
	$uploadPath			= "/upload/holiday/";			// 파일, 동영상 첨부 경로
	
	$maxSaveSize		= 10*1024*1024;					// 10Mb
	$userCon			= false;
	$isComment			= false;							// 댓글 사용여부
	$useFile  			= false;							// 파일 첨부 [사용: true, 사용 안함 : false]
	$useMovie  	 		= false;							// 파일 첨부 [사용: true, 사용 안함 : false]
	$useRelationurl		= false;							// 관련URL 첨부 [사용: true, 사용 안함 : false]
	$useMain			= false;							//메인 노출 사용

	if ($_REQUEST['pageRows'] != "") $pageRows = $_REQUEST['pageRows'];
?>