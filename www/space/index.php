<?
	$p = "company";
	$sp = 1;
	$spc= 0;
	$root = $_SERVER['DOCUMENT_ROOT'];
	include_once $root."/header.php";
?>
<script>
	$(function(){
		var target = location.hash;
	
		console.log($('#sub').offset().top);

		if(target != ""){
			$('html, body').stop().animate({scrollTop : $(target).offset().top - 40},800);
		}
	});
	$(window).scroll(function(){
		var pos = $(document).scrollTop();
		var maplist = $('.map_list').offset().top;
		
		var sp1 = $('#space1').offset().top;
		var sp2 = $('#space2').offset().top;
		var sp3 = $('#space3').offset().top;
		var sp4 = $('#space4').offset().top;
		var sp5 = $('#space5').offset().top;
		var sp6 = $('#space6').offset().top;

		console.log('pos =' + pos);
		console.log('sp2 =' + pos);
		
		if(pos >= maplist ){
			$('.quick_list').addClass('fixed');
		}else{
			$('.quick_list').removeClass('fixed');
		}

		if(pos >= (sp1 - 160)){
			$('.map_list .map_wrap').removeClass('on');
			$('#space1').addClass('on');
			$('.quick_list ul li a').removeClass('on');
			$('.quick_list ul li').eq(0).find('a').addClass('on');
		}
		if(pos >= (sp2 - 160)){
			$('.map_list .map_wrap').removeClass('on');
			$('#space2').addClass('on');
			$('.quick_list ul li a').removeClass('on');
			$('.quick_list ul li').eq(1).find('a').addClass('on');
		}
		if(pos >= (sp3 - 160)){
			$('.map_list .map_wrap').removeClass('on');
			$('#space3').addClass('on');
			$('.quick_list ul li a').removeClass('on');
			$('.quick_list ul li').eq(2).find('a').addClass('on');
		}
		if(pos >= (sp4 - 160)){
			$('.map_list .map_wrap').removeClass('on');
			$('#space4').addClass('on');
			$('.quick_list ul li a').removeClass('on');
			$('.quick_list ul li').eq(3).find('a').addClass('on');
		}
		if(pos >= (sp5 - 160)){
			$('.map_list .map_wrap').removeClass('on');
			$('#space5').addClass('on');
			$('.quick_list ul li a').removeClass('on');
			$('.quick_list ul li').eq(4).find('a').addClass('on');
		}
		if(pos >= (sp6 - 300)){
			$('.map_list .map_wrap').removeClass('on');
			$('#space6').addClass('on');
			$('.quick_list ul li a').removeClass('on');
			$('.quick_list ul li').eq(5).find('a').addClass('on');
		}
	});
