<?
	$pageTitle			= "아카이빙";
	$tablename			= "gallery";
	$pageRows			= 9;
	$uploadPath			= "/upload/gallery/";			// 파일, 동영상 첨부 경로
	
	$maxSaveSize		= 50*1024*1024;					// 50Mb
	$userCon			= true;
	$isComment			= false;							// 댓글 사용여부
	$useFile  			= true;							// 파일 첨부 [사용: true, 사용 안함 : false]
	$useMovie  	 		= false;							// 파일 첨부 [사용: true, 사용 안함 : false]
	$useRelationurl		= false;							// 관련URL 첨부 [사용: true, 사용 안함 : false]
	$useMain			= false;							//메인 노출 사용

	if ($_REQUEST['pageRows'] != "") $pageRows = $_REQUEST['pageRows'];
	
?>