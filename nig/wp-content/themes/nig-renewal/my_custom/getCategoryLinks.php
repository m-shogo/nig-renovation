<?php

function get_category_menus( $categorySlug , $root_page ){

	switch( $categorySlug ){
		
		case 'prizes-and-awards':
		case 'prizes-and-awards_ja':
			
			$root_page[count($root_page)] = array(
						'ID'			=> '',
						'post_title'	=> ($categorySlug == 'prizes-and-awards_ja' ? '研究' : 'Research'),
						'post_name'		=> '',
						'link'			=> ''	
					);
			krsort($root_page);
			break;
	}
	
	
	return $root_page;


}
