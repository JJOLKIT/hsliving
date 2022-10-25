<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<?
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

	include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Reply2.class.php";

	include "config.php";

	$notice = new Reply2($pageRows, $tablename, $_REQUEST);
	$rowPageCount = $notice->getMyCount($_SESSION['member_no']);
	$result = $notice->getMyList($_SESSION['member_no']);
?>
<?
	$p = "mypage";
	$sp = 2;
	$spc= 0;
	$root = $_SERVER['DOCUMENT_ROOT'];
	include_once $root."/header.php";
?>
<SCRIPT type="text/javascript">
$(document).ready(function(e){
	$("input[type=text][name*=sval]").keypress(function(e){
		if(e.keyCode == 13){
			goSearch();
		}
	});
	$('.qna_pass .cancel').on('click',function(){
		$('.qna_pass').stop().fadeOut(400,function(){
			$('.qna_pass input[type="password"]').val('');
			$('.qna_pass #no').val('');
		});
	});
});

function goSearch() {
	$("#searchForm").submit();
}

function getPass(no){
	$('.qna_pass #no').val(no);
	$('.qna_pass').stop().fadeIn(400);
}


function getQna(){
	var pwd = $('.qna_pass #qnapass').val();
	var no = $('.qna_pass #no').val();


	$.ajax({
		url : 'ajax.php',
		cache : false,
		type : 'POST',
		data : { 'no' : no, 'password' : pwd},
		
		success : function(data){
			var res = data.trim();
			if(res == "success"){
				if( (window.location.href).indexOf('?') > 0){
					window.location.href='<?=$notice->getQueryString("view.php", '', $_REQUEST) ?>&no='+no;
				}else{
					window.location.href="view.php?no="+no;
				}
					
				
			}else{
				alert('비밀번호가 일치하지 않습니다.');
			}
		},
		
		error : function(res){
			console.log(res);
		}


	});
}
</SCRIPT>
<div id="sub" class="list_idx">
	<?include_once $root."/include/sub_visual.php";?>
	<div class="con_wrap">
		<div class="cont_top">
			<div class="size">
				<div class="t_wrap">
					<span>화성시 생활문화창작소</span>
					<b>문의</b>
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
									<option value="all" <?=getSelected("all", $_REQUEST['stype']) ?>>전체</option>
									<option value="title" <?=getSelected("title", $_REQUEST['stype']) ?>>제목</option>
									<option value="contents" <?=getSelected("contents", $_REQUEST['stype']) ?>>내용</option>
								</select>
							</span>
							<span class="searchWord">
								<input type="text" id="sval" name="sval" placeholder="검색어를 입력해주세요." value="<?=$_REQUEST['sval'] ?>" title="검색어 입력" onKeypress="">
								<input type="button" id="" value="검색" title="검색" onclick="goSearch();">
							</span>
						</form>
					</div>
					<div class="bbs_list">
            <table class="list">
                <caption>게시판 목록</caption>
                <colgroup>
                    <col width="100px" />
                    <col width="*" />
                    <col width="110px" />
                    <col width="110px" />
                    <col width="130px" />
                    <col width="110px" />
                </colgroup>
                <thead>
                    <tr>
                        <th>번호</th>
                        <th>제목</th>
                        <th>답변상태</th>
                        <th>작성자</th>
                        <th>작성일</th>
                        <th>조회수</th>
                    </tr>
                </thead>
                <tbody>
                <? if ($rowPageCount[0] == 0) { ?>
                    <tr>
                        <td colspan="5" align="center">등록된 데이터가 없습니다.</td>
                    </tr>
                <?
                     } else {
                        $targetUrl = "";
                        $topClass = "";
                        $i = 0;
                        while ($row=mysql_fetch_assoc($result)) {
							$row = escape_html($row);
                            $targetUrl = "style='cursor:pointer;' onclick=\"location.href='".$notice->getQueryString('view.php', $row[no], $_REQUEST)."'\"";
                            if ($row[top] == '1') {
                                $topClass = "class='notice'";
                            } else {
                                $topClass = "";
                            }
                ?>
					<?
						if($row['member_fk'] > 0 && $row['member_fk'] == $_SESSION['member_no']){
					?>
					<tr <?=$targetUrl?>>
					<?}else{?>
                    <tr onclick="getPass('<?=$row[no]?>');" style="cursor:pointer;" >
					<?}?>
                        <td class="no">
                            <? if ($row[top] == "1") { ?>
                                <img src="/img/ico_top.png" alt="TOP공지" />
                            <? } else { ?>
                                <?=$rowPageCount[0] - (($notice->reqPageNo-1)*$pageRows) - $i?>
                            <? } ?>
                        </td>
                        <td class="txt_l title">
                            <div>
														<? if ($row['secret'] == 1) { ?>
                                 <span class="secret"><img src="/img/ico_secret.png" alt="비밀글" /></span>
                            <? } ?>


                            <? for ($j=0; $j<$row[nested]; $j++) { ?>&nbsp;<? } ?>
                            <? if ($row[nested] > 0) { ?>
                                &rdca;
                            <? } ?>
                            <?=$row[title]?>
                            
                            <? if (checkNewIcon($row['registdate'], $row['newicon'], 1)) { ?>
                                <img src="/img/ico_new.png" alt="새글" />
                            <? } ?>

                            </div>
                        </td>
												<td class="state">
													<? if ($row['state'] == 0) { ?>
															<span class="waiting"><b>답변</b>대기</span>
												 <? } else if($row['state'] == 1) { ?>
															<span class="complete"><b>답변</b>완료</span>
												 <? } ?>
												 </td>
                        <td class="name"><? if($row['nested']) { echo $row['name']; } else { echo preg_replace('/.(?!.)/u','*',$row['name']); } ?>님</td>
                        <td class="date"><?=getYMD($row[registdate])?></td>
                        <td class="hit"><?=$row[readno]?></td>
                    </tr>
                <?
                        $i++;
                        }
                     }
                ?>
                </tbody>
            </table>
						</div>
            
            <div class="pagenate clear">
                <?=pageList($notice->reqPageNo, $rowPageCount[1], $notice->getQueryString('index.php', 0, $_REQUEST))?>
            </div>
						<div class="rnd_btns clear">
                <a href="<?=$notice->getQueryString('write.php', 0, $_REQUEST)?>"><span>문의등록</span></a>
            </div>
            <!-- //pagenate -->
			<!-- 비밀번호 입력팝업 -->
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



		
		<!-- //여기까지 게시판--->
	</div>
	</div>
	<!-- //size--->
</div>
<!-- //sub--->
<?
	include_once $root."/footer.php";
?>


			