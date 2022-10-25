<div class="sub_visual" style="background-image:url('/img/sub_visual.jpg')">
	<div class="size">
		<div class="tb">
			<div class="tbc">
				<div class="txt">
					<b><?=$json[$p][0]['name']?></b>
					<? if($p != "space"){?>
					<div class="deplist">
						<ul class="clear">
						<?for($i = 0; $i < count($json[$p][1]); $i++){  ?>
							<li <?if($i == $sp){ echo "class='active'";}?>><a href="<?=$json[$p][1][$i]['link']?>"><span><?=$json[$p][1][$i]['name']?></span></a></li>
						<?}?>
						</ul>
					</div>
					<?}?>
				</div>
			</div>
		</div>
		<div class="nav">
			
			<span><img src="/img/home_ico.png"/></span>
			<span><?=$json[$p][0]['name']?></span>
			<? if($p != "space"){?>
			<span><?=$json[$p][1][$sp]['name']?></span>
			<?}else{?>
			<span><?=$json[$p][0]['name']?></span>
			<?}?>
		</div>
		<div class="nav mov">
			<div class="nav_wrap">
				<span><img src="/img/home_ico.png"/></span>
				<span><?=$json[$p][0]['name']?></span>
				<? if($p != "space"){?>
				<span><a href="javascript:;" class="show_dep"><?=$json[$p][1][$sp]['name']?></a></span>
				<?}else{?>
				<span><a href="javascript:;" class="show_dep"><?=$json[$p][0]['name']?></a></span>
				<?}?>
				<div class="mo_dep">
					<ul class="clear">
					<?for($i = 0; $i < count($json[$p][1]); $i++){  ?>
						<li <?if($i == $sp){ echo "class='active'";}?>><a href="<?=$json[$p][1][$i]['link']?>"><?=$json[$p][1][$i]['name']?></a></li>
					<?}?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>