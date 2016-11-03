<?php

/**
 * CS_CUSTOMER Class.
 *
 * @author   Living Dreams
 * @category Model
 * @package  Customer Search/CS_CUSTOMER
 * @version  1.0.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

require_once 'base-model.php';
require_once 'cs-credit-log.php';
require_once 'cs-search-results.php';

if (!class_exists('CS_CUSTOMER')) {

    class CS_CUSTOMER extends baseModel {

        /**
         * Table identity (requred)
         * @static
         */
        const TABLE_NAME = 'cs_customer';
        const PRIMARY_KEY = 'id';

        /**
         * Table fields
         * @var string 
         */
        public $id;
        public $firstname;
        public $lastname;

        /**
         * Create Table
         * @global type $wpdb
         */
        public static function createTable() {
            //Create table
            global $wpdb;
            $table_name = $wpdb->prefix . self::TABLE_NAME;
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE IF NOT EXISTS $table_name (
    id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    owner bigint(20) UNSIGNED NOT NULL,
    lastname varchar(64) NOT NULL,
    firstname varchar(64) NOT NULL,
    other_fn_1 varchar(64) NULL,
    other_fn_2 varchar(64) NULL,
    prefix varchar(64) NULL,
    suffix varchar(64) NULL,
    other_ln varchar(64) NULL,
    ssn varchar(64) NULL,
    m_i varchar(64) NOT NULL,
    street_address varchar(255) NULL,
    city varchar(64) NULL,
    state varchar(64) NULL,
    zipcode varchar(64) NULL,
    issue_id mediumint(9) NULL,
    is_dispute bigint(20) NULL,
    status tinyint(1) DEFAULT 0 NOT NULL, 
    created_on datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
    UNIQUE KEY id (id)
  ) $charset_collate;";
            $wpdb->query($sql);
        }

        /**
         * Drop Table
         * @global type $wpdb
         */
        public static function dropTable() {
            global $wpdb;
            $tablename = $wpdb->prefix . self::TABLE_NAME;
            $wpdb->query("DROP TABLE IF EXISTS $tablename");
        }

        /**
         * Handles data query and filter, sorting, and pagination.
         */
        public function prepare_items() {

            $this->_column_headers = array($this->get_columns(), array(), array());

            /** Process bulk action */
            $this->process_bulk_action();

            $per_page = $this->get_items_per_page('customers_per_page', 10);
            $current_page = $this->get_pagenum();
            $condition = " LIMIT $per_page";
            $condition .= " OFFSET " . ( $current_page - 1 ) * $per_page;
            $total_items = count($this->get_results());

            $this->set_pagination_args([
                'total_items' => $total_items, //WE have to calculate the total number of items
                'per_page' => $per_page //WE have to determine how many items to show on a page
            ]);

            $this->items = $this->get_results($condition, ARRAY_A);
        }

        /**
         *  Associative array of columns
         * @return array
         */
        public function get_columns() {
            $columns = [
                'cb' => '<input type="checkbox" />',
                'prefix' => __('Prefix'),
                'firstname' => __('First Name'),
                'suffix' => __('Suffix'),
                'lastname' => __('Last Name'),      
                'm_i' => __('M.I'),
                'city' => __('City'),
                'zipcode' => __('State'),
                'ssn' => __('SSN/FEIN'),
               
                
            ];

            return $columns;
        }

        /**
         * 
         * @param array $item
         * @param string $column_name
         * @return string
         */
        function column_default($item, $column_name) {
            switch ($column_name) {
                case 'prefix':
                case 'firstname':
                case 'suffix':
                case 'lastname':
                case 'm_i': 
                case 'city':  
                case 'zipcode':  
                case 'ssn':
                    return $item[$column_name];
                default:
                    return print_r($item, true); //Show the whole array for troubleshooting purposes
            }
        }

        /**
         * Render the bulk edit checkbox
         * @param array $item
         * @return string
         */
        function column_cb($item) {
            return sprintf(
                    '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']
            );
        }

        function column_prefix($item) {
            // create a nonce
            $delete_nonce = wp_create_nonce('cs_delete_customer');
            $edit_nonce = wp_create_nonce('cs_edit_customer');

            $actions = [
                'edit' => sprintf('<a href="?page=%s&action=%s&customer=%s&_wpnonce=%s">Edit</a>', 'cs-control-panel-add-customers', 'edit', absint($item['id']), $edit_nonce),
                'delete' => sprintf('<a href="#" onclick="deleteCustomer(' . $item['id'] . ')">Delete</a>'),
            ];

            return sprintf('%1$s <span style="color:silver"></span>%2$s',
                    /* $1%s */ $item['prefix'],
                    /* $2%s */ $this->row_actions($actions)
            );
        }

        public function insert() {
            //call before insert data
            if (method_exists($this, 'before_insert'))
                $this->before_insert();
            //if inserted
            if ($this->wpdb->insert($this->table, $this->attributes))
                return $this->wpdb->insert_id;
            $this->error = $this->wpdb->last_error;
            return false;
        }

        /* public function search($firstname) {
          if(!empty($firstname))
          $sql_where ="firstname='$firstname'";

          $results = $this->wpdb->get_results("SELECT * FROM $this->table");
          if($results){
          return $results;
          }
          $this->error = $this->wpdb->last_error;
          return false;
          } */

        public function prepare_items_search($firstname, $lastname, $mi, $streetaddress, $city, $ssn, $state, $zipcode, $issueid) {
            $condition = "";
            $concatinate = "";
            if ((!empty($firstname)) || (!empty($lastname)) || (!empty($mi)) || (!empty($streetaddress)) ||
                    (!empty($city)) || (!empty($state)) || (!empty($zipcode)) || (!empty($issueid)) || (!empty($ssn)) ) {
                $condition = "WHERE ";
                if (!empty($firstname)) {
                    $condition .= " ((firstname = '$firstname') || (other_fn_1 ='$firstname') || (other_fn_2 ='$firstname'))";
                    $concatinate = " AND ";
                }
                if (!empty($lastname)) {
                    $condition .= $concatinate . " ((lastname= '$lastname')|| (other_ln ='$lastname'))";
                    $concatinate = " AND ";
                }
                if (!empty($mi)) {
                    $condition .= $concatinate . " m_i= '$mi' ";
                    $concatinate = " AND ";
                }
                if (!empty($streetaddress)) {
                    $condition .= $concatinate . " street_address= '$streetaddress' ";
                    $concatinate = " AND ";
                }
                if (!empty($city)) {
                    $condition .= $concatinate . " city= '$city' ";
                    $concatinate = " AND ";
                }
                 if (!empty($ssn)) {
                    $condition .= $concatinate . "  ssn = '$ssn' ";
                    $concatinate = " AND ";
                } 
                if (!empty($state)) {
                    $condition .= $concatinate . " state= '$state' ";
                    $concatinate = " AND ";
                }
                if (!empty($zipcode)) {
                    $condition .= $concatinate . " zipcode= '$zipcode' ";
                    $concatinate = " AND ";
                }
                if (!empty($issueid)) {
                    $condition .= $concatinate . " issue_id= '$issueid' ";
                    $concatinate = " AND ";
                }
            }



            $this->_column_headers = array($this->get_columns(), array(), array());

            /** Process bulk action */
            $this->process_bulk_action();

            $per_page = $this->get_items_per_page('customers_per_page', 10);
            $current_page = $this->get_pagenum();

            $total_items = count($this->get_results($condition));
            $condition .= " LIMIT $per_page";
            $condition .= " OFFSET " . ( $current_page - 1 ) * $per_page;

            $this->set_pagination_args([
                'total_items' => $total_items, //WE have to calculate the total number of items
                'per_page' => $per_page //WE have to determine how many items to show on a page
            ]);

            $this->items = $this->get_results($condition, ARRAY_A);
        }

        public function prepare_frontend_search($firstname, $lastname, $mi, $streetaddress, $city, $ssn, $state, $zipcode) {

            $credit_log = new CS_CREDIT_LOG();
            $search_results = new CS_SEARCH_RESULTS();

            $concatinate = "";
            if ((!empty($firstname)) && (!empty($lastname))) {
                $condition = "WHERE ";
                if (!empty($firstname)) {
                    $condition .= " ((firstname = '$firstname') || (other_fn_1 ='$firstname') || (other_fn_2 ='$firstname'))";
                    $concatinate = " AND ";
                }
                if (!empty($lastname)) {
                    $condition .= $concatinate . " ((lastname= '$lastname')|| (other_ln ='$lastname'))";
                    $concatinate = " AND ";
                }
                if (!empty($mi)) {
                    $condition .= $concatinate . " m_i= '$mi' ";
                    $concatinate = " AND ";
                } if (!empty($streetaddress)) {
                    $condition .= $concatinate . " street_address= '$streetaddress' ";
                    $concatinate = " AND ";
                }
                if (!empty($city)) {
                    $condition .= $concatinate . " city= '$city' ";
                    $concatinate = " AND ";
                }
                 if (!empty($ssn)) {
                    $condition .= $concatinate . " ssn= '$ssn' ";
                    $concatinate = " AND ";
                }                    
                if (!empty($state)) {
                    $condition .= $concatinate . " state= '$state' ";
                    $concatinate = " AND ";
                }
                if (!empty($zipcode)) {
                    $condition .= $concatinate . " zipcode= '$zipcode' ";
                    $concatinate = " AND ";
                }
            }


            global $current_user; // Use global
            get_currentuserinfo(); // Make sure global is set, if not set it.
            $user_id = get_current_user_id(); // Current login user

            if (!user_can($current_user, "Subscriber")) { // Check user object has not got subscriber role
                /* Start get General option */
                $cus_options = get_option('cs_settings');
                $cus_number = $cus_options['cus_number'];
                $point = $cus_number["cs_point_deduct"];
                $description = $cus_number["cs_des_add_search"];

                $results = $this->get_results($condition);

                if ($results) {
                    $result_ids = array();
                    foreach ($results as $res) {
                        $result_ids[] = $res->id;
                    }

                    $result_array = (implode(",", $result_ids));

                    $data = array(
                        'owner_id' => $user_id,
                        'search_terms' => serialize($_REQUEST),
                        'search_results' => $result_array,
                        'created_on' => date("Y-m-d H:i:s")
                    );

                    $search_id = $search_results->save($data);
                }

                $details = array(
                    'business_id' => $user_id,
                    'points' => -$point,
                    'description' => $description,
                    'search_id' => $search_id,
                    'status' => 3,
                    'created_on' => date("Y-m-d H:i:s")
                );

                if ($credit_log->save($details)) {
                    $existing_points = get_user_meta($user_id, '_total_points', true);
                    $full_total_points = (-$point) + $existing_points;
                    update_user_meta($user_id, '_total_points', $full_total_points);
                }
            }

            if ($results) {
                return $results;
            } else {
                return false;
            }
        }

        /**
         * Get single row
         * @param string $attribute
         * @param void $value
         * @return array
         */
        public function get_customer_row($condition) {
            if ($row = $this->wpdb->get_row("SELECT * FROM $this->table WHERE $condition")) {
                $this->set_attributes((array) $row);
                return $row;
            } else
                return false;
        }

        /* Start dispute process */

        public function getUserMeta($business_name) {
            global $wpdb;
            if (!empty($business_name)) {
                $usermeta = $wpdb->get_results("SELECT * FROM $wpdb->usermeta WHERE meta_key = 'business_name' AND meta_value = '$business_name'");
            }
            return $usermeta;
        }

        public function getCustomer($user_id, $first_name, $last_name, $city) {
            global $wpdb;
            $tablename = $wpdb->prefix . self::TABLE_NAME;
            if (!empty($user_id)) {
                $customer = $wpdb->get_results("SELECT * FROM $tablename WHERE owner = '$user_id' AND firstname = '$first_name' AND lastname = '$last_name' AND city = '$city'");
            }
            return $customer;
        }

       

        /* End dispute process */

        /**
         * Update Customer Row
         * @global object $wpdb
         */
        public function update_is_dispute($data) {
            global $wpdb;
            if ($wpdb->update($this->table, array('is_dispute' => $data['is_dispute']), array('id' => $data['id'])))
                return true;
            else
                return false;
        }

    }

}
