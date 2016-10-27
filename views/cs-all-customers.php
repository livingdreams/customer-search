<?php
$customer = new CS_CUSTOMER();
$issue = new CS_ISSUE();
$dispute = new CS_DISPUTE_ENTRY();
$user_id = get_current_user_id(); // Current login user

/* Max Number of results to show */
$max = 10;
/* Get the current page eg index.php?pg=4 */

if (isset($_GET['pg'])) {
    $p = $_GET['pg'];
} else {
    $p = 1;
}

$limit = ($p - 1) * $max;
$prev = $p - 1;
$next = $p + 1;
$limits = (int) ($p - 1) * $max;

$condition = "WHERE owner=$user_id";
//Get total records from db
$totalres = count($customer->get_results_all($condition));
//divide it with the max value & round it up
$totalposts = ceil($totalres / $max);
$lpm1 = $totalposts - 1;
?>

<div class="cs_wrapper">
    <?php
    $condition = "WHERE owner=$user_id limit $limits,$max";
    //var_dump($condition);
    $results = $customer->get_results_all($condition);
    if ($results) {
        ?>
        <table>
            <thead>

            <th>Name</th>
            <th>Last 4 digits of SSN /FEIN</th>
            <th>M.I</th>
            <th>Address</th>
            <th>Issue</th>
            <th></th>
            </thead>
            <tbody>
                <?php
                foreach ($results as $res) {
                    if (!empty($res->suffix)) {
                        $suffix = '(' . $res->suffix . ')';
                    } else {
                        $suffix = '';
                    }
                    ?>
                    <tr>
                        <td><?php echo $res->prefix . ' ' . $res->firstname . ' ' . $res->lastname . ' ' . $suffix; ?></td>
                        <td><?php echo $res->ssn; ?></td>
                        <td><?php echo $res->m_i; ?></td>
                        <td><?php echo $res->street_address . ' ' . $res->city . ' ' . $res->zipcode; ?></td>
                        <td>
                            <?php
                            $issue_text = $issue->get_row('id', $res->issue_id);
                            if ($issue_text) {
                                echo $issue_text->issue_text;
                            }
                            ?>
                        </td>
                        <td>
                            <a href="<?= bloginfo('url') ?>/contribute-information?action=edit-customer&customer=<?= $res->id; ?>">Edit</a>
                            <a href=""  onclick="deleteCustomer('<?php echo $res->id; ?>')" >Delete</a>
                            <?php if ($res->is_dispute != NULL) { ?> <a href=""  onclick="verifyCustomer('<?php echo $res->id; ?>', '<?php echo $res->is_dispute; ?>')" >Verify</a> <?php } ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
        <?php
        echo pagination($totalposts, $p, $lpm1, $prev, $next);
    }
    ?>
</div>

