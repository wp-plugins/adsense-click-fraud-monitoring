<?php

/**
 * admin settings
 *
 * @package     ClickFraud Monitoring
 * @copyright   Copyright (c) 2013, René Hermenau
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 * @Version     1.7.7
 */

// Nothing to do when called directly
if ( !function_exists( 'add_action' ) ) {
	echo "Nothing to do";
	exit;
}
// Include classes
include_once 'cfmonitor.php';
include_once 'class.cfmonitor.php';
include_once 'class.cfmonitor-admin.php';


add_action( 'admin_menu', 'cfmonitor_admin_menu' );

function cfmonitor_admin_menu() {
		cfmonitor_load_menu();
}

function cfmonitor_load_menu() {
	//$current_theme = wp_get_theme();
	$pluginpath = CFMONITOR_PLUGIN_INSTALL_FILE;
	$active_plugin = get_option('active_plugins');
	
	$plugin_key = array_keys($active_plugin,"$pluginpath");
	$active_plugin_key = $plugin_key[0];
	
	add_submenu_page('plugins.php', __('Click-Fraud Monitor'), __('Click-Fraud Monitor'), 'manage_options', 'cfmonitor-config', 'cfmonitor_conf');
	
}


	global $wp_version;
	
	// all admin functions are disabled in old versions
	if ( !function_exists('is_multisite') && version_compare( $wp_version, '3.0', '<' ) ) {
	
		function cfmonitor_version_warning() {
			echo "
			<div id='cfmonitor-warning' class='updated fade'><p><strong>".sprintf(__('Click-Fraud Monitor %s requires WordPress 3.0 or higher.'), CFMONITOR_VERSION) ."</strong> ".sprintf(__('Please <a href="%s">upgrade WordPress</a> to a current version.'), 'http://codex.wordpress.org/Upgrading_WordPress'). "</p></div>
			";
		}
		add_action('admin_notices', 'cfmonitor_version_warning');
	
		return;
	}

function cfmonitor_admin_init() {
    global $wp_version;
    
    
    // all admin functions are disabled in old versions
    if ( !function_exists('is_multisite') && version_compare( $wp_version, '3.0', '<' ) ) {
        
        function cfmonitor_version_warning() {
			echo "
			<div id='cfmonitor-warning' class='updated fade'><p><strong>".sprintf(__('Click-Fraud Monitor %s requires WordPress 3.0 or higher.'), CFMONITOR_VERSION) ."</strong> ".sprintf(__('Please <a href="%s">upgrade WordPress</a> to a current version.'), 'http://codex.wordpress.org/Upgrading_WordPress'). "</p></div>
			";
		}
		add_action('admin_notices', 'cfmonitor_version_warning');
		return;    
    }
}
add_action('admin_init', 'cfmonitor_admin_init');

add_action( 'admin_enqueue_scripts', 'cfmonitor_load_js_and_css' );

// Make sure when installed WPSEO that sitemap is deactivated - Fixed
/*function wpseofix() {
    if ( defined( 'WPSEO_VERSION' ) ) {
    echo "<div id='cfmonitor-warning' class='updated fade'><p><strong>".sprintf(__('Click Fraud Monitor %s needs disabling the XML sitemap feature of WPSEO for proper functionality.'), cfmonitor_VERSION) ."</strong></p></div>";	
    }
}
add_action('admin_notices', 'wpseofix');
*/
function cfmonitor_load_js_and_css() {
	global $hook_suffix;
        
	if (
		 $hook_suffix == 'plugins_page_cfmonitor-config'
	) {
		wp_register_style( 'cfmonitor.css', CFMONITOR_PLUGIN_URL . 'cfmonitor.css', array(), '1.0.0.1' );
		wp_enqueue_style('cfmonitor.css');
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script('cfmonitor-validate',CFMONITOR_PLUGIN_URL . 'js/jquery.validate_min.js');
		wp_enqueue_script('cfmonitor-form',CFMONITOR_PLUGIN_URL . 'js/form_validate_script_min.js');
                
                $clickmonitor = new clickfraudmonitor();
		$myip = $clickmonitor->getclientip();
                wp_localize_script('cfmonitor-validate', 'cfclientip', $myip);

	}
        
}



