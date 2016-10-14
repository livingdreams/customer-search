<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//require_once (CSROOT_DIR . 'models/cs_customer.php');
//require_once (CSROOT_DIR . 'models/cs_issue.php');

/**
 * Project Manager Ajax Callback
 */
if (!function_exists('cs_ajax_callback')) {

    function cs_ajax_callback() {

        if (isset($_REQUEST['method'])) {

            if ($_REQUEST['method'] == 'register') {
                $customer = new CS_CUSTOMER();
                $customer->set_attributes($_REQUEST['data']);

                if ($customer->insert()) {
                    $data['status'] = true;
                    $data['message'] = 'Customer has been registered successfully.';
                } else {
                    $data['status'] = false;
                    $data['message'] = $customer->error;
                }
            }

            if ($_REQUEST['method'] == 'edit') {
                $customer = new CS_CUSTOMER();
                $customer->set_attributes($_REQUEST['data']);

                if ($customer->update()) {
                    $data['status'] = true;
                    $data['message'] = __('Customer has been updated successfully.');
                } else {
                    $data['status'] = false;
                    $data['message'] = $customer->error;
                }
            }

            /* Start Add, Update, Delete process for "issue section" */
            if ($_REQUEST['method'] == 'new_issue') {
                $issue = new CS_ISSUE();
                $issue->set_attributes($_REQUEST['data']);

                if ($issue->insert()) {
                    $data['status'] = true;
                    $data['message'] = 'reload';
                } else {
                    $data['status'] = false;
                    $data['message'] = $issue->error;
                }
            }

            if ($_REQUEST['method'] == 'edit_issue') {
                $issue = new CS_ISSUE();
                $issue->set_attributes($_REQUEST['data']);

                if ($issue->update()) {
                    $data['status'] = true;
                    $data['message'] = 'reload';
                } else {
                    $data['status'] = false;
                    $data['message'] = $issue->error;
                }
            }

            /* Start general setting add section */
            if ($_REQUEST['method'] == 'general') {
                parse_str($_REQUEST['data'], $data);
                $settings = get_option('cs_settings');
                if ($settings) {
                    update_option('cs_settings', $data);
                    $data['status'] = true;
                    $data['message'] = 'Options Saved!';
                } else {
                    $data['status'] = true;
                    $data['message'] = 'Options Added!';
                    add_option('cs_settings', $data);
                }
            }
            /* End general setting add section */

            /* Start frontend customers add process */
            if ($_REQUEST['method'] == 'register_frontend') {
                global $current_user; // Use global
                get_currentuserinfo(); // Make sure global is set, if not set it.
                $user_id = get_current_user_id(); // Current login user

                /* start get General option */
                $cus_options = get_option('cs_settings');
                $cus_number = $cus_options['cus_number'];
                $point = $cus_number["cs_point_add"];
                $description = $cus_number["cs_des_add"];
                /* end get General option */

                if (!user_can($current_user, "Subscriber")) { // Check user object has not got subscriber role
                    $customer = new CS_CUSTOMER();
                    $customer->set_attributes($_REQUEST['data']);

                    $member_id = $customer->insert();
                    if ($member_id) {
                        $details = array(
                            'business_id' => $user_id,
                            'members_id' => $member_id,
                            'points' => $point,
                            'description' => $description,
                            'status' => 2,
                            'created_on' => date("Y-m-d H:i:s")
                        );

                        $log = new CS_CREDIT_LOG();
                        if ($log->save($details)) {
                            $existing_points = get_user_meta($user_id, '_total_points', true);
                            $full_total_points = $point + $existing_points;
                            update_user_meta($user_id, '_total_points', $full_total_points);
			    
                            $data['status'] = true;
                            $data['message'] = 'Customer has been registered successfully.';
                        }
                    } else {
                        $data['status'] = false;
                        $data['message'] = $customer->error;
                    }
                }
            }


            /* End frontend customers add process */

            /* Start frontend customers edit process */
            if ($_REQUEST['method'] == 'edit_frontend') {
                $customer = new CS_CUSTOMER();
                $customer->set_attributes($_REQUEST['data']);

                if ($customer->update()) {
                    $data['status'] = true;
                    $data['message'] = __('Customer has been updated successfully.');
                } else {
                    $data['status'] = false;
                    $data['message'] = $customer->error;
                }
            }
            /* End frontend customer edit process */


            /* Start dispute process */
            if ($_REQUEST['method'] == 'cus_dispute') {
                $customer_dispute = new CS_DISPUTE_ENTRY();
                $customer_dispute->set_attributes($_REQUEST['data']);
                parse_str($_REQUEST['data'], $data);

                $business_name = $data['cus_business_name'];
                $first_name    = $data['cus_first_name'];
                $last_name     = $data['cus_last_name'];
                $issue         = $data['issue_id'];
                $city          = $data['cus_city'];
                $mailing_addrs = $data['cus_mailing_address'];
                $email_addrs   = $data['cus_email_address'];
                $phone_num     = $data['cus_phone'];

                $customer = new CS_CUSTOMER();
                $match_user_meta = $customer->getUserMeta($business_name);

                if ((!empty($match_user_meta)) && (!empty($first_name)) && (!empty($last_name)) && (!empty($city))) {
                    $user_id = $match_user_meta[0]->user_id;
                    $cus_details = $customer->getCustomer($user_id, $first_name, $last_name, $city);


                    if (!empty($user_id)) {
                        $user_details = get_userdata($user_id);
                        
                        $member_details = get_user_by('id', $user_id);
                        $email = $member_details->user_email;



                        if ((!empty($match_user_meta)) && (!empty($cus_details))) {
                            if ((count($match_user_meta) > 0) && (count($cus_details) > 0)) {
                                $db_business_name = $match_user_meta[0]->meta_value;
                                $exact_match_business_name = strcmp($db_business_name, $business_name);

                                if ($exact_match_business_name == (int) 0) {

                                    $details = array(
                                        'lastname' => $last_name,
                                        'firstname' => $first_name,
                                        'businessname' => $business_name,
                                        'owner' => $user_id,
                                        'issue_id' => $issue,
                                        'city' => $city,
                                        'mailingaddress' => $mailing_addrs,
                                        'emailaddress' => $email_addrs,
                                        'phonenumber' => $phone_num,
                                        'created_on' => date("Y-m-d"),
                                        'to_be_deleted' => date('Y-m-d', strtotime("+15 days")),
                                    );

                                    $condition = "lastname = '$last_name' AND firstname = '$first_name' AND businessname = '$business_name' AND city = '$city' AND mailingaddress = '$mailing_addrs' AND emailaddress = '$email_addrs' AND phonenumber = '$phone_num' AND status = 0";

                                    $is_added = $customer_dispute->get_dispute_row($condition);
                                    if ($is_added == false) {
                                        $dispute_id = $customer_dispute->save($details);
                                        if ($dispute_id) {

                                            $data_dispute = array(
                                                'id' => $cus_details[0]->id,
                                                'is_dispute' => $dispute_id,
                                            );

                                            $customer->update_is_dispute($data_dispute); //Update customer table entry
                                            $data['status'] = 2;
                                            $data['message'] = "Thank you. An e-mail has been sent to the Business Contact person for further actions";
                                            
                                            //$message = "Hello World";
                                            ob_start();
                                            include('email-templates/notify-dispute.php');
                                            $message = ob_get_clean();
                                            sendEmail($email, 'Dispute an entry', $message);
                                            ob_end_clean();
                                        } else {
                                            $data['status'] = false;
                                            $data['message'] = "An error";
                                        }
                                    } else {
                                        $data['status'] = false;
                                        $data['message'] = "Already you have added a dispute";
                                    }
                                } else {
                                    $data['status'] = false;
                                    $data['message'] = "No matching record found";
                                }
                            } else {
                                $data['status'] = false;
                                $data['message'] = "No matching record found";
                            }
                        }
                    }
                } else {
                    $data['status'] = false;
                    $data['message'] = "No matching record found";
                }
            }
            /* End dispute process */


            echo json_encode($data);
            wp_die();
        }
    }

}
add_action('wp_ajax_cs_ajax_action', 'cs_ajax_callback');
add_action('wp_ajax_nopriv_cs_ajax_action', 'cs_ajax_callback');

