<?php

/*
 	Class Name: class.cfmonitor.php
 	Author: Rene Hermenau
 	Version: 1.3
 	Description: Main class for AdSense Click Fraud Monitoring
	@since 1.0
*/

 class clickfraudmonitor {

	var $wpdb = null;
	var $clientip = null;
	var $clickcount = null; //max clicks until ban
	var $clientfound = null; 
	var $isblockedcount = null;
	var $table_name = null;
        var $current_url = null;

	function __construct($dbcls=null)
	{
                global $wpdb;
                $this->wpdb = $wpdb;
                
		if ($dbcls) $this->wpdb = $dbcls;
		//else $this->getdbo();
		$this->table_name = $this->wpdb->prefix."clickfraudmonitor";
		$this->clientip = $this->getclientip();
		$this->clickcount = (isset($_POST['count']))?isset($_POST['count']):isset($_GET['count']);
                //$this->current_url = (isset($_POST['clickurl']))?isset($_POST['clickurl']):isset($_GET['clickurl']);
		$this->clientfound = $this->checkclient();
                if (isset($_POST['clickurl']))
                    $this->current_url = $_POST['clickurl'];
                
        /* call ajax*/
        if (is_admin()) {
            add_action( 'wp_ajax_nopriv_ajax-updateclicks', array( &$this, 'ajax_updateclicks' ) );
            add_action( 'wp_ajax_nopriv_ajax-checkclicks', array( &$this, 'ajax_checkclicks' ) );
            add_action( 'wp_ajax_ajax-updateclicks', array( &$this, 'ajax_updateclicks' ) );
            add_action( 'wp_ajax_ajax-checkclicks', array( &$this, 'ajax_checkclicks' ) );
            }
            add_action( 'init', array( &$this, 'init' ) );
        /* end call ajax*/
	}
        
        public function init()
	{
	wp_enqueue_script( 'ajax-checkclicks', plugin_dir_url( __FILE__ ) . 'js/checkclicks.js', array( 'jquery' ) );
        wp_enqueue_script( 'ajax-updateclicks', plugin_dir_url( __FILE__ ) . 'js/updateclicks.js', array( 'jquery' ) );
	wp_localize_script( 'ajax-checkclicks', 'AjaxCheckClicks', array(
		    'ajaxurl' => admin_url( 'admin-ajax.php' ),
		    'nonce' => wp_create_nonce( 'ajax_checkclicks_nonce' )
		) );
        wp_localize_script( 'ajax-updateclicks', 'AjaxUpdateClicks', array(
		    'ajaxurl' => admin_url( 'admin-ajax.php' ),
		    'nonce' => wp_create_nonce( 'ajax_updateclicks_nonce' )
		) );
        }

        public function ajax_updateclicks()
	{
		if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], 'ajax_updateclicks_nonce' ) )
			die ( 'Invalid Nonce' );
                $clickmonitor = new clickfraudmonitor();
                $result['result'] = $clickmonitor->updateclick();
                $result['message'] = ($result['result'])?'':'Error';
                //echo json_decode($_POST['data']);
                //$clicker = json_decode($_POST['data']);
		header( "Content-Type: application/json" );
 
                echo json_encode (array(
                    'clicks' => $result['result'],
                    //'message' => $result['message'],
                    'message' => 'updated',
                        ));
		exit;
	}
        
        public function ajax_checkclicks()
	{
		if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], 'ajax_checkclicks_nonce' ) )
			die ( 'Invalid Nonce' );
                $clickmonitor = new clickfraudmonitor();
                $result['result'] = $clickmonitor->checkclient();
                $result['message'] = ($result['result'])?'':'Error';
		header( "Content-Type: application/json" );

                echo json_encode (array(
                    'clicks' => $result['result'], 
                    //'message' => $result['message']
                    'message' => 'checkclick'
                        ));
		exit;
	}


        function getclientip() {
        //$clientip =   $_SERVER['REMOTE_ADDR']; 
        //return $clientip;
        //$clientip =   $_SERVER['REMOTE_ADDR']; 
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            //check ip from share internet  
            $clientip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            //to check ip is pass from proxy  
            $clientip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $clientip = $_SERVER['REMOTE_ADDR'];
        }

        return $clientip;
    }

	  function getFirstClickTimeStamp()
	{
		$getresult = null;
		$result = $this->wpdb->get_var( "SELECT CLICK_TIMESTAMP FROM ".esc_attr($this->table_name)." where IP_ADDRESS='".esc_attr($this->clientip)."' order by CLICK_TIMESTAMP asc limit 0,1");
		$fulldate = explode(" ",$result);
		$date =  $fulldate[0];
		$time = isset($fulldate[1]);
		$firstclickdata[] = explode("-",$date);
		$firstclickdata[] = explode(":",$time);

		return $firstclickdata;

	}
        
         function getLastClickTimeStamp()
	{
		$getresult = null;
		$result = $this->wpdb->get_var( "SELECT CLICK_TIMESTAMP FROM ".esc_attr($this->table_name)." where IP_ADDRESS='".esc_attr($this->clientip)."' order by CLICK_TIMESTAMP desc limit 0,1");
		$fulldate = explode(" ",$result);
		
                $date =  $fulldate[0];
		$time = isset($fulldate[1]);
		$lastclickdata[] = explode("-",$date);
		$lastclickdata[] = explode(":",$time);

		return $lastclickdata;

	}

	  function dateDiff($start, $end)
	{
		$start_ts = strtotime($start);
		$end_ts = strtotime($end);
		$diff = $end_ts - $start_ts;
		return round($diff / 86400);
	}
        
        function getcustomclass()
        {
            $customclass = get_option('cfmonitor_customclass');
            return $customclass;
        }


	  function checkclient()
	{
		$bannedperiod = get_option('cfmonitor_ban_period');
		$daySpan = get_option('cfmonitor_day_span');
		$clickdata = $this->getFirstClickTimeStamp();
		$clickfirstdate = $clickdata[0];
		$clickdateimplode = implode("-",$clickfirstdate);
		$clickdate = str_replace("-","",$clickdateimplode);
                
                $clicklastdata = $this->getLastClickTimeStamp();
                $clicklastdatearray = $clicklastdata[0];
                $clicklastdateimplode = implode("-",$clicklastdatearray);

		$currentdatedata =  date('Y-m-d'); //date("2013-05-18");
		$currentdate = str_replace("-","",$currentdatedata);
		$enddatedata = strtotime ( '+'.$bannedperiod.' day' , strtotime ( $clickdateimplode ) ) ;
		$enddate =  str_replace("-","",date ( 'Y-m-d' , $enddatedata ));
		$endformat = date($enddate);

		//$daysDiff = $this->dateDiff($clickdateimplode,$currentdatedata);
                $daysDiff = $this->dateDiff($clicklastdateimplode,$currentdatedata);
		$sql = "select IP_ADDRESS,BLOCKED from ".$this->table_name." where IP_ADDRESS='".$this->clientip."'"; 
		$results = $this->wpdb->get_results($sql);

		if(empty($results))
		{
			$countresult = 0;
			//return array($countresult, $daysDiff, $daySpan); //only for testing
                        return $countresult;
		}

		else if(!empty($results))
		{
			foreach($results as $row)
			{
				$clickip = $row->IP_ADDRESS;

				if($daysDiff <= $daySpan) // count the last n days
				{
                                        $sqlquery = "select * from ".$this->table_name." where IP_ADDRESS ='".$clickip."' and CLICK_TIMESTAMP >= '$clicklastdateimplode%'";
					//$sqlquery = "select * from ".$this->table_name." where IP_ADDRESS ='".$clickip."' and CLICK_TIMESTAMP >= '$clickdateimplode%'";
					$resultsql = $this->wpdb->get_results($sqlquery);
				}
				else if($daysDiff > $daySpan) //count new
				{

					$sqlquery = "select * from ".$this->table_name." where IP_ADDRESS ='".$clickip."' and CLICK_TIMESTAMP like '$currentdatedata%'";
					$resultsql = $this->wpdb->get_results($sqlquery);
				}

				$countresult = count($resultsql);
				//return array($countresult, $daysDiff, $daySpan); //only for testing
                                return $countresult;

			}
		}
	}


	  function updateclick(){
		$clickcount = get_option('cfmonitor_click_threshold');
		$bannedperiod = get_option('cfmonitor_ban_period');
		$daySpan = get_option('cfmonitor_day_span');

		$clickdata = $this->getFirstClickTimeStamp();
		$clickfirstdate = $clickdata[0];
		$clickdateimplode = implode("-",$clickfirstdate);
		$clickdate = str_replace("-","",$clickdateimplode);
                
                $clicklastdata = $this->getLastClickTimeStamp();
                $clicklastdatearray = $clicklastdata[0];
                $clicklastdateimplode = implode("-",$clicklastdatearray);
		//$clicklastdate = str_replace("-","",$clicklastdateimplode);
                //$currentdate = str_replace("-","",$currentdate1);
                
		$currentdatedata = date('Y-m-d'); //date("2012-09-15"); 
		
		$enddate = strtotime ( '+'.$bannedperiod.' day' , strtotime ( $clickdateimplode ) ) ;
		$enddate =  str_replace("-","",date ( 'Y-m-d' , $enddate ));
		$endformat = date($enddate);
                
		$daysDiff = $this->dateDiff($clickdateimplode,$currentdatedata);
                
                /* get the current url including get params 
                 * We ll return it via the ajax post request in check.js
                 */
                
       
                //$daysDifflastClick = $this->dateDiff($clicklastdateimplode,$currentdatedata);
                
                // Delete banning after  banning period
                /*if ($daysDifflastClick > $bannedperiod) {
                    $setfield = 'BLOCKED=0 ';
                    $sql = "UPDATE " . $this->table_name . " SET " . $setfield . " where IP_ADDRESS='" . $this->clientip . "'";
                    $resultinsert = $this->wpdb->query($sql);
                }*/
                
                     
		if ($this->clientfound < $clickcount-1)
		{
			$sql =	"INSERT INTO ".esc_attr($this->table_name)." (IP_ADDRESS, BLOCKED, CLICK_TIMESTAMP, URL) values('".esc_attr($this->clientip)."',0,now(),'" . $this->current_url . "')";
			$resultinsert = $this->wpdb->query($sql);
		}
		else
		{
			$setfield = '';
                        
                        //if (($this->clientfound >= $clickcount || $this->clickcount >= $clickcount)) {
			if (($this->clientfound >= $clickcount-1)) {
                                // Insert last click
                                $sql_lastclick = "INSERT INTO ".esc_attr($this->table_name)." (IP_ADDRESS, BLOCKED, CLICK_TIMESTAMP, URL) values('".esc_attr($this->clientip)."',0,now(),'" . $this->current_url . "')";
                                $insertlastclick = $this->wpdb->query($sql_lastclick);
                                // set all clicks to blocked
				$setfield = 'BLOCKED=1 ';

				$sql =	"UPDATE ".$this->table_name." SET ".$setfield." where IP_ADDRESS='".$this->clientip."'";
				$resultinsert = $this->wpdb->query($sql);
			}
                        /* < premium >*/
                        
		}

                
		return $resultinsert;

	}

	  function clientdetail($preurl) {
		$clientdetail = array(
			"client_ip"=>$this->getclientip(),
			"clickcount"=>get_option('cfmonitor_click_threshold'),
			"bannedperiod"=>get_option('cfmonitor_ban_period'),
			"preurl" => $preurl,
			"firstclickdate" => $this->getFirstClickTimeStamp(),
			"updatedVisitCount" => $this->checkclient(),
                        "isblockedcount" => $this->isblockedcount(),
                        "customclass" => $this->getcustomclass(),
                        "firstclick" => get_option('cfmonitor_blockfirst'),
                        "disablead" => get_option('cfmonitor_disablead'),
                        "currentURL" => $_SERVER['REQUEST_URI']
                    
		);
		return $clientdetail;
	}
        
       /* < premium >*/

    
    function isblockedcount(){
		$bannedperiod = get_option('cfmonitor_ban_period');
		$daySpan = get_option('cfmonitor_day_span');
		$clickdata = $this->getFirstClickTimeStamp();
		$clickfirstdate = $clickdata[0];
		$clickdateimplode = implode("-",$clickfirstdate);
		$clickdate = str_replace("-","",$clickdateimplode);
                
                $clicklastdata = $this->getLastClickTimeStamp();
                $clicklastdatearray = $clicklastdata[0];
                $clicklastdateimplode = implode("-",$clicklastdatearray);

		$currentdatedata =  date('Y-m-d'); //date("2013-05-18");
		$currentdate = str_replace("-","",$currentdatedata);
		$enddatedata = strtotime ( '+'.$bannedperiod.' day' , strtotime ( $clickdateimplode ) ) ;
		$enddate =  str_replace("-","",date ( 'Y-m-d' , $enddatedata ));
		$endformat = date($enddate);

		//$daysDiff = $this->dateDiff($clickdateimplode,$currentdatedata);
                $daysDiff = $this->dateDiff($clicklastdateimplode,$currentdatedata);
                
		$sql = "select IP_ADDRESS,BLOCKED from ".$this->table_name." where IP_ADDRESS='".$this->clientip."' and BLOCKED = 1"; 
		$results = $this->wpdb->get_results($sql);

		if(empty($results))
		{
			$countresult = 0;
			return $countresult;
		}

		else if(!empty($results))
		{
			foreach($results as $row)
			{
				$clickip = $row->IP_ADDRESS;

				if($daysDiff <= $daySpan)
				{
					$sqlquery = "select * from ".$this->table_name." where IP_ADDRESS ='".$clickip."' and CLICK_TIMESTAMP >= '$clickdateimplode%' and BLOCKED = 1";
					$resultsql = $this->wpdb->get_results($sqlquery);
				}
				else if($daysDiff > $daySpan)
				{

					$sqlquery = "select * from ".$this->table_name." where IP_ADDRESS ='".$clickip."' and CLICK_TIMESTAMP like '$currentdatedata%' AND BLOCKED = 1";
					$resultsql = $this->wpdb->get_results($sqlquery);
				}

				$countresult = count($resultsql);
				return $countresult;
			}
		}
	}
}
?>