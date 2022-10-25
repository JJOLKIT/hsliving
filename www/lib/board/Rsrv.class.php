<?
/*


*/

include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db/DBConnection.class.php";

class Rsrv {

	// 검색 파라미터 (초기 개발시 검색조건 세팅필요)
	var $param = array (
					"pageRows",
					"stype",
					"sval",
					"sdateType",
					"sstartdate",
					"senddate",
					"sgb",
					"scategory",
					"sstate",
					"smember_fk"
				);

	var $tableName;			// 테이블명
	var $pageRows;			// 페이지 로우수
	var $startPageNo=0;		// limit 시작페이지
	public $reqPageNo=1;	// 요청페이지
	var $conn;


	


	// 생성자
	function Rsrv($pageRows=0, $tableName='', $request='') {
		$this->pageRows = $pageRows;
		$this->tableName = $tableName;
		$this->reqPageNo = ($request['reqPageNo'] == 0) ? 1 : $request['reqPageNo'];	// 요청페이지값 없을시 1로 세팅
		if ($request['reqPageNo'] > 0) {
			$this->startPageNo = ($request['reqPageNo']-1) * $this->pageRows;
		}
	}

	// 검색 파라미터 queryString 생성
	function getQueryString($page="", $no=0, $request='') {	
		$str = '';
		
		for ($i=0; $i<count($this->param); $i++) {
			if (chkIsset($request[$this->param[$i]] )) {
				$str .= $this->param[$i]."=".$request[$this->param[$i]]."&";
			}
		}

		if ($no > 0) $str .= "no=".$no;			// no값이 있을 경우에만 파라미터 세팅 (페이지 이동시 no필요 없음)

		$return = '';
		if ($str) {
			$return = $page.'?'.$str;
		} else {
			$return = $page.'?';
		}
		
		return $return;
	}
	
	/**
	 * 검색조건에 맞는 hidden값 자동 생성
	 * @param string $request
	 * @return string
	 */
	function getQueryStringToHidden($request='') {
		$str = '';
	
		for ($i=0; $i<count($this->param); $i++) {
			if (chkIsset($request[$this->param[$i]] )) {
				$str .= "<input type='hidden' name='".$this->param[$i]."' value='".$request[$this->param[$i]]."'/>\n";
			}
		}
	
		return $str;
	}

	// sql WHERE절 생성
	function getWhereSql($p) {
		$whereSql = " WHERE 1=1";


		if ($p['sval']) {
			if ($p['stype'] == 'all') {
				$whereSql .= " AND ( (name like '%".$p['sval']."%' ) or (title like '%".$p['sval']."%' ) or (contents like '%".$p['sval']."%') ) ";
			} else {
				$whereSql .= " AND (".$p['stype']." LIKE '%".$p['sval']."%' )";
			}
		}
		if ($p['sstartdate'] != '') {
			if ($p['senddate'] != '') {
				$whereSql .= " AND (registdate BETWEEN '".$p['sstartdate']." 00:00:00' AND '".$p['senddate']." 23:59:59') ";
			}
		}

		if($p['scategory'] != ""){
			$whereSql .= " AND category  = ".$p['scategory'];
		}
		if($p['sprogram_fk'] != ""){
			$whereSql .= " AND program = ".$p['sprogram_fk'];
		}
		
		if($p['sstate'] != ""){
			$whereSql .= " AND state = ".$p['sstate'];
		}

		if($p['sgb'] != ""){
			$whereSql .= " AND gb = ".$p['sgb'];
		}
		
		if($p['smember_fk'] != ""){
			$whereSql .= " AND member_fk = ".$p['smember_fk'];
		}

		


// 		if ($p['smain'] != '') {
// 			$whereSql .= " AND main = ".$p['smain'];
// 		}
		return $whereSql;
	}


	// 전체로우수, 페이지카운트
	function getCount($param = "") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$param = escape_string($param);
		
		$whereSql = $this->getWhereSql($param);	// where절
		$sql = " SELECT COUNT(*) AS cnt FROM ".$this->tableName.$whereSql;

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		$row=mysql_fetch_array($result);
		$totalCount = $row['cnt'];
		$pageCount = getPageCount($this->pageRows, $totalCount);

