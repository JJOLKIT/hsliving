<? include_once $_SERVER['DOCUMENT_ROOT']."/include/common.php"; ?>
<? include_once $_SERVER['DOCUMENT_ROOT']."/header.php"; ?>
<? include_once $_SERVER['DOCUMENT_ROOT']."/include/popup/mainPopup.php" ?>
<?
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/function.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/codeUtil.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/dateUtil.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/util/page.php";
	
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Visual.class.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/GalleryCt.class.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Notice.class.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Gallery.class.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/board/Sns.class.php";
	
	$visual = new Visual(99, 'visual', $_REQUEST);
	$vPageCount = $visual->getMainCount($_REQUEST);
	$vresult = $visual->getMainList($_REQUEST);

	$program = new GalleryCt(10, 'program', 'program_category', $_REQUEST);
	$pPageCount = $program->getCount($_REQUEST);
	$presult = ($program->getList($_REQUEST));

	$notice = new Sns(3, 'notice', $_REQUEST);
	$rowPageCount = $notice->getCount($_REQUEST);
	$result = $notice->getList($_REQUEST);

	$event = new Gallery(3, 'event', $_REQUEST);
	$ePageCount = $event->getCount($_REQUEST);
	$eresult = $event->getList($_REQUEST);

	$facebook = new Sns(3, 'facebook', $_REQUEST);
	$fPageCount = $facebook->getCount($_REQUEST);
	$fresult = $facebook->getList($_REQUEST);

	$youtube = new Sns(3, 'youtube', $_REQUEST);
	$yPageCount = $youtube->getCount($_REQUEST);
	$yresult = $youtube->getList($_REQUEST);

	$insta = new Sns(3, 'insta', $_REQUEST);
	$iPageCount = $insta->getCount($_REQUEST);
	$iresult = $insta->getList($_REQUEST);
?>
<?
	$root = $_SERVER['DOCUMENT_ROOT'];
	$p = "main";
?>

