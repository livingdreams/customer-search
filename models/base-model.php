<?php

/**
 * baseModel Class.
 *
 * @author   Living Dreams
 * @category Model
 * @package  Customer Search/baseModel
 * @version  1.0.0
 */
require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
if (!class_exists('baseModel')) {

    class baseModel extends WP_List_Table {

        /**
         * post attributes
         * @var array 
         */
        public $attributes = array();

        /**
         * error message
         * @var string 
         */
        public $error = '';

        /**
         * wpdb globle object
         * @var object 
         */
        public $wpdb;

        /**
         * Table identiry
         * @var string 
         */
        public $table;
        public $primarykey;

        /**
         * Set wpdb object and table name
         * @global type $wpdb
         */
        public function __construct() {
            global $wpdb;
            $this->wpdb = $wpdb;
            $this->table = $this->wpdb->prefix . static::TABLE_NAME;
            $this->primarykey = static::PRIMARY_KEY;
            if (is_admin()){
                parent::__construct([
                    'singular' => __(static::TABLE_NAME), //singular name of the listed records
                    'plural' => __(static::TABLE_NAME . "s"), //plural name of the listed records
                    'ajax' => false //should this table support ajax?
                ]);
            }
        }

        /**
         * Set post attributes before insert
         * @param void $data
         */
        public function set_attributes($data) {  
            if (empty($data))
                exit();
            if (is_array($data))
                $this->attributes = $data;
            else
                parse_str($data, $this->attributes);
            foreach ($this->attributes as $name => $value)
                if (property_exists($this, $name))
                    $this->$name = $value;
        }

        /**
         * Insert into db
         * @return boolean
         */
        public function insert() {
            //call before insert data
            if (method_exists($this, 'before_insert'))
                $this->before_insert();
            //if inserted
            if ($this->wpdb->insert($this->table, $this->attributes))
                return true;
            $this->error = $this->wpdb->last_error;
            return false;
        }

        /**
         * Insert into db
         * @return boolean
         */
        public function update() {
            $pk = $this->primarykey;
            if ($this->wpdb->update($this->table, $this->attributes, array($pk => $this->$pk)))
                return true;
            $this->error = $this->wpdb->last_error;
            return false;
        }

        /**
         * Check for duplicates
         * @param string $attribute
         * @param void $value
         * @return boolean
         */
        public function is_duplicate($attribute, $value) {
            return $this->get_row($attribute, $value) ? true : false;
        }

        /**
         * Get single row
         * @param string $attribute
         * @param void $value
         * @return array
         */
        public function get_row($attribute, $value) {
            if ($row = $this->wpdb->get_row("SELECT * FROM $this->table WHERE $attribute = '$value'")) {
                $this->set_attributes((array) $row);
                return $row;
            }
            $this->error = __("$attribute '$value' is not found");
            return false;
        }

        /**
         * Get all results
         * @return array
         */
        public function get_results($condition = '', $output = OBJECT) {
            //$condition = $condition != '' ? 'WHERER ' . $condition : '';
            if ($result = $this->wpdb->get_results("SELECT * FROM $this->table $condition", $output))
                return $result;
            $this->error = __("No results found");
            return false;
        }
        
        /**
         * Delete feed data
         * @global object $wpdb
        */
        public function delete($id){
            global $wpdb;
            //$table_name = $wpdb->prefix . self::TABLE_NAME;
            if($wpdb->delete( $this->table, array( 'id' => $id ) ) )
                return true;
            else
                return false;
        }
        
        
        
        /**
         * Get all results
         * @return array
         */
        public function get_results_all($condition = '') {
            //$condition = $condition != '' ? 'WHERER ' . $condition : '';
            if ($result = $this->wpdb->get_results("SELECT * FROM $this->table $condition"))
                return $result;
            $this->error = __("No results found");
            return false;
        }
        
        
        
    }

}