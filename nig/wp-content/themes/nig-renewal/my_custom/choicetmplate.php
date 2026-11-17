<?PHP

//	投稿テンプレート選択追加 ----------------------------------------------------

if( is_admin() ){
add_action('admin_menu', 'custom_tmpl_hooks');
add_action('save_post', 'save_custom_tmpl');
add_action('save_post', 'save_custom_icon');
add_action('save_post', 'save_custom_pickup');

add_action('save_post', 'save_custom_sidemenu');
}
function custom_tmpl_hooks() {
	add_meta_box('custom_icon', 'トップアイコン', 'custom_icon_input', 'post', 'advanced', 'high');
	add_meta_box('custom_pickup', 'ピックアップ【カテゴリTop用】', 'custom_pickup_input', 'post', 'advanced', 'high');
	// add_meta_box('custom_sidemenu', 'サイドメニュー選択', 'custom_sidemenu_input', 'page', 'advanced', 'high');

	add_meta_box('custom_tmpl', 'テンプレート選択(拡張予定', 'custom_tmpl_input', 'post', 'advanced', 'high');
	add_meta_box('custom_tmpl', 'テンプレート選択(拡張予定', 'custom_tmpl_input', 'page', 'advanced', 'high');
}



//	カスタムテンプレート ------------------------------------------------
function custom_tmpl_input() {
	global $post;
	$_slect_temple = array( '' => 'デフォルト' , "sample" => 'サンプル');


	echo '<input type="hidden" name="custom_tmpl_noncename" id="custom_tmpl_noncename" value="'.wp_create_nonce('custom-tmpl').'" />';
	
	$select = '<select name="custom_tmpl" id="custom_tmpl">'.PHP_EOL;
	foreach( $_slect_temple as $k => $v ){
		$select .= sprintf( '	<option value="%s"%s>%s</option>'.PHP_EOL , $k , ((get_post_meta($post->ID,'_custom_tmpl',true) == $k) ? ' selected' : '') , $v );
	}
	
	$select .= '</select>'.PHP_EOL;
	
	echo $select;
}


function save_custom_tmpl($post_id) {
	// 自動保存やブロックエディタの REST 保存ではメタボックスの nonce が POST に含まれない。
	// PHP 8 では未定義キーの参照が Warning になるため、参照前に存在を確認する。
	if (!isset($_POST['custom_tmpl_noncename']) || !wp_verify_nonce($_POST['custom_tmpl_noncename'], 'custom-tmpl')) {
		return $post_id;
	}
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
	$custom_tmpl = isset($_POST['custom_tmpl']) ? $_POST['custom_tmpl'] : '';
	update_post_meta($post_id, '_custom_tmpl', $custom_tmpl);
}
function insert_custom_tmpl() {
	
	if (is_page() || is_single()) {
		if (have_posts()) : while (have_posts()) : the_post();
			return get_post_meta(get_the_ID(), '_custom_tmpl', true);
		endwhile; endif;
		rewind_posts();
	}
}

