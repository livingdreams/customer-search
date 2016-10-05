<?php
$customers = new CS_CUSTOMER();
$issue = new CS_ISSUE();

$user_id = get_current_user_id(); // Current login user
$existing_points = get_user_meta($user_id, '_total_points', true);

$cus_options = get_option('cs_settings');
$cus_number = $cus_options['cus_number'];
$point = $cus_number["cs_point_deduct"];

if (isset($_REQUEST)) {
    if ((!empty($_REQUEST['firstname'])) && (!empty($_REQUEST['lastname']))) {
        $results = $customers->prepare_frontend_search($_REQUEST['firstname'], $_REQUEST['lastname'], $_REQUEST['m_i'], $_REQUEST['streetaddress'], $_REQUEST['city'], $_REQUEST['state'], $_REQUEST['zipcode']);
        if (!$results) {
            ?>
            <div class="error errorMessage">No Results Found</div>
            <?php
        }
    }
}
if (($existing_points > 0) && ($existing_points >= $point)) {
    ?>
    <div class="cs_wrapper">
        <form id="posts-filter" method="post" action="">
            <div class="et_pb_row">
                <div class="et_pb_column">
                    <h2>Search Customers:</h2>
                </div>
            </div>
            
            <div class="">	

                 <div class="et_pb_row">
                     <div class="et_pb_column et_pb_column_1_2">
                         <div class="">
                             <label class="required-cs">First Name</label>
                             <input id="post-search-input" type="text" value="" name="firstname" required="required" size="30">
                         </div>
                     </div>

                     <div class="et_pb_column et_pb_column_1_2">
                         <div class="">
                             <label class="required-cs">Last Name</label>
                             <input id="post-search-input" type="text" value=""  name="lastname" required="required" size="30">
                         </div>
                     </div> 
                 </div> 

                <div class="et_pb_row">
                     <div class="et_pb_column et_pb_column_1_2">
                         <div class="">
                             <label class="">M.I</label>
                             <input id="post-search-input" type="text" value="" name="m_i" size="30">
                         </div>
                     </div>

                     <div class="et_pb_column et_pb_column_1_2">
                         <div class="">
                             <label class="">Street Address</label>
                             <textarea id="post-search-input" type="textarea" name="streetaddress" cols="53" style="resize:none;" rows="2"></textarea>
                         </div>
                     </div> 
                 </div> 

                <div class="et_pb_row">
                     <div class="et_pb_column et_pb_column_1_2">
                         <div class="">
                             <label class="">City</label>
                             <input id="post-search-input" type="text" value=""  name="city" size="30">
                         </div>
                     </div>

                     <div class="et_pb_column et_pb_column_1_2">
                         <div class="">
                             <label class="">State</label>
                             <select name="state"  id="post-search-input">
                                     <option  value="">Select State</option>
                                     <?php
                                     $usStates = array(
                                         "AL" => "Alabama",
                                         "AK" => "Alaska",
                                         "AZ" => "Arizona",
                                         "AR" => "Arkansas",
                                         "CA" => "California",
                                         "CO" => "Colorado",
                                         "CT" => "Connecticut",
                                         "DE" => "Delaware",
                                         "FL" => "Florida",
                                         "GA" => "Georgia",
                                         "HI" => "Hawaii",
                                         "ID" => "Idaho",
                                         "IL" => "Illinois",
                                         "IN" => "Indiana",
                                         "IA" => "Iowa",
                                         "KS" => "Kansas",
                                         "KY" => "Kentucky",
                                         "LA" => "Louisiana",
                                         "ME" => "Maine",
                                         "MD" => "Maryland",
                                         "MA" => "Massachusetts",
                                         "MI" => "Michigan",
                                         "MN" => "Minnesota",
                                         "MS" => "Mississippi",
                                         "MO" => "Missouri",
                                         "MT" => "Montana",
                                         "NE" => "Nebraska",
                                         "NV" => "Nevada",
                                         "NH" => "New Hampshire",
                                         "NJ" => "New Jersey",
                                         "NM" => "New Mexico",
                                         "NY" => "New York",
                                         "NC" => "North Carolina",
                                         "ND" => "North Dakota",
                                         "OH" => "Ohio",
                                         "OK" => "Oklahoma",
                                         "OR" => "Oregon",
                                         "PA" => "Pennsylvania",
                                         "RI" => "Rhode Island",
                                         "SC" => "South Carolina",
                                         "SD" => "South Dakota",
                                         "TN" => "Tennessee",
                                         "TX" => "Texas",
                                         "UT" => "Utah",
                                         "VT" => "Vermont",
                                         "VA" => "Virginia",
                                         "WA" => "Washington",
                                         "WV" => "West Virginia",
                                         "WI" => "Wisconsin",
                                         "WY" => "Wyoming"
                                     );

                                     foreach ($usStates as $state) {
                                         $state_name = $state;
                                         ?>
                                         <option value="<?php echo $state_name; ?>"><?php echo $state_name; ?></option>
                                         <?php
                                     }
                                     ?>
                                 </select>
                         </div>
                     </div> 
                 </div> 

                <div class="et_pb_row">
                    <input class="button" type="submit" value="Search Customers" id="search-submit">
                 </div> 

            </div>
       </form>

        <div id = "poststuff">
            <?php
            //if (isset($_REQUEST)) {
            //if ((!empty($_REQUEST['firstname'])) && (!empty($_REQUEST['lastname']))) {
            // $results = $customers->prepare_frontend_search($_REQUEST['firstname'], $_REQUEST['lastname'], $_REQUEST['m_i'], $_REQUEST['streetaddress'], $_REQUEST['city'], $_REQUEST['state'], $_REQUEST['zipcode']);
            if ($results) {
                ?>
                <table>
                    <thead>
                    <th>Name</th>
                    <th>M.I</th>
                    <th>Address</th>
                    <th>Issue</th>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $res) { ?>
                            <tr>
                                <td><?php echo $res->firstname . ' ' . $res->lastname; ?></td>
                                <td><?php echo $res->m_i; ?></td>
                                <td><?php echo $res->street_address . ' ' . $res->city . ' ' . $res->zipcode; ?></td>
                                <td>
                                    <?php
                                    if (!empty($res->issue_id)) {
                                        $issue_text = $issue->get_row('id', $res->issue_id);
                                        if ($issue_text) {
                                            echo $issue_text->issue_text;
                                        }
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <?php
            }
            // }
            // }
            ?>
            <br class="clear">
        </div>
    </div>
<?php } else {
    ?>
    <div class="error buy-points-message">Insufficient credits to search. Please buy points</div> 
<?php }
?>

