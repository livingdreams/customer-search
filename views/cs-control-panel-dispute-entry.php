<div class="wrap">
    
    <div class="pm_block">
        <div class="errorMessage"></div>
        <div class="successMessage"></div>
    </div>
    
    <form id="customer-reg" class="form-wrap" method="cus_dispute">
        
        <div class="et_pb_row">
            <div class="et_pb_column et_pb_column_1_2">
                <div class="">
                    <label class="required-cs">First Name</label>
                    <input class="regular-text code" name="cus_first_name" type="text" id="cus_first_name" required="required" value=""/>
                </div>
            </div>

            <div class="et_pb_column et_pb_column_1_2">
                <div class="">
                    <label class="required-cs">Last Name</label>
                    <input class="regular-text" name="cus_last_name" type="text" id="cus_last_name" required="required" value=""/>
                </div>
            </div> 
        </div> 
        
        <div class="et_pb_row">
            <div class="et_pb_column et_pb_column_1_2">
                <div class="">
                    <label class="required-cs">Your city</label>
                    <input class="regular-text code" name="cus_city" type="text" id="cus_city" required="required" value=""/>
                </div>
            </div>

            <div class="et_pb_column et_pb_column_1_2">
                <div class="">
                    <label class="">Mailing Address</label>
                    <textarea class="regular-text" name="cus_mailing_address" type="textarea" id="cus_mailing_address"  style="resize:none;" rows="2" cols="53"></textarea>
                </div>
            </div> 
        </div> 
        
        <div class="et_pb_row">
            <div class="et_pb_column et_pb_column_1_2">
                <div class="">
                    <label class="">Email Address</label>
                    <input class="regular-text code" name="cus_email_address" type="text" id="cus_email_address" value=""/>
                </div>
            </div>

            <div class="et_pb_column et_pb_column_1_2">
                <div class="">
                    <label class="">Phone Number</label>
                    <input class="regular-text code" name="cus_phone" type="text" id="cus_phone" value=""/>
                </div>
            </div> 
        </div> 
        
        <div class="et_pb_row">
            <div class="et_pb_column et_pb_column_1_2">
                <div class="">
                    <label class="required-cs">Business Name</label>
                    <input class="regular-text code" name="cus_business_name" type="text" id="cus_business_name" required="required" value=""/>
                </div>
            </div>

            <div class="et_pb_column et_pb_column_1_2">
                <div class="">
                    <label class="">Issue</label>
                     <select name="issue_id"  id="issue_id">
                     <option  value="">Select Issue</option>
                     <?php
                     $issue_result  = new CS_ISSUE();
                     $issues        = $issue_result->get_results();
                        foreach ($issues as $issue) { 
                            $id   = $issue->id;
                            $issue= $issue->issue_text;
                            ?>
                            <option value="<?php echo $id; ?>"><?php echo $issue; ?></option>
                     <?php }
                     ?> 
                     </select> 
                </div>
            </div> 
        </div> 
        
        <div class="et_pb_row">
            <div class="et_pb_column et_pb_column_1_2">
                <div class="">
                    <button id="submit"  type="submit" name="submit" class="button-primary ">Submit</button>
                    <img id="loading" src="<?= admin_url('images/loading.gif') ?>" title="loading" style="display:none;"/>
                </div>
            </div>
        </div> 
        
    </form>
</div>