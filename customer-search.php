<?php

/*
  Plugin Name: Customer Search
  Plugin URI: livingdreams.lk
  Description: Adding customers by members and search for the releavant customers
  Author: Living Dreams
  Version: 1.0.0
  Author URI: livingdreams.lk
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

//define plugins directory
if (!defined('CSROOT_DIR')) {
    define('CSROOT_DIR', plugin_dir_path(__FILE__));
}

require_once ('admin/functions.php');
require_once ('models/cs-customer.php');
require_once ('models/cs-issue.php');
require_once ('models/cs-credit-log.php');
require_once ('models/cs-search-results.php');
require_once ('models/cs-dispute-entry.php');

function create_customer_tables_on_activation() {
    CS_CUSTOMER::createTable();
    CS_ISSUE::createTable();
    CS_CREDIT_LOG::createTable();
    CS_SEARCH_RESULTS::createTable();
    CS_DISPUTE_ENTRY::createTable();
}

register_activation_hook(__FILE__, 'create_customer_tables_on_activation');



add_action('admin_menu', 'CustomerMenu');
if (!function_exists('CustomerMenu')) {

    function CustomerMenu() {
        //add_menu_page('Customers', 'Customers', 'manage_options', 'cs-control-panel', IncludePhpFile_CS);
        //add_submenu_page('cs-control-panel', 'General', 'General', 'manage_options', 'cs-control-panel-general', IncludePhpFile_CS);
        add_menu_page('Customers', 'Customer Search Settings', 'manage_options', 'cs-control-panel-general', IncludePhpFile_CS);
        add_submenu_page('cs-control-panel-general', 'Customers', 'Customers', 'manage_options', 'cs-control-panel', IncludePhpFile_CS);
        add_submenu_page('cs-control-panel-general', 'Search Customers', 'Search Customers', 'manage_options', 'cs-control-panel-search', IncludePhpFile_CS);
        add_submenu_page('cs-control-panel-general', 'Add Issues', 'Add Issues', 'manage_options', 'cs-control-panel-issues', IncludePhpFile_CS);
        add_submenu_page('cs-control-panel-general', 'Add Customers', 'Add Customers', 'manage_options', 'cs-control-panel-add-customers', IncludePhpFile_CS);
    }

}


/* if (!function_exists('CustomerControlPanel')) {

  function CustomerControlPanel() {
  ?>
  <h2>Customers</h2>
  <?php
  }

  } */



if (!function_exists('IncludePhpFile_CS')) {

    function IncludePhpFile_CS() {
        if (isset($_GET['page'])) {
            $filename = $_GET['page'];
            //if (file_exists($filename))
            require_once("{$filename}.php");
        }
    }

}

if (!function_exists('CustomerSearchScript')) {

    function CustomerSearchScript() {
		$pluginPageArray = array('cs-control-panel-add-customers', 'cs-control-panel-issues', 'cs-control-panel-general', 'cs-control-panel');

        if (isset($_GET['page']) && in_array($_GET['page'], $pluginPageArray)) {
            wp_enqueue_script('cs-admin-scripts', plugins_url('assets/js/admin.js', __FILE__), array('jquery'));
            wp_enqueue_style('cs-admin-styles', plugins_url('assets/css/styles.css', __FILE__));
        }
    }

}
add_action('admin_enqueue_scripts', 'CustomerSearchScript');

/**
 * Shortcode to add customers
 */
function cs_add_Customer() {
    ob_start();
    if (is_user_logged_in())
        require_once('views/cs-control-panel-add-customers.php');
    $contents = ob_get_clean();
    return $contents;
}

add_shortcode('CS_ADD_CUSTOMER', 'cs_add_Customer');

/**
 * Enqueue styles and scripts
 */
function Customer_Search_FrontScripts() {
    wp_enqueue_style('cs-frontend-styles', plugins_url('assets/css/styles.css', __FILE__));
    wp_enqueue_script('cs-customer-scripts', plugins_url('assets/js/script.js', __FILE__), array('jquery'));
    wp_localize_script('cs-customer-scripts', 'cs_ajax', array('url' => admin_url('admin-ajax.php')));
}

add_action('wp_enqueue_scripts', 'Customer_Search_FrontScripts');


/* Adding custom field to product */

// Display Fields
add_action('woocommerce_product_options_general_product_data', 'woo_add_cs_custom_general_fields');

