<?PHP

################################################################################
#	投稿基本設定
################################################################################

//	投稿テンプレート選択追加 
include_once( get_template_directory() .'/my_custom/choicetmplate.php' );
include_once( get_template_directory() .'/my_custom/categoryarcive.php' );
include_once( get_template_directory() .'/my_custom/getColors.php' );
include_once( get_template_directory() .'/my_custom/orderCategoy.php' );
include_once( get_template_directory() .'/my_custom/choiceMaincategory.php' );
//	現在の言語
if( defined( "ICL_LANGUAGE_CODE" ) ){
	$NowLanguage = array(
						"now"	=> ICL_LANGUAGE_CODE,
						"load"	=> ICL_LANGUAGE_CODE == 'ja' ? 'ja' : '',
						"plus"	=> ICL_LANGUAGE_CODE == 'ja' ? '_ja' : ''
				 );
}else{
	$NowLanguage = array(
						"now"	=> 'en',
						"load"	=> '',
						"plus"	=> ""
				 );
}
//	リクルート表示用
include_once( get_template_directory() .'/my_custom/recruitment.php' );

// Pタグ削除
remove_filter('the_content', 'wpautop');

//add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) );

global $SeideMenus , $NowLanguage;
$SeideMenus = array(
	'sidemenu_aboutnig'	=> array( 'name' => 'About NIG'	, 'jp' => '遺伝研について'  , 'color' => 'bgColorBr'),
	'sidemenu_research'	=> array( 'name' => 'Research'	, 'jp' => '研究'	, 'color' => 'bgColorBl'),
	'sidemenu_phd'		=> array( 'name' => 'PhD Program'	, 'jp' => '大学院', 'color' => 'bgColorLb'),
	'sidemenu_RIC'		=> array( 'name' => 'Research Infrastructure &amp; Collaboration'	, 'jp' => '研究支援事業', 'color' => 'bgColorGr'),
	'sidemenu_Model'	=> array( 'name' => 'Model Organism Resources'	, 'jp' => 'モデル生物リソース'	, 'color' => 'bgColorPu'),
	'sidemenu_Career'	=> array( 'name' => 'Career Development'	, 'jp' => 'キャリア形成', 'color' => 'bgColorBr'),
	'sidemenu_Outreach'	=> array( 'name' => 'Outreach'				, 'jp' => '社会に向けての活動', 'color' => 'bgColorBr')
);

//	ヘッダーパン屑ジャンプスルーリスト
$JumpHeadLink = array( 
	"about-nig" => true,
	"research" => true,
	"reserch" => true,
	"phd-program" => true,
	"research-infrastructure-collaboration" => true,
	"model-organism-resources" => true,
	"career-development" => true,
	"outreach" => true
 );


################################
/*
add_filter('the_excerpt', 'addScripts');

function addScripts() {
	echo 'sda';
	if (!hasShortCode('shortcode'))
		return;
}

function hasShortcode($shortcode) {
	global $wp_query;
	echo 'asd';
	echo $posts   = $wp_query->posts;
	$pattern = '/\[' . preg_quote($shortcode) . '[^\]]*\]/im';
	$found   = false;
	$hasTeaser = !( is_single() || is_page() );

	foreach($posts as $post) {
		if (isset($post->post_content)) {
			$post_content = $post->post_content;
			if ( $hasTeaser && preg_match('/<!--more(.*?)?-->/', $post_content, $matches) ) {
				$content = explode($matches[0], $post_content, 2);
				$post_content = $content[0];
			}

			if ( !empty($post_content) && preg_match($pattern, $post_content) ) {
				$found = true;
			}
		}
		if ( $found )
			break;
	}
	unset($posts);
	return $found;
}*/
###############################



//	カスタムメニュー設定 ------------------------------------
register_nav_menus( array('headmenu'=>'ヘッダーメニュー') );
//register_nav_menus( array('headmenu_jp'=>'ヘッダーメニュー[日本語]') );

foreach( $SeideMenus as $k => $v ){
	register_nav_menus( array( $k => $v['name'].'(サイドメニュー)') );
//	register_nav_menus( array( $k.'_jp' => $v['jp'].'(サイドメニュー)') );
}
register_nav_menus( array('sidemenu_Sitemap'=>'Sitemap(サイドメニュー)') );

//	投稿アイキャッチ有効化
add_theme_support( 'post-thumbnails', array( 'post' ) );
the_post_thumbnail( 'thumbnail', 'class=eyecatchimg' );			//	1アイキャッチクラス設定

global $_hpRootDir , $_sslURL , $_userLoginData;

