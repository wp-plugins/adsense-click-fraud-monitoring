<?php

/*
 *	Class Name: class.cfmonitor-admin.php
 *	Author: Rene Hermenau
 *      version 1.7.6
 *	@scince 1.7
 *	Description: Main template class for AdSense Click Fraud Monitoring
*/

/*  Copyright 2011  Matthew Van Andel  (email : matt@mattvanandel.com)
 *  Copyright 2013 Rene Hermenau admin@x-simulator.de

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/*************************** LOAD THE BASE CLASS *******************************
 *******************************************************************************
 * The WP_List_Table class isn't automatically available to plugins, so we need
 * to check if it's available and load it if necessary.
 */
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}




/************************** CREATE A PACKAGE CLASS *****************************
 *******************************************************************************
 * Create a new list table package that extends the core WP_List_Table class.
 * WP_List_Table contains most of the framework for generating the table, but we
 * need to define and override some methods so that our data can be displayed
 * exactly the way we need it to be.
 * 
 * To display this example on a page, you will first need to instantiate the class,
 * then call $yourInstance->prepare_items() to handle any data manipulation, then
 * finally call $yourInstance->display() to render the table to the page.
 * 
 * Our theme for this list table is going to be movies.
 */
class cfmonitor_table extends WP_List_Table {
         
    
    /** ************************************************************************
     * REQUIRED. Set up a constructor that references the parent constructor. We 
     * use the parent reference to set some default configs.
     ***************************************************************************/
    function __construct(){
        global $status, $page, $wpdb;
                
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'IP_ADDRESS',     //singular name of the listed records
            'plural'    => 'IP_ADDRESS',    //plural name of the listed records
            'ajax'      => false,        //does this table support ajax?
            'screen' => null,
        ) );
        
    }
    
    
    /** ************************************************************************
     * Recommended. This method is called when the parent class can't find a method
     * specifically build for a given column. Generally, it's recommended to include
     * one method for each column you want to render, keeping your package class
     * neat and organized. For example, if the class needs to process a column
     * named 'title', it would first see if a method named $this->column_title() 
     * exists - if it does, that method will be used. If it doesn't, this one will
     * be used. Generally, you should try to use custom column methods as much as 
     * possible. 
     * 
     * Since we have defined a column_title() method later on, this method doesn't
     * need to concern itself with any column with a name of 'title'. Instead, it
     * needs to handle everything else.
     * 
     * For more detailed insight into how columns are handled, take a look at 
     * WP_List_Table::single_row_columns()
     * 
     * @param array $item A singular item (one full row's worth of data)
     * @param array $column_name The name/slug of the column to be processed
     * @return string Text or HTML to be placed inside the column <td>
     **************************************************************************/
    function column_default($item, $column_name){
        switch($column_name){
            case 'IP_ADDRESS':
            case 'CLICK_TIMESTAMP':
            case 'CLICKCOUNT':
            case 'URL':
            case 'WHOIS':
                return $item[$column_name];
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }
    
        
    /** ************************************************************************
     * Recommended. This is a custom column method and is responsible for what
     * is rendered in any column with a name/slug of 'title'. Every time the class
     * needs to render a column, it first looks for a method named 
     * column_{$column_title} - if it exists, that method is run. If it doesn't
     * exist, column_default() is called instead.
     * 
     * This example also illustrates how to implement rollover actions. Actions
     * should be an associative array formatted as 'slug'=>'link html' - and you
     * will need to generate the URLs yourself. You could even ensure the links
     * 
     * 
     * @see WP_List_Table::::single_row_columns()
     * @param array $item A singular item (one full row's worth of data)
     * @return string Text to be placed inside the column <td> (movie title only)
     **************************************************************************/
     function column_IP_ADDRESS($item){
        
        //Build row actions
        $actions = array(
            'delete_selected_row'    => sprintf('<a href="?page=%s&action=%s&ip_address=%s">Delete (When blocked)</a>',$_REQUEST['page'],'delete_selected_row',$item['IP_ADDRESS']),
        );
        
        //Return the title contents
        return sprintf('%1$s <span style="color:silver">(id:%2$s)</span>%3$s',
            /*$1%s*/ $item['IP_ADDRESS'],
            /*$2%s*/ $item['ID'],
            /*$3%s*/ $this->row_actions($actions)
        );
    }
    
    /** ************************************************************************
     * REQUIRED if displaying checkboxes or using bulk actions! The 'cb' column
     * is given special treatment when columns are processed. It ALWAYS needs to
     * have it's own method.
     * 
     * @see WP_List_Table::::single_row_columns()
     * @param array $item A singular item (one full row's worth of data)
     * @return string Text to be placed inside the column <td> (movie title only)
     **************************************************************************/
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("IP_ADDRESS")
            /*$2%s*/ $item['IP_ADDRESS']                //The value of the checkbox should be the record's id, here IP_ADDRESS as unique identifier
        );
    }
    
    
    /** ************************************************************************
     * REQUIRED! This method dictates the table's columns and titles. This should
     * return an array where the key is the column slug (and class) and the value 
     * is the column's title text. If you need a checkbox for bulk actions, refer
     * to the $columns array below.
     * 
     * The 'cb' column is treated differently than the rest. If including a checkbox
     * column in your table you must create a column_cb() method. If you don't need
     * bulk actions or checkboxes, simply leave the 'cb' entry out of your array.
     * 
     * @see WP_List_Table::::single_row_columns()
     * @return array An associative array containing column information: 'slugs'=>'Visible Titles'
     **************************************************************************/
    function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
            'IP_ADDRESS'     => 'IP Address',
            'CLICKCOUNT' => 'Clicks',
            'CLICK_TIMESTAMP'    => 'Last click time',
            'URL' => 'URL / Page of action',
            'WHOIS'  => 'Whois service'
        );
        return $columns;
    }
    
    /** ************************************************************************
     * Optional. If you want one or more columns to be sortable (ASC/DESC toggle), 
     * you will need to register it here. This should return an array where the 
     * key is the column that needs to be sortable, and the value is db column to 
     * sort by. Often, the key and value will be the same, but this is not always
     * the case (as the value is a column name from the database, not the list table).
     * 
     * This method merely defines which columns should be sortable and makes them
     * clickable - it does not handle the actual sorting. You still need to detect
     * the ORDERBY and ORDER querystring variables within prepare_items() and sort
     * your data accordingly (usually by modifying your query).
     * 
     * @return array An associative array containing all the columns that should be sortable: 'slugs'=>array('data_values',bool)
     **************************************************************************/
    function get_sortable_columns() {
        $sortable_columns = array(
            'IP_ADDRESS'     => array('IP_ADDRESS',false),     //true means it's already sorted
            //'CLICKCOUNT'    => array('CLICKCOUNT',false),
            'CLICK_TIMESTAMP'  => array('CLICK_TIMESTAMP',false),
            'URL' => array('URL',false)
        );
        return $sortable_columns;
    }
    
    
    /** ************************************************************************
     * Optional. If you need to include bulk actions in your list table, this is
     * the place to define them. Bulk actions are an associative array in the format
     * 'slug'=>'Visible Title'
     * 
     * If this method returns an empty value, no bulk action will be rendered. If
     * you specify any bulk actions, the bulk actions box will be rendered with
     * the table automatically on display().
     * 
     * Also note that list tables are not automatically wrapped in <form> elements,
     * so you will need to create those manually in order for bulk actions to function.
     * 
     * @return array An associative array containing all the bulk actions: 'slugs'=>'Visible Titles'
     **************************************************************************/
    function get_bulk_actions() {
        $actions = array(
            'delete_all'    => 'Delete all (blocked) user',
            'delete_selected' => 'Delete selected (blocked) user'
        );
        return $actions;
    }
    

        /**
	 * Adds a dropdown that allows filtering on the posts SQL query
	 * Disabled
	 * @return bool
	 */
	function posts_filter_dropdown() {
		echo '<select name="qry_filter">';
		echo '<option value="">' . __( "All SQl queries", 'cfmonitor' ) . '</option>';
		foreach ( array(
					  'qry_all'      => __( 'Show all clicks' ),
					  'qry_more'     => __( 'More queries' )
				  ) as $val => $text ) {
			$sel = '';
			if ( isset( $_POST['qry_filter'] ) && $_POST['qry_filter'] == $val )
				$sel = 'selected ';
			echo '<option ' . $sel . 'value="' . $val . '">' . $text . '</option>';
		}
		echo '</select>';
	}
        
        /*
         * Add Filter button with extra table navbar
         * 
         */
        
            /*function extra_tablenav($which) {
       
        ?>
        		<div class="alignleft actions">
        <?php
        if ('top' == $which && !is_singular()) {
            submit_button(__('Filter'), 'button', false, false, array('id' => 'post-query-submit'));
        }
        ?>
        		</div>
        <?php
    }*/
        
      
    
    /** ************************************************************************
     * Optional. You can handle your bulk actions anywhere or anyhow you prefer.
     * For this example package, we will handle it in the class to keep things
     * clean and organized.
     * 
     * @see $this->prepare_items()
     **************************************************************************/
    function process_bulk_action() {

        global $wpdb;
            
        $table_adclick = $wpdb->prefix."clickfraudmonitor";
        //Detect when a bulk action is being triggered...
        if( 'delete_all'===$this->current_action() ) {
            $strSQL = "DELETE FROM " . $table_adclick . " WHERE BLOCKED=1";
	    $wpdb->query($strSQL);
            wp_die('IP adresses permanently deleted');
        }
        if( 'delete_selected'===$this->current_action() ) {
            
             $checkbox = $_GET['ip_address']; 
             for($i=0;$i<count($checkbox);$i++)
		{
			if($checkbox[$i] != "")
			{
				$checkboxdata = $checkbox[$i]; 
				//$strSQL = "DELETE FROM $table_cfmonitor WHERE IP_ADDRESS ='".$checkboxdata."' ";
                                $strSQL = "DELETE FROM $table_adclick WHERE IP_ADDRESS ='".$checkboxdata."' AND BLOCKED=1";
                                //$strSQL = "DELETE FROM $table_adclick WHERE IP_ADDRESS ='" .$_GET['ip_address']. "' AND BLOCKED=1";
				$results = $wpdb->query($strSQL);
			}
		}
        }
        
        if ('delete_selected_row' === $this->current_action()) {

            $checkbox = $_GET['ip_address'];
            $strSQL = "DELETE FROM $table_adclick WHERE IP_ADDRESS ='" . $checkbox . "' AND BLOCKED=1";

            $results = $wpdb->query($strSQL);
        }
        
    }
    
    
    /** ************************************************************************
     * REQUIRED! This is where you prepare your data for display. This method will
     * usually be used to query the database, sort and filter the data, and generally
     * get it ready to be displayed. At a minimum, we should set $this->items and
     * $this->set_pagination_args(), although the following properties and methods
     * are frequently interacted with here...
     * 
     * @global WPDB $wpdb
     * @uses $this->_column_headers
     * @uses $this->items
     * @uses $this->get_columns()
     * @uses $this->get_sortable_columns()
     * @uses $this->get_pagenum()
     * @uses $this->set_pagination_args()
     * @param bool
     **************************************************************************/
    function prepare_items() {
        global $wpdb, $arg1; //This is used only if making any database queries
        
        /**
        * Name of the plugin table
        */
        
            
        $table_adclick = $wpdb->prefix."clickfraudmonitor";
        
        /**
         * First, lets decide how many records per page to show
         */
        $per_page = 15;
        /*
         * Get the current page
         */
        $pagenum = isset( $_GET['paged'] ) ? absint( $_GET['paged'] ) : 1;
        
        /*
         * get the offset of the limited db request
         */
        $offset = ( $pagenum - 1 ) * $per_page;
        
        /* 
         * Get total numbers of table entries
         */
        //$total = $wpdb->get_var( "SELECT COUNT('id') FROM {$table_adclick}" );
        
        /*
         * Get number of pages for pagination
         */
        //$num_of_pages = ceil( $total / $per_page );
        
        /**
         * REQUIRED. Now we need to define our column headers. This includes a complete
         * array of columns to be displayed (slugs & titles), a list of columns
         * to keep hidden, and a list of columns that are sortable. Each of these
         * can be defined in another method (as we've done here) before being
         * used to build the value for our _column_headers property.
         */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        
        /**
         * REQUIRED. Finally, we build an array to be used by the class for column 
         * headers. The $this->_column_headers property takes an array which contains
         * 3 other arrays. One for all columns, one for hidden columns, and one
         * for sortable columns.
         */
        $this->_column_headers = array($columns, $hidden, $sortable);
        
        
        /**
         * Optional. You can handle your bulk actions however you see fit. In this
         * case, we'll handle them within our package just to keep things clean.
         */
        $this->process_bulk_action();
        
        
        /**
         * Instead of querying a database, we're going to fetch the example data
         * property we created for use in this plugin. This makes this example 
         * package slightly different than one you might build on your own. In 
         * this example, we'll be using array manipulation to sort and paginate 
         * our data. In a real-world implementation, you will probably want to 
         * use sort and pagination data to build a custom query instead, as you'll
         * be able to use your precisely-queried data immediately.
         */
        /* sort order */
        $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'ID'; //If no sort, default to ID/IP_ADDRESS
        $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc'; //If no sort, default to asc
        
        /**
         * 
         * arg1 switch query | all ip addresses or only the blocked ones
         */
        $sql = "";
        if ($arg1 == 'blocked') {
            $blocked = 'BLOCKED=1';
            $sql = "select *,max(CLICK_TIMESTAMP) as CLICK_TIMESTAMP from ".$table_adclick." where " . $blocked . " group by IP_ADDRESS order by " . $orderby . " " . $order . " LIMIT " . $offset . "," . $per_page;
            //$sql = "select *,max(CLICK_TIMESTAMP) as CLICK_TIMESTAMP from ".$table_adclick." where " . $blocked . " group by IP_ADDRESS order by " . $orderby . " " . $order;
            // count results
            //$total = $wpdb->get_var( "SELECT COUNT('id') FROM {$table_adclick} where IP_ADDRESS ='" . $ip . "' and " . $blocked );
            $total = $wpdb->get_var("SELECT count(DISTINCT IP_ADDRESS) FROM {$table_adclick} where " . $blocked);
            //$total = $wpdb->get_var( "SELECT COUNT(IP_ADRESS) FROM {$table_adclick} where " . $blocked);
            //$total = $total;
            //$total = 3;
        } else {
            $blocked = 'BLOCKED=1 OR BLOCKED=0';
            $sql = "select * from ".$table_adclick." where " . $blocked . " order by " . $orderby . " " . $order . " LIMIT " . $offset . "," . $per_page;
               // count results
               //$total = $wpdb->get_var( "SELECT COUNT('id') FROM {$table_adclick} where IP_ADDRESS ='" . $ip . "' and " . $blocked );
            $total = $wpdb->get_var( "SELECT COUNT('id') FROM {$table_adclick} where " . $blocked);
        }
        
        $result_qry = $wpdb->get_results($sql);
        
        $data = array();
        /* build the result array */
       if (!empty($result_qry)) {
            foreach ($result_qry as $row) {
                $ip = $row->IP_ADDRESS;
                $timestamp = $row->CLICK_TIMESTAMP;
                //$blocked = $row->BLOCKED;
                $query = "select * from " . $table_adclick . " where IP_ADDRESS ='" . $ip . "' and " . $blocked . " order by " . $orderby . " " . $order . "";
                $results = $wpdb->get_results($query);
             
                               /*
                * Get number of pages for pagination
                */
                //$num_of_pages = ceil( $total / $per_page );
                //$total = $wpdb->get_var( "SELECT COUNT('id') FROM {$table_adclick} where IP_ADDRESS ='" . $ip . "' and " . $blocked );
                
                if ($arg1 == 'blocked') {
                $countresult = count($results);
                }else {
                $countresult = 1;    
                }
                $data[] = array(
                        'ID' => $row->ID,
                        'IP_ADDRESS' => $row->IP_ADDRESS,
                        'CLICKCOUNT' => $countresult,
                        'CLICK_TIMESTAMP' => $timestamp,
                        'URL' => $row->URL,
                        'WHOIS' => '<a href="' . plugin_dir_url( __FILE__ ) . '/phpwhois/whois.php?query=' . $row->IP_ADDRESS . '&output=normal" target="_blank">Whois</a>'                  
                );
            }
        }
        
        /**
         * modify pagination string
         * 
         */
        

     
        
        /**
         * This checks for sorting input and sorts the data in our array accordingly.
         * 
         * In a real-world situation involving a database, you would probably want 
         * to handle sorting by passing the 'orderby' and 'order' values directly 
         * to a custom query. The returned data will be pre-sorted, and this array
         * sorting technique would be unnecessary.
         */
        /*function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'IP_ADDRESS'; //If no sort, default to title
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc'; //If no order, default to asc
            $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
            return ($order==='aschghg') ? $result : -$result; //Send final sort direction to usort
            
        }*/
        //usort($data, 'usort_reorder');
        //usort($data, 'usort_reorder');
                
        /**
         * REQUIRED for pagination. Let's figure out what page the user is currently 
         * looking at. We'll need this later, so you should always include it in 
         * your own package classes.
         */
        //$current_page = $this->get_pagenum();
        //$current_page = $pagenum;        
        /**
         * REQUIRED for pagination. Let's check how many items are in our data array. 
         * In real-world use, this would be the total number of items in your database, 
         * without filtering. We'll need this later, so you should always include it 
         * in your own package classes.
         */
        $total_items = count($data);
        
        
        /**
         * The WP_List_Table class does not handle pagination for us, so we need
         * to ensure that the data is trimmed to only the current page. We can use
         * array_slice() to 
         */
        //$data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        
        /**
         * REQUIRED. Now we can add our *sorted* data to the items property, where 
         * it can be used by the rest of the class.
         */
        $this->items = $data;
        
        /**
         * REQUIRED. We also have to register our pagination options & calculations.
         */
        $this->set_pagination_args( array(
            'total_items' => $total,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            //'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
            'total_pages' => ceil( $total / $per_page )
        ) );
    }
   
    
}