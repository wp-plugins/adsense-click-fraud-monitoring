<?php
/*
 *  Plugin Name: AdSense Click-Fraud Monitoring Plugin
 *  Author: Rene Hermenau
 *  Author URI: https://plus.google.com/u/0/105229046305078704903/posts
 *  Plugin URI: http://www.clickfraud-monitoring.com
 *  Version: 1.8.6
 *  Description: Monitors and prevents malicious clicks on Adsense ads. Could prevent an exclusion from your Google Adsense account.
 *
 * Click-Fraud Monitoring is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Click-Fraud Monitoring is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Click-Fraud Monitoring. If not, see <http://www.gnu.org/licenses/>.
*/


// Nothing to do when called directly
if ( !function_exists( 'add_action' ) ) {
	echo "Nothing to do";
	exit;
}


global $wpdb;
global $disable_ads;
global $installed_ver;
/* get the current version */
$installed_ver = get_option('cfmonitor_version');

/* todo */
//define('PLUGIN_NAME_SLUG','cfmonitor');
define('CLICK_TABLE', $wpdb->prefix."clickfraudmonitor");
define('CFMONITOR_VERSION', '1.8.6'); /*important for the upgrade routine. must be updated to current version*/
define('CFMONITOR_PLUGIN_URL', plugin_dir_url( __FILE__ )); //production
define('CFMONITOR_PLUGIN_INSTALL_FILE', plugin_basename(__FILE__));

include_once 'class.cfmonitor.php';


if ( is_admin() )
	require_once dirname( __FILE__ ) . '/admin.php';


/**
 * Upgrade routine for new table and plugin layout
 */
/* get the installed version number */

function cfmonitor_upgrade_db() {
    /* compare installed version number with current version number */
   if (isset($installed_ver) != CFMONITOR_VERSION) {
        $tablename = CLICK_TABLE;
        $sql = "CREATE TABLE " . $tablename . " (
            ID int(11) NOT NULL AUTO_INCREMENT,
            IP_ADDRESS varchar(20) NOT NULL,
            BLOCKED TINYINT(1) NOT NULL DEFAULT '0',
            CLICK_TIMESTAMP TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            URL varchar(250) NULL,
            PRIMARY KEY  (ID))";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);
        /* update version number */
        update_option("cfmonitor_version", CFMONITOR_VERSION);
	update_option('cfmonitor_checkurl','');
    }
}

function cfmonitor_update_db_check() {
   if (isset($installed_ver) != CFMONITOR_VERSION) {
        cfmonitor_upgrade_db();
    }
}
if (is_admin()){
add_action( 'plugins_loaded', 'cfmonitor_update_db_check' );
}


/**
 * Initialize and create tables at activation process
 */
function createtable_clickfraud()
{
	$tablename = CLICK_TABLE;
	$q1 = "CREATE TABLE ".$tablename." (
                ID int(11) NOT NULL AUTO_INCREMENT,
                IP_ADDRESS varchar(20) NOT NULL,
                BLOCKED TINYINT(1) NOT NULL DEFAULT '0',
                CLICK_TIMESTAMP TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                URL varchar(250) NULL,
                PRIMARY KEY  (ID))";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $q1 );			
        // create all option rows
        add_option('cfmonitor_version', CFMONITOR_VERSION);
        add_option('cfmonitor_noads','false');
        add_option('cfmonitor_click_threshold','2');
        add_option('cfmonitor_ban_period','1');
        add_option('cfmonitor_day_span','7');
        add_option('cfmonitor_email', get_option('admin_email'));
        add_option('cfmonitor_myip','');
        add_option('cfmonitor_customclass','cfmonitor');
        add_option('cfmonitor_blockfirst','');
        add_option('cfmonitor_disablead','false');       
        add_option('cfmonitor_checkurl','');
}
        //register_activation_hook(__FILE__,'createtable_clickfraud');
      
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
        if( 'plugins.php?page=cfmonitor-config' != site_url() )
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
    wp_enqueue_script('jquery');

    //$clickmonitor = new clickfraudmonitor($wpdb);
    $clickmonitor = new clickfraudmonitor();
    if (!is_admin()) {
        wp_enqueue_script('cfmonitorcheck', CFMONITOR_PLUGIN_URL . 'js/check_min.js');

        $path = CFMONITOR_PLUGIN_URL . 'clickupdate.php';
        //echo "tester" .$path;
        $clientdata = $clickmonitor->clientdetail($path);
        if ($clientdata['isblockedcount'] >= $clientdata['clickcount'] || get_option('cfmonitor_noads') === 'true' || cf_should_block_for_myip_option($clientdata['client_ip']))
            wp_enqueue_script('click-bomb-hidediv', CFMONITOR_PLUGIN_URL . 'js/hideads.js');
        //echo "VisitcountA" . $clientdata['isblockedcount'] . "clickcount" . $clientdata['clickcount'];
    } else {
        //echo "VisitcountB" . $clientdata['isblockedcount'] . "clickcount" . $clientdata['clickcount'];
    }
    wp_localize_script('cfmonitorcheck', 'clientcfmonitor', $clientdata);
    //$thisarray = $clickmonitor->checkclient();
    //echo "CheckClient: " . $thisarray[0] . "Day diff are:" . $thisarray[1] . "Day span is " . $thisarray[2]; //for testing
}	
add_action('wp_enqueue_scripts','cfenqueue_plugin_scripts');

//delete table data on uninstall
function prefix_on_deactivate() {
    global $wpdb;
    $tablename = CLICK_TABLE;
    $optiontable = $wpdb->prefix . "options";
    $sql = "DROP TABLE " . $tablename;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $wpdb->query($sql);

// delete all option rows
    delete_option('cfmonitor_noads');
    delete_option('cfmonitor_click_threshold');
    delete_option('cfmonitor_ban_period');
    delete_option('cfmonitor_day_span');
    delete_option('cfmonitor_email');
    delete_option('cfmonitor_myip', '');
    delete_option('cfmonitor_customclass', 'cfmonitor');
    delete_option('cfmonitor_blockfirst');
    delete_option('cfmonitor_disablead');
    delete_option('cfmonitor_checkurl');

    //$wpdb->show_errors();
}
/* Action when plugin is deleted */
//register_uninstall_hook(__FILE__, 'prefix_on_deactivate');
/* action when plugin is deactivated */
//register_deactivation_hook(__FILE__, 'prefix_on_deactivate');
//$wpdb->show_errors();
//$wpdb->print_error();

/**
 * Plugins row action links
 *
 * @author Michael Cannon <mc@aihr.us>
 * @since 2.0
 * @param array $links already defined action links
 * @param string $file plugin file path and name being processed
 * @return array $links
 */
function cfmonitor_plugin_action_links( $links, $file ) {
	$settings_link = '<a href="' . admin_url( 'plugins.php?page=cfmonitor-config' ) . '">' . esc_html('General Settings') . '</a>';
	if ( $file == 'adsense-click-fraud-monitoring/cfmonitor.php' )
		array_unshift( $links, $settings_link );

	return $links;
}
add_filter( 'plugin_action_links', 'cfmonitor_plugin_action_links', 10, 2 );

/* initialize class 
* check first if option  exist 
*/
 
if (!get_option('cfmonitor_click_threshold')) {
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

register_activation_hook(__FILE__,'createtable_clickfraud');
register_uninstall_hook(__FILE__, 'prefix_on_deactivate');

?>