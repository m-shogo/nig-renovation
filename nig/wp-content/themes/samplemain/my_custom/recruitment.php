<?php

//	投稿タイプ名
define( "RECRUITMENT_POST_TYPE" , '_recruitment' );
define( "RECRUITMENT_CUSTOM_ENDDAY" , '_recruitmentEndDay' );
define( "RECRUITMENT_CUSTOM_ALERTDAY" , '_recruitmentAlertDay' );

//	ページスラッグ名
define( "RECRUITMENT_PAGE_SLAG" , 'opportunities' );

#######################################################################################

function RECRUITMENT_getTitle( $num ){
	global $NowLanguage;
	
	$_title = $NowLanguage['now'] == 'en' ? 
				array( 'Opportunities' , 'Recruitment' , 'End' ) : 
				array( '求人募集' , '募集中' , '募集終了' ) ;
				
	return isset( $_title[$num] ) ? $_title[$num] : '--' ;
}

// カスタム投稿タイプの追加

add_action( 'init', 'RECRUITMENT_create_post_type' );
function RECRUITMENT_create_post_type() {
	register_post_type( RECRUITMENT_POST_TYPE, // 投稿タイプ名の定義
		array(
			'description' => __( 'Recruitment.' ),
			'labels' => array(
				'name' => __( '求人情報' ), // 表示する投稿タイプ名
				'singular_name' => __( '求人情報' ),
				'add_new' => '新規追加',
				'add_new_item' => '求人を新規追加',
				'edit_item' => '求人を編集する',
				'new_item' => '求人追加'
			),
			'public' => true,
			'menu_position' =>5,
			'menu_icon'		=> 'dashicons-id',
			'supports' => array( 'title', 'editor')
		)
	);
}


add_action('admin_menu', 'RECRUITMENT_add_Recruitment');
add_action('save_post', 'RECRUITMENT_save_Recruitment');

function RECRUITMENT_add_Recruitment(){
	if(function_exists('RECRUITMENT_add_Recruitment')){
		add_meta_box('_recruitmentFild', '掲載終了日', 'RECRUITMENT_insert_Recruitment', RECRUITMENT_POST_TYPE, 'normal', 'high');
	}
}

function RECRUITMENT_insert_Recruitment(){
	global $post;
	wp_nonce_field(wp_create_nonce(__FILE__), 'my_nonce');
	
	$d1 = get_post_meta($post->ID, RECRUITMENT_CUSTOM_ENDDAY , true);
	$d1 = ("9999/12/31" == $d1) ? '' : $d1;
	
	$d2 = get_post_meta($post->ID, RECRUITMENT_CUSTOM_ALERTDAY, true);
	$d2 = ("9999/12/31" == $d2) ? '' : $d2;
	
	echo '<label for="'.RECRUITMENT_CUSTOM_ENDDAY.'">終了日</label>：<input type="text" id="'.RECRUITMENT_CUSTOM_ENDDAY.'" name="'.RECRUITMENT_CUSTOM_ENDDAY.'" size="10" value="'.esc_html($d1).'" /><span id="_reErr"></span><br>';
	echo '<label for="'.RECRUITMENT_CUSTOM_ALERTDAY.'">アラート日</label>：<input type="text" id="'.RECRUITMENT_CUSTOM_ALERTDAY.'" name="'.RECRUITMENT_CUSTOM_ALERTDAY.'" size="10" value="'.esc_html($d2).'" /><span id="_reErr2"></span><br>';
echo '
<p>日付は[YYYY/mm/dd]の形式で入力ください<br>
※ 終了日は[指定日まで] / アラート日は[指定日以降]の表示となります。
</p>

<script>

jQuery(document).ready(function() {

	function zeroPlus (s){
		str = String(s);
		if(str.length == 1 )	return "0"+ String(str);
		else					return str;
	}
	function isDate (str) {
	  delim = "/";
	  var arr = str.split(delim);
	  
	  if (arr.length !== 3){
		  delim = "-";
		  var arr = str.split(delim);
		  if (arr.length !== 3){
		 	return false;
		  }	  
	  }
	  const date = new Date(arr[0], arr[1] - 1, arr[2]);
	  if (arr[0] !== String(date.getFullYear()) || arr[1] !== ("0" + (date.getMonth() + 1)).slice(-2) || arr[2] !== ("0" + date.getDate()).slice(-2)) {
	    return false;
	  } else {
	     date.setDate(date.getDate() - 6);
		 return date.getFullYear() + "/" + zeroPlus(date.getMonth() + 1) + "/" + zeroPlus(date.getDate()) ;
	  }
	};
	
	jQuery("#'.RECRUITMENT_CUSTOM_ENDDAY.'").on("change" , function(){
	  	jQuery("#_reErr").html("");
		
		if( jQuery("#'.RECRUITMENT_CUSTOM_ENDDAY.'").val() == "" )	return; 
		var date = isDate(jQuery("#'.RECRUITMENT_CUSTOM_ENDDAY.'").val());
		if( date != false ){
			//	アラート日変更
			if( jQuery("#'.RECRUITMENT_CUSTOM_ALERTDAY.'").val() == "" ){
					
				 jQuery("#'.RECRUITMENT_CUSTOM_ALERTDAY.'").val( date );
						
			}
		}else{
		  	jQuery("#_reErr").html("日付形式で入力ください");
		}
	});

	jQuery("#'.RECRUITMENT_CUSTOM_ALERTDAY.'").on("change" , function(){
		  	jQuery("#_reErr2").html("");
		if( jQuery("#'.RECRUITMENT_CUSTOM_ALERTDAY.'").val() == "" )	return; 
		var date = isDate(jQuery("#'.RECRUITMENT_CUSTOM_ALERTDAY.'").val());
		if( date != false ){
		}else{
		  	jQuery("#_reErr2").html("日付形式で入力ください");
		}
	});
});

</script>
';

}

