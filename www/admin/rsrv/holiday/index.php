<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/common.php"; ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";

include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Schedule.class.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/loginCheck.php";
include "config.php"; 

$smonth = $_REQUEST['smonth'];
$preYear = getYearMonth($smonth, -12);
$preMonth = getYearMonth($smonth, -1);
$curMonth = getYearMonth($smonth, 0);
$nexMonth = getYearMonth($smonth, 1);
$nexYear = getYearMonth($smonth, 12);

$s = new Schedule($pageRows, $tablename, $_REQUEST);
$result = rstToArray($s->getCalendar($curMonth));


?>
<!doctype html>
<html lang="ko">
<head>

<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/headHtml.php"; ?>
<script type="text/javascript" src="/admin/js/plugin.js"></script>
<script type="text/javascript" src="/admin/js/popup.js"></script>
<script>
function goSave(v) {
	if ($("#title"+v).val() == "") {
		alert("내용을 입력해 주세요");
		$("#title"+v).focus();
		return false;
	}
	$.ajax({
		type : "POST",
		url: "process.php",
		async: false,
		data: {
			startday : $("#startday"+v).val(),
			type : $("#type"+v+":checked").val(),
			title : $("#title"+v).val(),
			cmd : "write"
		},
		success: function( data ) {
			var r = data.trim();
			//$("#close"+v).trigger('click');
			location.reload(true);
		},
		error:function(e) {
			alert(e.responseText);
		}
	});
}

function goEdit(v) {
	if ($("#title"+v).val() == "") {
		alert("내용을 입력해 주세요");
		$("#title"+v).focus();
		return false;
	}
	$.ajax({
		type : "POST",
		url: "process.php",
		async: false,
		data: {
			startday : $("#startday"+v).val(),
			type : $("#type"+v+":checked").val(),
			title : $("#title"+v).val(),
			cmd : $("#cmd"+v).val(),
			no : $("#no"+v).val()
		},
		success: function( data ) {
			var r = data.trim();
			//$("#close"+v).trigger('click');
			location.reload(true);
		},
		error:function(e) {
			alert(e.responseText);
		}
	});
}

function goDel(v) {
	if (confirm("삭제하시겠습니까?")) {
		$.ajax({
			type : "POST",
			url: "process.php",
			async: false,
			data: {
				cmd : "delete",
				no : v
			},
			success: function( data ) {
				var r = data.trim();
				location.reload(true);
			},
			error:function(e) {
				alert(e.responseText);
			}
		});
	}
}
</script>
</head>


