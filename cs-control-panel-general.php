<?php
    $cus_options = get_option('cs_settings');
    $cus_number = $cus_options['cus_number'];
?>

<div class="wrap">
    <h1>General Options</h1>
    
    <div class="pm_block">
        <div class="errorMessage"></div>
        <div class="successMessage"></div>
    </div>
    
    <div id="col-container">
        <div id="col-left">
            <div class="col-wrap">
                <form id="user_general" class="form-wrap" method="general">
                    <div class="form-field form-required area-wrap">
                        <label for="issue">Points to add when adding customers</label>
                        <input class="regular-text" name="cus_number[cs_point_add]" type="text" id="cs_point_add" required="required" value="<?= $cus_number['cs_point_add'] ?>" <?= $_GET['action'] == 'edit' ? 'readonly=""' : '' ?>/>
                    </div>
                    <div class="form-field form-required area-wrap">
                        <label for="issue">Points to deduct when searching customers</label>
                        <input class="regular-text" name="cus_number[cs_point_deduct]" type="text" id="cs_point_deduct" required="required" value="<?= $cus_number['cs_point_deduct']; ?>"/>
                    </div>
                    <div class="form-field form-required area-wrap">
                        <label for="issue">Description to add in log when adding customers</label>
                        <textarea class="regular-text" name="cus_number[cs_des_add]" type="textarea" id="cs_des_add" required="required" rows="5" cols="53" /><?php if(isset($cus_number['cs_des_add'])){ echo $cus_number['cs_des_add']; } ?></textarea>
                    </div>
                    <div class="form-field form-required area-wrap">
                        <label for="issue">Description to add in log when searching customers</label>
                        <textarea class="regular-text" name="cus_number[cs_des_add_search]" type="textarea" id="cs_des_add_search" required="required" rows="5" cols="53" /><?php if(isset($cus_number['cs_des_add_search'])){ echo $cus_number['cs_des_add_search']; } ?></textarea>
                    </div>
                    <p class="submit">
                        <button type="submit" name="submit" class="button-primary ">Update</button>
                        <img id="loading" src="<?= admin_url('images/loading.gif') ?>" title="loading" style="display:none;"/>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>