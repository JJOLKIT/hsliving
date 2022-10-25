<?
	$p = "mypage";
	$sp = 1;
	$spc= 0;
	$root = $_SERVER['DOCUMENT_ROOT'];
	include_once $root."/header.php";
?>

<div id="sub" class="apply_idx apply_write">
	<?include_once $root."/include/sub_visual.php";?>
	<div class="con_wrap">
  <div class="cont_top">
    <div class="size">
      <div class="t_wrap">
        <span>화성시 생활문화창작소</span>
        <b>문의내역</b>
      </div>
    </div>
  </div>
  <div class="has_contit nbd">
    <div class="size clear">
      <!-- 여기서부터 게시판--->
      <div class="bbs con_info">
        <div class="view">
          <div class="title">
            <dl>
              <dt>1234</dt>
              <dd><span class="name">작성자</span>테스트</dd>
              <dd><span class="date">날짜</span>2022-05-31</dd>
              <dd><span class="hit">조회수</span>22</dd>
            </dl>
          </div>
          <!-- //title--->
          <div class="cont">
            <p>asdasdasdasdasd</p>
          </div>
          <div class="answers">
            <b>답변</b>
            <div>
              <p>예약 문의 드립니다.<br>4월 1일 프로그램 예약 했는데, 확정이 되었나요?</p>
            </div>
          </div>
          <!-- //cont--->
          <div class="link">
            <!-- //file--->
            <!-- //link--->
          </div>


          <div class="clear mt50">
            <div class="fl_l rnd_btns"><a href="index.php"><span>목록으로</span></a></div>
            <div class="fl_r sm_btns">
              <a href="edit.php?no=29" class=""><span>수정</span></a>
              <a href="javascript:;" class="" onclick="goDelete();"><span>삭제</span></a>
            </div>
          </div>
        </div>
        <!-- //view--->
        <div class="qna_pass">
          <input type="hidden" id="no" value="" title="클릭게시물">
          <input type="hidden" id="type" value="" title="질답형태">
          <p class="topic txt_c">글작성시 입력한 비밀번호를 입력해주세요</p>
          <div class="txt_c ipt">
            <span>비밀번호</span>
            <input type="password" name="pass" id="qnapass">
          </div>
          <div class="btnSet txt_c">
            <a href="javascript:;" class="btn" onclick="getQna();">확인</a>
            <a href="javascript:;" class="btn cancel">취소</a>
          </div>
        </div>
      </div>

    </div>
    <!-- //여기까지 게시판--->
  </div>
</div>
</div>


<?
	include_once $root."/footer.php";
?>