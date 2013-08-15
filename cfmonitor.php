<?php
/*
 	Plugin Name: Click-Fraud Monitoring
 	Author: Rene Hermenau
        Author URI: https://plus.google.com/u/0/105229046305078704903/posts
        Plugin URI: http://www.clickfraud-monitoring.com
 	Version: 1.6
 	Description: <strong>Monitors and prevents malicious clicks on Adsense ads.</strong> Important to prevent a exclusion from your Google Adsense account. <strong>How to use:</strong> Activate the Plugin -> Go to <a href="./plugins.php?page=cfmonitor-config">settings</a>, Save settings and wrap a div container around your Adsense code. For default use the class:<strong> div= 'cfmonitor' </strong><br><a href="http://www.clickfraud-monitoring.com/" target="_blank">Documentation</a> | <a href="http://demo.clickfraud-monitoring.com/" target="_blank">Demo site</a>
*/

// Nothing to do when called directly
if ( !function_exists( 'add_action' ) ) {
	echo "Nothing to do";
	exit;
}


global $wpdb;
global $disable_ads;
/* todo */
//define('PLUGIN_NAME_SLUG','cfmonitor');
define('CLICK_TABLE', $wpdb->prefix."clickfraudmonitor");
define('CFMONITOR_VERSION', '1.2.0');
define('CFMONITOR_PLUGIN_URL', plugin_dir_url( __FILE__ )); //production
define('CFMONITOR_PLUGIN_INSTALL_FILE', plugin_basename(__FILE__));

include_once 'class.cfmonitor.php';


if ( is_admin() )
	require_once dirname( __FILE__ ) . '/admin.php';


function createtable_clickfraud()
{
	$tablename = CLICK_TABLE;
	$q1 = "CREATE TABLE ".$tablename." (IP_ADDRESS varchar(20) NOT NULL , BLOCKED TINYINT(1) NOT NULL DEFAULT '0', CLICK_TIMESTAMP TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY  (IP_ADDRESS,CLICK_TIMESTAMP))";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $q1 );			
        // create all option rows
        add_option('cfmonitor_noads','false');
        add_option('cfmonitor_click_threshold','2');
        add_option('cfmonitor_ban_period','1');
        add_option('cfmonitor_day_span','7');
        add_option('cfmonitor_email', get_option('admin_email'));
        add_option('cfmonitor_myip','');
        add_option('cfmonitor_customclass','cfmonitor');
        add_option('cfmonitor_blockfirst','');
        add_option('cfmonitor_disablead','false');       
}
        register_activation_hook(__FILE__,'createtable_clickfraud');

/* Strips the comma separated list of IP adresses */		
function cf_should_block_for_myip_option($client_ip) {
    $option_value = get_option('cfmonitor_myip');

    if (strpos($option_value, ',') !== false) {
        $ips = explode(',', $option_value);
        foreach ($ips as $ip) {
            if (trim($ip) === $client_ip)
                return true;
        }

        return false;
    }
    return $option_value === $client_ip;
}		
		
function cfenqueue_admin_scripts() {
		if( 'plugins.php?page=cfmonitor-config' != $page )
        {
             return;
        }
        wp_enqueue_style( 'prefix-style', plugins_url('style_admin.css', __FILE__) );
	    wp_register_style( 'cfmonitor.css', CFMONITOR_PLUGIN_URL . 'cfmonitor.css', array(), '1.0.0.1' );
        wp_enqueue_style('cfmonitor.css');
}
add_action( 'admin_enqueue_scripts', 'cfenqueue_admin_scripts' );

		
function cfenqueue_plugin_scripts() {
    global $wpdb;
	wp_enqueue_script( 'jquery' );

	//$clickmonitor = new clickfraudmonitor($wpdb);
        $clickmonitor = new clickfraudmonitor();
	if (!is_admin()) {
		wp_enqueue_script('cfmonitorcheck', CFMONITOR_PLUGIN_URL . 'js/check_min.js');
               
                $path = CFMONITOR_PLUGIN_URL.'clickupdate.php';
		$clientdata = $clickmonitor->clientdetail($path);
		if ($clientdata['isblockedcount'] >= $clientdata['clickcount'] || get_option('cfmonitor_noads') === 'true' || cf_should_block_for_myip_option($clientdata['client_ip']))
			wp_enqueue_script('click-bomb-hidediv', CFMONITOR_PLUGIN_URL. 'js/hideads_min.js');
                        //echo "VisitcountA" . $clientdata['isblockedcount'] . "clickcount" . $clientdata['clickcount'];
                } else {
                    //echo "VisitcountB" . $clientdata['isblockedcount'] . "clickcount" . $clientdata['clickcount'];
                }
		wp_localize_script('cfmonitorcheck', 'clientcfmonitor', $clientdata );
                //$thisarray = $clickmonitor->checkclient();
                //echo "CheckClient: " . $thisarray[0] . "Day diff are:" . $thisarray[1] . "Day span is " . $thisarray[2]; //for testing
	}	

add_action('wp_enqueue_scripts','cfenqueue_plugin_scripts');

//delete custom table when deactivating plugin
function prefix_on_deactivate() {
global $wpdb;
$tablename = CLICK_TABLE;
$optiontable = $wpdb->prefix."options";
$sql = "DROP TABLE " . $tablename;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $wpdb->query($sql);
    
// delete all option rows
delete_option('cfmonitor_noads');
delete_option('cfmonitor_click_threshold');
delete_option('cfmonitor_ban_period');
delete_option('cfmonitor_day_span');
delete_option('cfmonitor_email');
delete_option('cfmonitor_myip','');
delete_option('cfmonitor_customclass','cfmonitor');
delete_option('cfmonitor_blockfirst');
delete_option('cfmonitor_disablead');
  
      //$wpdb->show_errors();
}
//register_uninstall_hook(__FILE__, 'prefix_on_deactivate');
register_deactivation_hook(__FILE__, 'prefix_on_deactivate');
//$wpdb->show_errors();
//$wpdb->print_error();

/* initialise class 
* check first if option  exist 
*/
 
if( !get_option('cfmonitor_click_threshold') ) {
// no nothing here
} else {
new clickfraudmonitor();
}

/* for debugging */
/*function tl_save_error() {
    update_option( 'plugin_error',  ob_get_contents() );
}
add_action( 'activated_plugin', 'tl_save_error' );
echo get_option( 'plugin_error' );
 */
?>