function RECRUITMENT_checkData($day){
	
	$day = str_replace('/','-',$day);
	
	$d = explode('-',$day);
	
	$d[1] = isset($d[1]) ? $d[1] : 0;
	$d[2] = isset($d[2]) ? $d[2] : 0;
	
	$d[0] = is_numeric($d[0]) ? $d[0] : 0;
	$d[1] = is_numeric($d[1]) ? $d[1] : 0;
	$d[2] = is_numeric($d[2]) ? $d[2] : 0;
	
	return checkdate( $d[1] , $d[2] , $d[0]);
	
}

function RECRUITMENT_save_Recruitment($post_id){
	$my_nonce = isset($_POST['my_nonce']) ? $_POST['my_nonce'] : null;
	if(!wp_verify_nonce($my_nonce, wp_create_nonce(__FILE__))) {
		return $post_id;
	}
	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return $post_id; }
	if(!current_user_can('edit_post', $post_id)) { return $post_id; }

	$data = isset($_POST[RECRUITMENT_CUSTOM_ENDDAY])	? $_POST[RECRUITMENT_CUSTOM_ENDDAY] : '';
	$data = RECRUITMENT_checkData($data) ? $data : "9999/12/31";
/*
	if( RECRUITMENT_checkData($data) ){
		
		$data2 = date("Y/m/d", strtotime( $data ) - (86400 * 7));
	}else{
		$data	= "9999/12/31";
		$data2	= "9999/12/31";
	}
*/
	if(get_post_meta($post_id, RECRUITMENT_CUSTOM_ENDDAY) == ""){	
		add_post_meta($post_id, RECRUITMENT_CUSTOM_ENDDAY, $data, true);
	}elseif($data != get_post_meta($post_id, RECRUITMENT_CUSTOM_ENDDAY, true)){
		update_post_meta($post_id, RECRUITMENT_CUSTOM_ENDDAY, $data);
	}elseif($data == ""){
		delete_post_meta($post_id, RECRUITMENT_CUSTOM_ENDDAY, get_post_meta($post_id, RECRUITMENT_CUSTOM_ENDDAY, true));
	}

	$data2 = isset($_POST[RECRUITMENT_CUSTOM_ALERTDAY])	? $_POST[RECRUITMENT_CUSTOM_ALERTDAY] : '';
	$data2 = RECRUITMENT_checkData($data2) ? $data2 : "9999/12/31";
	
	if(get_post_meta($post_id, RECRUITMENT_CUSTOM_ALERTDAY) == ""){	
		add_post_meta($post_id, RECRUITMENT_CUSTOM_ALERTDAY, $data2, true);
	}elseif($data2 != get_post_meta($post_id, RECRUITMENT_CUSTOM_ALERTDAY, true)){
		update_post_meta($post_id, RECRUITMENT_CUSTOM_ALERTDAY, $data2);
	}elseif($data2 == ""){
		delete_post_meta($post_id, RECRUITMENT_CUSTOM_ALERTDAY, get_post_meta($post_id, RECRUITMENT_CUSTOM_ALERTDAY, true));
	}

}


