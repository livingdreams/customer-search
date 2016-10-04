<?php

/**
 * CS_ISSUE Class.
 *
 * @author   Living Dreams
 * @category Model
 * @package  Customer Search/CS_SEARCH_RESULTS
 * @version  1.0.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

require_once 'base-model.php';

if (!class_exists('CS_SEARCH_RESULTS')) {

    class CS_SEARCH_RESULTS extends baseModel {

        /**
         * Table identity (requred)
         * @static
         */
        const TABLE_NAME = 'cs_search_results';
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
    owner_id int(11) NOT NULL,
    search_terms longtext NOT NULL,
    search_results varchar(255) NULL,
    created_on datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
    UNIQUE KEY id (id)
  ) $charset_collate;";
            $wpdb->query($sql);
        }

        /**
         * Save feed data
         * @global object $wpdb
         */
        public function save($data) {
            global $wpdb;
            $table_name = $wpdb->prefix . self::TABLE_NAME;
            if ($wpdb->insert($table_name, $data))
                return $wpdb->insert_id;
            else
                return false;
        }
        
    }

}
