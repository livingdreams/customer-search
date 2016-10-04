<?php
$customers = new CS_CUSTOMER();
$issue_result = new CS_ISSUE();
?>
<div class="cs_wrapper">
    <h1>Customer Search</h1>
    <hr>
    <form id="posts-filter" method="get" action="">			
        <p >
            <input type="hidden" name="page" value="cs-control-panel-search" />
            <label class="screen-reader-text" for="post-search-input">Search Customers:</label>
            <input id="post-search-input" type="text" value="" placeholder="First name" name="firstname" size="30">
            <input id="post-search-input" type="text" value="" placeholder="Last name" name="lastname" size="30">
            <input id="post-search-input" type="text" value="" placeholder="M.I" name="m_i" size="30">
            <input id="post-search-input" type="text" value="" placeholder="Street Address " name="streetaddress" size="30">
            <input id="post-search-input" type="text" value="" placeholder="City" name="city" size="30">
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
            <input id="post-search-input" type="text" value="" placeholder="Zip Code" name="zipcode" size="30">

         
            <select name="issue_id"  id="post-search-input">
                <option  value="">Select Issue</option>
                <?php
                $issues = $issue_result->get_results();
                foreach ($issues as $issue) {
                    $id = $issue->id;
                    $issue = $issue->issue_text;
                    ?>
                    <option value="<?php echo $id; ?>"><?php echo $issue; ?></option>
                <?php }
                ?> 
            </select>
                <input class="button" type="submit" value="Search Customers" id="search-submit">
                </p>
                </form>

                <div id = "poststuff">
                    <div id = "post-body" class = "metabox-holder columns-2">
                        <div id = "post-body-content">
                            <div class = "meta-box-sortables ui-sortable">
                                <form method = "post">
                                    <?php
                                    if (isset($_REQUEST['firstname'])) {
                                        $customers->prepare_items_search($_REQUEST['firstname'], $_REQUEST['lastname'], $_REQUEST['m_i'], $_REQUEST['streetaddress'], $_REQUEST['city'], $_REQUEST['state'], $_REQUEST['zipcode'], $_REQUEST['issue_id']);
                                        $customers->display();
                                    }
                                    ?>
                                </form>
                            </div>
                        </div>
                    </div>
                    <br class="clear">
                </div>
                </div>
