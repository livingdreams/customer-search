<?php
$log = new CS_CREDIT_LOG();
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

$condition = "WHERE business_id=$user_id";
//Get total records from db
$totalres = count($log->get_results_all($condition));
//divide it with the max value & round it up
$totalposts = ceil($totalres / $max);
$lpm1 = $totalposts - 1;
?>
<div class="cs_wrapper">
    <?php
    $condition = "WHERE business_id=$user_id ORDER BY created_on DESC limit $limits ,$max";
    $results = $log->get_results_all($condition);
    if ($results) {
        ?>
        <table>
            <thead>
            <th>Date</th>
            <th>Status</th>
            <th>Points</th>
            </thead>
            <tbody>
                <?php foreach ($results as $res) { ?>
                    <tr>
                        <td><?php echo $date = date('Y-m-d', strtotime($res->created_on)); ?></td>
                        <td><?php
                        if($res->status == 1)
                            echo 'Points Purchased'; 
                        elseif($res->status == 2)
                            echo 'Customer Added'; 
                        elseif($res->status == 3)
                            echo 'Customer Searched'; 
                        ?></td>
                        <td><?php echo $res->points; ?></td>
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