//	カスタムアイコン ------------------------------------------------
function custom_icon_input() {
	global $post;
	$_slect_temple = array( 
				'' => 'なし' ,
				"new"			=> 'New' ,
				"past"			=> 'past',
				"pressrelease"	=> 'pressrelease',
				"recruitment"	=> 'recruitment',
				"award"			=> 'award',
				"publish"		=> 'publish',
				"collaborations"		=> 'collaborations',
				"jobs"			=> 'jobs'
				
		 );


	echo '<input type="hidden" name="custom_icon_noncename" id="custom_icon_noncename" value="'.wp_create_nonce('custom-icon').'" />';
	
	$select = '<select name="custom_icon" id="custom_icon">'.PHP_EOL;
	foreach( $_slect_temple as $k => $v ){
		$select .= sprintf( '	<option value="%s"%s>%s</option>'.PHP_EOL , $k , ((get_post_meta($post->ID,'_custom_icon',true) == $k) ? ' selected' : '') , $v );
	}
	
	$select .= '</select>'.PHP_EOL;
	
	echo $select;
}
function save_custom_icon($post_id) {
	// nonce が無いリクエスト（自動保存・REST 保存など）では保存せず戻る。
	if (!isset($_POST['custom_icon_noncename']) || !wp_verify_nonce($_POST['custom_icon_noncename'], 'custom-icon')) {
		return $post_id;
	}
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
	$custom_icon = isset($_POST['custom_icon']) ? $_POST['custom_icon'] : '';
	update_post_meta($post_id, '_custom_icon', $custom_icon);
}
function insert_custom_icon() {
	
	if (is_page() || is_single()) {
		if (have_posts()) : while (have_posts()) : the_post();
			return get_post_meta(get_the_ID(), '_custom_icon', true);
		endwhile; endif;
		rewind_posts();
	}
}
//	カスタムサイドメニュー ------------------------------------------------
function custom_pickup_input() {
	global $post;
	global $SeideMenus;

	$set = get_post_meta($post->ID,'_custom_pickup',true) ? ' checked' : '';
	echo '<input type="hidden" name="custom_pickup_noncename" id="custom_pickup_noncename" value="'.wp_create_nonce('custom-pickup').'" />';
	
	$select = '<label><input type="checkbox" value="1" name="custom_pickup" id="custom_pickup"'.$set.'> ピックアップ設定</label>'.PHP_EOL;
	
	echo $select;
}


function save_custom_pickup($post_id) {
	// nonce が無いリクエスト（自動保存・REST 保存など）では保存せず戻る。
	if (!isset($_POST['custom_pickup_noncename']) || !wp_verify_nonce($_POST['custom_pickup_noncename'], 'custom-pickup')) {
		return $post_id;
	}
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
	// チェックボックスは未選択だと POST に含まれない。未選択は空文字として保存し、設定を外せるようにする。
	$custom_pickup = isset($_POST['custom_pickup']) ? $_POST['custom_pickup'] : '';
	update_post_meta($post_id, '_custom_pickup', $custom_pickup);
}
function insert_custom_pickup() {
	
	if (is_page() || is_single()) {
		if (have_posts()) : while (have_posts()) : the_post();
			return get_post_meta(get_the_ID(), '_custom_pickup', true);
		endwhile; endif;
		rewind_posts();
	}
}


//	カスタムサイドメニュー ------------------------------------------------
function custom_sidemenu_input() {
	global $post;
	global $SeideMenus;


	echo '<input type="hidden" name="custom_sidemenu_noncename" id="custom_sidemenu_noncename" value="'.wp_create_nonce('custom-sidemenu').'" />';
	
	$select = '<select name="custom_sidemenu" id="custom_sidemenu">'.PHP_EOL;
		$select .= '	<option value="">なし</option>'.PHP_EOL;
	foreach( $SeideMenus as $k => $v ){
		$select .= sprintf( '	<option value="%s"%s>%s</option>'.PHP_EOL , $k , ((get_post_meta($post->ID,'_custom_sidemenu',true) == $k) ? ' selected' : '') , $v['name'] );
	}
	
	$select .= '</select>'.PHP_EOL;
	
	echo $select;
}


function save_custom_sidemenu($post_id) {
	// サイドメニューのメタボックスは現在未登録のため、通常の更新でも nonce は送られない。
	// キーが無いときは保存せず戻し、PHP 8 の Warning を出さない。
	if (!isset($_POST['custom_sidemenu_noncename']) || !wp_verify_nonce($_POST['custom_sidemenu_noncename'], 'custom-sidemenu')) {
		return $post_id;
	}
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
	$custom_sidemenu = isset($_POST['custom_sidemenu']) ? $_POST['custom_sidemenu'] : '';
	update_post_meta($post_id, '_custom_sidemenu', $custom_sidemenu);
}
function insert_custom_sidemenu() {
	
	if (is_page() || is_single()) {
		if (have_posts()) : while (have_posts()) : the_post();
			return get_post_meta(get_the_ID(), '_custom_sidemenu', true);
		endwhile; endif;
		rewind_posts();
	}
}

