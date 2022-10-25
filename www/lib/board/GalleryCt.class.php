<?
/*


*/

include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db/DBConnection.class.php";

class GalleryCt {

	// 검색 파라미터 (초기 개발시 검색조건 세팅필요)
	var $param = array (
					"pageRows",
					"stype",
					"sval",
					"sdateType",
					"sstartdate",
					"senddate",
					"scategory",
					"sstate1",
					"sstate2",
					"sstate3",
					"stitle",
					"srdate",
					"sdisplay",
					"stoday"
				);

	var $tableName;			// 테이블명
	var $pageRows;			// 페이지 로우수
	var $startPageNo=0;		// limit 시작페이지
	public $reqPageNo=1;	// 요청페이지
	var $conn;

	// 생성자
	function GalleryCt($pageRows=0, $tableName='', $category_tableName='', $request='') {
		$this->pageRows = $pageRows;
		$this->tableName = $tableName;
		$this->category_tableName = $category_tableName;
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
				$whereSql .= " AND ( (title like '%".$p['sval']."%' ) or (contents like '%".$p['sval']."%') OR (place like '%".$p['sval']."%') or (genre like '%".$p['sval']."%') or (teacher like '%".$p['sval']."%') ) ";
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
			$whereSql .= " AND category = ".chkIsset($p['scategory']);
		}

		if($p['sstate1'] != "" || $p['sstate2'] != "" || $p['sstate3'] != ""){

			$stateSql = "";
			$now = Date('Y-m-d');

			$whereSql .= " AND ( ";
			//예정
			if($p['sstate1'] != ""){
				$stateSql .=" ('$now' < sday)  ";
			}
			
			//진행중
			if($p['sstate2'] != ""){
				if( $stateSql != "" ){ $stateSql .= " OR "; }
				$stateSql .= " ('$now' >= sday AND '$now' <= eday) ";
			}

			//종료
			if($p['sstate3'] != ""){
				if( $stateSql != "" ) { $stateSql .= " OR "; }
				$stateSql .= " ('$now' > eday) ";
			}

			$whereSql .= $stateSql; 
			$whereSql .= " ) "; 
		}

		if($p['stitle'] != ""){
			$whereSql .= " AND title LIKE '%".$p['stitle']."%'";
		}
		if($p['srdate'] != ""){
			/*
			$whereSql .= " AND ('$p[srdate]' >= sday AND '$p[srdate]' <= eday) ";
			*/
			$whereSql .= " AND (stday = '$p[srdate]') ";
		}

		if($p['sdisplay'] != ""){
			$whereSql .= " AND display = ".$p['sdisplay'];
		}