// ルート階層 -- 
$_hpRootDir = home_url('/');
if( mb_substr($_hpRootDir,-1) !== '/'  )	$_hpRootDir .= '/';	
switch( $NowLanguage['now'] ){
	case 'ja':
		$_hpRootDir = str_replace('ja/' , '' , $_hpRootDir);
		break;
	case 'en':
		$_hpRootDir = str_replace('en/' , '' , $_hpRootDir);
		break;
}

################################################################################


/*--------------------------
	最上部親階層取得(ページ)
-------------------------------*/
function ps_get_most_root_page( $cur_post, $cnt = 0 ) {
	if ( $cnt > 100 ) { return false; }
	$cnt++;
	if ( $cur_post->post_parent == 0 ) {
		$root_page = $cur_post;
	} else {
		$root_page = ps_get_most_root_page( get_post( $cur_post->post_parent ), $cnt );
	}
	
	if( $root_page->post_content ) unset($root_page->post_content);
	
	return $root_page;
}




/*--------------------------
	ページの階層コード取得
-------------------------------*/

function ps_get_root_page( $cur_post, &$root_page ) {
	

	$d = array(
		'ID'			=> $cur_post->ID,
		'post_title'	=> $cur_post->post_title,
		'post_name'		=> $cur_post->post_name,
		'link'			=> get_page_link( $cur_post->ID)	
	);
	
	if( $cur_post->post_content == '' ){
		$d['link'] = '';
	}
	
	$root_page[] = $d;
	
	if ( $cur_post->post_parent > 0 ) {
		ps_get_root_page( get_post( $cur_post->post_parent ), $root_page );
	}else{
		krsort($root_page);
	}
}

/*--------------------------
	カテゴリの階層取得
-------------------------------*/
function ps_get_most_root_category( $cur_cate, &$root_page ) {

	global $_hpRootDir;

	// get_category 失敗時は WP_Error / null になるためガード
	if ( ! is_object( $cur_cate ) || is_wp_error( $cur_cate ) ) {
		return;
	}
	
	$root_page[] = array(
		'ID'			=> $cur_cate->term_id,
		'post_title'	=> $cur_cate->name,
		'post_name'		=> $cur_cate->slug,
		'link'			=> sprintf( '%s?category_name=%s' , home_url('/') ,$cur_cate->slug )	
	);
	if ( isset( $cur_cate->category_parent ) && $cur_cate->category_parent > 0 ) {
		ps_get_most_root_category( get_category( $cur_cate->category_parent ), $root_page );
	}else{
		krsort($root_page);
	}
}



/*--------------------------
	子階層取得
-------------------------------*/

function ps_get_child_page( $id , $plink = '' ) {
	$redat = array();
		
	$args = array(
	    'post_parent' => $id,
	    'post_type' => 'page',
		'posts_per_page'   => 30,
	    'order' => 'ASC',
	    );
		
	$posts = get_posts($args);
	
	if( !empty( $posts ) ){
		foreach($posts as $post) {
		
			$redat[$post->ID] = array(
				'ID'			=> $post->ID,
				'post_title'	=> $post->post_title,
				'post_name'		=> $post->post_name,
				'link'			=> get_page_link( $post->ID),
				'sub'			=> ps_get_child_page( $post->ID )
			);
		}
	}
	return $redat;
}

/*--------------------------
	タイトル追加
-------------------------------*/
function _plusTitle(){
	global $post;
	$title = get_post_meta($post->ID, 'plustitle', true);
	
	if( $title != '' )	echo sprintf('<span>%s</span>' , $title );

}

/*--------------------------
	Newアイコン設定追加
-------------------------------*/
function _plusNewIcon( $postDate , $overday = 0 ){
//	  20241004変更 Newアイコン出さない　上のoverday を 31 にすると、一か月になる。
	global $NowLanguage,$_hpRootDir;
	$strtime = strtotime( $postDate );
	
	$re = '';
	
	//	Newアイコン添付日数
	$check = time() - ( 86400 * $overday );
	
	if( $strtime >= $check ){
		switch( $NowLanguage['now'] ){
			case 'ja':
//				$re = '<span class="icon_text icon_new">新着</span>';
				$re = '';
				break;
			default:
//				$re = '<span class="icon_text icon_new">New</span>';
				$re = '';
				break;
				
		}
	}
	
	return $re;

}

