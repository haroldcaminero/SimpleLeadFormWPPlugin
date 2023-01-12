<?php 
/* 

Plugin Name: Harold's Simple Lead Generation Form
Description: Simple Lead Generation Form
Version: 0.0.1
Author: Harold Jayson Caminero 

*/

define( 'HLGF_PLUGIN_PATH', plugin_dir_path(__FILE__) );
define( 'HLGF_PLUGIN_URL', plugin_dir_url(__FILE__) );
define( 'HLGF_PLUGIN_NAME', 'haroldleadsgen' );

require_once( HLGF_PLUGIN_PATH.'includes/Customer.php' );

$Customer = new Harold_Leads\Customer();

