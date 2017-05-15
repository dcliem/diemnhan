<?php
class CF7AdvanceDB{
	
	function __construct() {
		add_action( 'admin_menu', array($this,'renderGUI') );
		add_action( 'wpcf7_before_send_mail', array( $this, 'beforeSendEmail' ));
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueueLibs' ) );
		
		//ajax
		add_action( 'wp_ajax_delete_cf7_data', array($this, 'cf7AdbAjaxController') );
		add_action( 'wp_ajax_export_cf7_data', array($this, 'cf7AdbExportController') );
		add_action('admin_menu', array($this, 'cf7AdbExportController'));

		//admin notice
		if( get_option( 'cf7-adb-data-show-notif', false ) ){
			add_action( 'admin_notices', array( $this, 'dashboard_notices' ) ); 
		}
		
	}
	
	function renderGUI(){
		add_submenu_page( 'wpcf7','Contact Form Advanced Database','Contact Form Advanced Database', 'manage_options', 'cf7-adb', array($this,'renderBackend') );
	}
	
	function renderBackend(){
		
		require_once('display/cf7-db-view.php');

		//hide notification
		update_option( 'cf7-adb-data-show-notif', 0 );
		
	}
	

	function beforeSendEmail( $cf7 ){
			
		$submission = WPCF7_Submission::get_instance();
		if ( $submission ) {
			$data = $submission->get_posted_data();
			$dataArr = array_merge($data,array('created_date' => current_time( 'mysql' )));
			add_post_meta($data['_wpcf7'],'cf7-adb-data',$dataArr);	
			$unread_messages = get_post_meta( $data['_wpcf7'], 'cf7-adb-data-unread', true );
			update_post_meta( $data['_wpcf7'],'cf7-adb-data-unread', ( intval( $unread_messages ) + 1 ) );
			//status 1= show notification | 2 = hide notofication
			update_option( 'cf7-adb-data-show-notif', 1 );
		}
	}
	
	function enqueueLibs($hook){
			wp_enqueue_style( 'cf7-adb', CF7ADBURL.'/lib/css/style.css' );
			wp_enqueue_script( 'cf7-dataTables', CF7ADBURL.'/lib/js/jquery.dataTables.min.js', array(), '1.10.6', true );
			wp_enqueue_script( 'cf7-script', CF7ADBURL.'/lib/js/cf7-script.js', array(), '1.0.0', true );		
	}
	
	

	function cf7AdbAjaxController() {
	   
	   if(!empty($_POST['data'])){
			foreach($_POST['data'] as $postData){
				delete_post_meta($postData['id'],$postData['key'],maybe_unserialize(base64_decode($postData['val'])));
			}
			echo "success";
	   }else{
		echo "error";
	   }
		
	   die();
	}
	
	function cf7AdbExportController() {
	  // filename for download\
		$hook = add_submenu_page(null, '', '', 'administrator', 'cf7-adb-export-xls', function(){});
		add_action('load-' . $hook, function() {
			$id= $_GET['id'];
			$filename = "contact_form_advanced_database_" . date('Ymd') . ".csv";
			header("Content-Disposition: attachment; filename=\"$filename\"");
			header("Content-Type: application/vnd.ms-excel");
			
			require_once('display/export.php');
			
        exit;
		});
		
	}

	function dashboard_notices() {
	        echo"<div class='updated cfdb-notif'> <p>New Message Received. Please Click <a href='admin.php?page=cf7-adb'>Here</a> to view new messages.</p></div>"; 
	}
	


					
}



?>
