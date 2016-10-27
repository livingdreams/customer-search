<?php

/**
 * CS_ISSUE Class.
 *
 * @author   Living Dreams
 * @category Model
 * @package  Customer Search/CS_ISSUE
 * @version  1.0.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

require_once 'base-model.php';

if (!class_exists('CS_ISSUE')) {

    class CS_ISSUE extends baseModel {
        /**
         * Table identity (requred)
         * @static
         */
        const TABLE_NAME = 'cs_issue';
        const PRIMARY_KEY = 'id';

        /**
         * Table fields
         * @var string 
         */
         public $id;
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
    id int(11) NOT NULL AUTO_INCREMENT,
    issue_text text NOT NULL,
    status tinyint(1) DEFAULT 0 NOT NULL, 
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

            $per_page = $this->get_items_per_page('issues_per_page', 10);
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
                'id' => __('ID'),
                'issue_text' => __('Issue'),
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
                case 'id':
                case 'issue_text':
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
        
         /**
         * Render the bulk edit
         * @param array $item
         * @return string
         */
        function column_id($item) {
            // create a nonce
            $edit_nonce   = wp_create_nonce('cs_edit_issue');
            $delete_nonce = wp_create_nonce('cs_delete_issue');

            $actions = [
                'edit'   => sprintf('<a href="?page=%s&action=%s&edit_id=%s&_wpnonce=%s">Edit</a>', 'cs-control-panel-issues', 'edit_issue', absint($item['id']), $edit_nonce),
                'delete' => sprintf('<a href="#" onclick="deleteIssue(' . $item['id'] .')">Delete</a>'),
            ];

            return sprintf('%1$s <span style="color:silver"></span>%2$s',
                    /* $1%s */ $item['id'],
                    /* $2%s */ $this->row_actions($actions)
            );
        }
        
        
    

    }
    
}