<body>
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php"; ?>
<!-- s:warp -->
	<div class="contWrap">
		<div class="titWrap">
			<h2><?=$pageTitle ?></h2>
		</div>
		<div class="diary btnSet clear">
			<ul>
				<li><a href="index.php?smonth=<?=$preYear?>"class="board first" title="이전년도">이전년도</a></li><li><a href="index.php?smonth=<?=$preMonth?>"class="board prev" title="이전달">이전달</a></li>
				<li class="month"><?=substr($curMonth, 0, 4)?>년 <?=substr($curMonth, 5)?>월</li>
				<li><a href="index.php?smonth=<?=$nexMonth?>"class="board next" title="다음달">다음달</a></li><li><a href="index.php?smonth=<?=$nexYear?>"class="board last" title="다음년도">다음년도</a></li>
			</ul>
		</div>
		<!-- //pagenate -->
		<div class="diaryList">
			<table>
				<caption> 달력 목록 </caption>
				<colgroup>
					<col width="14.2%" />
					<col width="14.3%" />
					<col width="14.3%" />
					<col width="14.3%" />
					<col width="14.3%" />
					<col width="14.3%" />
					<col width="14.3%" />
				</colgroup>
				<thead>
					<tr>
						<th>일</th>
						<th>월</th>
						<th>화</th>
						<th>수</th>
						<th>목</th>
						<th>금</th>
						<th>토</th>
					</tr>
				</thead>
				<tbody>
				<?
					$tot = count($result);
					if ($tot == 0) {
				?>
					<tr>
						<td>달력이 존재하지 않습니다.</td>
					</tr>
				<?
					} else {
						for ($i=0; $i<count($result); $i++) {
							$row = $result[$i];
							
							$name = $row[name];			// 요일
							$today = $row[today];		// 날짜
							$holiday = $row[holiday];	// 공휴일 여부(공휴일인 경우 공휴일명)
							
							$styleMouse = "";
							$dateStyle = "";
							
							if ($name == 1) {
							} else if ($name == 7) {
								$dateStyle = "blue";
							}
							
							if ($holiday != '0') {
								$dateStyle = "red";
							}
							
							if ($holiday == '0') {
								if ($name == 1) {
									$dateStyle = "red";
								}
							}
							
							$date = "<span class='".$dateStyle."'>".substr($today,8)."</span>";
							
							if ($i == 0 || 1 == $name) { 
				?>
					<tr>
				<?
							}
							if ($i == 0) {
								for ($j=0; $j<$name-1; $j++) {
				?>
						<td></td>
				<?
								}
							}
				?>
						<td>
							<? if ($row['cnt'] == 0) { ?>
							<a href="javascript:;" class="popupTrigger" data-popup-id="pop_date<?=$i?>"><span class="date <?=$dateStyle?>"><?=$date?></span></a>
							<? 
				                } else { 
				                    $result2 = rstToArray($s->getTodayList($row['today']));
				                    
				            ?>
							<a href="javascript:;" class="popupTrigger" data-popup-id="pop_date<?=$i?>_0"><span class="date <?=$dateStyle?>"><?=$date?></span></a>
							<? } ?>
							<? 
								if ($row['cnt'] > 0) {
									$result2 = rstToArray($s->getTodayList($row['today']));
									for ($m=0; $m<count($result2); $m++) {
										$row2 = $result2[$m];
							?>
										<span><a href="#" class="popupTrigger <? if ($row['type']==1) echo "red"; ?>" data-popup-id="pop_date<?=$i?>_<?=$m?>" title="수정하기"><?=$row2['title']?></a> <input type="button" value="x" title="삭제" class="del_btn" onclick="goDel('<?=$row2['no']?>');"/></span>
							<? 
									} 
								}		
							?>
						</td>
				<?
							if ($i == $tot-1) {
								for ($k=0; $k<7-$name; $k++) {
				?>
						<td></td>
				<?
								}
							}
				?>
				<?
							if ($i == $tot-1 || 7 == $name) {
				?>
					</tr>
				<?
							}
						}
					}
				?>
					
				</tbody>
			</table>
		</div>
		<!-- //list -->
		<div class="caption mt20">
			<ul>
				<li>날짜를 클릭하면 해당날짜에 내용등록이 가능합니다.</li>
				<li>등록된 내용을 클릭하면 수정이 가능합니다.</li>
				<li>[x] 버튼을 클릭하면 삭제됩니다.</li>
			</ul>
		</div>
	</div>
	<!-- //contents -->
</div>
<!-- e:warp --> 
<? include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php"; ?>

<!-- s:일정등록팝업-->

<style>

.popup {
  position:fixed;
  top:0;
  right:0;
  width:100%;
  height:100%;
 
  z-index:1000;
  -webkit-transition:-webkit-transform 0.5s, opacity 0.5s;
  transition:transform 0.5s, opacity 0.5s;
  overflow-y:auto;
  -webkit-overflow-scrolling:touch;
  display:none;
  -webkit-transform:translate3d(0,0,0);
  transform:translate3d(0,0,0);
}

.popup:not([class*="fade-"]){
  background:rgba(17, 17, 17, 0.9);
}

.popup .content {
  overflow-y: auto;
}

.popupShown .popup.visible {
  display:block;
  -webkit-animation:fadeIn 0.75s;
  animation:fadeIn 0.75s;
}

.popupShown .popup.visible:not(.animated) .popupContent {
   -webkit-animation:zoomOut 0.5s 0.25s backwards;
   animation:zoomOut 0.5s 0.25s backwards;
}

.popupShown .slow .popup.visible {
  -webkit-animation:fadeIn 1.25s;
  animation:fadeIn 1.25s;
}

  .popupShown .slow .popup.visible:not(.animated) .popupContent {
     -webkit-animation:zoomOut 1s 0.25s backwards;
     animation:zoomOut 1s 0.25s backwards;
  }