/*--------------------------
	Endアイコン設定追加
-------------------------------*/
function _plusEndIcon( $date , $overday = 0 ){

	global $NowLanguage,$_hpRootDir;
	
	if( !$date )	return '';
	
	$bd = strtotime( date( "Y-m-d 23:59:59" ) ) - 86400;
	
	$re = '';

		
	if( $bd > strtotime( $date ) ){
		switch( $NowLanguage['now'] ){
			case 'ja':
				$re = '</span><span class="icon_text icon_past">終了</span>';
				break;
			default:
				$re = '</span><span class="icon_text icon_past">End</span>';
				break;
				
		}
	}
	
	return $re;

}


//カスタムポスト追加
add_action( 'init', 'create_post_type' );
function create_post_type() {

    register_post_type( 'top_important', [ // 投稿タイプ名の定義
        'labels' => [
            'name'          => 'Important', // 管理画面上で表示する投稿タイプ名
            'singular_name' => 'Important',      // カスタム投稿の識別名
        ],
        'public'        => true,  // 投稿タイプをpublicにするか
        'has_archive'   => false, // アーカイブ機能ON/OFF
        'menu_position' => 5,     // 管理画面上での配置場所
        'show_in_rest'  => false,  // 5系から出てきた新エディタ「Gutenberg」を有効にする
    ]);

    register_post_type( 'top_slider', [ // 投稿タイプ名の定義
        'labels' => [
            'name'          => 'MainImageSlider', // 管理画面上で表示する投稿タイプ名
            'singular_name' => 'MainImageSlider',      // カスタム投稿の識別名
        ],
        'public'        => true,  // 投稿タイプをpublicにするか
        'has_archive'   => false, // アーカイブ機能ON/OFF
        'menu_position' => 5,     // 管理画面上での配置場所
        'show_in_rest'  => false,  // 5系から出てきた新エディタ「Gutenberg」を有効にする
    ]);
    
    register_post_type( 'top_pickup', [ // 投稿タイプ名の定義
        'labels' => [
            'name'          => 'PickupSlider', // 管理画面上で表示する投稿タイプ名
            'singular_name' => 'PickupSlider',      // カスタム投稿の識別名
        ],
        'public'        => true,  // 投稿タイプをpublicにするか
        'has_archive'   => false, // アーカイブ機能ON/OFF
        'menu_position' => 5,     // 管理画面上での配置場所
        'show_in_rest'  => false,  // 5系から出てきた新エディタ「Gutenberg」を有効にする
    ]);
}
function my_remove_post_support() {
    remove_post_type_support('top_important','editor');  //エディターを消す
    remove_post_type_support('top_slider','editor');  //エディターを消す
    remove_post_type_support('top_pickup','editor');  //エディターを消す
}
add_action('init','my_remove_post_support');

//パーマリンク欄を隠す
add_action('admin_print_styles','hide_permalink');
function hide_permalink(){
    $current_screen = get_current_screen();
    if(isset($current_screen) && $current_screen->post_type === 'top_important') {
        $style = '<style>#edit-slug-box {display: none !important; }</style>';
        echo $style;
    }
    if(isset($current_screen) && $current_screen->post_type === 'top_slider') {
        $style = '<style>#edit-slug-box {display: none !important; }</style>';
        echo $style;
    }
    if(isset($current_screen) && $current_screen->post_type === 'top_pickup') {
        $style = '<style>#edit-slug-box {display: none !important; }</style>';
        echo $style;
    }
}

//感嘆符や矢印などの記号テキストが絵文字になるのを禁止
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

// acf導入の調整
function my_acf_init() { 
    if (function_exists('acf_update_setting')) { 
        acf_update_setting('remove_wp_meta_box', false); 
    } 
}
add_action('acf/init', 'my_acf_init');

//親ページのスラッグをクラスとして子ページに追加
function pagename_class($classes = '') {
if (is_page()) {
$page = get_page(get_the_ID());
$classes[] = $page->post_name;
}
return $classes;
}
add_filter('body_class','pagename_class');


//大学院新着を呼び出す

function Include_my_php($params = array()) {
    extract(shortcode_atts(array(
        'file' => 'default'
    ), $params));
    ob_start();
   include(get_stylesheet_directory() . "/archive_post/$file.php");
    return ob_get_clean();
}
add_shortcode('onphp', 'Include_my_php');




