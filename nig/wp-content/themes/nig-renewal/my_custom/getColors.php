<?php

function get_menue_color( $c_slug  ) {

	$colors_array = array( 
	
		'seminar' 				=> 'bgColorBl',
		'prizes-and-awards' 	=> 'bgColorBl',
		'prizes-and-awards_ja' 	=> 'bgColorBl',
		'phd-program' 			=> 'bgColorLb',
		'phd-program_ja' 		=> 'bgColorLb',
		'important-notice' 		=> 'bgColorLb',
      	'whats-new' 			=> 'bgColorLb',  
		'important-notice_ja' 	=> 'bgColorLb',
		'important-notice_ja_test' 	=> 'bgColorLb',
      	'whats-new_ja' 			=> 'bgColorLb',        
		'nigjob-faculty'	=> 'bgColorGl',
		'nigjob-faculty-2'	=> 'bgColorGl',
		'nigjob-administration'	=> 'bgColorGl',
		'nigjob-administration-2'	=> 'bgColorGl',
		'nigjob-Postdoc'		=> 'bgColorGl',
		'nigjob-Postdoc-2'		=> 'bgColorGl',
		'nigjob-Technical'		=> 'bgColorGl',
		'nigjob-Technical-2'	=> 'bgColorGl',
		'nigjob-Other'			=> 'bgColorGl',
		'nigjob-Other-2'		=> 'bgColorGl',
//		'reserch-highlights' 	=> 'bgColorBl',
//		'research-highlights' 	=> 'bgColorBl',
//		'information' 			=> 'bgColorBr',
//		'events' 				=> 'bgColorLb',
	
	);
	
	$color = isset( $colors_array[$c_slug] ) ? $colors_array[$c_slug] : 'bgColorGl';
	
	return $color;


}
