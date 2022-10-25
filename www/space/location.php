<?
	$p = "company";
	$sp = 2;
	$spc= 0;
	$root = $_SERVER['DOCUMENT_ROOT'];
	include_once $root."/header.php";
?>

<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=0b2d4b51009f5f68172347291abf716b"></script>
<div id="sub" class="location">
	<?include_once $root."/include/sub_visual.php";?>
	<div class="con_wrap">
		<div class="con1">
			<div class="size clear">
				<div class="t_wrap">
					<span>화성시 생활문화창작소</span>
					<b>찾아오시는 길</b>
				</div>
				<div class="txt_wrap">
					<div class="tp_wrap">
						<p>경기도 화성시 봉담읍 효행로 212 <b>‘화성시 생활문화창작소’</b> </p>
						<b class="cal">031-267-2050, 2051</b>
					</div>
					<div class="btm_wrap">
						<div class=" b_wrap"><div class="img"><img src="/img/loc_ico01.png"></div><b>대중교통</b></div>
						<div class="p_wrap">
							<p>일반버스 : 1000, 34, 46, 700-2, 720-2,  65</p>
							<p>마을버스 : 31, 31-2, 9</p>
							<p>직행버스 : 1007, 1009, 7790, 8000</p>
							<p>화성시민캠퍼스(36159 / 36161) 하차 약 80m 도보 이동</p>
						</div>
					</div>
					<div class="btm_wrap mt0">
						<div class=" b_wrap"><div class="img"><img src="/img/loc_ico02.png"></div><b>자가용</b></div>
						<div class="p_wrap">
							<p>네비게이션 '화성시 생활문화창작소' 검색</p>
							<p>주차 : 생활문화창작소 앞 주차장 (무료)</p>
						</div>
					</div>
				</div>
				<div id="map"></div>
			</div>
		</div>

	</div>
</div>

<script>

	var mapContainer = document.getElementById('map'), // 지도를 표시할 div 
		mapOption = { 
				center: new kakao.maps.LatLng( 37.2289789339218, 126.96699496916355), // 지도의 중심좌표
				level: 4 // 지도의 확대 레벨
		};

	var map = new kakao.maps.Map(mapContainer, mapOption); // 지도를 생성합니다

	var imageSrc = '/img/loc_mark.png', // 마커이미지의 주소입니다    
		imageSize = new kakao.maps.Size(42, 56), // 마커이미지의 크기입니다
		imageOption = {offset: new kakao.maps.Point(42, 56)}; // 마커이미지의 옵션입니다. 마커의 좌표와 일치시킬 이미지 안에서의 좌표를 설정합니다.

	// 마커의 이미지정보를 가지고 있는 마커이미지를 생성합니다
	var markerImage = new kakao.maps.MarkerImage(imageSrc, imageSize, imageOption),
		markerPosition = new kakao.maps.LatLng(37.22935355812993, 126.96956963915818); // 마커가 표시될 위치입니다
	//37.495133678534415, 127.03179591054541
	// 마커를 생성합니다
	var marker = new kakao.maps.Marker({
		position: markerPosition, 
		image: markerImage // 마커이미지 설정 
	});

	// 마커가 지도 위에 표시되도록 설정합니다
	marker.setMap(map); 
</script>
<?
	include_once $root."/footer.php";
?>