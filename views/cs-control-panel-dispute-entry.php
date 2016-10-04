<div class="wrap">
    
    <div class="pm_block">
        <div class="errorMessage"></div>
        <div class="successMessage"></div>
    </div>
    
    <form id="customer-reg" class="form-wrap" method="cus_dispute">
        <table class="form-table">
            <tbody>
                <tr>
                    <th><label class="required-cs">Your First Name</label></th>
                    <td><input class="regular-text code" name="cus_first_name" type="text" id="cus_business_name" required="required" value=""/></td>
                </tr>
                
                <tr>
                    <th><label class="required-cs">Your Last Name</label></th>
                    <td><input class="regular-text code" name="cus_last_name" type="text" id="cus_business_name" required="required" value=""/></td>
                </tr>
                
                <tr>
                    <th><label class="required-cs">Your city</label></th>
                    <td><input class="regular-text code" name="cus_city" type="text" id="cus_city" required="required" value=""/></td>
                </tr>
                
                <tr>
                    <th><label class="required-cs">Business Name</label></th>
                    <td><input class="regular-text code" name="cus_business_name" type="text" id="cus_business_name" required="required" value=""/></td>
                </tr>
                
                <tr>
                    <th><label>Issue</label></th>
                    <td>
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
                    </td>
                </tr>
                
                 <tr>
                    <th><label>&nbsp;</label></th>
                    <td>
                        <button id="submit"  type="submit" name="submit" class="button-primary ">Submit</button>
                        <img id="loading" src="<?= admin_url('images/loading.gif') ?>" title="loading" style="display:none;"/>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>