/* Start delete issue process */

function deleteIssue() {
    $id = $_POST['id'];
    if (!empty($id)) {
        $issue = new CS_ISSUE();
        if ($issue->delete($id)) {
            $data['status'] = 'success';
            $data['message'] = 'Issue has been deleted successfully.';
        }
    }
    echo json_encode($data);
    wp_die();
}

add_action('wp_ajax_delete_issue', 'deleteIssue');
add_action('wp_ajax_nopriv_delete_issue', 'deleteIssue');
/* End delete issue process */

/* Start delete customer process */

function deleteCustomer() {
    $id = $_POST['id'];
    if (!empty($id)) {
        $cus = new CS_CUSTOMER();
        if ($cus->delete($id)) {
            $data['status'] = 'success';
            $data['message'] = 'Customer has been deleted successfully.';
        }
    }
    echo json_encode($data);
    wp_die();
}

add_action('wp_ajax_delete_customer', 'deleteCustomer');
add_action('wp_ajax_nopriv_delete_customer', 'deleteCustomer');
/* End delete customer process */




/* Start delete customer process */

function verifyCustomer() {
    $id = $_POST['id'];
    $dispute_id = $_POST['dispute_id'];
    if ((!empty($id)) && (!empty($dispute_id))) {
        $cus = new CS_CUSTOMER();
        $dispute = new CS_DISPUTE_ENTRY();

        $data = array(
            'id' => $_POST['id'],
            'is_dispute' => NULL,
        );

        $details = array(
            'status' => 1, //verify status
            'id' => $dispute_id,
        );

        if ($dispute->update_row($details)) {
            if ($cus->update_is_dispute($data)) {
                $data['status'] = 'success';
                $data['message'] = 'Customer verified.';
            }
        }
    }
    echo json_encode($data);
    wp_die();
}

