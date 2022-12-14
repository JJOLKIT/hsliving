<?
/*


*/

include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db/DBConnection.class.php";

class Visual {

	// 검색 파라미터 (초기 개발시 검색조건 세팅필요)
	var $param = array (
					"pageRows",
					"stype",
					"sval",
					"sdateType",
					"sstartdate",
					"senddate",
					"smain"
				);

	var $tableName;			// 테이블명
	var $pageRows;			// 페이지 로우수
	var $startPageNo=0;		// limit 시작페이지
	public $reqPageNo=1;	// 요청페이지
	var $conn;

	// 생성자
	function Visual($pageRows=0, $tableName='', $request='') {
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
			if (chkisset($request[$this->param[$i]] )) {
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
			if (chkisset($request[$this->param[$i]] )) {
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

 		if ($p['smain'] != '') {
 			$whereSql .= " AND main = ".$p['smain'];
 		}
		return $whereSql;
	}


	// 전체로우수, 페이지카운트
	function getCount($param = "") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
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

	function getMainCount($param = "") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$whereSql = $this->getWhereSql($param);	// where절
		$sql = " SELECT COUNT(*) AS cnt FROM ".$this->tableName.$whereSql." AND main = 1";

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

	// 목록
	function getList($param='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$whereSql = $this->getWhereSql($param);	// where절

		$sql = "
			SELECT *, 
				(SELECT COUNT(*) FROM comment WHERE tablename = '".$this->tableName."' AND parent_fk = ".$this->tableName.".no) AS comment_count
			FROM ".$this->tableName."
			".$whereSql."
			ORDER BY seq desc, top DESC, registdate DESC LIMIT ".$this->startPageNo.", ".$this->pageRows." ";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}
	

	function getMaxSeq(){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "SELECT max(seq) as cnt FROM ".$this->tableName;

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		$data = mysql_fetch_assoc($result);
		return $data['cnt']+1;
	}
	// 관리자 등록
	function insert($req="") {
		$req = getReqAddSlash($req);
		
		$seq = $this->getMaxSeq();
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		//$gno = $this->getMaxGno();
		$sql = "
			INSERT INTO ".$this->tableName." (
				main, seq, name, title, subtitle, relation_url, registdate, stday, etday, ";
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
			".chkIsset($req[main]).",
			".$seq.",
			'$req[name]',
			'$req[title]',
			'$req[subtitle]',
			'$req[relation_url]',
			";
		if ($req[registdate]) { 
			$sql .= "'$req[registdate]', ";
		} else {
			$sql .= " NOW(), ";
		}
		if ($req[stday]) { 
			$sql .= "'$req[stday]', ";
		} else {
			$sql .= "'0000-00-00',";
		}
		if ($req[etday]) { 
			$sql .= "'$req[etday]', ";
		} else {
			$sql .= "'0000-00-00', ";
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
//outlog($sql);
		$sql = "SELECT LAST_INSERT_ID() AS lastNo";
		$result = mysql_query($sql, $conn);
		$row = mysql_fetch_array($result);
		$lastNo = $row['lastNo'];
		mysql_close($conn);
		return $lastNo;
	}

	// 수정
	function update($req="") {
		$req = getReqAddSlash($req);
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

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
				main = ".chkIsset($req[main]).", name='$req[name]', title='$req[title]', subtitle='$req[subtitle]', relation_url='$req[relation_url]',";
		if ($req[filename]) {
			$sql .= "filename='$req[filename]', filename_org='$req[filename_org]', filesize='$req[filesize]', ";
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
		if ($req[stday]) { 
			$sql .= "stday='$req[stday]', ";
		} else {
			$sql .= "stday='0000-00-00', ";
		}
		if ($req[etday]) { 
			$sql .= "etday='$req[etday]', ";
		} else {
			$sql .= "etday='0000-00-00', ";
		}
		$sql .= " top=".chkIsset($req[top]).", newicon=".chkIsset($req[newicon])."
			WHERE no = ".$req['no'];
	//outlog($sql);
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
			SELECT *
			FROM ".$this->tableName."
			WHERE no = ".$no;

		// 조회수 증가
		if ($userCon) {
			mysql_query("UPDATE ".$this->tableName." SET readno=readno+1 WHERE no=".$no, $conn);
		}
		
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
			".$whereSql." AND main = 1
			ORDER BY seq ASC, registdate DESC LIMIT ".$this->startPageNo.", ".$this->pageRows." ";
//outlog($sql);
		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}
	
	function updateDisplay($no=0, $display=0){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "UPDATE ".$this->tableName." SET display = ".$display." WHERE no =".$no;

		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}

	function upSeq($seq=0, $no=0){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			SELECT no, seq FROM ".$this->tableName." WHERE seq > ".$seq." order by seq asc limit 1
		";

		$result = mysql_query($sql, $conn);
		$data = mysql_fetch_assoc($result);

		$upno = $data['no']; //윗번호
		$upseq = $data['seq']; //윗seq

		$nowno = $no; //현재no
		$nowseq = $seq; //현재seq
		
		//// 서로 seq 변경

		$sql3 = "UPDATE ".$this->tableName." SET seq = ".$upseq." WHERE no = ".$nowno;
		$sql4 = "UPDATE ".$this->tableName." SET seq = ".$nowseq." WHERE no =".$upno;
		outlog($sql3);
		outlog($sql4);
		$result3 = mysql_query($sql3, $conn);
		$result4 = mysql_query($sql4, $conn);

		mysql_close($conn);

		return $result3;

	}

	function downSeq($seq=0, $no=0){
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "
			SELECT no, seq FROM ".$this->tableName." WHERE seq < ".$seq." order by seq desc limit 1
		";

		$result = mysql_query($sql, $conn);
		$data = mysql_fetch_assoc($result);

		$downno = $data['no']; //아래no
		$downseq = $data['seq']; //아래seq

		$nowno = $no;
		$nowseq = $seq;

		$sql3 = "UPDATE ".$this->tableName." SET seq = ".$downseq." WHERE no =".$no;
		$sql4 = "UPDATE ".$this->tableName." SET seq = ".$nowseq." WHERE no =".$downno;

		$result3 = mysql_query($sql3, $conn);
		$result4 = mysql_query($sql4, $conn);

		mysql_close($conn);

		return $result3;

	}

	
	function getSeqList() {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		

		$sql = "
			SELECT *
			FROM ".$this->tableName."
			ORDER BY seq ASC, registdate DESC ";

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}

	function updateSeqAll($seq=0, $no=0) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		$sql = "UPDATE ".$this->tableName." SET seq = ".$seq." WHERE no =".$no;

		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		return $result;
	}


}


?>