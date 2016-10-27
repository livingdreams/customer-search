<?php
$customer = new CS_CUSTOMER();
if (isset($_GET['customer'])) {
    $customer_row = $customer->get_row('id', $_GET['customer']);
}
?>


<div class="cs_wrapper">

    <h1><?= $_GET['action'] == 'edit' ? 'Edit' : 'New' ?> Client</h1>
    <hr>

    <div class="cs_block">
        <div class="errorMessage"></div>
        <div class="successMessage"></div>
    </div>

    <form id="customer-reg" method="<?= $_GET['action'] == 'edit' ? 'edit' : 'register' ?>">
        <?php if ($_GET['action'] == 'edit'): ?>
            <input type="hidden" id="id" name="id" value="<?= $customer_row->id ?>"/>
        <?php else: ?>
            <input type="hidden" id="isActive" name="status" value="1"/>
            <input type="hidden" id="createdOn" name="created_on" value="<?= date('Y-m-d h:i') ?>"/>
            <input type="hidden" id="owner" name="owner" value="<?= get_current_user_id() ?>"/>
        <?php endif; ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th><label class="required">Prefix</label></th>
                    <td><input class="regular-text code" name="prefix" type="text" id="prefix"  value="<?= $customer_row->prefix; ?>"/></td>
                </tr>
                <tr>
                    <th><label class="required">First Name</label></th>
                    <td><input class="regular-text code" name="firstname" type="text" id="firstname" required="required" value="<?= $customer_row->firstname; ?>"/></td>
                </tr>
                 <tr>
                    <th><label class="required">Suffix</label></th>
                    <td><input class="regular-text code" name="suffix" type="text" id="suffix"  value="<?= $customer_row->suffix; ?>"/></td>
                </tr>
                <tr>
                    <th><label class="required">Last Name</label></th>
                    <td><input class="regular-text" name="lastname" type="text" id="lastname" required="required" value="<?= $customer_row->lastname; ?>"/></td>
                </tr>
                <tr>
                    <th><label class="required">Other First Name 1</label></th>
                    <td><input class="regular-text code" name="other_fn_1" type="text" id="other_fn_1"  value="<?= $customer_row->other_fn_1; ?>"/></td>
                </tr>
                <tr>
                    <th><label class="required">Other First Name 2</label></th>
                    <td><input class="regular-text code" name="other_fn_2" type="text" id=" other_fn_2"  value="<?= $customer_row->other_fn_2; ?>"/></td>
                </tr>
                <tr>
                    <th><label class="required">Other Last Name</label></th>
                    <td><input class="regular-text code" name="other_ln" type="text" id="other_ln"  value="<?= $customer_row->other_ln; ?>"/></td>
                </tr>
                <tr>
                    <th><label class="required">Last 4 digits of SSN /FEIN</label></th>
                    <td><input class="regular-text code" name="ssn" type="text" id="ssn" maxlength="4" pattern="\d{4}"  value="<?= $customer_row->ssn; ?>"/></td>
                </tr>
                <tr>
                    <th><label>M.I</label></th>
                    <td><input class="regular-text" name="m_i" type="text" id="m_i"  value="<?= $customer_row->m_i; ?>"/></td>
                </tr>
                <tr>
                    <th><label>Street Address </label></th>
                    <td><textarea class="regular-text" name="street_address" type="textarea" id="street_address"  rows="5" cols="53" /><?php if(isset($customer_row->street_address)){ echo $customer_row->street_address; } ?></textarea></td>
                </tr>
                <tr>
                    <th><label>City</label></th>
                    <td><input class="regular-text" name="city" type="text" id="city" required="required" value="<?= $customer_row->city; ?>" /></td>
                </tr>
                <tr>
                    <th><label>State</label></th>
                    <td>
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
                        <select name="state"  id="state" required="required">
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
                    </td>
                </tr>
                <tr>
                    <th><label class="required">Zip Code</label></th>
                    <td><input class="regular-text" name="zipcode" type="text" id="zipcode"  value="<?= $customer_row->zipcode; ?>"/></td>
                </tr>
                <tr>
                    <th><label>Issue</label></th>
                    <td>
                        <?php
                        $db_issue_id = $customer_row->issue_id; 
                        ?>
                        <select name="issue_id"  id="issue_id" required="required">
                        <option  value="">Select Issue</option>
                         <?php
                         $issue_result  = new CS_ISSUE();
                         $status        = 0;
                         $condition     = "WHERE status=$status";
                         $issues        = $issue_result->get_results($condition);
                            foreach ($issues as $issue) { 
                                $id   = $issue->id;
                                $issue= $issue->issue_text;
                                ?>
                                <option value="<?php echo $id; ?>"<?php if($db_issue_id == $id){ echo 'selected="selected"';} ?>><?php echo $issue; ?></option>
                            <?php }
                        ?> 
                         </select> 
                    </td>
                </tr>
               
                <tr>
                    <th><label>&nbsp;</label></th>
                    <td>
                        <button id="btnACsubmit"  type="submit" name="btnACsubmit" class="button-primary "><?= $_GET['action'] == 'edit' ? 'Update' : 'Create' ?> Customer</button>
                        <img id="loading" src="<?= admin_url('images/loading.gif') ?>" title="loading" style="display:none;"/>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>