add_action('wp_ajax_verify_customer', 'verifyCustomer');
add_action('wp_ajax_nopriv_verify_customer', 'verifyCustomer');
/* End delete customer process */

//The function
function pagination($totalposts, $p, $lpm1, $prev, $next) {
    $adjacents = 3;
    if ($totalposts > 1) {
        $pagination .= "<center><div>";
        //previous button
        if ($p > 1)
            $pagination.= "<a href=\"?pg=$prev\"><< Previous</a> ";
        else
            $pagination.= "<span class=\"disabled\"><< Previous</span> ";
        if ($totalposts < 7 + ($adjacents * 2)) {
            for ($counter = 1; $counter <= $totalposts; $counter++) {
                if ($counter == $p)
                    $pagination.= "<span class=\"current\">$counter</span>";
                else
                    $pagination.= " <a href=\"?pg=$counter\">$counter</a> ";
            }
        }elseif ($totalposts > 5 + ($adjacents * 2)) {
            if ($p < 1 + ($adjacents * 2)) {
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                    if ($counter == $p)
                        $pagination.= " <span class=\"current\">$counter</span> ";
                    else
                        $pagination.= " <a href=\"?pg=$counter\">$counter</a> ";
                }
                $pagination.= " ... ";
                $pagination.= " <a href=\"?pg=$lpm1\">$lpm1</a> ";
                $pagination.= " <a href=\"?pg=$totalposts\">$totalposts</a> ";
            }
            //in middle; hide some front and some back
            elseif ($totalposts - ($adjacents * 2) > $p && $p > ($adjacents * 2)) {
                $pagination.= " <a href=\"?pg=1\">1</a> ";
                $pagination.= " <a href=\"?pg=2\">2</a> ";
                $pagination.= " ... ";
                for ($counter = $p - $adjacents; $counter <= $p + $adjacents; $counter++) {
                    if ($counter == $p)
                        $pagination.= " <span class=\"current\">$counter</span> ";
                    else
                        $pagination.= " <a href=\"?pg=$counter\">$counter</a> ";
                }
                $pagination.= " ... ";
                $pagination.= " <a href=\"?pg=$lpm1\">$lpm1</a> ";
                $pagination.= " <a href=\"?pg=$totalposts\">$totalposts</a> ";
            }else {
                $pagination.= " <a href=\"?pg=1\">1</a> ";
                $pagination.= " <a href=\"?pg=2\">2</a> ";
                $pagination.= " ... ";
                for ($counter = $totalposts - (2 + ($adjacents * 2)); $counter <= $totalposts; $counter++) {
                    if ($counter == $p)
                        $pagination.= " <span class=\"current\">$counter</span> ";
                    else
                        $pagination.= " <a href=\"?pg=$counter\">$counter</a> ";
                }
            }
        }
        if ($p < $counter - 1)
            $pagination.= " <a href=\"?pg=$next\">Next >></a>";
        else
            $pagination.= " <span class=\"disabled\">Next >></span>";
        $pagination.= "</center>\n";
    }
    return $pagination;
}

