<?php

/**
 * Plugin Name: Contact Form Advance Database
 * Plugin URI: http://www.christianbautista.info/contact-form-advance-database
 * Description: A Very Simple plugin that will capture all the emails being sent using Contact Form 7 Plugin
 * Version: 1.0.8
 * Author: Christian A. Bautista
 * Author URI: http://www.christianbautista.info
 * License: Free
 */
 define('CF7ADBURL',plugin_dir_url(__FILE__));
 require( 'lib/cf7_adb.class.php' );

 //initiate Main Class
 $CF7AdvanceDB = new CF7AdvanceDB();

 