/*--------------------------
	トップページ用表記
------------------------------*/
function RECRUITMENT_getTopList(){
	
	global $post , $_hpRootDir , $NowLanguage;
	
	$retHtml = '';
	$type = RECRUITMENT_POST_TYPE;
	
	$args =	array( 
		'posts_per_page'	=> 20,
		'post_type'			=> RECRUITMENT_POST_TYPE,
		'orderby'			=> 'post_date',
		'order'	 			=> 'DESC',
		'meta_query' => array(
			array(
				'key'		=> RECRUITMENT_CUSTOM_ENDDAY,
				'value'		=> date("Y-m-d"),
				'type'		=> 'DATE',
				'compare'	=> '>=',
			),
			'relation'=>'AND'
		)
	);
	
	// カテゴリーの記事データ取得
	$myposts = query_posts( $args );
	
	if(!empty($myposts)) {
	
?>
				<div id="recruitmentBox">
					<h2><img src="<?php echo $_hpRootDir; ?>images/index/opportunities.svg" alt="Opportunities"></h2>
					<ul>
<?php

		// 取得した記事の個数分繰り返す
		foreach($myposts as $post) :
			// 記事オブジェクトの整形
			setup_postdata($post);	
			
			
			//	外部リンク
			$a = get_post_meta($post->ID , RECRUITMENT_CUSTOM_ALERTDAY , true );

            if( $NowLanguage['now'] == 'en' ) {
                $alert = (strtotime($a) <= time()) ? sprintf( ' <span class="icon_text icon_soon">Closing soon</span> ' ) 
                : '';
            } else {
                $alert = (strtotime($a) <= time()) ? sprintf( ' <span class="icon_text icon_soon">締切間近</span> ' ) 
                : '';
            }

			if( !strtotime($a) ) $alert = '';
			
			$endday = get_post_meta($post->ID , RECRUITMENT_CUSTOM_ENDDAY , true );

			// 投稿ごとの区切りのdiv
?>
						<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><?php echo $alert; ?></li>
<?PHP
		endforeach;
		wp_reset_query();
?>
					</ul>
				</div>
<?PHP
	}
}



/*--------------------------
	トップページ用表記
------------------------------*/
function RECRUITMENT_getList( $endType=false , $start = 0 ){
	
	global $post , $_hpRootDir , $NowLanguage;
	
	$retHtml = '';
	$type = RECRUITMENT_POST_TYPE;
	
	$args =	array( 
		'posts_per_page'	=> 50,
		'post_type'			=> RECRUITMENT_POST_TYPE,
		'orderby'			=> 'post_date',
		'order'	 			=> 'DESC',
		'meta_query' => array(
			array(
				'key'		=> RECRUITMENT_CUSTOM_ENDDAY,
				'value'		=> date("Y-m-d"),
				'type'		=> 'DATE',
				'compare'	=> ($endType ? '<' : '>='),
			),
			'relation'=>'AND'
		)
	);
	
	// カテゴリーの記事データ取得
	$myposts = query_posts( $args );
	
	if(!empty($myposts)) {
	
?>
				<ul id="_recruitList">
<?php
		// 取得した記事の個数分繰り返す
		foreach($myposts as $post) :
			// 記事オブジェクトの整形
			setup_postdata($post);	
			
			
			//	外部リンク
			$a = get_post_meta($post->ID , RECRUITMENT_CUSTOM_ALERTDAY , true );
			
			if( !$endType ){
				$alert = (strtotime($a) <= time()) ? sprintf( ' <img src="%simages/index/%sicon_soon.gif" alt="closing soon" height="15" />' ,
										 $_hpRootDir , ($NowLanguage['now'] == 'en' ? '' : 'ja/')) 
										 : '';
				if( !strtotime($a) ) $alert = '';
			}else{
				$alert = '';
			}
			
			$endday = get_post_meta($post->ID , RECRUITMENT_CUSTOM_ENDDAY , true );
			

			// 投稿ごとの区切りのdiv
?>
					<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?><?php echo $alert; ?></a></li>
<?PHP
		endforeach;
		wp_reset_query();
?>
				</ul>
<?PHP
	}
}

/*--------------------------
	サイドメニュー
------------------------------*/
function RECRUITMENT_getSideMenu(){

?>
				<ul id="list">
					<li><a href="<?php echo home_url( RECRUITMENT_PAGE_SLAG ); ?>"><?php echo RECRUITMENT_getTitle(1); ?></a></li>
					<li><a href="<?php echo home_url( RECRUITMENT_PAGE_SLAG.'?end=1' ); ?>"><?php echo RECRUITMENT_getTitle(2); ?></a></li>
				</ul>

<?php
}