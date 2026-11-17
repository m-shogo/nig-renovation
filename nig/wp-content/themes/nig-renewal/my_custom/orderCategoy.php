<?PHP

function my_pre_get_posts($query) {
global $post;

    if ((is_category('events') || is_category('events_ja'))
		 && empty( $query->query_vars['numberposts'] )) {
        $query->set('meta_key', 'holdday');
		// 年フィルタ未指定時の Undefined array key を防ぐ
		$filter_year = isset( $_GET['y'] ) ? intval( $_GET['y'] ) : 0;
		if( $filter_year ){
	        $query->set('meta_query', array(
				 array(
				 	"key"	=> 'holdday',
					"compare" => ">",
					"value" => $filter_year
				),array(
				 	"key"	=> 'holdday',
					"compare" => "<",
					"value" => $filter_year + 1
				)
				
			));
		}
        $query->set('orderby', ' holdday DESC');
    }

}

add_action('pre_get_posts', 'my_pre_get_posts');

//	カスタムフィールド holdday(開催日)保存時に日付変換
function holdday_to_date($meta_value){

	if( $meta_value ){
		$time = strtotime( $meta_value );
		return date("Y/m/d" , $time);
	}
	return ;
}
add_filter('sanitize_post_meta_holdday','holdday_to_date',12);




###############################################################################################

# ページ送り






###############################################################################################

global $wpdb;

class dbengine{

	private $wpdb;
	public 	$prefix;
	private $fundList = '';
	 
	function __construct(){
		//初期化時にテーブル名を決定する
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->prefix = $wpdb->base_prefix;
	}
	
	
	//-------------------------------------------------------//
	//概要		：イベント用サイドメニュー年表取得
	//-------------------------------------------------------//
	public function my_get_year_archives( $cat_ID ){
	
		if( !$this->fundList ){
			$sql = 'SELECT '.
						' op1.holdday '.
					' FROM '.$this->prefix.'posts AS wpconts'.
					 ' LEFT JOIN ('.
					 	' SELECT  post_id ,  CAST( LEFT( meta_value , 4 ) AS unsigned)  AS holdday FROM '.$this->prefix.'postmeta'.
						' WHERE meta_key = "holdday"'.
						' AND meta_value IS NOT NULL'.
					 ') AS op1 ON wpconts.ID = op1.post_id'.
					" INNER JOIN ".$this->prefix."term_relationships AS c1 ON (
						wpconts.ID = c1.object_id
					) INNER JOIN ".$this->prefix."term_taxonomy AS c2 ON (
						c1.term_taxonomy_id = c2.term_taxonomy_id
					)".
					' WHERE wpconts.post_type = "post"'.
					' AND wpconts.post_status != "auto-draft"'.
					' AND ( wpconts.post_status = "publish" OR wpconts.post_status = "pending")'.
					' AND op1.holdday IS NOT NULL'.
					' AND c2.term_id = '.intval($cat_ID).
					' GROUP BY op1.holdday'.
					' ORDER BY op1.holdday DESC , wpconts.ID DESC';
			$dat = $this->selectQuery( $sql );
			
			if( is_array( $dat ) && !empty( $dat) ){
				foreach(  $dat as $v ){
					
					$this->fundList .= sprintf( '<li><a href="%s?cat=%d&amp;y=%d">%d</a></li>' ,  home_url('/') , $cat_ID , $v['holdday'] , $v['holdday'] );
					
				}
			}else{
					$this->fundList .= sprintf( '<li><a href="%s?cat=%d&amp;y=%d">%d</a></li>' ,  home_url('/') , $cat_ID , date("Y") ,  date("Y") );
			}
		}
		