/*
add_filter('cron_schedules', 'cs_cron_schedule');

function cs_cron_schedule($schedules) {

    $schedules['fifteendays'] = array(
        'interval' => 86400, // Every 15 days 1296000
        'display' => __('Every 15 days'),
    );

    return $schedules;
}

add_action('wp', 'setup_schedule');

function setup_schedule() {
    if (!wp_next_scheduled('fifteen_days_pruning')) {
        wp_schedule_event(time(), 'fifteendays', 'fifteen_days_pruning');
    }
}
 */

// schedule the feedburner_refresh event only once
if( !wp_next_scheduled( 'feedburner_refresh' ) ) {
   wp_schedule_event( time(), 'daily', 'feedburner_refresh' );
}
 

add_action( 'feedburner_refresh', 'update_rss_subscriber_count' );
function update_rss_subscriber_count() {
   $issue = new CS_ISSUE();
    $data  = array('issue_text'=>'This is a cronjob', 'status'=>1);
    $issue->set_attributes($data);
    $issue->insert();
}


function delete_is_not_verified_customers() {
    $customer_obj = new CS_CUSTOMER();
    $dispute_obj = new CS_DISPUTE_ENTRY();
    $date = date('Y-m-d');

    $condition = "WHERE status = 0 AND to_be_deleted <= '$date'";
    $disputes = $dispute_obj->get_results($condition);


    if ($disputes) {
        foreach ($disputes as $dispute) {

            $condition_cus = "WHERE status = 1 AND is_dispute = $dispute->id";
            $customers = $customer_obj->get_results($condition_cus);
            if ($customers) {

                foreach ($customers as $customer) {

                    $customer_obj->delete($customer->id);
                    $details = array(
                        'status' => 2, //expired status
                        'id' => $dispute->id,
                    );
                    $dispute_obj->update_row($details);
                }
            }
        }
    }
}

add_action('fifteen_days_pruning', 'delete_is_not_verified_customers');
//add_action('mycronjob', 'delete_is_not_verified_customers');


/**
 * Send php mail message
 * @param string $to
 * @param string $subject
 * @param string $message
 * @return boolean
 */
function sendEmail($to, $subject, $message) {
    //filter email content type
    add_filter('wp_mail_content_type', 'setHtmlContentType');

    $headers = 'From: Bizware <webmaster@lankabird.com>' . "\r\n" .
            'Reply-To: noreply@lankabird.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

    if (wp_mail($to, $subject, $message, $headers)) {
        remove_filter('wp_mail_content_type', 'setHtmlContentType');
        return true;
    } else {
        return false;
    }
}

//set email content type
function setHtmlContentType() {
    return 'text/html';
}
