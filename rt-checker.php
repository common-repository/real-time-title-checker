<?php
/*
Plugin Name: Real-time TITLE Checker
Description: It is a plug-in which can confirm the related article title on the page edit screen.
Version: 1.0.2
Author: rei.k
Author URI: https://blog.webico.work/
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

function myplugin_load_textdomain() {
	load_plugin_textdomain('real-time-title-checker');
}
add_action('plugins_loaded', 'myplugin_load_textdomain' );

if ( !class_exists('realtime_checker') ) {
class realtime_checker {
	 public function __construct() {
	 	//投稿・ページ編集画面のカスタマイズ  
		add_action( 'edit_form_after_title', array($this, 'cousom_edit_title'));
		//投稿・ページ編集画面にcss/jsを読み込ませる
		add_action( 'admin_enqueue_scripts', array($this, 'custom_enqueue'));
		//設定画面のカスタマイズ
		add_action( 'admin_menu', array($this, 'plugin_menu'));
		//プラグインの有効化
		register_activation_hook( __FILE__, array($this, 'plug_activate') );
		//プラグインの停止
		register_deactivation_hook( __FILE__, array($this, 'plug_deactivate') );
     }
     public function cousom_edit_title() {
        global $wpdb;

		$opt = get_option('rt_options'); //管理画面設定の取得
		$perst = get_option('permalink_structure'); //パーマリンク設定の取得

		//管理画面にて有効時の処理
		$sselect = "post_date,post_title,post_status,post_type";
		if(strpos($perst,'postname') !== false){ //パーマリンクカスタム構造にpostnameが含まれている場合 
			$sselect .= ",concat('/', post_name, '/') as post_name";
		}

		//count
		if(isset($opt['op3'])){
			$count = count($opt) - 1;
		}else{
			$count = count($opt);
		}

		$swhere = "post_status = 'publish'";
		for ($i = 1; $i <= $count; $i++) {
    		$swhere .= " OR post_status = %s";
		}
		$swhere_s = [];
		if(isset($opt['op2-1'])){ array_push($swhere_s,"pending"); };
		if(isset($opt['op2-2'])){ array_push($swhere_s,"draft"); };
		if(isset($opt['op2-3'])){ array_push($swhere_s,"private"); };
		if(isset($opt['op2-4'])){ array_push($swhere_s,"future"); };
		if(isset($opt['op2-5'])){ array_push($swhere_s,"trash"); };
		
		$data = "SELECT $sselect FROM $wpdb->posts WHERE $swhere ORDER BY post_date";
		$data = $wpdb->prepare($data,$swhere_s);
		$data = $wpdb->get_results($data);

		$show_txt = __('show', 'real-time-title-checker');
		$hide_txt = __('hide', 'real-time-title-checker');
		
		if(isset($data)){
			echo "<div id='rt-title-wrap' class='hidden'>";
			if(isset($opt['op3'])){ echo '<input name="wordsflg" type="hidden" value="true">';}
			echo "<div class='rt-button'><p class='button'><span class='on hidden'>".$show_txt." ▼</span><span class='off'>".$hide_txt." ▲</span></p></div><ul id='rt-title' class='rt-list'>";
			if(strpos($perst,'postname') !== false){
				foreach ($data as $value) {
					echo "<li><span class='target'>".esc_html($value->post_title)."</span><span><span class='parmalink'>".esc_url(urldecode($value->post_name))."</span> | ".esc_html($value->post_status)." | ".esc_html($value->post_type)."</span></li>";
				}
			}else{
				foreach ($data as $value) {
					echo "<li><span class='target'>".esc_html($value->post_title)."</span><span>".esc_html($value->post_status)." | ".esc_html($value->post_type)."</span></li>";
				}
			}
			echo "</ul></div>";
		}
    }

    
	public function custom_enqueue($hook_suffix) {
		// 新規投稿または編集画面のみ
		if( 'post.php' == $hook_suffix || 'post-new.php' == $hook_suffix ) {
			wp_enqueue_script('custom_js', plugins_url('inc/js/custom.js', __FILE__), array('jquery'));
			wp_enqueue_style('custom_css', plugins_url('inc/css/custom.css', __FILE__));
		}
	}

   
	public function plugin_menu() {
	     add_options_page(
	          'Real-time TITLE Checker', // page_title（オプションページのHTMLのタイトル）
	          'TITLE Checker', // menu_title（メニューで表示されるタイトル）
	          'administrator', // capability
	          'rt-checker-wbc', // menu_slug
	          array($this,'plugin_menu_html') // function
	     );
	}
	//設定画面用のHTML
	public function plugin_menu_html() {
	    include_once( 'views/options.php' );
	}
	public function plug_activate() {
          $opt = get_option('rt_options');
          if ( ! $opt ) {
               // 設定のデフォルトの値
               $user_setting = array(
                    'op2-1' => 1,
                    'op2-2' => 1,
                    'op2-3' => 1,
                    'op2-4' => 1,
                    'op2-5' => 1
               );
               update_option( 'rt_options', $user_setting );
          }
     }
 
     public function plug_deactivate() {
          delete_option('rt_options');
     }
}
}
$reseach = new realtime_checker();
?>