<div id="main">
	<div class="main_visual">
		<div class="size">
			<div class="swiper">
				<div class="swiper-container">
					<div class="swiper-wrapper">
						<?
							if($vPageCount[0] == 0){
						?>
						<div class="swiper-slide">
							<div class="tb" style="background-image:url('/img/main_visual.jpg')">
								<div class="tbc">
									<div class="txt">
										<span>22년 원데이클라스</span>
										<b>바리스타 1일 체험 강좌 개설</b>
										<p>신청기간   2022.04.10 - 2022.04.29</p>
									</div>
								</div>
							</div>
						</div>
						<?}else{
							while($vrow = mysql_fetch_assoc($vresult)){
						?>
						<div class="swiper-slide" onclick="location.href='<?=$vrow['relation_url']?>';">
							<div class="pcta">
								<div class="tb" style="background-image:url('/upload/visual/<?=$vrow['imagename']?>')">
									<div class="tbc">
										<div class="txt">
											<?if($vrow['subtitle'] != ''){?>
											<span><?=$vrow['subtitle']?></span>
											<?}?>
											<?if($vrow['title'] != ''){?>
											<b><?=$vrow['title']?></b>
											<?}?>
											<?if($vrow['stday'] != '0000-00-00'){?>
											<p>신청기간   <?=$vrow['stday']?> - <?=$vrow['etday']?></p>
											<?}?>
										</div>
									</div>
								</div>
							</div>

							<!-- 모바일 -->
							<div class="mo">
								<div class="tb back_img" style="background-image:url('/upload/visual/<?=$vrow['filename']?>')">
									<div class="tbc">
										<div class="txt">
											<?if($vrow['subtitle'] != ''){?>
											<span><?=$vrow['subtitle']?></span>
											<?}?>
											<?if($vrow['title'] != ''){?>
											<b><?=$vrow['title']?></b>
											<?}?>
											<?if($vrow['stday'] != '0000-00-00'){?>
											<p>신청기간   <?=$vrow['stday']?> - <?=$vrow['etday']?></p>
											<?}?>
										</div>
									</div>
								</div> 
							</div>
						</div>
						<?}}?>
						<!-- <div class="swiper-slide">
							<div class="tb" style="background-image:url('/img/main_visual.jpg')">
								<div class="tbc">
									<div class="txt">
										<span>22년 원데이클라스</span>
										<b>바리스타 1일 체험 강좌 개설</b>
										<p>신청기간   2022.04.10 - 2022.04.29</p>
									</div>
								</div>
							</div>
						</div> -->
					</div>
				</div>
				<div class="btm_wrap">
					<div class="swiper-pagination"></div>
					<div class="slide_btns">
						<div class="swiper-button-prev prev"><img src="/img/slide_prev.svg"/></div>
						<div class="swiper-button-next next"><img src="/img/slide_next.svg"/></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="con1">
		<div class="size clear">
			<div class="t_wrap">
				<span>화성시 생활문화창작소</span>
				<b>공간 소개</b>
				<p>화성시 생활문화창작소의 <br/>다섯개의 공간을 소개합니다.</p>
			</div>
			<div class="ul_wrap">
				<ul class="clear">
					<li>
						<div class="pic_wrap" style="background-image:url('/img/main_con1_img1.jpg')">
							<a href="/space/index.php#space1">
								<span>01</span>
								<div class="shd tb">
									<div class="tbc">
										<b>키친랩</b>
										<p>최대 수용인원 8명</p>
									</div>
								</div>
							</a>
						</div>
					</li>
					<li>
						<div class="pic_wrap" style="background-image:url('/img/main_con1_img2.jpg')">
							<a href="/space/index.php#space2">
								<span>02</span>
								<div class="shd tb">
									<div class="tbc">
										<b>커뮤니티라운지</b>
										<p>최대 수용인원 100명</p>
									</div>
								</div>
							</a>
						</div>
						<div class="pic_wrap" style="background-image:url('/img/main_con1_img4.jpg')">
							<a href="/space/index.php#space4">
								<span>04</span>
								<div class="shd tb">
									<div class="tbc">
										<b>디자인랩</b>
									</div>
								</div>
							</a>
						</div>
					</li>
					<li class="last">
						<div class="pic_wrap" style="background-image:url('/img/main_con1_img3.jpg')">
							<a href="/space/index.php#space3">
							<span>03</span>
								<div class="shd tb">
									<div class="tbc">
										<b>커뮤니티룸</b>
										<p>최대 수용인원 20명</p>
									</div>
								</div>
							</a>
						</div>
						<div class="pic_wrap" style="background-image:url('/img/main_con1_img5.jpg')">
							<a href="/space/index.php#space5">
							<span>05</span>
								<div class="shd tb">
									<div class="tbc">
										<b>리빙랩</b>
										<p>최대 수용인원 7명</p>
									</div>
								</div>
							</a>
						</div>
					</li>
				</ul>
			</div>
			<div class="ul_wrap mov swiper">
				<div class="slide_btns">
					<div class="swiper-button-prev prev"><img src="/img/slide_prev.svg"/></div>
					<div class="swiper-button-next next"><img src="/img/slide_next.svg"/></div>
				</div>
				<div class="swiper-container">
					<ul class="swiper-wrapper">
						<li class="swiper-slide">
							<div class="pic_wrap" style="background-image:url('/img/main_con1_img1.jpg')">
								<a href="/space/index.php#space1">
								<span>01</span>
								<div class="shd tb">
									<div class="tbc">
										<b>키친랩</b>
										<p>최대 수용인원 8명</p>
										</div>
									</div>
								</a>
							</div>
						</li>
						<li class="swiper-slide">
						<div class="pic_wrap" style="background-image:url('/img/main_con1_img2.jpg')">
							<a href="/space/index.php#space2" class="on">
								<span>02</span>
								<div class="shd tb">
									<div class="tbc">
										<b>커뮤니티라운지</b>
										<p>최대 수용인원 9명</p>
									</div>
								</div>
							</a>
						</div>
					</li>
					<li class="swiper-slide">
						<div class="pic_wrap" style="background-image:url('/img/main_con1_img3.jpg')">
							<a href="/space/index.php#space3" class="on">
								<span>03</span>
								<div class="shd tb">
									<div class="tbc">
										<b>커뮤니티룸</b>
										<p>최대 수용인원 20명</p>
									</div>
								</div>
							</a>
						</div>
					</li>
					<li class="swiper-slide">
						<div class="pic_wrap" style="background-image:url('/img/main_con1_img4.jpg')">
							<a href="/space/index.php#space4" class="on">
							<span>04</span>
								<div class="shd tb">
									<div class="tbc">
										<b>디자인랩</b>
									</div>
								</div>
							</a>
						</div>
					</li>
					<li class="swiper-slide">
						<div class="pic_wrap" style="background-image:url('/img/main_con1_img5.jpg')">
							<a href="/space/index.php#space5" class="on">
							<span>05</span>
								<div class="shd tb">
									<div class="tbc">
										<b>리빙랩</b>
										<p>최대 수용인원 7명</p>
									</div>
								</div>
							</a>
						</div>
					</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="ab_img"><img src="/img/con1_bg.png"/></div>
	</div>
	<div class="con2">
		<div class="size ">
			<div class="tp_wrap clear">
				<div class="t_wrap">
					<span>화성시 생활문화창작소</span>
					<b>프로그램 소개</b>
				</div>
				<div class="bt_wrap">
					<div class="slide_btns">
						<div class="swiper-button-prev prev"><img src="/img/slide_prev.svg"/></div>
						<div class="swiper-button-next next"><img src="/img/slide_next.svg"/></div>
					</div>
				</div>
			</div>
			<div class="sl_wrap">
				<div class="swiper">
					<div class="swiper-container">
						<div class="swiper-wrapper">
							<?
								if($pPageCount[0] == 0){	
							?>
							<div class="swiper-slide">
								<a class="pic" href="javascript:;"  style="background-image:url('/img/main_con2.jpg')">
									<img src="/img/main_con2.jpg"/>
								</a>
							</div>
							<?}else{
							while($prow = mysql_fetch_assoc($presult)){
							?>
							<div class="swiper-slide">
								<a class="pic" href="/program/view.php?no=<?=$prow['no']?>"  style="background-image:url('/upload/program/<?=$prow['imagename']?>')">
									<img src="/img/main_con2.jpg"/>
								</a>
							</div>
							<?}}?>
							<!--<div class="swiper-slide">
								<a class="pic" href="javascript:;"  style="background-image:url('/img/main_con2.jpg')">
									<img src="/img/main_con2.jpg"/>
								</a>
							</div>-->
	
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="con3">
		<div class="size">
			<div class="hf_wrap  clear">
				<div class="hf_box">
					<h2>공지사항</h2>
					<div class="ul_wrap">
						<ul class="clear">
							<?
								if($rowPageCount[0] == 0){	
							?>
							<li>
								등록된 게시글이 없습니다.
							</li>
							<?}else{
							while($row = mysql_fetch_assoc($result)){
							?>
							<li>
								<a href="/news/notice/view.php?no=<?=$row['no']?>">
									<div class="tb">
										<div class="tbc">
											<b><?=$row['title']?></b>
										</div>
									</div>
									<p><?=strip_tags($row['contents'])?></p>
									<span><?=getYMD($row['registdate'])?></span>
								</a>
							</li>
							<?}}?>
							<!-- <li>
								<a href="javascript:;">
									<div class="tb">
										<div class="tbc">
											<b>화성시 생활문화 창작소 홈페이지 리뉴얼</b>
										</div>
									</div>
									<p>화성시 생활문화 창작소가 홈페이지를 리뉴얼 했습니다! 새단장한 모습으로 다시 돌아온 창작소…</p>
									<span>2022-04-23</span>
								</a>
							</li> -->
						</ul>
					</div>
				</div>
				<div class="hf_box">
					<h2>행사일정</h2>
					<div class="ul_wrap2">
						<ul>
							<?
								if($ePageCount[0] == 0){	
							?>
							<li>
								<a href="javascript:;" style="cursor:default">
									<p><em>등록된 게시글이 없습니다.</em></p>
								</a>
							</li>
							<?}else{
							while($erow = mysql_fetch_assoc($eresult)){
							?>
							<li>
								<a href="/news/gallery/view.php?no=<?=$erow['no']?>">
									<p><em><?=strip_tags($erow['title'])?></em></p>
									<span><?=getYMD($erow['registdate'])?></span>
								</a>
							</li>
							<?}}?>
							<!-- <li>
								<a href="javascript:;">
									<p><em>22년 상반기 대관문의 및 예약안내, 실시간 예약현황 공지</em></p>
									<span>2022-04-23</span>
								</a>
							</li> -->
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="con4">
		<div class="size">
			<div class="mov">
				<ul class="clear">
					<li>
						<a href="https://www.facebook.com/hslcc2022/" target="_blank">
							<p class="pic" style="background-image:url('/img/main_fico01.png')"><img src="/img/main_fico03.png"/></p>
							<span>facebook</span>
						</a>
					</li>
					<li>
						<a href="https://www.youtube.com/channel/UCl2ikh3zmSMuLujxYNVaaJg" target="_blank">
							<p class="pic" style="background-image:url('/img/main_fico02.png')"><img src="/img/main_fico03.png"/></p>
							<span>YOUTUBE</span>
						</a>
					</li>
					<li>
						<a href="https://www.instagram.com/hslcc2022/" target="_blank">
							<p class="pic" style="background-image:url('/img/main_fico03.png')"><img src="/img/main_fico03.png"/></p>
							<span>instagram</span>
						</a>
					</li>
				</ul>
			</div>
			<div class="tab_wrap">
				<div class="tab_list">
					<ul>
						<li>
							<a href="javascript:;" class="on">
								<span><svg version="1.1" id="Layer_3" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"	 y="0px" viewBox="0 0 35 35" style="enable-background:new 0 0 35 35;" xml:space="preserve"><path class="st0" d="M26.78,0H8.22C3.69,0,0,3.68,0,8.22v18.56C0,31.32,3.69,35,8.22,35h18.56c4.54,0,8.22-3.68,8.22-8.22V8.22	C35,3.68,31.32,0,26.78,0z M17.5,28.63c-6.15,0-11.13-4.98-11.13-11.13c0-6.15,4.98-11.13,11.13-11.13s11.13,4.98,11.13,11.13	C28.63,23.64,23.65,28.63,17.5,28.63z M29,8.54c-1.43,0-2.6-1.16-2.6-2.59c0-1.43,1.16-2.59,2.6-2.59c1.43,0,2.59,1.16,2.59,2.59	C31.6,7.38,30.44,8.54,29,8.54z"/><path class="st0" d="M17.71,13.2c-2.58,0-4.66,2.09-4.66,4.67c0,2.58,2.09,4.66,4.66,4.66c2.58,0,4.67-2.09,4.67-4.66	C22.38,15.29,20.29,13.2,17.71,13.2z"/></svg>
								</span>
								<b>instagram</b>
							</a>
						</li>
						<li>
							<a href="javascript:;" >
								<span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 284.46 282.2"><defs></defs><g id="Layer_2" data-name="Layer 2"><g data-name="Layer 1"><path  d="M225.83,0H58.64A58.81,58.81,0,0,0,0,58.64V223.56A58.81,58.81,0,0,0,58.64,282.2h93.23V174.53H115.6v-44.2h36.27V91.8c0-65.73,88.39-47.6,88.39-47.6V82.73H217.6c-21.81,0-20.4,20.4-20.4,20.4v27.2h43.06l-5.66,44.2H197.2V282.2h28.63a58.81,58.81,0,0,0,58.63-58.64V58.64A58.81,58.81,0,0,0,225.83,0Z"/></g></g></svg></span>
								<b>facebook</b>
							</a>
						</li>
						<li>
							<a href="javascript:;">
								<span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 183.96 128.77"><defs></defs><title>Asset 1</title><g id="Layer_2" data-name="Layer 2"><g data-name="Layer 1"><path d="M180.11,20.11A23,23,0,0,0,163.85,3.85C149.5,0,92,0,92,0S34.45,0,20.11,3.85A23,23,0,0,0,3.84,20.11C0,34.45,0,64.39,0,64.39s0,29.93,3.84,44.27a23.05,23.05,0,0,0,16.27,16.27C34.45,128.77,92,128.77,92,128.77s57.52,0,71.87-3.84a23,23,0,0,0,16.26-16.27C184,94.32,184,64.39,184,64.39S184,34.45,180.11,20.11ZM73.58,92V36.79l47.8,27.6Z"/></g></g></svg></span>
								<b>youtube</b>
							</a>
						</li>
					</ul>
				</div>
				<div class="tab_box">
					<div class="tabs on">
						<ul class="ist clear">
							<?
								if($iPageCount[0] == 0){	
							?>
							<li>
								<a href="javascript:;">
									등록된 게시글이 없습니다.
								</a>
							</li>
							<?}else{
							while($irow = mysql_fetch_assoc($iresult)){
							?>
							<li>
								<a href="<?=$irow['relation_url']?>" target="_blank">
									<div class="pic" style="background-image:url('/upload/insta/<?=$irow['imagename']?>')"><img src="/img/main_con3.jpg"/></div>
									<div class="txt">
										<em></em>
										<b><?=$irow['title']?></b>
										<span><?=getYMD($irow['registdate'])?></span>
									</div>
								</a>
							</li>
							<?}}?>
							<!-- <li>
								<a href="javascript:;">
									<div class="pic" style="background-image:url('/img/main_con3.jpg')"><img src="/img/main_con3.jpg"/></div>
									<div class="txt">
										<em></em>
										<b>커뮤니티 라운지 대관 후기</b>
										<span>2022-04-23</span>
									</div>
								</a>
							</li> -->
						</ul>
					</div>
					<div class="tabs ">
						<ul class="fb clear">
							<?
								if($fPageCount[0] == 0){	
							?>
							<li>
								<a href="javascript:;">
									등록된 게시글이 없습니다.
								</a>
							</li>
							<?}else{
							while($frow = mysql_fetch_assoc($fresult)){
							?>
							<li>
								<a href="<?=$frow['relation_url']?>" target="_blank">
									<div class="pic" style="background-image:url('/upload/facebook/<?=$frow['imagename']?>')"><img src="/img/main_con3.jpg"/></div>
									<div class="txt">
										<em></em>
										<b><?=$frow['title']?></b>
										<span><?=getYMD($frow['registdate'])?></span>
									</div>
								</a>
							</li>
							<?}}?>
							<!-- <li>
								<a href="javascript:;">
									<div class="pic" style="background-image:url('/img/main_con3.jpg')"><img src="/img/main_con3.jpg"/></div>
									<div class="txt">
										<em></em>
										<b>커뮤니티 라운지 대관 후기</b>
										<span>2022-04-23</span>
									</div>
								</a>
							</li> -->
						</ul>
					</div>
					<div class="tabs ">
						<ul class="ytb clear">
							<?
								if($yPageCount[0] == 0){	
							?>
							<li>
								<a href="javascript:;">
									등록된 게시글이 없습니다.
								</a>
							</li>
							<?}else{
							while($yrow = mysql_fetch_assoc($yresult)){
							?>
							<li>
								<a href="<?=$yrow['relation_url']?>" target="_blank">
									<div class="pic" style="background-image:url('/upload/youtube/<?=$yrow['imagename']?>')"><img src="/img/main_con3.jpg"/></div>
									<div class="txt">
										<em></em>
										<b><?=$yrow['title']?></b>
										<span><?=getYMD($yrow['registdate'])?></span>
									</div>
								</a>
							</li>
							<?}}?>
							<!-- <li>
								<a href="javascript:;">
									<div class="pic" style="background-image:url('/img/main_con3.jpg')"><img src="/img/main_con3.jpg"/></div>
									<div class="txt">
										<em></em>
										<b>커뮤니티 라운지 대관 후기</b>
										<span>2022-04-23</span>
									</div>
								</a>
							</li> -->
						</ul>
					</div>
					
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	var swiper = new Swiper(".main_visual .swiper-container", {
		slidesPerView: "auto",
		spaceBetween: 30,
		autoplay:{
			delay:3000
		},
		speed: 1000,
		<?if($vPageCount[0] < 2){?>
		loop:false,
		<?}else{?>
		loop:true,
		<?}?>
		pagination: {
			el: ".main_visual .swiper-pagination",
		 // clickable: true,
		},
		navigation: {
			nextEl: ".main_visual .swiper-button-next",
			prevEl: ".main_visual .swiper-button-prev",
		},
	});
	var space = new Swiper(".con1 .swiper-container", {
		slidesPerView: "auto",
		spaceBetween: 30,
		//centeredSlides:true,
	/*	autoplay:{
			delay:1500
		},*/
		navigation: {
			nextEl: ".con1 .swiper-button-next",
			prevEl: ".con1 .swiper-button-prev",
		},
	});
	var pr = new Swiper(".con2 .swiper-container", {
		//		slidesPerView: 1.3,
		breakpoints : {
			320 : {slidesPerView: 1.3},
			560 : {slidesPerView: "auto"}
		},
		
		spaceBetween: 30,
		/*autoplay:{
			delay:1500
		},*/
		<?if($pPageCount[0] < 8){?>
		loop:false,
		<?}else{?>
		loop:true,
		<?}?>
		navigation: {
			nextEl: ".con2 .swiper-button-next",
			prevEl: ".con2 .swiper-button-prev",
		},
	});
</script>
<?
	include_once $root."/footer.php";
?>