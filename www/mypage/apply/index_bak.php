<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

	include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Schedule.class.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Rsrv.class.php";
	include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";

	include "config.php"; 

	$notice = new Rsrv($pageRows, $tablename, $_REQUEST);
	$rowPageCount = $notice->getMyCount($_SESSION['member_no']);
	$result = $notice->getMyList($_SESSION['member_no']);

	
	$s = new Schedule(99, 'holiday', $_REQUEST);
	$sresult = rstToArray($s->getCalendar($curMonth));
?>
<?
	$p = "mypage";
	$sp = 0;
	$spc= 0;
	$root = $_SERVER['DOCUMENT_ROOT'];
	include_once $root."/header.php";
?>

<div id="sub" class="list_idx">
  <?include_once $root."/include/sub_visual.php";?>
  <div class="con_wrap">
    <div class="cont_top">
      <div class="size">
        <div class="t_wrap">
          <span>화성시 생활문화창작소</span>
          <b>신청현황</b>
        </div>
      </div>
    </div>
    <!-- 여기서부터 게시판--->
    <div class="has_contit nbd">
      <div class="size clear">
        <div class="bbs con_info">
          <div class="bbsSearch">
            <form method="get" name="searchForm" id="searchForm" action="index.php">
              <span class="select srchSelect">
								<select id="stype" name="stype" class="dSelect" title="검색분류 선택">
									<option value="all" >전체</option>
									<option value="title" >제목</option>
									<option value="contents" >내용</option>
								</select>
							</span>
              <span class="searchWord">
								<input type="text" id="sval" name="sval" placeholder="검색어를 입력해주세요." value="" title="검색어 입력" onKeypress="">
								<input type="button" id="" value="검색" title="검색" onclick="goSearch();">
							</span>
            </form>
          </div>
          <div class="bbs_list">
            <table class="list">
              <caption>게시판 목록</caption>
              <colgroup>
                <col width="100px" />
                <col width="140px" />
                <col width="*" />
                <col width="110px" />
                <col width="130px" />
                <col width="130px" />
              </colgroup>
              <thead>
                <tr>
                  <th>번호</th>
                  <th>구분</th>
                  <th>신청내역</th>
                  <th>신청상태</th>
                  <th>신청일</th>
                  <th>취소 신청</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="no">1</td>
                  <td class="catg txt_c">프로그램</td>
                  <td class="txt_l title">
                    <p>꽃 피는 봄이오면</p>
                  </td>
                  <td class="name txt_c">확정</td>
                  <td class="date txt_c">2022-05-31</td>
									<td class="form_btn">
                    <a href="view.php">취소신청</a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="pagenate clear">
            <ul class="paging">
              <li><a href="javascript:;" class="current">1</a></li>
            </ul>
          </div>
          <!-- //pagenate -->




        <!-- //여기까지 게시판--->
      </div>
    </div>
    <!-- //size--->
  </div>
<?
	include_once $root."/header.php";
?>