.popupShown .fast .popup.visible {
  -webkit-animation:fadeIn 0.5s;
  animation:fadeIn 0.5s;
}
  .popupShown .fast .popup.visible .popupContent {
     -webkit-animation:zoomOut 0.5s 0.25s backwards;
     animation:zoomOut 0.5s 0.25s backwards;
  }
  </style>

<? 
	for ($i=0; $i<count($result); $i++) { 
		$row = $result[$i];	
?>
<div class="popup" data-popup-id="pop_date<?=$i?>">
	<div class="diary_wr popupContent">
		<div class="diary_top">
			<span class="title">휴일내용등록</span>
			<p class="close" id="close<?=$i?>"></p>
		</div>
		<div class="box">
			<div class="write">
				<div class="wr_box">
				<form name="frm<?=$i?>" id="frm<?=$i?>">
					<table>
						<colgroup>
							<col width="20%">
							<col width="*">
						</colgroup>
						<tbody>
						<tr>
							<th>날짜</th>
							<td>
								<input type="text" name="startday" id="startday<?=$i?>"  value="<?=$row['today']?>" class="dateTime" readonly />
							</td>
						</tr>
						<tr>
							<th>휴일체크</th>
							<td colspan="3">
								<input type="checkbox" name="type" id="type<?=$i?>" value="1" /> 휴일체크
							</td>
						</tr>
						<tr>
							<th>내용</th>
							<td>
								<input type="text" name="title" id="title<?=$i?>"  value=""/>
							</td>
						</tr>
						</tbody>
					</table>
				<input type="hidden" name="cmd" id="cmd<?=$i?>" value="write"/>
				</form>	
				</div>
				<!-- //wr_box -->
				<div class="btnSet">
					<a href="javascript:;" class="btn" onclick="goSave('<?=$i?>');">저장</a>
				</div>
				<!-- //btnSet -->
			</div>
		</div>
	</div>
</div>



<? 
	if ($row['cnt'] > 0) {
		$result2 = rstToArray($s->getTodayList($row['today']));
		for ($m=0; $m<count($result2); $m++) {
			$row2 = $result2[$m];
?>
<div class="popup" data-popup-id="pop_date<?=$i?>_<?=$m?>" >
	<div class="diary_wr popupContent">
		<div class="diary_top">
			<span class="title">휴일내용등록</span>
			<p class="close" id="close<?=$i?>_<?=$m?>"></p>
		</div>
		<div class="box">
			<div class="write">
				<div class="wr_box">
				<form name="frm<?=$i?>_<?=$m?>" id="frm<?=$i?>_<?=$m?>">
					<table>
						<colgroup>
							<col width="20%">
							<col width="*">
						</colgroup>
						<tbody>
						<tr>
							<th>날짜</th>
							<td>
								<input type="text" name="startday" id="startday<?=$i?>_<?=$m?>"  value="<?=$row['today']?>" class="dateTime" readonly/>
							</td>
						</tr>
						<tr>
							<th>휴일체크</th>
							<td colspan="3">
								<input type="checkbox" name="type" id="type<?=$i?>_<?=$m?>" value="1" <?=getChecked(1, $row2['type'])?>/> 휴일체크
							</td>
						</tr>
						<tr>
							<th>내용</th>
							<td>
								<input type="text" name="title" id="title<?=$i?>_<?=$m?>"  value="<?=$row2['title']?>"/>
							</td>
						</tr>
						</tbody>
					</table>
				<input type="hidden" name="cmd" id="cmd<?=$i?>_<?=$m?>" value="edit"/>
				<input type="hidden" name="no" id="no<?=$i?>_<?=$m?>" value="<?=$row2['no']?>"/>
				</form>		
				</div>
				<!-- //wr_box -->
				<div class="btnSet">
					<a href="javascript:;" class="btn" onclick="goEdit('<?=$i?>_<?=$m?>');">수정</a>
				</div>
				<!-- //btnSet -->
			</div>
		</div>
	</div>
</div>
<?
		}
	}
?>
<? } ?>
<!-- e:일정등록팝업-->
</body>
</html>
