$(function(){
	//이미지맵 반응형
	$('img[usemap]').rwdImageMaps();
	// Mobile Ui 
	
	var mobile = {
		open : function(){
			$('.menu_all').stop().animate({'left':'0'},400,'easeInOutQuad');
		},
		close : function(){
			$('.menu_all').stop().animate({'left':'100%'}, 400, 'easeInOutQuad');
		},
		open2 : function(){
			$('.menu_all_mo').stop().animate({'left':'0'},400,'easeInOutQuad');
		},
		close2 : function(){
			$('.menu_all_mo').stop().animate({'left':'100%'}, 400, 'easeInOutQuad');
		},
		down : function(target){
			$(target).addClass('on');
			$(target).next().stop().slideDown(400,'easeInOutQuad');
			
		},
		up : function(target){
			$(target).removeClass('on');
			$(target).next().stop().slideUp(400, 'easeInOutQuad');
		},
		siblingsUp : function(target){
			$(target).parent().siblings('li').children('a').removeClass('on');
			$(target).parent().siblings('li').children('ul').stop().slideUp(400, 'easeInOutQuad');
		},
		bgOn : function(){
			$('.mbg').stop().fadeIn(400);
		},
		bgOff : function(){
			$('.mbg').stop().fadeOut(400);
		}
	}

	
	$('.all_menu.pcv').on('click',function(){
		mobile.open();
		//mobile.bgOn();
		$('html,body').css({'overflow':'hidden' , 'height' : '100%'});
		//$.fn.fullpage.setAutoScrolling(false);
	});
	$('.menu_close').on('click',function(){
		mobile.close();
		//mobile.bgOff();
		$('html,body').css({'overflow':'visible' , 'height' : 'initial'});
	});
	$('.all_menu.mov').on('click',function(){
		mobile.open2();
		mobile.bgOn();
		$('html,body').css({'overflow':'hidden' , 'height' : '100%'});
		//$.fn.fullpage.setAutoScrolling(false);
	});
	$('.menu_close_mo').on('click',function(){
		mobile.close2();
		mobile.bgOff();
		$('html,body').css({'overflow':'visible' , 'height' : 'initial'});
	});

	$('.menu_all_mo .menu_wrap > ul > li > a').on('click',function(){
		if($(this).hasClass('on')){
			$('.menu_all_mo .menu_wrap > ul > li > a').removeClass('on');
			$('.menu_all_mo .menu_wrap > ul > li .depth2').stop().slideUp(400);
			


		}else{
			$('.menu_all_mo .menu_wrap > ul > li > a').removeClass('on');
			$('.menu_all_mo .menu_wrap > ul > li .depth2').stop().slideUp(400);
			$(this).addClass('on');
			
			$(this).siblings('.depth2').stop().slideDown(400);
		}
	});
	$('#top_btn a').on('click', function() {
		$('html, body').stop().animate({scrollTop:0}, '500');
		return false;
	});
	$('#main .tab_list ul li a').on('click',function(){
		var idx = $(this).parent().index();
		if($(this).hasClass('on')){
			
		}else{
			$('#main .tab_list ul li a').removeClass('on');
			$(this).addClass('on');
			$('#main .tab_box .tabs').removeClass('on');
			$('#main .tab_box .tabs').eq(idx).addClass('on');
		}
	});
	$('.sub_visual .nav.mov span a').on('click',function(){
		var idx = $(this).parent().index();
		if($(this).hasClass('on')){
			$(this).removeClass('on');
			$('.nav.mov .mo_dep').stop().slideUp(400);
		}else{
			$(this).addClass('on');
			$('.nav.mov .mo_dep').stop().slideDown(400);

		}
	});
	
	var gnbFlag = false;

	$('.gnb .depth1 > ul > li').on('mouseover',function(){
		if($(this).hasClass('on')){
			var idx = $(this).index();
			$(this).removeClass('on');
		}else{
			if(gnbFlag == false) {
				$(this).find('.depth2').stop().slideDown(400);
			}
			gnbFlag = true;
		}
	}).on('mouseleave',function(){

		if($(this).hasClass('on')){
			var idx = $(this).index();
			$(this).removeClass('on');
			//$('.allgnb .menus ul').eq(idx).removeClass('on');
		}else{
			if(gnbFlag == true){
				$(this).find('.depth2').stop().slideUp(400);
			}
			gnbFlag = false;
		}
	});

	$(' .quick_list ul li a, .imgmap area').click(function(event){            
            event.preventDefault();
            $('html,body').stop().animate({scrollTop:$(this.hash).offset().top - 40}, 800);
    });

});





/* 삭제하지 말것 */
String.prototype.replaceAll = function(org, dest) {
    return this.split(org).join(dest);
}

function refresh_captcha(){
	document.getElementById("capt_img").src="/include/captcha.php?waste="+Math.random(); 
	return false;
}
function openPopup(id){
	$('#'+id).stop().fadeIn(400);
}

function closePopup(id){
	$('#'+id).stop().fadeOut(400);
}


/* - - - - - - - - - */ 