function cfmonitor_nonce_field($action = -1) { return wp_nonce_field($action); }
$cfmonitor_nonce = 'cfmonitor-update-key';

function cfmonitor_conf() {
	global $cfmonitor_nonce;

	if ( isset($_POST['submit']) ) {
		if ( function_exists('current_user_can') && !current_user_can('manage_options') )
			die(__('Cheatin&#8217; uh?'));

		check_admin_referer( $cfmonitor_nonce );
		//$key = preg_replace( '/[^a-h0-9]/i', '', $_POST['key'] );
		//$home_url = parse_url( get_bloginfo('url') );
		

		if ( isset( $_POST['clickthreshold'] ) )
			update_option( 'cfmonitor_click_threshold', $_POST['clickthreshold'] );
		
		if ( isset( $_POST['cfmonitor_ban_period'] ) )
			update_option( 'cfmonitor_ban_period', $_POST['cfmonitor_ban_period'] );
		
		if ( isset( $_POST['cfmonitor_day_span'] ) )
			update_option( 'cfmonitor_day_span', $_POST['cfmonitor_day_span'] );
                
                if ( isset( $_POST['cfmonitor_email'] ) )
			update_option( 'cfmonitor_email', $_POST['cfmonitor_email'] );
                
                 if ( isset( $_POST['cfmonitor_noads'] ) ){
			update_option( 'cfmonitor_noads', $_POST['cfmonitor_noads'] );
                 }else{
                        update_option( 'cfmonitor_noads', 'false' );
                 }
                  if ( isset( $_POST['cfmonitor_customclass'] ) )
			update_option( 'cfmonitor_customclass', $_POST['cfmonitor_customclass'] );
                  
                  if ( isset( $_POST['cfmonitor_myip'] ) )
			update_option( 'cfmonitor_myip', $_POST['cfmonitor_myip'] );
                  
                  if ( isset( $_POST['cfmonitor_blockfirst'] ) ){
			update_option( 'cfmonitor_blockfirst', $_POST['cfmonitor_blockfirst'] );
                  }else{
                        update_option( 'cfmonitor_blockfirst', 'false' );
                 }
                 
                  if ( isset( $_POST['cfmonitor_disablead'] ) ){
			update_option( 'cfmonitor_disablead', $_POST['cfmonitor_disablead'] );
                  }else{
                        update_option( 'cfmonitor_disablead', 'false' );
                 }
                 
                 if ( isset( $_POST['cfmonitor_checkurl'] ) ){
			update_option( 'cfmonitor_checkurl', $_POST['cfmonitor_checkurl'] );
                  }else{
                        update_option( 'cfmonitor_checkurl', 'false' );
                 }


	} 

?>
<?php if ( !empty($_POST['submit'] ) ) : ?>
<div id="message" class="updated fade"><p><strong><?php _e('Options saved.') ?></strong></p></div>
<?php endif; ?>

	<div class="wrap rm_wrap">
            <div>
                <img src="<?php echo plugin_dir_url( __FILE__ );?>./images/logo.png"><br>Free Version: <?php echo CFMONITOR_VERSION; ?><br>Get the premium version at <a href="http://demo.clickfraud-monitoring.com" target="_blank">clickfraud-monitoring.com</a><br>
            </div>
		<h3><?php _e('Anti Click-Fraud Settings'); ?></h3>
		<div class="rm_opts">
			<form action="" method="post" id="cfmonitor-conf" style="margin: auto;">
				<div class="rm_section" style="float:left;">
					<div class="clearfix" style="display: block;">
						<div class="rm_input rm_textvalidate">
							<div class="label">
								<label for="clickthreshold"><?php _e('Click Limit:'); ?></label>
							</div>
							<div class="rm_field">
								<input id="clickthreshold" name="clickthreshold" class="required number" type="text" value="<?php if(get_option('cfmonitor_click_threshold') == 0 || get_option('cfmonitor_click_threshold') == null) { echo "2"; } else { echo get_option('cfmonitor_click_threshold');} ?>" /> 
							</div>
							<div class="rm_desc"><small>
								(<?php _e('Maximum count of clicks above the User IP will be blocked'); ?>)
								</small>
							</div>
						</div>

						<!-- TODO
                                                <div class="rm_input rm_text">

							<div class="label">
								<label for="cfmonitor_ban_period"><?php //_e('Duration of ban in days:'); ?> </label>
							</div>
							<div class="rm_field">
								<input name="cfmonitor_ban_period" class="required number" id="cfmonitor_ban_period" type="text" value="<?php //if(get_option('cfmonitor_ban_period') == 0 || get_option('cfmonitor_ban_period') == null ) { echo "1"; } else{ echo get_option('cfmonitor_ban_period'); } ?>" />
							</div>
							<div class="rm_desc"><small>(<?php //_e('The duration in days for which the IP Address will be banned'); ?>)
								</small>
							</div>
						</div>//-->
						
						
						<div class="rm_input rm_text">

							<div class="label">
								<label for="cfmonitor_day_span"><?php _e('Clicks during last n days:'); ?> </label>
							</div>
							<div class="rm_field">
								<input name="cfmonitor_day_span" class="required number" id="cfmonitor_day_span" type="text" value="<?php if(get_option('cfmonitor_day_span') == 0 || get_option('cfmonitor_day_span') == null) { echo "7"; } else{ echo get_option('cfmonitor_day_span'); } ?>" />
							</div>
							<div class="rm_desc"><small>(<?php _e('Count Clicks of a single user since the last n days.'); ?>)
								</small>
							</div>
						</div>
                                                <div class="rm_input rm_text">
                                                <div class="label">
                                                 <label for="cfmonitor_email"><?php _e('E-Mail address:'); ?> </label>
                                                 </div>
                                                 <div class="rm_field">
                                                 <input name="cfmonitor_email" class="required email" id="cfmonitor_email" type="email" value="<?php if (get_option('cfmonitor_email') == '' || get_option('cfmonitor_email') == null) {echo get_option('admin_email');} else { echo get_option('cfmonitor_email');} ?>" />
                                                 </div>
                                                    <div class="rm_desc"><small>(<?php _e('Sent a notification for any blocked IP to this email - <strong>It only works in the PREMIUM  version! Get it at <a href="http://demo.clickfraud-monitoring.com">Clickfraud-Monitoring.com</a></strong>'); ?>) </small>
                                                 </div>
                                                 </div>
                                            <div class="rm_input rm_text">
                                                <div class="label">
                                                 <label for="cfmonitor_noads"><?php _e('Disable all ads:'); ?> </label>
                                                 </div>
                                                 <div class="rm_field">
                                                     <input name="cfmonitor_noads" id="cfmonitor_noads" value="true" type="checkbox" <?php if (get_option('cfmonitor_noads') == 'true'){echo 'checked="checked"';}else{echo '';} ?>/>
                                                 </div>
                                                    <div class="rm_desc"><small>(<?php _e('Disable and hide all advertisements. Advisable when you recognize a lot of malicious clicks, until the problem is solved.'); ?>) </small>
                                                 </div>
                                                 </div>
                                            </div>   
                                                <div style="float:clear;" id="div-cfadvanced">
                                                <div class="rm_input rm_text">
                                                <div class="label">
                                                 <label for="cfmonitor_customclass"><?php _e('Custom Class:'); ?> </label>
                                                 </div>
                                                 <div class="rm_field">
                                                 <input name="cfmonitor_customclass" class="text" id="cfmonitor_customclass" type="text" value="<?php if (get_option('cfmonitor_customclass') == '' || get_option('cfmonitor_customclass') == null) {echo 'cfmonitor';} else { echo get_option('cfmonitor_customclass');} ?>" />
                                                 </div>
                                                    <div class="rm_desc"><small>(<?php _e('You can use any custom class for the monitored ads. E.g.: <strong>"myads"</strong>. Default is <strong>"cfmonitor"</strong>. Do not use any special or blank characters. A custom class makes it a little bit harder for bots to find the ads on your site.'); ?> </small>
                                                 </div>
                                                 </div>
                                                    <div class="rm_input rm_text">
                                                <div class="label">
                                                 <label for="cfmonitor_myip"><?php _e('Block my IP:'); ?> </label>
                                                 </div>
                                                 <div class="rm_field">
                                                 <input name="cfmonitor_myip" class="text" id="cfmonitor_myip" type="text" value="<?php if (get_option('cfmonitor_myip') == '' || get_option('cfmonitor_myip') == null) {echo '';} else { echo get_option('cfmonitor_myip');} ?>" />
                                                 <br><a href="javascript:void(0)" id="clickgetmyip">Get my IP address</a>
                                                 </div>
                                                 <div class="rm_desc"><small>(<?php _e('Fill in your IP address to block any ads on your site. Useful to prevent unintended clicks by yourself or your teammates. Leave empty when you do not want to block yourself. You may also include a list of IP addresses by separating them with commas (ie: 111.222.33.44, 55.22.77.888)'); ?>) </small></div>
                                                    
                                                 </div>
                                                    <script>
                                                    jQuery(document).ready( function($) {
                                                    $( "#howtoslider" ).click(function() {
                                                        $( "#helpslider" ).slideToggle( "slow", function() {
                                                          // Animation complete.
                                                        });
                                                      });
                                                    });
                                                    </script>
                                                
                                                    <div class="rm_input rm_text" style="border: 1px solid #DBDBDB;padding:5px;">
                                                            <strong>Requirements:</strong> Use the following div container around your ads:
                                                            <pre style='background-color: black;color:white;padding:3px;'>&lt;div class="<?php echo (get_option('cfmonitor_customclass') == true ) ? get_option('cfmonitor_customclass') : 'cfmonitor' ?>"&gt;ADSENSE CODE HERE&lt;/div&gt;</pre>
                                                        
                                                            <span style="width:100%;">The name 'cfmonitor' is default. You can choose a name of your choice but must change the field 'Custom Class' than.<br><a href="return:void(0);" id="howtoslider">How to check if this plugin is working?</a></span>
                                                            <div style="padding:20px;display:none;" id="helpslider"><a href="./wp-admin/post-new.php" target="_blank">Create a new post</a> and write the following text in it (WordPress editor in text mode):<p>
                                                            <pre style='background-color: black;color:white;padding:3px;'>&lt;div class="<?php echo (get_option('cfmonitor_customclass') == true ) ? get_option('cfmonitor_customclass') : 'cfmonitor' ?>"&gt;CLICK HERE FOR A TEST&lt;/div&gt;</pre>
                                                    Open the new created page and click on the text a few times. When the text hides itself you are done. If the text does not hide check your website for any js error or create a new topic in the <a href="https://wordpress.org/support/plugin/adsense-click-fraud-monitoring" target="_blank">WordPress support forum.</a>
                                                            </div>
                                                            <div style="float:clear;">
                                                                    
                                                                    <?php
                                                                    // Create DOM from URL or file
                                                                    require_once 'cfmonitor_simple_html_dom.php';
                                                                    //$checkurl = 'http://127.0.0.1/dev/hello-world/';
                                                                    $checkurl = (get_option('cfmonitor_checkurl') == true) ? (string)get_option('cfmonitor_checkurl') : get_site_url();
                                                                    $html = clickfraud_file_get_html($checkurl);
                                                                    $headers = clickfraud_get_page_headers($checkurl);
                                                                    if (method_exists($html, 'find')){ 
                                                                        if($headers=='200'){                        
                                                                                if ($html->find('.' . get_option('cfmonitor_customclass')) != null) {
                                                                                    echo "<h2>Class '" . get_option('cfmonitor_customclass') . "' found on " . get_option('cfmonitor_checkurl') . " <br> It seem that the script is working properly.</h2>";
                                                                                } else {
                                                                                    echo "<h2>No Class '" . get_option('cfmonitor_customclass') . "' found. Check if the class '<strong>" . get_option('cfmonitor_customclass') . "</strong>' is found in the HTML source of the URL specified in the field below: </h2>";
                                                                                }
                                                                            }else {
                                                                                echo "<br><strong style='color:red;'>No valid URL for check run specified, or URL is not reachable.</strong><br><br>";
                                                                            }
                                                                    }
                                                                    
             
                                                                    ?>
                                                                    <small style='width:380px;'>Specify a URL which should be tested for the class <strong><?php echo (get_option('cfmonitor_customclass') == true ) ? get_option('cfmonitor_customclass') : 'cfmonitor' ?> </strong> 
                                                                        available in your website´s HTML source: </small><br>
                                                                        <input name="cfmonitor_checkurl" class="text" id="cfmonitor_checkurl" style='width:300px;' type="text" value="<?php if (get_option('cfmonitor_checkurl') == '' || get_option('cfmonitor_checkurl') == null) {echo get_site_url();} else { echo get_option('cfmonitor_checkurl');} ?>" />

                                                                    <input type="submit" name="submit" class="button-primary" value="<?php _e('Save settings and run a check  &raquo;'); ?>" />

                                                                    <!--<a href="./plugins.php?page=<?php //echo $_REQUEST['page'];  ?>" target="_self" class="button-primary"><span style="button-primary">Run Check</span></a>!-->
                                                                </div>
                                                    
                                                    
                                                    
                                                    </div>
                                                 
                                                 <!--   <div class="rm_input rm_text">
                                                <div class="label">
                                                 <label for="cfmonitor_blockfirst"><?php //_e('Block first click:'); ?> </label>
                                                 </div>
                                                 <div class="rm_field">
                                                     <input name="cfmonitor_blockfirst" id="cfmonitor_blockfirst" value="true" type="checkbox" <?php //if (get_option('cfmonitor_blockfirst') == 'true'){echo 'checked="checked"';}else{echo '';} ?>/>
                                                 </div>
                                                    <div class="rm_desc"><small>(<?php //_e('Blocks the first click of a user or bot. Allows the further clicks until limit is reached! A very smart function to prevent malicious clicks E.g.: <strong><br>Assume a bot is clicking on your ads:</strong> When "Block first click" is activated, the click is not counted but most bots does not recognize unsuccesful clicks. So they leave. Well, maybe they come back later with another IP, trying to harm you. That doesn´t matter as long as the bot is only clicking once. If the bot clicks a second time, the click is counted. Further click and we are able to block it with the "Click Limit". <strong><br>Assume a real person clicks on your ad:</strong> Nothing happens for the first click, but as long as the user realy wants to visit the ad (that´s the only type of person we want to have ) he will click again and the click is counted until "click limit" is reached. That reduces the "invalid clicks" in your account a lot and minimizes the risk become banned.<br><br><br> This does not work with "hidden" ads. <strong>Your CTR could drop when you block the first click. Check carefully if the increase of safety is it worth.</strong>'); ?> </small>
                                                 </div>
                                                 </div>-->
                                                    <!-- <div class="rm_input rm_text">
                                                <div class="label">
                                                 <label for="cfmonitor_disablead"><?php //_e('Disable ad when its blocked:'); ?> </label>
                                                 </div>
                                                 <div class="rm_field">
                                                     <input name="cfmonitor_disablead" id="cfmonitor_disablead" value="true" type="checkbox" <?php //if (get_option('cfmonitor_disablead') == 'true'){echo 'checked="checked"';}else{echo '';} ?>/>
                                                 </div>
                                                    <div class="rm_desc"><small>(<?php //_e('Choose if you only want to disable the ad. It will be visible for visitors and bots, even when it´s blocked. Default setting is "unchecked", so ads are hidden and invisible when they are blocked'); ?> </small>
                                                 </div>
                                                 </div>-->
                                                   
                                                </div>
                                               
                                        </div>
                          
                           
                            
                         
				

				<?php cfmonitor_nonce_field($cfmonitor_nonce); ?>
			</form>

		</div>
	</div>
<div style="clear:both;">
    <form action="" method="GET" id="ipblocktable" style="">
        <input type='hidden' name='page' value='<?php echo $_REQUEST['page']; ?>'/>
    <?php 
    /* filter mysql query */

    unblockIP(); 
    ?>
        <div class='btnUnblock' style="float:left;padding:5px;">
            <p>
                <input type='submit' name='btnUnblock' class='button-primary' value='Delete selected user' />
                
            </p>
        </div>
        <div class='btnUnblock' style="float:left;padding:5px;">
            <p>
                <input type='submit' name='allclicks' class='button-primary' value='Statistic' />
            </p>
        </div>
        <div class='btnUnblock' style="float:left;padding:5px;">
            <p>
                <a href="./plugins.php?page=<?php echo $_REQUEST['page']; ?> " target="_self" class="button-primary"><span style="button-primary">Show blocked User</span></a> 
                
            </p>
        </div>

</form>
</div>
<?php

}

function unblockIP()
{

        
        if (isset($_GET['btnUnblock']) || !isset($_GET['allclicks'])){
            /* show list of blocked IP addresses */
            echo "<br><h3>Blocked user:</h3>";
            $IPlistTable = new cfmonitor_table();
            $IPlistTable->prepare_items($arg1 = 'blocked');
            $IPlistTable->display();
            
        }
	if (isset($_GET['btnUnblock']))
	{
        $checkbox = $_GET['ip_address']; 
				
		global $wpdb;
		$table_cfmonitor = CLICK_TABLE;
	
		for($i=0;$i<count($checkbox);$i++)
		{
			if($checkbox[$i] != "")
			{
				$checkboxdata = $checkbox[$i]; 
                $strSQL = "DELETE FROM $table_cfmonitor WHERE IP_ADDRESS ='".$checkboxdata."' AND blocked=1";
				$results = $wpdb->query($strSQL);
			}
		}
	
	}

    if (isset($_GET['allclicks']) && $_GET['allclicks'] == 'Statistic') {
        if (isset($_GET['ip_address'])) {
            $checkbox = $_GET['ip_address'];
        }
		/*<PREM>*/
        echo '<img src="' . plugin_dir_url(__FILE__) . '/images/smiley_sad.png" alt="I am soory"><br><h3>I am very soory!</h3>
                    <br> This feature is only available in the premium version. <p>
                    The development of this plugin takes up a lot of time,<br> 
                    but i am willing to integrate as many of your desired features as possible.<br> 
                    Due to my limited time it is only possible to create that special functions<br>
                    when i find a way to earn a small amount for my work.<br> 
                    <p>
                    Personally i use and love free software and i am sure you also!<br>
                    So i decided to make new features first for the premium version and<br>
                    later i integrate most of this functions step by step into the free version as well.<br> 
                    
                    <p>So if you cannot wait for the latest release and want to support me,<br>
                    <b>just purchase it.</b></p>
           
                    Thanks for reading all that stuff.</p>
                    Yours, René
                   <br>
                   <br><a href="http://demo.clickfraud-monitoring.com/?download=click-fraud-monitoring" target="_blank" class="button-primary">Let me support you - I purchase the premium version</a>';
		

        /*<PREM>*/
		echo "<br><h3>Blocked user:</h3>";
            $IPlistTable = new cfmonitor_table();
            $IPlistTable->prepare_items($arg1 = 'blocked');
            $IPlistTable->display();
        
    }
       
}


?>