		$data[0] = $totalCount;
		$data[1] = $pageCount;

		return $data;
	}

	// 목록
	function getList($param='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection(); //DB CONNECT
		$param = escape_string($param);
		
		$whereSql = $this->getWhereSql($param);	// where절
		

		$sql = "
			SELECT *
			FROM ".$this->tableName."
			".$whereSql."
			ORDER BY registdate DESC LIMIT ".$this->startPageNo.", ".$this->pageRows." ";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}




	// 관리자 등록
	function insert($req="") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$req = escape_string($req);
		//$gno = $this->getMaxGno();
		
		$sql = "
			INSERT INTO ".$this->tableName." (
			member_fk, category, place, gb, company, reg_number, birthday, addr, purpose, amount, dc, dc_txt, price, state, pay_state, cell, name, email, title, contents, relation_url, registdate, ";
		if ($req[filename]) {
			$sql .= "filename, filename_org, filesize, ";
		}
		if ($req[moviename]) {
			$sql .= "moviename, moviename_org, ";
		}
		if ($req[imagename]) {
			$sql .= "imagename, imagename_org, image_alt, ";
		}
		if ($req[readno]) {
			$sql .= "readno, ";
		}
		$sql .= "top, newicon
			) VALUES (
			".chkIsset($req[member_fk]).",
			".chkIsset($req['category']).", 
			".chkIsset($req['place']).", 
			".chkIsset($req['gb']).", 
			'$req[company]', 
			'$req[reg_number]', 
			'$req[birthday]', 
			'$req[addr]', 
			".chkIsset($req['purpose']).", 
			".chkIsset($req['amount']).", 
			".chkIsset($req['dc']).", 
			".chkIsset($req['dc_txt']).", 
			".chkIsset($req['price']).", 
			".chkIsset($req['state']).", 
			".chkIsset($req['pay_state']).", 
			'$req[cell]',
			'$req[name]', 
			'$req[email]', 
			'$req[title]',
			'$req[contents]',
			'$req[relation_url]', ";
		if ($req[registdate]) { 
			$sql .= "'$req[registdate]', ";
		} else {
			$sql .= " NOW(), ";
		}
		if ($req[filename]) {
			$sql .= "'$req[filename]', '$req[filename_org]', $req[filesize], ";
		}
		if ($req[moviename]) {
			$sql .= "'$req[moviename]', '$req[moviename_org]', ";
		}
		if ($req[imagename]) {
			$sql .= "'$req[imagename]', '$req[imagename_org]', '$req[image_alt]', ";
		}
		if ($req[readno]) {
			$sql .= chkIsset($req[readno]).", ";
		}
		
		$sql .= "
			".chkIsset($req[top]).",
			".chkIsset($req[newicon])."
			)";

		mysql_query($sql, $conn);


		outlog($sql);
		$sql = "SELECT LAST_INSERT_ID() AS lastNo";
		$result = mysql_query($sql, $conn);
		$row = mysql_fetch_array($result);
		$lastNo = $row['lastNo'];
		mysql_close($conn);
		return $lastNo;
	}

	// 수정
	function update($req="") {

		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$req = escape_string($req);
		// 기존 첨부파일 삭제
		if ($req[filename_chk] == "1") {
			mysql_query("UPDATE ".$this->tableName." SET filename='', filename_org='', filesize=0 WHERE no=".$req[no], $conn);
		}
		// 기존 동영상파일 삭제
		if ($req[moviename_chk] == "1") {
			mysql_query("UPDATE ".$this->tableName." SET moviename='', moviename_org='' WHERE no=".$req[no], $conn);
		}
		// 기존 목록이미지 삭제
		if ($req[imagename_chk] == "1") {
			mysql_query("UPDATE ".$this->tableName." SET imagename='', imagename_org='' WHERE no=".$req[no], $conn);
		}

		$sql = "
			UPDATE ".$this->tableName." SET 
				place = ".chkIsset($req[place]).", state = ".chkIsset($req[state]).", gb= ".chkIsset($req[gb]).", 
				birthday = '$req[birthday]',
				cell = '$req[cell]', addr = '$req[addr]',
				purpose = ".chkIsset($req[purpose]).", amount = ".chkIsset($req[amount]).", company = '$req[company]', reg_number = '$req[reg_number]',
				name='$req[name]', email='$req[email]', title='$req[title]', contents='$req[contents]',
        		relation_url='$req[relation_url]', price= ".chkIsset($req[price]).", ";
		if ($req[filename]) {
			$sql .= "filename='$req[filename]', filename_org='$req[filename_org]', filesize=$req[filesize], ";
		}
		if ($req[moviename]) {
			$sql .= "moviename='$req[moviename]', moviename_org='$req[moviename_org]', ";
		}
		if ($req[imagename]) {
			$sql .= "imagename='$req[imagename]', imagename_org='$req[imagename_org]', image_alt='$req[image_alt]', ";
		}
		if ($req[readno]) {
			$sql .= "readno=".chkIsset($req[readno]).", ";
		}
		if ($req[registdate]) { 
			$sql .= "registdate='$req[registdate]', ";
		}
		$sql .= " top=".chkIsset($req[top]).", newicon=".chkIsset($req[newicon])."
			WHERE no = ".$req['no'];
		outlog($sql);
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}

	// 삭제
	function delete($no=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		

		$sql = " DELETE FROM ".$this->tableName." WHERE no = ".$no;

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}

	// 디테일 삭제
	function detailDelete($no=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		

		$sql = " DELETE FROM ".$this->tableName."_detail WHERE rsrv_fk = ".$no;

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}


	// 목록
	function getData($no=0, $userCon) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();


		$sql = "
			SELECT *
			FROM ".$this->tableName."
			WHERE no = ".$no;

		// 조회수 증가
		if ($userCon) {
			mysql_query("UPDATE ".$this->tableName." SET readno=readno+1 WHERE no=".$no, $conn);
		}
		
		$result = mysql_query($sql, $conn);
		
		$data = mysql_fetch_assoc($result);

		mysql_close($conn);

		return $data;
	}

	// 파일명 가져오기
	function getFilenames($no=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			SELECT 
				filename, moviename, imagename
			FROM ".$this->tableName."
			WHERE no = ".$no;
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}

	// rownum 구하기
	function getRowNum($req='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$req = escape_string($req);
		$whereSql = $this->getWhereSql($req);	// where절

		$sql = "
			SELECT rownum FROM (
				SELECT @rownum:=@rownum+1 AS rownum, no, title FROM ".$this->tableName.", (SELECT @rownum:=0) AS r
				".$whereSql."
				ORDER BY top DESC, registdate DESC
			) AS r2
			WHERE r2.no = ".$req['no'];
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		
		$row=mysql_fetch_array($result);
		$rownum = $row['rownum'];

		return $rownum;
	}

	// 다음글 가져오기
	function getPrevRowNum($req='', $rownum=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$req = escape_string($req);
		$whereSql = $this->getWhereSql($req);	// where절

		$sql = "
			SELECT
				ifnull(rownum,0), ifnull(no,0) AS prev_no, title AS prev_title, registdate AS prev_registdate
			FROM (
				SELECT @rownum:=@rownum+1 AS rownum, no, title, registdate from ".$this->tableName.", (SELECT @rownum:=0) AS r
				".$whereSql."
				ORDER BY top DESC, registdate DESC
			) AS r2
			WHERE r2.rownum = $rownum+1";
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		$data = mysql_fetch_assoc($result);

		return $data;
	}

	// 이전글 가져오기
	function getNextRowNum($req='', $rownum=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$req = escape_string($req);
		$whereSql = $this->getWhereSql($req);	// where절

		$sql = "
			SELECT
				ifnull(rownum,0), ifnull(no,0) AS next_no, title AS next_title, registdate AS next_registdate
			FROM (
				SELECT @rownum:=@rownum+1 AS rownum, no, title, registdate from ".$this->tableName.", (SELECT @rownum:=0) AS r
				".$whereSql."
				ORDER BY top DESC, registdate DESC
			) AS r2
			WHERE r2.rownum = $rownum-1";
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		$data = mysql_fetch_assoc($result);

		return $data;
	}

	// 조회수 +1
	function updateReadno($no=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "UPDATE ".$this->tableName." SET readno = readno+1 WHERE no = $no";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}

	// 메인목록 조회
	function getMainList($number) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$whereSql = $this->getWhereSql($param);	// where절

		$sql = "
			SELECT *
			FROM ".$this->tableName."
			ORDER BY registdate DESC 
			LIMIT 0, ".$number." ";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}
	
	
	//해당 날짜 시간 조회
	function getTimeList($place = 0, $rdate=''){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			SELECT * FROM rsrv_detail WHERE rdate = '$rdate' AND rsrv_fk IN (SELECT no FROM rsrv WHERE place = ".chkIsset($place)." AND state != 3 AND state != 4) ORDER BY rtime ASC
		";

		$result = mysql_query($sql, $conn);

		mysql_close($conn);

		return $result;
	}

	function getTimeListEdit($place = 0, $rdate='', $no=0){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			SELECT * FROM rsrv_detail WHERE rdate = '$rdate' AND rsrv_fk IN (SELECT no FROM rsrv WHERE place = ".chkIsset($place)." AND state != 3 AND state != 4) AND no NOT IN ($no) ORDER BY rtime ASC
		";
		outlog($sql);

		$result = mysql_query($sql, $conn);

		mysql_close($conn);

		return $result;
	}

	function insertDetail($req=''){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$req = escape_string($req);


		$sql = "
			INSERT INTO rsrv_detail (rsrv_fk, rdate, rtime, rhour, registdate) VALUES ( 
				".chkIsset($req[rsrv_fk]).", '$req[rdate]', '$req[rtime]', ".chkIsset($req[rhour]).", NOW()
			)
		";
		outlog($sql);
		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;

	}

	function getDetailList($req=''){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$whereSql =  "";
		if($req['suser'] == 1){
			$whereSql .= " AND rsrv_fk IN (SELECT no FROM rsrv WHERE state != 3 AND state != 4)";
		}
		$sql = "
			SELECT *,
			(SELECT category FROM rsrv WHERE no = rsrv_detail.rsrv_fk) AS category,
			(SELECT place FROM rsrv WHERE no = rsrv_detail.rsrv_fk) AS place,
			(SELECT state FROM rsrv WHERE no = rsrv_detail.rsrv_fk) AS state
			FROM rsrv_detail WHERE rdate = '$req[srdate]' ".$whereSql." ORDER BY rtime ASC
		";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}
	
	function getDataDetail($no=0, $userCon){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "SELECT a.*, b.* FROM rsrv_detail as a LEFT JOIN rsrv as b ON a.rsrv_fk = b.no WHERE a.no = ".$no;
//outlog($sql);
		$result = mysql_query($sql, $conn);
		$data = mysql_fetch_assoc($result);

		return $data;
	}

	function getRdate($fk=0, $year=''){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "SELECT * FROM ".$this->tableName."_detail WHERE rsrv_fk = ".chkIsset($fk);
//outlog($sql);
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}

	function updateDetail($req=''){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$req = escape_string($req);
		$sql = "UPDATE rsrv_detail SET 
			rdate = '$req[rdate]',
			rtime = '$req[rtime]',
			rhour = ".chkIsset($req[rhour])."
			WHERE no = $req[no]
		";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;

	}

		function deleteDetail($rsrv_fk=''){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		

		$sql = " DELETE FROM ".$this->tableName."_detail WHERE rsrv_fk = ".$rsrv_fk;
//outlog($sql);
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;

	}

	function cancel($req=''){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$req = escape_string($req);

		$sql = "UPDATE ".$this->tableName." SET state = ".chkIsset($req[state]).", 
			refund_account = '$req[refund_account]', refund_name = '$req[refund_name]', refund_bank = '$req[refund_bank]', refund_price = ".chkIsset($req[refund_price]).", refund_reason = '$req[refund_reason]', refund_option = ".chkIsset($req[refund_option])." ";
		if($req['refund_date'] != ""){
			$sql .= ", refund_date = '$req[refund_date]' ";
		}
		$sql .= " WHERE no = ".chkIsset($req[no])."
		";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;

	}

	function getMyCount($param1='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$param = escape_string($param);
		$whereSql = $this->getWhereSql($param);	// where절
		$sql = " SELECT COUNT(*) AS cnt FROM ".$this->tableName." WHERE member_fk = '".$param1."'";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
//outlog($sql); 
		$row=mysql_fetch_array($result);
		$totalCount = $row['cnt'];
		$pageCount = getPageCount($this->pageRows, $totalCount);

		$data[0] = $totalCount;
		$data[1] = $pageCount;

		return $data;
	}
	
	// 개인 작성물 조회
	function getMyList($param1='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$param = escape_string($param);
		
		$sql = "
			SELECT *
			FROM ".$this->tableName."
			WHERE member_fk = '".$param1."'
			ORDER BY registdate DESC LIMIT ".$this->startPageNo.", ".$this->pageRows." ";
//outlog($sql);

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}


	function getGroupList($rsrv_fk=0, $sno = 0){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "SELECT * FROM rsrv_detail WHERE rsrv_fk = ".$rsrv_fk." AND no != ".$sno." ORDER BY rdate ASC";

		$result = mysql_query($sql, $conn);

		mysql_close($conn);

		return $result;

	}



	// 사용자 수정
	function userUpdate($req="") {

		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$req = escape_string($req);
		// 기존 첨부파일 삭제
		if ($req[filename_chk] == "1") {
			mysql_query("UPDATE ".$this->tableName." SET filename='', filename_org='', filesize=0 WHERE no=".$req[no], $conn);
		}
		// 기존 동영상파일 삭제
		if ($req[moviename_chk] == "1") {
			mysql_query("UPDATE ".$this->tableName." SET moviename='', moviename_org='' WHERE no=".$req[no], $conn);
		}
		// 기존 목록이미지 삭제
		if ($req[imagename_chk] == "1") {
			mysql_query("UPDATE ".$this->tableName." SET imagename='', imagename_org='' WHERE no=".$req[no], $conn);
		}

		$sql = "
			UPDATE ".$this->tableName." SET 
			member_fk = ".chkIsset($req[member_fk]).", category = ".chkIsset($req[category]).", place = ".chkIsset($req[place]).", gb = ".chkIsset($req[gb]).", company = '$req[company]', reg_number = '$req[reg_number]', birthday = '$req[birthday]', addr = '$req[addr]', purpose = ".chkIsset($req[purpose]).", amount = ".chkIsset($req[amount]).", dc = ".chkIsset($req[dc]).", dc_txt = ".chkIsset($req[dc_txt]).", price = ".chkIsset($req[price]).", state = ".chkIsset($req[state]).", pay_state = ".chkIsset($req[pay_state]).", cell = '$req[cell]', title='$req[title]', contents='$req[contents]',
			name='$req[name]', email='$req[email]', relation_url='$req[relation_url]', ";
		if ($req[filename]) {
			$sql .= "filename='$req[filename]', filename_org='$req[filename_org]', filesize=$req[filesize], ";
		}
		if ($req[moviename]) {
			$sql .= "moviename='$req[moviename]', moviename_org='$req[moviename_org]', ";
		}
		if ($req[imagename]) {
			$sql .= "imagename='$req[imagename]', imagename_org='$req[imagename_org]', image_alt='$req[image_alt]', ";
		}
		if ($req[readno]) {
			$sql .= "readno=".chkIsset($req[readno]).", ";
		}
		if ($req[registdate]) { 
			$sql .= "registdate='$req[registdate]', ";
		}
		$sql .= " top=".chkIsset($req[top]).", newicon=".chkIsset($req[newicon])."
			WHERE no = ".$req['no'];
		outlog($sql);
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}
}


?>