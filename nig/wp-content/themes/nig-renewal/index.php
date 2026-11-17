<?php

#############################
##	変数設定
#############################
global $NowLanguage;

//	共通ヘッダー読込み
get_header( $NowLanguage['load'] );

if(  $NowLanguage['load'] == 'ja' )		get_template_part( 'index','ja' );
else									get_template_part( 'index','en' );


get_footer($NowLanguage['load']);

?>
