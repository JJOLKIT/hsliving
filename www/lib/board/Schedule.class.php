<?
/*


*/

include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db/DBConnection.class.php";

class Schedule {

	// 검색 파라미터 (초기 개발시 검색조건 세팅필요)
	var $param = array (
					"pageRows",
					"stype",
					"sval",
					"sdateType",
					"sstartdate",
					"senddate"
				);

	var $tableName;			// 테이블명
	var $pageRows;			// 페이지 로우수
	var $startPageNo=0;		// limit 시작페이지
	public $reqPageNo=1;	// 요청페이지
	var $conn;

	// 생성자
	function Schedule($pageRows=0, $tableName='', $request='') {
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
// 		if ($p['smain'] != '') {
// 			$whereSql .= " AND main = ".$p['smain'];
// 		}
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

	// 목록
	function getList($param='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
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
	
	function getTodayList($today='') {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
	
		$sql = "
			SELECT *
			FROM ".$this->tableName."
			WHERE startday = '".$today."'
			ORDER BY starttime, registdate ASC ";
outlog($sql);	
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
	
		return $result;
	}

	// 관리자 등록
	function insert($req="") {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();

		//$gno = $this->getMaxGno();
		$sql = "
			INSERT INTO ".$this->tableName." (
				startday, name, title, contents, ";
		if ($req[filename]) {
			$sql .= "filename, filename_org, filesize, ";
		}
		$sql .= "registdate, type
			) VALUES (
			'$req[startday]', 
			'$req[name]', 
			'$req[title]',
			'$req[contents]', ";
		
		if ($req[filename]) {
			$sql .= "'$req[filename]', '$req[filename_org]', $req[filesize], ";
		}
		if ($req[registdate]) {
			$sql .= "'$req[registdate]' ";
		} else {
			$sql .= " NOW(), ".chkIsset($req[type])." ) ";
		}
		
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

		// 기존 첨부파일 삭제
		if ($req[filename_chk] == "1") {
			mysql_query("UPDATE ".$this->tableName." SET filename='', filename_org='', filesize=0 WHERE no=".$req[no], $conn);
		}

		$sql = "
			UPDATE ".$this->tableName." SET 
				startday='$req[startday]', name='$req[name]', title='$req[title]', ";
		if ($req[filename]) {
			$sql .= "filename='$req[filename]', filename_org='$req[filename_org]', filesize=$req[filesize], ";
		}
		$sql .= " contents='$req[contents]', type=".chkIsset($req[type])."
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
			SELECT *
			FROM ".$this->tableName."
			WHERE no = ".$no;

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
			WHERE r2.rownum = $rownum+1";
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		$data = mysql_fetch_assoc($result);

		return $data;
	}

	// 이전글 가져오기
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
			WHERE r2.rownum = $rownum-1";
		
		$result = mysql_query($sql, $conn);
		mysql_close($conn);
		$data = mysql_fetch_assoc($result);

		return $data;
	}



	function getCalendar($month) {
		$dbconn = new DBConnection();
		$conn = $dbconn->getConnection();
		
		$sql = "
			SELECT today, name, CONVERT(holiday USING euckr) AS holiday, IFNULL(cnt, 0) AS cnt, type							
			FROM																											
				(SELECT	today, name, IFNULL(holidayName, 0) AS holiday													
				FROM																										
					(SELECT today, name	FROM calendar WHERE today LIKE '".$month."%') AS c												
					LEFT OUTER JOIN																							
					(SELECT CONVERT(CASE 	WHEN holidayType ='01' THEN '설날(신정)'												
							WHEN holidayType ='02' THEN '설날(구정)'														
							WHEN holidayType ='03' THEN '삼일절'															
							WHEN holidayType ='04' THEN '어린이날'															
							WHEN holidayType ='05' THEN '석가탄신일'														
							WHEN holidayType ='06' THEN '현충일'															
							WHEN holidayType ='07' THEN '광복절'															
							WHEN holidayType ='08' THEN '추석'																
							WHEN holidayType ='09' THEN '개천절'		
							WHEN holidayType ='10' THEN '한글날'					
							ELSE '성탄절' END USING utf8) AS holidayName, holiday														
					FROM legalholiday																						
					WHERE holiday LIKE '".$month."%') AS d																				
					ON c.today = d.holiday) AS a																			
				LEFT OUTER JOIN																								
				(SELECT startday, COUNT(*) AS cnt, type FROM ".$this->tableName." WHERE startday LIKE '".$month."%' GROUP BY startday) AS b	
				ON a.today = b.startday																						
			ORDER BY today ";
outlog($sql);				
		$result = mysql_query($sql, $conn);
		mysql_close($conn);

		return $result;
	}
	
	function getDayCount($year) {
	    $dbconn = new DBConnection();
	    $conn = $dbconn->getConnection();
	    
	    $sql = " select (to_days('".$year."-12-31') - to_days('".$year."-01-01') + 1) AS yearDayCount, (select count(*) from schedule where startday like '".$year."-%') AS holidayCount ";
	    
	    $result = mysql_query($sql, $conn);
	    mysql_close($conn);
	    
	    $row=mysql_fetch_array($result);
	    $yearDayCount = $row['yearDayCount'];
	    $holidayCount = $row['holidayCount'];
	    
	    $data[0] = $yearDayCount;
	    $data[1] = $holidayCount;
	    
	    return $data;
	}
	
	function getAllList() {
	    $dbconn = new DBConnection();
	    $conn = $dbconn->getConnection();
	    
	    $sql = "
			SELECT REPLACE(RIGHT(startday,5),'-','') AS day, title, LEFT(startday,4) AS year
			FROM orderform_schedule
			ORDER BY startday ASC  ";
	    
	    $result = mysql_query($sql, $conn);
	    mysql_close($conn);
	    
	    return $result;
	}
	
	function chkHoliday($rdate=''){
	    $dbconn = new DBConnection();
	    $conn = $dbconn->getConnection();
	    $sql = "SELECT COUNT(*) AS cnt FROM holiday WHERE startday = '$rdate' ";
	    $result = mysql_query($sql, $conn);
	    $data = mysql_fetch_assoc($result);
	    
	    mysql_close($conn);
	    return $data['cnt'];
	    
	}
	

}


?>