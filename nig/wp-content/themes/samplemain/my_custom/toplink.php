<?php

class TopLink {
    function __construct() {
	  add_action('admin_menu', array($this, 'add_pages'));
    }
    function add_pages() {
	  add_menu_page('トップリンク','トップリンク',  'level_8', __FILE__, array($this,'top_link_option_page'), '', 27);
    }
	
function top_link_option_page() {
       //$_POST['toplink_options'])があったら保存
       if ( isset($_POST['toplink_options'])) {
           check_admin_referer('shoptions');
           $opt = $_POST['toplink_options'];
		   
			$opt = str_replace('\"', '"', $opt);
			$opt = str_replace('\\\'', '\'', $opt);
			$opt = str_replace('\\\\', '\\', $opt);
		   
           update_option('toplink_options', $opt);
           ?><div class="updated fade"><p><strong><?php _e('Options saved.'); ?></strong></p></div><?php
       }
       ?>
       <div class="wrap">
       <div id="icon-options-general" class="icon32"><br /></div><h2>トップリンク枠登録</h2>
           <form action="" method="post">
               <?php
               wp_nonce_field('shoptions');
               $opt = get_option('toplink_options');
               $top_link = isset($opt) ? $opt: null;
               ?> 
               <table class="form-table">
                   <tr valign="top">
                       <td><textarea name="toplink_options" type="text" id="inputtext" cols="120" rows="20" class="regular-text" ><?php  echo $top_link ?></textarea></td>
                   </tr>
               </table>
               <p class="submit"><input type="submit" name="Submit" class="button-primary" value="保存" /></p>
           </form>
       <!-- /.wrap --></div>
       <?php
   }
   }
$toplink = new TopLink;
