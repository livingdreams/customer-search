<?php
$customer = new CS_CUSTOMER();
$user_id = get_current_user_id(); 
if (isset($_GET['customer'])) {
  $customer_row = $customer->get_customer_row('id = '.$_GET['customer'].' AND owner = '.$user_id);
  if($customer_row){
        $visible = true;
  } 
}else{
    $visible = true;
}


if($visible):
?>


<div class="cs_wrapper">
    <div class="et_pb_row">
        <div class="et_pb_column">
            <h2><?= $_GET['action'] == 'edit-customer' ? 'Edit' : 'New' ?> Client</h2>

            <div class="cs_block">
                <div class="errorMessage"></div>
                <div class="successMessage"></div>
            </div>
        </div>
    </div>

    <form id="customer-reg" method="<?= $_GET['action'] == 'edit-customer' ? 'edit_frontend' : 'register_frontend' ?>">
        <?php if ($_GET['action'] == 'edit-customer'): ?>
            <input type="hidden" id="id" name="id" value="<?= $customer_row->id ?>"/>
        <?php else: ?>
            <input type="hidden" id="isActive" name="status" value="1"/>
            <input type="hidden" id="createdOn" name="created_on" value="<?= date('Y-m-d h:i') ?>"/>
            <input type="hidden" id="owner" name="owner" value="<?= get_current_user_id() ?>"/>
        <?php endif; ?>
            
         <div class="">	
             
             <div class="et_pb_row">
                <div class="et_pb_column et_pb_column_1_2">
                    <div class="">
                        <label class="required-cs">First Name</label>
                        <input class="regular-text code" name="firstname" type="text" id="firstname" required="required" value="<?= $customer_row->firstname; ?>"/>
                    </div>
                </div>

                <div class="et_pb_column et_pb_column_1_2">
                    <div class="">
                        <label class="required-cs">Last Name</label>
                        <input class="regular-text" name="lastname" type="text" id="lastname" required="required" value="<?= $customer_row->lastname; ?>"/>
                    </div>
                </div> 
            </div> 
             
             <div class="et_pb_row">
                <div class="et_pb_column et_pb_column_1_2">
                    <div class="">
                        <label class="">M.I</label>
                        <input class="regular-text code" name="m_i" type="text" id="m_i" value="<?= $customer_row->m_i; ?>"/>
                    </div>
                </div>

                <div class="et_pb_column et_pb_column_1_2">
                    <div class="">
                        <label class="">Street Address</label>
                        <textarea class="regular-text" name="street_address" type="textarea" id="street_address"  style="resize:none;" rows="2" cols="53"><?php if(isset($customer_row->street_address)){ echo $customer_row->street_address; } ?></textarea>
                    </div>
                </div> 
            </div> 
             
             <div class="et_pb_row">
                <div class="et_pb_column et_pb_column_1_2">
                    <div class="">
                        <label class="">City</label>
                        <input class="regular-text" name="city" type="text" id="city" value="<?= $customer_row->city; ?>" />
                    </div>
                </div>

                <div class="et_pb_column et_pb_column_1_2">
                    <div class="">
                        <label class="">State</label>
                        <?php
                        //Define the US State Array to be used to generate the STATE select box
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
                        $db_state = $customer_row->state; 
                        ?>
                        <select name="state"  id="state">
                        <option  value="">Select State</option>
                        <?php
                            foreach ($usStates as $state) {
                                $state_name= $state;
                                ?>
                                <option value="<?php echo $state_name; ?>" <?php if($db_state == $state_name){ echo 'selected="selected"';} ?>><?php echo $state_name; ?></option>
                                <?php
                            }
                        ?>
                        </select> 
                    </div>
                </div> 
            </div> 
             
             <div class="et_pb_row">
                <div class="et_pb_column et_pb_column_1_2">
                    <div class="">
                        <label class="required-cs">Zip Code</label>
                        <input class="regular-text" name="zipcode" type="text" id="zipcode" required="required" value="<?= $customer_row->zipcode; ?>"/>
                    </div>
                </div>

                <div class="et_pb_column et_pb_column_1_2">
                    <div class="">
                        <label class="">Issue</label>
                        <?php
                        $db_issue_id = $customer_row->issue_id; 
                        ?>
                        <select name="issue_id"  id="issue_id">
                        <option  value="">Select Issue</option>
                         <?php
                         $issue_result  = new CS_ISSUE();
                         $issues        = $issue_result->get_results();
                            foreach ($issues as $issue) { 
                                $id   = $issue->id;
                                $issue= $issue->issue_text;
                                ?>
                                <option value="<?php echo $id; ?>"<?php if($db_issue_id == $id){ echo 'selected="selected"';} ?>><?php echo $issue; ?></option>
                            <?php }
                        ?> 
                         </select> 
                    </div>
                </div> 
            </div> 
             
             <div class="et_pb_row">
                <div class="et_pb_column et_pb_column_1_2">
                    <div class="">
                        <button id="btnACsubmit"  type="submit" name="btnACsubmit" class="button-primary "><?= $_GET['action'] == 'edit-customer' ? 'Update' : 'Create' ?> Customer</button>
                        <img id="loading" src="<?= admin_url('images/loading.gif') ?>" title="loading" style="display:none;"/>
                    </div>
                </div>
            </div> 
             
         </div>
    </form>
</div>
<?php endif; ?>