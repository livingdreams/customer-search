<?php
$customers = new CS_CUSTOMER();
?>

<div>
    <h2>List Customers <a href="<?= bloginfo('url') ?>/wp-admin/admin.php?page=cs-control-panel-add-customers" class="page-title-action">Add New</a></h2>
    <div class="pm_block">
        <div class="errorMessage"></div>
        <div class="successMessage"></div>
    </div>
    <div id = "poststuff">
        <div id = "post-body" class = "metabox-holder columns-2">
            <div id = "post-body-content">
                <div class = "meta-box-sortables ui-sortable">
                    <form method = "post">
                        <?php
                        $customers->prepare_items();
                        $customers->display();
                        ?>
                    </form>
                </div>
            </div>
        </div>
        <br class="clear">
    </div>
</div>
    <?php