		echo $this->fundList;
	
	}


	//-------------------------------------------------------//
	//概要		：イベント用次ページ送り
	//-------------------------------------------------------//
	public function my_get_page_next( $postDATA , $cat_ID , $hollday ){
	
		$sql = 'SELECT '.
					"wpconts.ID , wpconts.post_title" .
				' FROM '.$this->prefix.'posts AS wpconts'.
				 ' LEFT JOIN ('.
				 	' SELECT  post_id ,  CAST( meta_value AS DATE) AS holdday FROM '.$this->prefix.'postmeta'.
					' WHERE meta_key = "holdday"'.
					' AND meta_value IS NOT NULL'.
				 ') AS op1 ON wpconts.ID = op1.post_id'.
				" INNER JOIN ".$this->prefix."term_relationships AS c1 ON (
					wpconts.ID = c1.object_id
				) INNER JOIN ".$this->prefix."term_taxonomy AS c2 ON (
					c1.term_taxonomy_id = c2.term_taxonomy_id
				)".
				' WHERE wpconts.post_type = "post"'.
				' AND wpconts.post_status != "auto-draft"'.
				' AND ( wpconts.post_status = "publish" OR wpconts.post_status = "pending")'.
				' AND op1.holdday IS NOT NULL'.
				' AND c2.term_id = '.intval($cat_ID).
				' AND op1.holdday >= '.$this->escapeStr( $hollday ).
				' AND wpconts.ID != '.$this->escapeStr( $postDATA->ID , 1 ).
				' AND IF( op1.holdday = '.$this->escapeStr( $hollday ).' ,  wpconts.ID > '.$this->escapeStr( $postDATA->ID , 1 ).' , true )'.
				' ORDER BY op1.holdday ASC , wpconts.ID ASC'.
				' LIMIT 0 , 1';
		$dat = $this->selectQuery( $sql );
		
		if( is_array( $dat[0] ) ){
			return get_permalink(  $dat[0]['ID'] );
		}
		return '';
	
	}
	

	//-------------------------------------------------------//
	//概要		：イベント用前ページ送り
	//-------------------------------------------------------//
	public function my_get_page_before( $postDATA , $cat_ID , $hollday ){
	
		$sql = 'SELECT '.
					"wpconts.ID , wpconts.post_title" .
				' FROM '.$this->prefix.'posts AS wpconts'.
				 ' LEFT JOIN ('.
				 	' SELECT  post_id ,  CAST( meta_value AS DATE) AS holdday FROM '.$this->prefix.'postmeta'.
					' WHERE meta_key = "holdday"'.
					' AND meta_value IS NOT NULL'.
				 ') AS op1 ON wpconts.ID = op1.post_id'.
				" INNER JOIN ".$this->prefix."term_relationships AS c1 ON (
					wpconts.ID = c1.object_id
				) INNER JOIN ".$this->prefix."term_taxonomy AS c2 ON (
					c1.term_taxonomy_id = c2.term_taxonomy_id
				)".
				' WHERE wpconts.post_type = "post"'.
				' AND wpconts.post_status != "auto-draft"'.
				' AND ( wpconts.post_status = "publish" OR wpconts.post_status = "pending")'.
				' AND op1.holdday IS NOT NULL'.
				' AND c2.term_id = '.intval($cat_ID).
				' AND op1.holdday <= '.$this->escapeStr( $hollday ).
				' AND wpconts.ID != '.$this->escapeStr( $postDATA->ID , 1 ).
				' AND IF( op1.holdday = '.$this->escapeStr( $hollday ).' ,  wpconts.ID < '.$this->escapeStr( $postDATA->ID , 1 ).' , true )'.
				' ORDER BY op1.holdday DESC , wpconts.ID DESC'.
				' LIMIT 0 , 1';
		$dat = $this->selectQuery( $sql );
		
		
		if( is_array( $dat[0] ) ){
			return get_permalink(  $dat[0]['ID'] );
		}
		return '';
	
	}	
		
	//-------------------------------------------------------//
	//概要		：SELECTクエリ実行
	//日付		：2010/06/08
	//引数		：$p_string	-> 対象文字列
	//			：$suujiflg	-> 文字制限付属
	//-------------------------------------------------------//
	public function selectQuery( $query ){
	//	$query = esc_sql($query);
		return $this->wpdb->get_results( $query , ARRAY_A);
		
	}
	
	public function entryQuery( $query ){
		
	
		return $this->wpdb->query( $query );
		
	}
	
	//-------------------------------------------------------//
	//概要		：SQLデータエスケープ
	//日付		：2010/06/08
	//引数		：$p_string	-> 対象文字列
	//			：$suujiflg	-> 文字制限付属
	//-------------------------------------------------------//
	public function escapeStr($p_string, $suuji_fig=''){
		$return_string = '';
		if( is_array($p_string )){
			$return_string = array();
			foreach($p_string as $key=>$value){
				// clDbc_Escape_Sql は未定義のため、存在時のみ動的呼び出し
				$escape_method = 'clDbc_Escape_Sql';
				if ( method_exists( __CLASS__, $escape_method ) ) {
					$return_string[$key] = forward_static_call( array( __CLASS__, $escape_method ), $value, $suuji_fig );
				} else {
					$return_string[$key] = esc_sql($value);
				}
			}
		}else{
			if( $suuji_fig == 1 )	$p_string = floatval($p_string+0);
			if( strlen($p_string) != 0){
				switch($suuji_fig){
					case 1:		$return_string = $p_string;				break;
					case 2:		$return_string = "'%".esc_sql($p_string)."%'";		break;
					case 3:		$return_string = "'%".esc_sql($p_string)."'";			break;
					case 4:		$return_string = "'".esc_sql($p_string)."%'";			break;
					default:	$return_string = "'".esc_sql($p_string)."'";		break;
				}
			}else{
				$return_string = 'null';
			}
		}
		return $return_string;
	}
	


	//-------------------------------------------------------//
	//概要		：SELECTクエリー作成
	//日付		：2010/06/08
	//引数		：$table->テーブルキー
	//引数		：$key->対象キー
	//-------------------------------------------------------//
	public function MakeSelect( $table , $key="*" ){
	
		$re_query = "";
		
		$re_query = 'SELECT '.$key.' FROM '.$table;
		return $re_query;
	}
	
	

}