// Save Fields
add_action('woocommerce_process_product_meta', 'woo_add_cs_custom_general_fields_save');

function woo_add_cs_custom_general_fields() {

    global $woocommerce, $post;

    echo '<div class="options_group">';

    // Custom fields will be created here...
    woocommerce_wp_text_input(
            array(
                'id' => '_point_field',
                'label' => __('Points to be add when purchasing the product', 'woocommerce'),
                'placeholder' => '',
            //'description' => __( 'Enter the custom value here.', 'woocommerce' ) 
            )
    );

    echo '</div>';
}

function woo_add_cs_custom_general_fields_save($post_id) {
    // Text Field
    $woocommerce_text_field = $_POST['_point_field'];
    if (!empty($woocommerce_text_field))
        update_post_meta($post_id, '_point_field', esc_attr($woocommerce_text_field));
}

add_action('woocommerce_order_status_completed', 'adding_customer_points');
/*
 * Do something after WooCommerce sets an order on completed
 */

function adding_customer_points($order_id) {
    // order object (optional but handy)
    $order = new WC_Order($order_id);
    $get_points = 0;
    $total_points = 0;
    // do some stuff here
    $items = $order->get_items();
    foreach ($items as $item) {
        $product_id = $item['product_id'];
        $get_points = get_post_meta($item['product_id'], '_point_field', true);
        /* multiply by points*quantity */
        $total_points += $get_points * $item['qty'];
    }

    $data = array(
        'business_id' => $order->user_id,
        'points' => $total_points,
        'order_id' => $order_id,
        'description' => 'Buy points',
        'status' => 1,
        'created_on' => date("Y-m-d H:i:s")
    );

    $log = new CS_CREDIT_LOG();

    if ($log->save($data)) {
        $existing_points = get_user_meta($order->user_id, '_total_points', true);
        $full_total_points = $total_points + $existing_points;
        update_user_meta($order->user_id, '_total_points', $full_total_points);
    }
}

/**
 * Notice requred plugins
 */
function cs_show_errors() {
    if (!class_exists('WooCommerce'))
        echo '<div class="error"><p>The <a href="https://wordpress.org/plugins/woocommerce/">WooCommerce</a> Plugin is required for Customer Search.</p></div>';
    if (!class_exists('MemberOrder'))
        echo '<div class="error"><p>The <a href="https://wordpress.org/plugins/paid-memberships-pro/">Paid Memberships Pro</a> Plugin is required for Customer Search.</p></div>';
}

add_action('admin_notices', 'cs_show_errors');

/**
 * Shortcode to search customers
 */
function cs_search_customer() {
    ob_start();
    if (is_user_logged_in())
        require_once('views/cs-control-panel-search.php');
    $contents = ob_get_clean();
    return $contents;
}

add_shortcode('CS_SEARCH_CUSTOMER', 'cs_search_customer');

/**
 * Shortcode to display dashboard
 */
function cs_result_dashboard() {
    ob_start();
    if (is_user_logged_in())
        require_once('views/cs-dashboard.php');
    $contents = ob_get_clean();
    return $contents;
}

add_shortcode('CS_RESULT_DASHBOARD', 'cs_result_dashboard');

/**
 * Shortcode to display total points
 */
function cs_total_points() {
    $user_id = get_current_user_id();
    $total_points = get_user_meta($user_id, '_total_points', true);
    if ($total_points)
        return $total_points;
    else
        return 0;
}

add_shortcode('CS_TOTAL_POINTS', 'cs_total_points');

function cs_all_customers() {
    ob_start();
    if (is_user_logged_in())
        require_once('views/cs-all-customers.php');
    $contents = ob_get_clean();
    return $contents;
}

add_shortcode('CS_ALL_CUSTOMERS', 'cs_all_customers');

/**
 * Shortcode to dispute entry
 */
function cs_dispute_entry() {
    ob_start();
    require_once('views/cs-control-panel-dispute-entry.php');
    $contents = ob_get_clean();
    return $contents;
}

add_shortcode('CS_DISPUTE_ENTRY', 'cs_dispute_entry');

/**
 * Shortcode to dispute entry
 */
function cs_credit_log() {
    ob_start();
    require_once('views/cs-credit-log.php');
    $contents = ob_get_clean();
    return $contents;
}

add_shortcode('CS_CREDIT_LOG', 'cs_credit_log');



