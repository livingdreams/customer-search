<?php

/**
 * CS_ISSUE Class.
 *
 * @author   Living Dreams
 * @category Model
 * @package  Customer Search/CS_DISPUTE_ENTRY
 * @version  1.0.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

require_once 'base-model.php';

if (!class_exists('CS_DISPUTE_ENTRY')) {

    class CS_DISPUTE_ENTRY extends baseModel {

        /**
         * Table identity (requred)
         * @static
         */
        const TABLE_NAME = 'cs_dispute_entry';
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
    lastname varchar(64) NOT NULL,
    firstname varchar(64) NOT NULL,
    businessname varchar(64) NOT NULL,
    owner bigint(20) NULL,
    issue_id int(11) NULL,
    city varchar(64) NOT NULL,
    mailingaddress varchar(64) NOT NULL,
    emailaddress varchar(64) NOT NULL,
    phonenumber varchar(64) NOT NULL,
    status tinyint(1) DEFAULT 0 NOT NULL, 
    created_on date DEFAULT '0000-00-00' NOT NULL,
    to_be_deleted date DEFAULT '0000-00-00' NOT NULL,
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
        
        /**
         * Get single row
         * @param string $attribute
         * @param void $value
         * @return array
         */
        public function get_dispute_row($condition) {
            if ($row = $this->wpdb->get_row("SELECT * FROM $this->table WHERE $condition")) {
                return $row;
            }
            else
                return false;
        }
        
        public function update_row($data) {
            global $wpdb;
            if($wpdb->update($this->table, array('status' => $data['status']), array('id' => $data['id'])))
                return true;
            else
                return false;
        }

        

        
        
    }

}
