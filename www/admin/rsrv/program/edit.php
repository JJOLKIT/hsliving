<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Rsrv2.class.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/GalleryCt.class.php";
include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php";

$notice = new Rsrv2($pageRows, $tablename, $_REQUEST);
$data = ($notice->getData($_REQUEST[no], $userCon));

$program = new GalleryCt(99, 'program', 'program_category', array());
$pdata = $program->getData($data['program_fk'], false);

?>
<!doctype html>
<html lang="ko">
<head>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<script>
	var oEditors; // 에디터 객체 담을 곳
	jQuery(window).load(function(){
		
		
		// 달력

	});
	
	function goSave() {
		
		if( $('#name').val() == "" ){
			alert('성명을 입력해 주세요.');
			$('#name').focus();
			return false;
		}
		if($('#cell').val().trim() == ""){
			alert('연락처를 입력해 주세요.');
			$('#cell').focus();
			return false;
		}

		if($('#birthday').val().trim() == ""){
			alert('생년월일을 입력해 주세요.');
			$('#birthday').focus();
			return false;
		}



		$('#frm').submit();
	}
	
	function dcCheck(obj){
		if( $(obj).is(':checked') ){
			if($(obj).val() == 1){
				$('.dc_txt').hide();
				$('.dc_txt').find('select').val('');
			}else{
				$('.dc_txt').show();

			}
		}
	}
</script>
</head>


<body>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>
<!-- s:warp -->
	<div class="contWrap">
		<div class="titWrap">
			<h2><?=$pageTitle ?> 수정</h2>
		</div>
		<div class="write">
		<form method="post" name="frm" id="frm" action="<?=getSslCheckUrl($_SERVER['REQUEST_URI'], 'process.php')?>" enctype="multipart/form-data">
			<div class="wr_box">
				<h3>등록정보</h3>
				<table class="row_line">
					<colgroup>
						<col width="8%">
						<col width="42%">
						<col width="8%">
						<col width="42%">
					</colgroup>
					<tbody>
					<tr>
						<th>신청일</th>
						<td colspan="3">
							<?=$data['registdate'] ?>
						</td>
						
					</tr>
					<tr>
						<th>프로그램명</th>
						<td><?=$data['title']?></td>
						<th>신청 프로그램 일시</th>
						<td><?=$data['rdate']?> (<?=substr($data['rtime'], 0, 5)?>)</td>
					</tr>
					<tr>
						<th>
							신청 상태
						</th>
						<td>
							<input type="radio" name="state" id="state1" value="1" <?=getChecked(1, $data['state'])?>><label for="state1"><?=getStateName(1)?></label>
							<input type="radio" name="state" id="state5" value="5" <?=getChecked(5, $data['state'])?>><label for="state5"><?=getStateName(5)?></label>
							<input type="radio" name="state" id="state2" value="2" <?=getChecked(2, $data['state'])?>><label for="state2"><?=getStateName(2)?></label>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			<!-- //wr_box -->
			<div class="wr_box">
				<h3>게시글</h3>
				<table>
					<colgroup>
						<col width="8%">
						<col width="*">
					</colgroup>
					<tbody>
					<tr>
						<th>성명</th>
						<td>
							<input type="text" name="name" id="name" value="<?=$data['name']?>" class="wid200"/>
						</td>
					</tr>
					<tr>
						<th>성별</th>
						<td>
							<?for($i = 1; $i <= 2; $i ++){?>
							<input type="radio" name="gender" value="<?=$i?>" id="g<?=$i?>" <?=getChecked($i, $data['gender'])?>><label for="g<?=$i?>"><?=getGenderName($i)?></label>
							<?}?>
							</td>
					</tr>
					<tr>
						<th>연락처</th>
						<td><input type="text" name="cell" id="cell" value="<?=$data['cell']?>" class="wid300" maxlength="13" onkeyup="isNumberOrHyphen(this);cvtPhoneNumber(this);"/></td>
					</tr>
					<tr>
						<th>생년월일</th>
						<td><input type="text" name="birthday" id="birthday" value="<?=$data['birthday']?>" class="wid200"/></td>
					</tr>
					<tr>
						<th>주소</th>
						<td><input type="text" name="addr" value="<?=$data['addr']?>" id="addr"/></td>
					</tr>
					
					<tr>
						<th>동반인</th>
						<td>
						<span class="select">
							<select name="together" id="together"> 
						<?
							if($pdata['together'] == 3){
						?>
						<option value="0" selected>선택불가</option>
						<?}else if($pdata['together'] == 1){?>
						<option value="0" <?=getSelected('0', $data['together'])?>>동반인 없음</option>
						<option value="1" <?=getSelected('1', $data['together'])?>>1인</option>
						<?}else if($pdata['together'] == 2){?>
						<option value="0" <?=getSelected('0', $data['together'])?>>동반인 없음</option>
						<option value="1" <?=getSelected('1', $data['together'])?>>1인</option>
						<option value="2" <?=getSelected('2', $data['together'])?>>2인</option>
						<?}?>


								</select>
							</span>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			<div class="wr_box">
				<h3>프로그램료</h3>
				<table class="row_line">
					<colgroup>
						<col width="8%">
						<col width="42%">
						<col width="8%">
						<col width="42%">
					</colgroup>
					<tbody>
					<tr>
						<th>프로그램료 감면여부</th>
						<td colspan="3">
							<?for($i = 1; $i<= 2; $i++){?>
								<input type="radio" name="dc" value="<?=$i?>" id="dc<?=$i?>" <?=getChecked($i, $data['dc'])?> onclick="dcCheck(this);"/><label for="dc<?=$i?>"><?=getDcName($i) ?></label>
							<?}?>
							<span class="select dc_txt" <?if($data['dc'] == 1 ) { echo "style='display:none;'";}?>>
								<select name="dc_txt">
									<?=getDcTxtOption($data['dc_txt'])?>
								</select>
							</span>
					
						</td>
					</tr>
					<tr>
						<th>프로그램료</th>
						<td colspan="3">
							<input type="text" name="price" id="price" class="wid200" value="<?=$data['price']?>"/>원
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			<!-- //wr_box -->
		<input type="hidden" name="cmd" value="edit" />
		<input type="hidden" name="no" value="<?=$data['no'] ?>" />
		<?=$notice->getQueryStringToHidden($_REQUEST) ?>
		</form>
		</div>
		<!-- //write -->
		<div class="btnSet clear">
			<a href="javascript:;" class="btn hoverbg save" onclick="goSave();">저장</a>
			<a href="<?=$notice->getQueryString('view.php', $_REQUEST['no'], $_REQUEST)?>" class="btn hoverbg">취소</a>
		</div>
		<!-- //btnSet -->
	</div>
	<!-- //contents -->
</div>
<!-- e:warp --> 
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>
</body>
</html>
