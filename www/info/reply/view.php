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
     $data = ($notice->getData($_REQUEST[no], $userCon));

?>
<?	
	$p = "";
	$sp = 0;
	$spc= 0;
	$root = $_SERVER['DOCUMENT_ROOT'];
	include_once $root."/header.php";
?>
<SCRIPT type="text/javascript">
$(document).ready(function(e){
});

function goDelete() {
	if (confirm("삭제하시겠습니까?")) {
		location.href="<?=$notice->getQueryString('process.php', $data['no'], $_REQUEST) ?>&cmd=delete";
	}
}



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

function getPass(no, type){
	$('.qna_pass #no').val(no);
	$('.qna_pass #type').val(type);
	$('.qna_pass').stop().fadeIn(400);
}


function getQna(){
	var pwd = $('.qna_pass #qnapass').val();
	var no = $('.qna_pass #no').val();
	var type = $('.qna_pass #type').val();



	$.ajax({
		url : 'ajax.php',
		cache : false,
		type : 'POST',
		data : { 'no' : no, 'password' : pwd},
		
		success : function(data){
			var res = data.trim();
			if(res == "success"){

				if(type == "edit"){
						window.location.href='<?=$notice->getQueryString("edit.php", $data[no], $_REQUEST) ?>';
				}else if(type == "delete"){
					if (confirm("삭제하시겠습니까?")) {
						location.href="<?=$notice->getQueryString('process.php', $data['no'], $_REQUEST) ?>&cmd=delete";
					}
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
<div id="sub" class="list_view">
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
		<div class="has_contit nbd">
			<div class="size clear">
				<!-- 여기서부터 게시판--->
							<div class="bbs con_info">
									 <div class="view">
												<div class="title">
														 <dl>
																	<dt><?=$data['title'] ?></dt>
																	<dd><span class="name">작성자</span><?=escape_html($data['name'])?></dd>
								<dd><span class="date">날짜</span><?=getYMD($data['registdate'])?></dd>
								<dd><span class="hit">조회수</span><?=$data[readno]?></dd>
														 </dl>
												</div>
												<!-- //title---> 
												<div class="cont"> <?=$data['contents'] ?> </div>
												<?if($data['state'] == 1){?>
												<div class="answers">
													<b>답변</b>
													<div><?=$data['an_contents'] ?></div>
												</div>
												<?}?>
												<!-- //cont--->
											 <div class="link">
							<? if ($data['filename']) { ?>
							<dl>
								<dt class="file">첨부파일</dt>
								<dd><a href="/lib/download.php?path=<?=$uploadPath?>&vf=<?=$data['filename']?>&af=<?=urlencode($data['filename_org'])?>" target="_blank"><?=$data[filename_org]?></a></dd>
							</dl>
							<? } ?>
							<!-- //file--->
							<?if($data['relation_url']){?>
							<dl>
								<dt class="url">관련링크</dt>
								<dd><a href="<?=$data['relation_url']?>" target="_blank" title="새 창 열림"><?=$data['relation_url']?></a></dd>
							</dl>
							<?}?>
							<!-- //link--->
						</div>
					

						<div class="clear mt50">
								 <div class="fl_l rnd_btns"><a href="<?=$notice->getQueryString('index.php', 0, $_REQUEST) ?>" ><span>목록으로</span></a></div>
								 <?
											if($data['member_fk'] > 0){
								 ?>
								 <div class="fl_r sm_btns">
											<a href="<?=$notice->getQueryString('edit.php', $data['no'], $_REQUEST) ?>" class=""><span>수정</span></a>
											<a href="javascript:;" class="" onclick="goDelete();"><span>삭제</span></a>
								 </div>
								 <? }else{?>
								 <div class="fl_r sm_btns">
											<?if($data['state'] == 0){?>
											<a href="javascript:;" class="" onclick="getPass('<?=$data['no']?>', 'edit');"><span>수정</span></a>
											<?}?>
											<a href="javascript:;" class="" onclick="getPass('<?=$data['no']?>', 'delete');"><span>삭제</span></a>
								 </div>
							 <?}?>
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
	<!-- //size--->
</div>
<!-- //sub--->
<?
	include_once $root."/footer.php";
?>