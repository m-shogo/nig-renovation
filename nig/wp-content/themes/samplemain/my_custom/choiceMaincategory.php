<?php

global $pagenow;
if(is_admin() && ($pagenow=='post-new.php' || $pagenow=='post.php')){

/*------------------------
	メインカテゴリ選択追加
	---------------------------*/

	add_action('admin_menu', 'add_mainCategory');
	add_action('save_post', 'save_mainCategory');
	function add_mainCategory(){
		if(function_exists('add_mainCategory')){
			add_meta_box('_myMainCategory', 'メインカテゴリ設定', 'insert_mainCategory', 'post', 'normal', 'low');
		}
	}
	
	function insert_mainCategory(){
		global $post;
		wp_nonce_field(wp_create_nonce(__FILE__), 'my_nonce');
?>
		<div id="_choiceMainCate"></div>'
		
		<script type="text/javascript">
		  //<![CDATA[
		  jQuery(document).ready(function($){
		  
	  
		  	var _makeChoiceCategorys = function( _coice ){
			
				jQuery("#_choiceMainCate").hide();
				jQuery("#_choiceMainCate").empty();
								
				var count = jQuery("#categorychecklist input:checked").length;
				
				if( count > 0 ){
				    jQuery("#categorychecklist input:checked").each(function(){
						jQuery("#_choiceMainCate").append("<label>"+jQuery(this).parent().html()+"</label>");
				    });
	
					jQuery("#_choiceMainCate label").each(function(){
						v = jQuery(this).find("input").attr("value");
						ch = ( count == 1 || _coice == v ) ? ' checked' : '' ;
						
						jQuery(this).find("input").remove();
						jQuery(this).prepend("<input type=\"radio\" value="+v+" name=\"_choiceCategory\""+ch+">");
						jQuery(this).append("<br>");
						
				    });
					
				}else{
				
					jQuery("#_choiceMainCate").append("カテゴリを選択してください");
				}
				
				jQuery("#_choiceMainCate").show();
			
			}
			
			_makeChoiceCategorys(<?php echo esc_html(get_post_meta($post->ID, '_choiceCategory', true));  ?>);
			
			jQuery(".categorychecklist input[type=checkbox]").on("change", function() {
				_makeChoiceCategorys(jQuery("#_choiceMainCate input[type=radio]:checked").val());
			});
			
		  
		  });
		  //]]>
		  </script>
<?php	
	}
	
	function save_mainCategory($post_id){
		$my_nonce = isset($_POST['my_nonce']) ? $_POST['my_nonce'] : null;
		if(!wp_verify_nonce($my_nonce, wp_create_nonce(__FILE__))) {
			return $post_id;
		}
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return $post_id; }
		if(!current_user_can('edit_post', $post_id)) { return $post_id; }
	
 		$data = $_POST['_choiceCategory'];
		
	
		if(get_post_meta($post_id, '_choiceCategory') == ""){
			add_post_meta($post_id, '_choiceCategory', $data, true);
		}elseif($data != get_post_meta($post_id, '_choiceCategory', true)){
			update_post_meta($post_id, '_choiceCategory', $data);
		}elseif($data == ""){
			delete_post_meta($post_id, '_choiceCategory', get_post_meta($post_id, '_choiceCategory', true));
		}
	}
	
	

/*--------------------------
	カテゴリ選択をradioに
function my_print_footer_scripts() {

	global $pagenow;
	global $post_type;
	if (is_admin() && ($pagenow=='post-new.php' || $pagenow=='post.php')){
		echo '<style type="text/css">
		#category-tabs, #category-adder {display:none;}
		#xxx-tabs, #xxx-adder {display:none;}
		
		.categorydiv .tabs-panel {padding: 0 !important; background: none; border: none !important;}
		</style>';
		
		echo '<script type="text/javascript">
		  //<![CDATA[
		  jQuery(document).ready(function($){
		    $(".categorychecklist input[type=checkbox]").each(function(){
		      $check = $(this);
		      var checked = $check.attr("checked") ? \' checked="checked"\' : \'\';
		      $(\'<input type="radio" id="\' + $check.attr("id")
		        + \'" name="\' + $check.attr("name") + \'"\'
		  	+ checked
			+ \' value="\' + $check.val()
			+ \'"/>\'
		      ).insertBefore($check);
		      $check.remove();
		    });
		  });
		  //]]>
		  </script>';		
	}


}
add_action('admin_print_footer_scripts', 'my_print_footer_scripts', 21);
-------------------------------*/


	
	
}

/*-------------
	メインカテゴリの優先順（数値の低い程優先上）
	--------------------------*/

function _defaultMainCategoryOrder( $slug ){

	$order = array(
				'prizes-and-awards_ja'	=> 1 ,	//	表彰
				'prizes-and-awards'	 	=> 1,	//	表彰
				'research-highlights_ja' => 2,	//	リサーチハイライト
				'research-highlights'	=> 2,	//	リサーチハイライト
				'events_ja'				=> 3,	//	イベント
				'events'				=> 3,	//	イベント
				'whats-new_ja'			=> 4,	//	
				'whats-new'				=> 4,	//	
				'information_ja' 		=> 100,	//	新着
				'information' 			=> 100,	//	新着
			);
	return isset( $order[$slug] ) ? $order[$slug] : 10000;
}