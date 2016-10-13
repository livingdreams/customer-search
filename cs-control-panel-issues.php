<?php
$issue = new CS_ISSUE();
if (isset($_GET['edit_id'])) {
    $issue_row = $issue->get_row('id', $_GET['edit_id']);
}elseif (isset($_GET['delete_id'])) {
    $issue_row = $issue->get_row('id', $_GET['delete_id']);
}
?>

<div class="wrap">

    <h1>Issues</h1>
    <hr>

    <div class="pm_block">
        <div class="errorMessage"></div>
        <div class="successMessage"></div>
    </div>
    <div id="col-container">
        <div id="col-right">
            <div class="col-wrap">
                <?php $issue = new CS_ISSUE(); ?>
                <form method = "post">
                    <?php
                    $issue->prepare_items();
                    $issue->display();
                    ?>
                </form>
            </div>
        </div>
        <div id="col-left">
            <div class="col-wrap">
                <h2>Add New Issue</h2>
                <form id="new-issue" class="form-wrap" method="<?= $_GET['action'] == 'edit_issue' ? 'edit_issue' : 'new_issue' ?>">
                    <?php if ($_GET['action'] == 'edit_issue'): ?>
                        <input type="hidden" id="id" name="id" value="<?= $issue_row->id ?>"/>
                    <?php elseif ($_GET['action'] == 'delete_issue'): ?>
                        <input type="hidden" id="id" name="id" value="<?= $issue_row->id ?>"/>
                    <?php endif; ?>
            
                    <div class="form-field form-required area-wrap">
                        <label for="issue">Issue</label>
                        <input class="regular-text" name="issue_text" type="text" id="issue_text" required="required" value="<?= $issue_row->issue_text; ?>"/>
                    </div>
                     
                    <fieldset>
                        <div id="radioGroup" class="form-field form-required radioGroup">
                            <label class="radio-inline">
                                <input type="radio" class="radio" value="0" <?= $issue_row->status == 0 ? "checked" : "" ?> name="status" />Issue for entry
                            </label> 

                             <label class="radio-inline">
                                <input type="radio" class="radio" value="1" <?= $issue_row->status == 1 ? "checked" : "" ?> name="status" />Issue for dispute
                            </label> 
                        </div>
                    </fieldset>
                         
                    <p class="submit">
                        <button type="submit" name="submit" class="button-primary "><?= $_GET['action'] == 'edit_issue' ? 'Update' : 'Add' ?> Issue</button>
                        <img id="loading" src="<?= admin_url('images/loading.gif') ?>" title="loading" style="display:none;"/>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