</script>
<div id="sub" class="space_idx">
	<?include_once $root."/include/sub_visual.php";?>
	<div class="con_wrap">
		<div class="con1" id="space0">
			<div class="size">
				<div class="t_wrap">
					<span>화성시 생활문화창작소</span>
					<b>공간 소개</b>
					<p>화성시 생활문화창작소의 열린 공간들을 소개합니다. <br/>도면 내 장소 명칭을 클릭하시면 해당 공간의 소개를 보실 수 있습니다.</p>
				</div>
				<div class="img imgmap">
					<img src="/img/map_space.png" usemap="#imgmap1" />
					<map id="imgmap1" name="imgmap1">
						<area alt="" title="" href="#space1" coords="258,462,733,672" shape="rect">
						<area alt="" title="" href="#space2" coords="158,0,593,4,593,195,625,217,676,215,679,455,251,459,210,410,156,402" shape="poly">
						<area alt="" title="" href="#space3" coords="737,460,1118,648" shape="rect">
						<area alt="" title="" href="#space4" coords="251,553,2,387" shape="rect">
						<area alt="" title="" href="#space5" coords="805,215,1042,457" shape="rect">
					</map>
				</div>
			</div>
		</div>
		<div class="map_list">
			<div class="quick_list">
				<ul>
					<!--<li>
						<a href="#space0"><span>가이드맵</span></a>
					</li>-->
					<li>
						<a href="#space1" class="on"><span>키친랩</span></a>
					</li>
					<li>
						<a href="#space2"><span>커뮤니티라운지</span></a>
					</li>
					<li>
						<a href="#space3"><span>커뮤니티룸</span></a>
					</li>
					<li>
						<a href="#space4"><span>디자인랩</span></a>
					</li>
					<li>
						<a href="#space5"><span>리빙랩</span></a>
					</li>
					<li>
						<a href="#space6"><span>야외테라스</span></a>
					</li>
				</ul>
			</div>
			<div class="map_wrap on" id="space1">
				<div class="size clear">
					<div class="map_tit con_tit">
						<b>키친랩</b>
					</div>
					<div class="map_info con_info ">
						<div class="mov img"><img src="/img/map_space01.png"/></div>
						<div class="txt">
							<b>키친은 크리에이티브한 공간이어야 합니다.</b>
							<p>생활문화창작소 키친은 ‘요리 만 하는 공간’에서 벗어나 레시피 개발, 소스 개발, 제로웨이스트, 케어푸드, 위생/편식 교육 등 주방의 모든 것을 배우고 체험함으로써 나만의 요리를 만들어 낼 수 있는 크리에이티브한 공간으로 운영됩니다.</p>
						</div>
						<div class="img_wrap clear">
							<div class="pic" style="background-image:url('/img/map1_space1_img01.jpg')"><img src="/img/map1_space1_img01.jpg"/></div>
							<div class="pic" style="background-image:url('/img/map1_space1_img2.jpg')"><img src="/img/map1_space1_img2.jpg"/></div>
							<div class="ab_img img"><img src="/img/map_space01.png"/></div>
						</div>
						<div class="info">
							<div>
								<b><em>활동</em></b>
								<p>쿠킹/베이킹 클래스, 영양/레시피 교육, 요리동호회/단체 활동</p>
							</div>
							<div>
								<b><em>구비물품</em></b>
								<p>아일랜드 주방 (인덕션, 오븐포함) 8조, 발효기, 냉장고, 블렌더믹서기, 반죽기, 소독기, 전기밥솥, 전자저울, 핸드믹서기, 각종 주방용품, 강의 녹화용 카메라, 식기류</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="map_wrap " id="space2">
				<div class="size clear">
					<div class="map_tit con_tit">
						<b>커뮤니티라운지</b>
					</div>
					<div class="map_info con_info ">
						<div class="mov img"><img src="/img/map_space02.png"/></div>
						<div class="txt">
							<b>커뮤니티라운지는 입체적인 멀티아트홀로 성장하고자 합니다.</b>
							<p>기존의 평면적인 전시/공연장과는 다르게 소규모 공연, 전시, 쇼와 같은 다양한 문화 콘텐츠를 입체적 공간에서 즐길 수 있도록 설계한 멀티아트홀로, 재미와 감동, 신선함과 편안함을 동시에 누리실 수 있습니다. </p>
						</div>
						<div class="img_wrap clear">
							<div class="pic" style="background-image:url('/img/map1_space1_img3.jpg')"><img src="/img/map1_space1_img3.jpg"/></div>
							<div class="pic" style="background-image:url('/img/map1_space1_img4.jpg')"><img src="/img/map1_space1_img4.jpg"/></div>
							<div class="ab_img img"><img src="/img/map_space02.png"/></div>
						</div>
						<div class="info">
							<div>
								<b><em>활동</em></b>
								<p>공연, 전시, 강연, 친목도모, 행사</p>
							</div>
							<div>
								<b><em>구비물품</em></b>
								<p>간이무대, 오디오 시스템, 테이블, 라운지바</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="map_wrap " id="space3">
				<div class="size clear">
					<div class="map_tit con_tit">
						<b>커뮤니티룸</b>
					</div>
					<div class="map_info con_info ">
						<div class="mov img"><img src="/img/map_space03.png"/></div>
						<div class="txt">
							<b>생활문화와 관련된 다양한 정보를 교류하는 공간입니다.</b>
							<p>전문강사 초빙 강연, 생활문화콘텐츠 교육 등 문화적 소양을 높일 수 있는 프로그램과 더불어 사용자 주도 정보교류 활동, 소모임, 스터디 등의 공간으로 활용됩니다.</p>
						</div>
						<div class="img_wrap clear">
							<div class="pic" style="background-image:url('/img/map1_space1_img5.jpg')"><img src="/img/map1_space1_img5.jpg"/></div>
							<div class="pic" style="background-image:url('/img/map1_space1_img6.jpg')"><img src="/img/map1_space1_img6.jpg"/></div>
							<div class="ab_img img"><img src="/img/map_space03.png"/></div>
						</div>
						<div class="info">
							<div>
								<b><em>활동</em></b>
								<p>교육, 스터디</p>
							</div>
							<div>
								<b><em>구비물품</em></b>
								<p>빔프로젝터, 오디오시스템, 교육용 테이블, 의자</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="map_wrap " id="space4">
				<div class="size clear">
					<div class="map_tit con_tit">
						<b>디자인랩</b>
					</div>
					<div class="map_info con_info ">
						<div class="mov img"><img src="/img/map_space04.png"/></div>
						<div class="txt">
							<b>생활문화와 관련된 맞춤 컨설팅을 제공합니다.</b>
							<p>디자인 및 제품홍보, 사진/영상 제작 등 생활문화와 관련된 다양한 분야의 컨설팅을 지원하는 공간입니다.</p>
						</div>
						<div class="img_wrap clear">
							<div class="pic" style="background-image:url('/img/map1_space1_img7.jpg')"><img src="/img/map1_space1_img7.jpg"/></div>
							<div class="pic" style="background-image:url('/img/map1_space1_img8.jpg')"><img src="/img/map1_space1_img8.jpg"/></div>
							<div class="ab_img img"><img src="/img/map_space04.png"/></div>
						</div>
						<div class="info">
							<div>
								<b><em>활동</em></b>
								<p>컨설팅</p>
							</div>
							<div>
								<b><em>구비물품</em></b>
								<p>회의테이블</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="map_wrap " id="space5">
				<div class="size clear">
					<div class="map_tit con_tit">
						<b>리빙랩</b>
					</div>
					<div class="map_info con_info ">
						<div class="mov img"><img src="/img/map_space05.png"/></div>
						<div class="txt">
							<b>생활문화 동호인들의 다양한 활동을 지원합니다.</b>
							<p>생활문화 동호인들의 활동 공간으로 동호회 활성화를 지원하여 성장을 견인하는 인큐베이터 공간으로 활용됩니다.</p>
						</div>
						<div class="img_wrap clear">
							<div class="pic" style="background-image:url('/img/map1_space1_img9.jpg')"><img src="/img/map1_space1_img9.jpg"/></div>
							<div class="pic" style="background-image:url('/img/map1_space1_img10.jpg')"><img src="/img/map1_space1_img10.jpg"/></div>
							<div class="ab_img img"><img src="/img/map_space05.png"/></div>
						</div>
						<div class="info">
							<div>
								<b><em>활동</em></b>
								<p>소모임, 스터디</p>
							</div>
							<div>
								<b><em>구비물품</em></b>
								<p>회의테이블, TV, 진열장</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="map_wrap last" id="space6">
				<div class="size clear">
					<div class="map_tit con_tit">
						<b>야외테라스</b>
					</div>
					<div class="map_info con_info ">
						
						<div class="txt">
							<b>생활문화창작소의 시그니처 공간입니다.</b>
							<p>화성시민이면 누구나 편안한 휴식을 취하실 수 있도록 마련된 생활문화창작소 시그니처 공간입니다.</p>
						</div>
						<div class="img_wrap clear">
							<div class="pic" style="background-image:url('/img/map1_space1_img11.jpg')"><img src="/img/map1_space1_img11.jpg"/></div>
							<div class="pic" style="background-image:url('/img/map1_space1_img12.jpg')"><img src="/img/map1_space1_img12.jpg"/></div>
						</div>
						<div class="info">
							<div>
								<b><em>활동</em></b>
								<p>휴게, 소모임</p>
							</div>
							<div>
								<b><em>구비물품</em></b>
								<p>테라스용 테이블, 의자</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?
	include_once $root."/footer.php";
?>