		if($p['stoday'] != ""){
			$whereSql .= "
				AND (sday <= '$p[stoday]' AND eday >= '$p[stoday]')
			";
		}

		
		return $whereSql;
	}


	// 전체로우수, 페이지카운트
	function getCount($param = "") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$param = escape_string($param);
		$whereSql = $this->getWhereSql($param);	// where절
		$sql = " SELECT COUNT(*) AS cnt FROM ".$this->tableName.$whereSql;
		outlog($sql);
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
		$conn = $dbconn->getConnection();
		$param = escape_string($param);
		$whereSql = $this->getWhereSql($param);	// where절

		$sql = "
			SELECT *,
				(SELECT COUNT(*) FROM comment WHERE tablename = '".$this->tableName."' AND parent_fk = ".$this->tableName.".no) AS comment_count,
				(SELECT title FROM ".$this->category_tableName." WHERE no = ".$this->tableName.".category) AS category_title
			FROM ".$this->tableName."
			".$whereSql."
			ORDER BY top DESC, registdate DESC LIMIT ".$this->startPageNo.", ".$this->pageRows." ";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}

	// 관리자 등록
	function insert($req="") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$req=  escape_string($req);

		//$gno = $this->getMaxGno();
		$sql = "
			INSERT INTO ".$this->tableName." (
				display, stday, rtime, sday, eday, place, teacher, genre, age, price, amount, together, contents2, category, name, title, contents, relation_url, registdate, ";
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
			".chkIsset($req[display]).",
			'$req[stday]',
			'$req[rtime]',
			'$req[sday]',
			'$req[eday]',
			'$req[place]',
			'$req[teacher]',
			'$req[genre]',
			'$req[age]',
			".chkIsset($req[price]).",
			".chkIsset($req[amount]).",
			".chkIsset($req[together]).",
			'$req[contents2]',
			".chkIsset($req[category]).",
			'$req[name]', 
			'$req[title]',
			'$req[contents]',
			'$req[relation_url]',
			";
		if ($req[registdate]) { 
			$sql .= "'$req[registdate]', ";
		} else {
			$sql .= " NOW(), ";
		}
		if ($req[filename]) {
			$sql .= "'$req[filename]', '$req[filename_org]', ".chkIsset($req[filesize]).", ";
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
				stday = '$req[stday]', rtime='$req[rtime]', sday='$req[sday]', eday='$req[eday]', place = '$req[place]', teacher = '$req[teacher]', genre = '$req[genre]', age = '$req[age]', price=".chkIsset($req[price]).", amount=".chkIsset($req[amount]).", together=".chkIsset($req[together]).", contents2= '$req[contents2]', category = ".chkIsset($req[category]).", name='$req[name]', title='$req[title]', contents='$req[contents]', relation_url= '$req[relation_url]', ";
		if ($req[filename]) {
			$sql .= "filename='$req[filename]', filename_org='$req[filename_org]', filesize=".chkIsset($req[filesize]).", ";
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

	// 목록
	function getData($no=0, $userCon) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$sql = "
			SELECT *,
			(SELECT title FROM ".$this->category_tableName." WHERE ".$this->category_tableName.".no = ".$this->tableName.".category) AS category_title,
			(SELECT COUNT(*) FROM rsrv2 WHERE program_fk = ".$this->tableName.".no AND (state < 3 OR state = 5)) AS count,
			(SELECT SUM(together) FROM rsrv2 WHERE program_fk = ".$this->tableName.".no AND (state < 3 OR state = 5)) AS sum_together
			FROM ".$this->tableName."
			WHERE no = ".$no;

		// 조회수 증가
		if ($userCon) {
			mysql_query("UPDATE ".$this->tableName." SET readno=readno+1 WHERE no=".$no, $conn);
		}
outlog($sql);		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		$data = mysql_fetch_assoc($result);

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
	

	// 카테고리 전체로우수, 페이지카운트
	function getCategoryCount($param = "") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$param = escape_string($param);
		$whereSql = $this->getWhereSql($param);	// where절
		$sql = " SELECT COUNT(*) AS cnt FROM ".$this->category_tableName.$whereSql;

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		$row=mysql_fetch_array($result);
		$totalCount = $row['cnt'];
		$pageCount = getPageCount($this->pageRows, $totalCount);

		$data[0] = $totalCount;
		$data[1] = $pageCount;

		return $data;
	}

	// 카테고리목록
	function getCategoryList($param='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$param = escape_string($param);
		$whereSql = $this->getWhereSql($param);	// where절

		$sql = "
			SELECT * FROM ".$this->category_tableName." ORDER BY no ASC ";
			outlog($sql);


		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}

	// 카테고리 등록
	function insertCategory($req="") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$req = escape_string($req);
		$sql = "
			INSERT INTO ".$this->category_tableName." (
				title 
			) VALUES (
				'$req[title]'
			)";

		mysql_query($sql, $conn);

		$sql = "SELECT LAST_INSERT_ID() AS lastNo";
		$result = mysql_query($sql, $conn);
		$row = mysql_fetch_array($result);
		$lastNo = $row['lastNo'];
		mysql_close($conn);
		return $lastNo;
	}

	// 카테고리상세
	function getCategoryData($no=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$no = escape_string($no);
		$sql = "
			SELECT * FROM ".$this->category_tableName." WHERE no = ".$no;
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		$data = mysql_fetch_assoc($result);

		return $data;
	}

	// 삭제
	function deleteCategory($no=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = " DELETE FROM ".$this->category_tableName." WHERE no = ".$no;

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}

	// 카테고리수정
	function updateCategory($req="") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$req = escape_string($req);
		$sql = "
			UPDATE ".$this->category_tableName." SET title = '$req[title]' WHERE no = ".$req['no'];

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}


	/*
	*@param
	* program_fk INT 
	* rdate DATE
	* rtime TIME
	*/
	function getAmount($req=''){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		$sql = "SELECT COUNT(*) AS cnt, SUM(together) AS together FROM rsrv2 WHERE (state < 3 OR state = 5) AND program_fk = ".chkIsset($req['program_fk'])." AND rdate = '$req[rdate]' AND rtime = '$req[rtime]' ";

		$result = mysql_query($sql, $conn);
		$data = mysql_fetch_assoc($result);
		mysql_close($conn);

		return $data['cnt'] + $data['together'];
	}

}


?>