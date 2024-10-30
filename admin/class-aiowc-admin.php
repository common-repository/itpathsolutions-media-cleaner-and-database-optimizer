<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.itpathsolutions.com
 * @since      1.0.0
 *
 * @package    Aiowc
 * @subpackage Aiowc/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Aiowc
 * @subpackage Aiowc/admin
 * @author     itpathsolutions <info@itpathsolutions.com>
 */
class Aiowc_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;


	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $cleaning_tables    The current version of this plugin.
	 */
	private $cleaning_tables =  array(
		'revision' => 'Revision',
		'draft' => 'Draft',
		'autodraft' => 'Auto Draft',
		'postmeta' => 'Orphan Postmeta',
		'commentmeta' => 'Orphan Commentmeta',
		'relationships' => 'Orphan Relationships',
		'feed' => 'Dashboard Transient Feed',
		'trashed_posts' => 'Trashed Posts',
		'pingbacks' => 'Pingbacks',
		'trackbacks' => 'Trackbacks',
		'orphan_usermeta' => 'Orphan Usermeta',
		'orphan_term_meta' => 'Orphan Term Meta',
		'expired_transients' => 'Expired Transients',
		'products' => 'Trash Products',
		'all_comments_trash' => 'All Comments (A to Z)',
		/*'trash' => 'Trash Comments',
		'spam' => 'Spam Comments',
		'pending_comments' => 'Pending Comments',*/
	);

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Aiowc_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Aiowc_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$current_screen = get_current_screen();

		if ($current_screen && in_array($current_screen->id, array('toplevel_page_aiowc', 'wp-optimizer_page_view-optimize','wp-optimizer_page_media-view'))) { 
			
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/aiowc-admin.css', array(), $this->version, 'all' );

			wp_enqueue_style( $this->plugin_name.'-Bootstrap', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' );

			wp_enqueue_style( $this->plugin_name.'-theme', plugin_dir_url( __FILE__ ) . 'css/theme.min.css', array(), $this->version, 'all' );

		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Aiowc_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Aiowc_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$current_screen = get_current_screen();

		if ($current_screen && in_array($current_screen->id, array('toplevel_page_aiowc', 'wp-optimizer_page_view-optimize','wp-optimizer_page_media-view'))) { 

			wp_enqueue_script( $this->plugin_name.'-bootstrap', plugin_dir_url( __FILE__ ) .  '/js/bootstrap.min.js', array(), '1.0.0',true);

			wp_enqueue_script( $this->plugin_name.'-chart', plugin_dir_url( __FILE__ ) . 'js/Chart.min.js', array(), '1.0.0',true);
			
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/aiowc-admin.js', array( 'clipboard', 'jquery', 'wp-util', 'wp-a11y', 'wp-api-request', 'wp-url', 'wp-i18n', 'wp-hooks' ), $this->version, false );

			wp_localize_script( $this->plugin_name, 'ajaxObj', array( 'ajaxurl' => admin_url('admin-ajax.php')));
		}

		if ( $current_screen && in_array($current_screen->id, array('wp-optimizer_page_media-view'))) {

			wp_enqueue_script( $this->plugin_name.'-dataTables', plugin_dir_url( __FILE__ ) . '/js/jquery.dataTables.min.js', array(), '1.0.0',true);
			wp_enqueue_script( $this->plugin_name.'-dataTables-bootstrap5', plugin_dir_url( __FILE__ ) . '/js/dataTables.bootstrap5.min.js', array(), '1.0.0',true);
		}

	}

	/**
	 * Register Wordpress Menu
	 *
	 * @since    1.0.0
	 */
	public function aiowc_register_menu(){

		add_menu_page(
			__('WP Optimizer', 'aiowc'),
			__('WP Optimizer', 'aiowc'),
			'manage_options',
			'aiowc',
			array($this,'aiowc_dashboard_callback'),
			'dashicons-superhero',
		);

	    add_submenu_page(
	        'aiowc',
	        __('Database Optimizer', 'aiowc'),
	        __('Database Optimizer', 'aiowc'),
	        'manage_options',
	        'view-optimize',
	        array($this, 'aiowc_view_optimize'),
	    );
	   	add_submenu_page(
	        'aiowc',
	        __('Media Cleaner', 'aiowc'),
	        __('Media Cleaner', 'aiowc'),
	        'manage_options',
	        'media-view',
	        array($this,'aiowc_meadia_optimize'),
		);
	}

	/**
	 * Admin menu HTML
	 *
	 * @since    1.0.0
	 */
	public function aiowc_dashboard_callback(){
		include dirname(__FILE__).'/partials/aiowc-admin-display.php';
	}
	/**
	 * Admin View Optimizer HTML
	 *
	 * @since    1.0.0
	 */
	public function aiowc_view_optimize(){
		include dirname(__FILE__).'/partials/aiowc-admin-table-display.php';
	}
	/**
	 * Admin Media View HTML
	 *
	 * @since    1.0.0
	 */
	public function aiowc_meadia_optimize(){
		include dirname(__FILE__).'/partials/aiowc-media-display.php';
	}

	/**
	 * Get Revision Count Function
	 *
	 * @since    1.0.0
	 */
	public static function aiowc_revision_cleaner_count($type){
		global $wpdb;
		$type = !empty($type) ? sanitize_key($type) : '';	
		switch($type){
			case "revision":
			    $rc_sql = $wpdb->prepare(
	                "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = %s",
	                'revision'
	            );
				break;
			case "orders":
				$rc_sql = $wpdb->prepare(
	                "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = %s",
	                'shop_order'
	            );
				break;
			case "draft":
				$rc_sql = $wpdb->prepare(
	                "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_status = %s",
	                'draft'
	            );
				break;
			case "autodraft":
				$rc_sql = $wpdb->prepare(
	                "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_status = %s",
	                'auto-draft'
	            );
				break;
			case "postmeta":
				$rc_sql = "SELECT COUNT(*) FROM {$wpdb->postmeta} pm LEFT JOIN {$wpdb->posts} wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL";
				break;
			case "commentmeta":
				$rc_sql = "SELECT COUNT(*) FROM {$wpdb->commentmeta} WHERE comment_id NOT IN (SELECT comment_id FROM {$wpdb->comments})";
				break;
			case "relationships":
				$rc_sql = $wpdb->prepare(
				    "SELECT COUNT(*) FROM {$wpdb->term_relationships} WHERE term_taxonomy_id = %d AND object_id NOT IN (SELECT ID FROM {$wpdb->posts})",
				    1
				);
				break;
			case "feed":
				$rc_sql = $wpdb->prepare(
				    "SELECT COUNT(*) FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s  OR option_name LIKE %s 
				    OR option_name LIKE %s",
				    '_site_transient_browser_%', '_site_transient_timeout_browser_%',
				    '_transient_feed_%', '_transient_timeout_feed_%'
				);
				break;
			case "trashed_posts":
				$rc_sql = $wpdb->prepare( 
					"SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_status = %s AND post_type = %s",
    				'trash',
    				'post'
				);
				break;
			case "pingbacks":
				$rc_sql = $wpdb->prepare(
				    "SELECT COUNT(*) FROM {$wpdb->comments} WHERE comment_type = %s",
				    'pingback'
				);
				break;
			case "trackbacks":
				$rc_sql = $wpdb->prepare(
				    "SELECT COUNT(*) FROM {$wpdb->comments} WHERE comment_type = %s",
				    'trackback'
				);
				break;
			case "orphan_usermeta":
				$rc_sql = $wpdb->prepare("SELECT COUNT(%s) FROM {$wpdb->usermeta} um LEFT JOIN {$wpdb->users} u ON um.user_id = u.ID WHERE u.ID IS NULL", 'ID');
				break;
			case "orphan_term_meta":
				$rc_sql = $wpdb->prepare("SELECT COUNT(%s) FROM {$wpdb->termmeta} tm LEFT JOIN {$wpdb->terms} t ON tm.term_id = t.term_id WHERE t.term_id IS NULL",'term_id');
				break;
			case "expired_transients":
				$rc_sql = $wpdb->prepare(
				    "SELECT COUNT(*) FROM {$wpdb->options} WHERE option_name LIKE %s  AND option_value < UNIX_TIMESTAMP(NOW())",
				    '_transient_timeout_%'
				);
				break;
			case "products":
			    $rc_sql = $wpdb->prepare(
				    "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = %s AND post_status = %s",
				    'product', 'trash'
				);
			    break;
		    case "trash":
				$rc_sql = $wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->comments} WHERE comment_approved = %s AND comment_type = %s", 'trash', 'comment' );
				break;
			case "pending_comments":
				$rc_sql = $wpdb->prepare(
	                "SELECT COUNT(*) FROM {$wpdb->comments} WHERE comment_approved = %s AND comment_type = %s",
	                '0','comment'
	            );
				break;
			case "spam":
				$rc_sql = $wpdb->prepare(
	                "SELECT COUNT(*) FROM {$wpdb->comments} WHERE comment_approved = %s AND comment_type = %s",
	                'spam', 'comment'
	            );			
				break;
			case "all_comments_trash":
				$rc_sql = $wpdb->prepare(
	                "SELECT COUNT(*) FROM {$wpdb->comments} WHERE comment_type = %s", 'comment'
	            );			
				break;
			default:
				$count = 0;
				break;
		}

		if (isset($rc_sql)) {
	        $count = $wpdb->get_var($rc_sql);
	    }
		return $count;
	}

	/**
	 * Code to get a count of images created in different sizes
	 *
	 * @since    1.0.0
	 */
	function aiowc_get_media_count($attachment_id) {
	    $attachment_metadata = wp_get_attachment_metadata($attachment_id);
	    if ($attachment_metadata && !empty($attachment_metadata['sizes'])) {

	        $attachment_data = $attachment_metadata['sizes'];
	        if (!empty($attachment_data) && is_array($attachment_data)) {
	            $array_count = count($attachment_data);
	            return $array_count + 1;
	        }  
	    }
	    else 
	    {
	        return 1;
	    }    
	}

	/**
	 * Code to get a list of images created in different sizes.
	 *
	 * @since    1.0.0
	 */
	function aiowc_get_media_list($attachment_id) {
	    
	    $attachment_metadata = wp_get_attachment_metadata($attachment_id);
	    
	    if ($attachment_metadata && !empty($attachment_metadata['sizes'])) {
	        
	        $attachment_data = $attachment_metadata['sizes'];
	        
	        if (!empty($attachment_data) && is_array($attachment_data)) {
	            
	            foreach ($attachment_data as $size => $info) {
	            	
	                $url = wp_get_attachment_image_url($attachment_id, $size);
	                if (!empty($url)) {
	                	$filesize = "";
	                	if( isset($info['filesize'])){
	                		$total_size = $this->aiowc_format_file_size($info['filesize']);
	                		$filesize = "(".$total_size.")";
	                	}
	                	
	                    $fileList[$size] =  "<a href='$url' target='_blank'>" . $info['width'].'x'.$info['height']. $filesize."</a>";
	                }
	            }
	            return array_unique($fileList);
	        }
	    } else {
	        return '-';
	    }    
	}

	function aiowc_format_file_size($size_in_bytes) {
	    $size_in_kb = $size_in_bytes / 1024;
	    $size_in_mb = $size_in_kb / 1024;
	    if ($size_in_kb < 1024) {
	        return round($size_in_kb, 2) . ' KB';
	    } elseif ($size_in_mb < 1024) {
	        return round($size_in_mb, 2) . ' MB';
	    } else {
	        return round($size_in_mb / 1024, 2) . ' GB';
	    }
	}


	/**
	 * Delete Revision Count Function
	 *
	 * @since    1.0.0
	 */
	function aiowc_delete_data($type){
		global $wpdb;
		$ewc_message = '';
		$rc_sql = '';
	    $result = array();
		switch($type) {
	        case "revision":
	            $rc_sql = $wpdb->prepare(
				    "DELETE FROM {$wpdb->posts} WHERE post_type = %s",
				    'revision'
				);
	            $ewc_message = __('All revisions are deleted','aiowc');
	            break;
	        case "draft":
	            $rc_sql = $wpdb->prepare(
				    "DELETE FROM {$wpdb->posts} WHERE post_status = %s",
				    'draft'
				);
	            $ewc_message = __('All drafts are deleted','aiowc');
	            break;
	        case "autodraft":
	            $rc_sql = $wpdb->prepare(
				    "DELETE FROM {$wpdb->posts} WHERE post_status = %s",
				    'auto-draft'
				);
	            $ewc_message = __('All autodrafts are deleted','aiowc');
	            break;
	        case "postmeta":
	            $rc_sql = $wpdb->prepare(
	            	"DELETE pm FROM {$wpdb->postmeta} pm LEFT JOIN {$wpdb->posts} wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL" 
	            );
	            $ewc_message = __('All orphan postmeta are deleted','aiowc');
	            break;
	        case "commentmeta":
	            $rc_sql = $wpdb->prepare(
				    "DELETE FROM {$wpdb->commentmeta} WHERE comment_id NOT IN (SELECT comment_id FROM {$wpdb->comments})",
				    1
				);
	            $ewc_message = __('All orphan commentmeta are deleted','aiowc');
	            break;
	        case "relationships":
	            $rc_sql = $wpdb->prepare(
				    "DELETE FROM {$wpdb->term_relationships} WHERE term_taxonomy_id = %d AND object_id NOT IN (SELECT ID FROM {$wpdb->posts})",
				    1
				);
	            $ewc_message = __('All orphan relationships are deleted','aiowc');
	            break;
	        case "feed":
				$rc_sql = $wpdb->prepare(
				    "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s 
				    OR option_name LIKE %s OR option_name LIKE %s",
				    '_site_transient_browser_%','_site_transient_timeout_browser_%',
				    '_transient_feed_%', '_transient_timeout_feed_%'
				);
	            $ewc_message = __('All dashboard transient feed are deleted','aiowc');
	            break;
			case "trashed_posts":
				$rc_sql = $wpdb->prepare(
				    "DELETE FROM {$wpdb->posts} WHERE post_status = %s AND post_type = %s",
				    'trash',
				    'post'
				);
				$ewc_message = __('All Trashed Posts are deleted','aiowc');
				break;
			case "pingbacks":
				$rc_sql = $wpdb->prepare(
				    "DELETE FROM {$wpdb->comments} WHERE comment_type = %s",
				    'pingback'
				);
				$ewc_message = __('All Pingbacks are deleted','aiowc');
				break;
			case "trackbacks":
				$rc_sql = $wpdb->prepare(
				    "DELETE FROM {$wpdb->comments} WHERE comment_type = %s",
				    'trackback'
				);
				$ewc_message = __('All Trackbacks are deleted','aiowc');
				break;
			case "orphan_usermeta":
				$rc_sql = $wpdb->prepare(
				    "DELETE um FROM {$wpdb->usermeta} um LEFT JOIN {$wpdb->users} u ON um.user_id = u.ID WHERE u.ID IS NULL"
				);
				$ewc_message = __('All Orphan User Meta are deleted','aiowc');
				break;
			case "orphan_term_meta":
				$rc_sql = $wpdb->prepare(
				    "DELETE tm FROM {$wpdb->termmeta} tm LEFT JOIN {$wpdb->terms} t ON tm.term_id = t.term_id WHERE t.term_id IS NULL"
				);
				$ewc_message = __('All Orphan Term Meta are deleted','aiowc');
				break;
			case "expired_transients":
				$current_time = current_time('timestamp');  // Get the current timestamp
				$rc_sql = $wpdb->prepare( 
					"DELETE FROM {$wpdb->options}  WHERE option_name LIKE %s AND option_value < %d",
				    '_transient_timeout_%', $current_time 
				);
				$ewc_message = __('All Expired Transients are deleted','aiowc');
				break;
			case "products":
	            $rc_sql = $wpdb->prepare("DELETE FROM {$wpdb->posts} WHERE post_status = %s AND post_type = %s", 'trash', 'product');
	            $ewc_message = __('All Products are deleted','aiowc');
	            break;
	         case "spam":
	            $rc_sql = $wpdb->prepare(
				    "DELETE FROM {$wpdb->comments} WHERE comment_approved = %s AND comment_type = %s",
				    'spam', 'comment');
	            $ewc_message = __('All spam comments are deleted','aiowc');
	            break;
	        case "trash":
	            $rc_sql = $wpdb->prepare("DELETE FROM {$wpdb->comments} WHERE comment_approved = %s AND comment_type = %s",
				    'trash', 'comment' );
	            $ewc_message = __('All trash comments are deleted','aiowc');
	            break;
	        case "pending_comments":
				$rc_sql = $wpdb->prepare(
				    "DELETE FROM {$wpdb->comments} WHERE comment_approved = %s AND comment_type = %s",
				    '0', 'comment' );
				$ewc_message = __('All Pending Comments are deleted','aiowc');
				break;

			 case "all_comments_trash":
				$rc_sql = $wpdb->prepare(
				    "DELETE FROM {$wpdb->comments} WHERE comment_type = %s", 'comment' );
				$ewc_message = __('All Comments are deleted','aiowc');
				break;
	    }
	    if (!empty($rc_sql) && isset($rc_sql)) {
	        $query_result = $wpdb->query($rc_sql);
	        if ($query_result !== false) {
	            $result['message'] = $ewc_message;
	        } else {
	            $result['message'] = __( "Deletion failed" , 'aiowc' );
	        }
	        $result['query_result'] = $query_result;
	    }
        return $result;	   
	}

	/**
	 * Delete all un-use data in data base 
	 *
	 * @since    1.0.0
	 */
	function aiowc_revision_cleaner($type = '') {
		$result = array();
		if ( isset( $_POST['aiowc_all_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['aiowc_all_nonce'] ) ) , 'aiowc_all_nonce' ) ){
		    $type = isset($_POST['type']) ? sanitize_key($_POST['type']) : '';
		    if($type == 'aiowc_revision_cleaner_all'){
		    	$result['message'] = $this->aiowc_delete_alldata();
		    }
		    elseif( $type == 'aiowc_revision_cleaner_optimize'){
		    	$result['message'] = $this->aiowc_revision_cleaner_optimize();
		    }else{
			    $result = $this->aiowc_delete_data($type);
		    }
		    $result['status'] = 200;
		}else{
	    	$result['message'] = __('Something went wrong!','aiowc');
			$result['status'] = 401;
	    }
	    echo wp_json_encode($result);
	    wp_die();
	}

	/**
	 * Delete all Revision
	 *  
	 * @since    1.0.0
	 * 
	 */
	function aiowc_delete_alldata() {
		$result = '';
		$non_del = '';
	    $cases = $this->cleaning_tables;

	    foreach ($cases as $case => $case_lable) {
	        $each_query = $this->aiowc_delete_data($case);
	        if($each_query['query_result'] == false){
	        	$non_del = ' '.$case_lable.',';
	        }
	    }
	    if( empty($non_del)){
	    	$non_del = 'All';
	    }
	    
	    $result = $non_del." unnecessary data are deleted";

	    return $result;
	}

	/**
	 * Optimize Database
	 *
	 * @since    1.0.0
	 */
	function aiowc_revision_cleaner_optimize(){
		global $wpdb;
		$rc_sql_1 = $wpdb->prepare('SHOW TABLE STATUS FROM `%s`', DB_NAME);
		$result = $wpdb->get_results($rc_sql_1);
		if($result){
			foreach($result as $row){
				$ot_name = $row->Name;
				$rc_sql = $wpdb->prepare('OPTIMIZE TABLE %s', $ot_name);
				$wpdb->query($rc_sql);
			}
		}

		return __( "Database optimized successfully" , 'aiowc' );
	}

	/**
	 * Get inactive plugins count
	 *
	 * @since    1.0.0
	 */
	function inactive_plugins_count() {

	    $installed_plugins = get_plugins();

	    $active_plugins = get_option('active_plugins');

	    $inactive_plugins = array_diff_key($installed_plugins, array_flip($active_plugins));

	    return count($inactive_plugins);
	}

	/**
	 * Get inactive theme count
	 *
	 * @since    1.0.0
	 */
	function inactive_themes_count() {
	    $installed_themes = wp_get_themes();
		$active_theme = wp_get_theme();
		$inactive_themes = array_filter($installed_themes, function ($theme) use ($active_theme) {
		    return $theme->get_stylesheet() !== $active_theme->get_stylesheet() &&
		           $theme->get_template() !== $active_theme->get_stylesheet() &&
		           $theme->get_stylesheet() !== $active_theme->get_template();
		});
		return count($inactive_themes);			
	}

	/**
	 * Delete specific select plugin
	 *
	 * @since    1.0.0
	 */
	function aiowc_delete_selected_plugin() {
		$response = array();
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'aiowc_plugin_nonce' ) ) {
			$response['message'] = __('Invalid nonce.','aiowc');
			$response['status'] = 401;
			wp_send_json($response);
			wp_die();
		}

		$plugin_ids = isset( $_POST['pluginIds'] ) ? array_map( 'sanitize_text_field', $_POST['pluginIds'] ) : array();

		if ( ! empty( $plugin_ids ) ) {
			foreach ( $plugin_ids as $plugin ) {
				delete_plugins( array( $plugin ) );
			}
			$response['message'] = __('Plugins deleted successfully.','aiowc');
			$response['status'] = 200;
		} else {
			$response['message'] = __('No plugins selected for deletion.','aiowc');
			$response['status'] = 404;
		}

		wp_send_json($response);
		wp_die();
	}

	/**
	 * Delete specific theme
	 *
	 * @since    1.0.0
	 */
	function aiowc_delete_selected_theme() {
		$response = array();

		// Check for nonce and verify it
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'aiowc_theme_nonce' ) ) {
			$response['message'] = __('Invalid nonce.','aiowc');
			$response['status'] = 401;
			wp_send_json($response);
			wp_die();
		}

		$themes_to_remove = isset( $_POST['themeIds'] ) ? array_map( 'sanitize_text_field', $_POST['themeIds'] ) : array();

		if ( ! empty( $themes_to_remove ) ) {
			foreach ( $themes_to_remove as $theme ) {
				delete_theme( basename( $theme ) );
			}
			$response['message'] = __('Themes deleted successfully.','aiowc');
			$response['status'] = 200;
		} else {
			$response['message'] = __('No themes selected for deletion.','aiowc');
			$response['status'] = 404;
		}

		wp_send_json($response);
		wp_die();
	}


	/**
	 * Code to delete a single image from the database and uploads folder..
	 *
	 * @since    1.0.0
	 */
	function single_attachment_delete_callback() {

	    $response = array();

	    if ( isset( $_POST['nonce'] )  && wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['nonce'] ) ) , 'aiowc_media_single_delete_nonce' ) ){
	    	$post_id = sanitize_text_field($_POST['post_id']);
	    	if(!empty($post_id)){
		        $isDeleted = wp_delete_attachment( $post_id, true );
		        if($isDeleted) {
		        	$response['message'] = __('Image deleted successfully!','aiowc');
				  	$response['status'] = 200;
		        }else{
				  	$response['message'] = __('Something went wrong!!','aiowc');
				  	$response['status'] = 404;
				}
			}else{
				$response['message'] = __('Something went wrong!!','aiowc');
				$response['status'] = 404;
			}
		}else{
	    	$response['message'] = __('Something went wrong!!','aiowc');
			$response['status'] = 401;
		}

	    if ( wp_doing_ajax() ) {
	        echo wp_json_encode($response);
	        wp_die();
	    }
	    return $response;
	}

	/**
	 * Code to delete multiple images from the database and uploads folder.
	 *
	 * @since    1.0.0
	 */
	function multiple_attachment_delete_callback() {

	    $response = array();
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['nonce'] ) ) , 'aiowc_media_multiple_delete_nonce' ) ){
	    	
	    	$post_ids = isset( $_POST['postIDs'] ) ? array_map( 'absint', $_POST['postIDs'] ) : array();
			if(is_array( $post_ids ) && !empty($post_ids)){
		        foreach ($post_ids as $post_id) {
		            wp_delete_attachment( $post_id, true );
		            $response['message'] = __('Image deleted successfully!','aiowc');
				  	$response['status'] = 200;
		        }
	    	}else{
		    	$response['message'] = __('Something went wrong!!','aiowc');
				$response['status'] = 401;
	    	}
		}else{
	    	$response['message'] = __('Something went wrong!!','aiowc');
			$response['status'] = 401;
		}
	    
	    if ( wp_doing_ajax() ) {
	        echo wp_json_encode($response);
	        wp_die();
	    }
	}

	/**
	* Code to have a list of posts where images ACF (attachments) are used.
	* 
	* @since    1.0.0
	*/
	function get_post_from_attachment_id($attachment_id) {
	    global $wpdb;
	    $unique_post_ids = array();
	    $post_ids = $wpdb->get_col($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_value LIKE %s", '%' . $wpdb->esc_like($attachment_id) . '%'));

	    foreach ($post_ids as $post_id) {
	        $post_parent = $wpdb->get_var($wpdb->prepare("SELECT post_parent FROM $wpdb->posts WHERE ID = %d", $post_id));
	        if ($post_parent !== null && $post_parent != 0 && !in_array($post_parent, $unique_post_ids) && in_array($post_parent, $post_ids)) {
	            $unique_post_ids[] = $post_parent;
	        }
	    }
	    return $unique_post_ids;
	}	
	
	/**
	* Get use media count
	* 
	* @since    1.0.0
	*/
	function get_unuse_media_count() {

		$media_count = 0;
		$args = array(
	        'post_type' => 'attachment',
	        'numberposts' => -1,
	        'post_status' => null,
	        'post_parent' => null,
	        'post_mime_type' => 'image',
	    );
	    $attachments = get_posts($args);

	    if ($attachments) {

          	foreach ($attachments as $attachment) {
	            $custom_post_type = get_post_types();
	            $usage_info = array();
	            $id = $attachment->ID;
	            $usage_media = get_posts(
	                array(
	                    'numberposts' => -1,
	                    'post_type' => $custom_post_type,
	                    'post_status' => array('publish', 'draft'),
	                    'meta_query' => array(
	                        array(
	                            'key' => '_thumbnail_id',
	                            'value' => $id,
	                            'compare' => '=',
	                        )
	                    )
	                )
	            );
	            if( !empty($usage_media)){
		            foreach ($usage_media as $usage_post) {
		                $usage_info[] = $usage_post->ID;
		            }
		        }

	            $content_media = get_posts(
	                array(
	                    'numberposts' => -1,
	                    'post_type' => $custom_post_type,
	                    'post_status' => array('publish', 'draft'),
	                )
	            );
	            if( !empty($content_media)){
		            foreach ($content_media as $post_content) {
		            	$page_builder = '';
		            	$post_content = $post_content->post_content;
		            	if (strpos($post_content, 'wp-image-' . $id ) !== false) {
			                // Identify the page builder
			                $page_builder = $this->identify_page_builder($post_content);
			            }
			            if(!empty($page_builder)){
		               		$usage_info[] = $page_builder;
			            }
		            }
		        }

	            // Check the usage of images through ACF in any post.
	            $post_ids = $this->get_post_from_attachment_id($id);
	            if(isset($post_ids) && !empty($post_ids) && is_array($post_ids)){
		            foreach ($post_ids as $post_id) {
		                $usage_info[] = $post_id;
		            }
		        }

	            $media_count_array  = array_unique($usage_info);
	            if(!empty($media_count_array)){
	            	$media_count++;
	            }

	        }
	    }
	    return $media_count;
	}

	// Function to identify the page builder (Elementor and Beaver Builder example)
	function identify_page_builder($content) {
	    $page_builder =  __('Unknown','aiowc');

	    // Check for Elementor
	    if (strpos($content, 'elementor') !== false) {
	        $page_builder = __('Elementor','aiowc');
	    }

	    // Check for Beaver Builder
	    if (strpos($content, 'fl-builder') !== false) {
	        $page_builder = __('Beaver Builder','aiowc');
	    }

	    // Check for WPBakery
	    if (strpos($content, 'vc_row') !== false) {
	        $page_builder = __('WPBakery','aiowc');
	    }

	    // Check for Divi
	    if (strpos($content, 'et_pb_section') !== false) {
	        $page_builder = __('Divi','aiowc');
	    }

	    // Check for Thrive Architect
	    if (strpos($content, 'tve_leads_form_container') !== false) {
	        $page_builder = __('Thrive Architect','aiowc');
	    }

	    // Check for Classic Editor
	    if (strpos($content, 'wp-editor-area') !== false) {
	        $page_builder = __('Classic Editor','aiowc');
	    }

	    return $page_builder;
	}
	/**
	 * Setting menu
	 * 
	 * */
	public function aiowc_plugin_action_links($links, $file) {

	    if (strpos($file, AIOWC_PLUGIN_BASENAME) !== false) { 

	        $custom_link = '<a href="' . esc_url(menu_page_url('aiowc', false)) . '">'.__('Settings','aiowc').'</a>';
	        $deactivate_link_position = array_search('deactivate', array_keys($links));
	        if ($deactivate_link_position !== false) {
	            array_splice($links, $deactivate_link_position + 1, 0, $custom_link);
	        }

	    }
	    return $links;
	}
}