function shortcode_get_posts($atts) {
	extract(shortcode_atts(array(
		'q' => '',
	), $atts));

	$posts = get_posts(html_entity_decode($q));

	$html = '';
	global $categoryData;
	foreach ($posts as $p) {
		
		setup_postdata($p);
		if( is_object( $categoryData ) && $categoryData->slug != 'seminar'){
			$icon = get_post_meta($p->ID,'_custom_icon',true);
			if( $icon ) {
                switch ($icon) {
                    case 'new':
                        $icon = '<span class="icon_text icon_new">新着</span>';
                        break;
                    case 'past':
                        $icon = '<span class="icon_text icon_past">終了</span>';
                        break;
                    case 'pressrelease':
                        $icon = '<span class="icon_text icon_pressrelease">プレスリリース</span>';
                        break;
                    case 'recruitment':
                        $icon = '<span class="icon_text icon_recruitment">募集</span>';
                        break;
                    case 'award':
                        $icon = '<span class="icon_text icon_award">受賞</span>';
                        break;
                    case 'publish':
                        $icon = '<span class="icon_text icon_publish">出版</span>';
                        break;
                    case 'collaborations':
                        $icon = '<span class="icon_text icon_collaborations">共同研究</span>';
                        break;
                    case 'jobs':
                        $icon = '<span class="icon_text icon_jobs">募集</span>';
                        break;
                    default:
                        $icon = '';
                }
            }
		}else{
			$icon = '';
		}
		

		
		$html .= '<div class="listlink_box"><div class="date">';
		$html .= date('Y/m/d', strtotime($p->post_date)) ;
		$html .= $icon ;
		$html .= '</div><p><a href="' . get_permalink($p->ID) . '">' . $p->post_title . '</a></p>';
		$html .= '</div>';
	}
	$html .= '';

	return $html;
}
add_shortcode('get_posts', 'shortcode_get_posts');


function shortcode_get_posts_en($atts) {
	extract(shortcode_atts(array(
		'q' => '',
	), $atts));

	$posts = get_posts(html_entity_decode($q));

	$html = '';
	global $categoryData;
	foreach ($posts as $p) {
		
		setup_postdata($p);
		if( is_object( $categoryData ) && $categoryData->slug != 'seminar'){
			$icon = get_post_meta($p->ID,'_custom_icon',true);
			if( $icon ) {
                switch ($icon) {
                    case 'new':
                        $icon = '<span class="icon_text icon_new">New</span>';
                        break;
                    case 'past':
                        $icon = '<span class="icon_text icon_past">Past</span>';
                        break;
                    case 'pressrelease':
                        $icon = '<span class="icon_text icon_pressrelease">Press release</span>';
                        break;
                    case 'recruitment':
                        $icon = '<span class="icon_text icon_recruitment">Recruitment</span>';
                        break;
                    case 'award':
                        $icon = '<span class="icon_text icon_award">Award</span>';
                        break;
                    case 'publish':
                        $icon = '<span class="icon_text icon_publish">Publish</span>';
                        break;
                    case 'collaborations':
                        $icon = '<span class="icon_text icon_collaborations">Collaborations</span>';
                        break;
                    case 'jobs':
                        $icon = '<span class="icon_text icon_jobs">Recruitment</span>';
                        break;
                    default:
                        $icon = '';
                }
            }
		}else{
			$icon = '';
		}
		

		
		$html .= '<div class="listlink_box"><div class="date">';
		$html .= date('Y/m/d', strtotime($p->post_date)) ;
		$html .= $icon ;
		$html .= '</div><p><a href="' . get_permalink($p->ID) . '">' . $p->post_title . '</a></p>';
		$html .= '</div>';
	}
	$html .= '';

	return $html;
}
add_shortcode('get_posts_en', 'shortcode_get_posts_en');

 //240205追加
function add_ancestor_page_to_body_classes($classes) {
    global $post;

    // $post が無い画面では親ページクラスを付与しない
    if ( ! isset( $post ) || ! is_object( $post ) ) {
        return $classes;
    }

    // 親ページが存在する場合
    if ($post->post_parent) {
        // 親ページの情報を取得
        $parent = get_post($post->post_parent);

        if ( $parent ) {
            // 親ページのスラッグをクラスに追加
            $classes[] = 'parent-' . $parent->post_name;

            // 親ページの先祖がある場合は再帰的に処理
            $classes = add_ancestor_page_recursive($parent, $classes);
        }
    }

    return $classes;
}

function add_ancestor_page_recursive($post, $classes) {
    // 親ページが存在する場合
    if ( is_object( $post ) && $post->post_parent ) {
        // 親ページの情報を取得
        $parent = get_post($post->post_parent);

        if ( $parent ) {
            // 親ページのスラッグをクラスに追加
            $classes[] = 'parent-' . $parent->post_name;

            // 再帰的に処理
            $classes = add_ancestor_page_recursive($parent, $classes);
        }
    }

    return $classes;
}

add_filter('body_class', 'add_ancestor_page_to_body_classes');
