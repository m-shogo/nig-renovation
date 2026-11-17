<?php
global $_hpRootDir,$NowLanguage;


//	共通ヘッダー読込み
get_header( $NowLanguage['load'] );

?>


	<div class="mainWrapp bgColorGl">
		<div id="bodies" class="autoWrapp">
			<div id="breadcrumb" class="bgColorGl">
				<ul>
					<li class="next"><a href="<?php echo home_url('/'); ?>">Home</a></li>
				</ul>
			</div>
		</div>
	</div>

	<div id="contentsWrapper" class="mainWrapp">
		<div class="autoWrapp">
		
			<div id="leftSlot">
			</div>
		
			<div id="rightSlot">

	
			</div>
			<br style="clear:both">
		</div>
	
	</div>


<?php get_footer($NowLanguage['load']); ?>