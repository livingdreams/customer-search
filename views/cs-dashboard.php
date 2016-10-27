<?php
$customer = new CS_CUSTOMER();
$search = new CS_SEARCH_RESULTS();
$issue = new CS_ISSUE();
$user_id = get_current_user_id(); // Current login user

/* Max Number of results to show */
$max = 3;
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

$condition = "WHERE owner_id=$user_id";
//Get total records from db
$totalres = count($search->get_results_all($condition));
//divide it with the max value & round it up
$totalposts = ceil($totalres / $max);
$lpm1 = $totalposts - 1;
?>
<div class="cs_wrapper">
    <?php
    $condition = "WHERE owner_id=$user_id ORDER BY created_on DESC limit $limits ,$max";
    $results = $search->get_results_all($condition);
    if ($results) {
        ?>
        <table>
            <thead>
            <th>Search Date</th>
            <th>Search Results</th>
            </thead>
            <tbody>
                <?php foreach ($results as $res) { ?>
                    <tr>
                        <td>
                            <?php
                            echo $date = date('Y-m-d', strtotime($res->created_on));

                            /* $array = array();
                              $array = unserialize($res->search_terms);
                              if (!empty($array)) {
                              foreach ($array as $key => $arr) {
                              // var_dump($arr);
                              if (!empty($arr)) {

                              $label = array(
                              'firstname' => 'Firstname',
                              'lastname' => 'Lastname',
                              'm_i' => 'M.I',
                              );

                              ?> <?php echo $label[$key] . '-' . $arr; ?><?php
                              }
                              }
                              } */
                            ?>
                        </td>

                        <td><?php
                            $customer_ids = explode(",", $res->search_results);
                            if ($customer_ids) {
                                ?>
                                <table>
                                    <thead>
                                    <th>Name</th>
                                    <th>SSN/FEIN</th>
                                    <th>M.I</th>
                                    <th>Address</th>
                                    <th>Issue</th>
                                    <th>Business Type</th>
                                    <th>City</th>
                                    <th>Zip_Code</th>
                                    <th>State</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($customer_ids as $cus_id) {

                                            $customer_details = $customer->get_row('id', $cus_id);
                                            //var_dump($customer_details);
                                            if ($customer_details) {

                                                if (!empty($customer_details->suffix)) {
                                                    $suffix = '(' . $customer_details->suffix . ')';
                                                } else {
                                                    $suffix = '';
                                                }
                                                ?>
                                                <tr><td><?php echo $customer_details->prefix . ' ' . $customer_details->firstname . ' ' . $customer_details->lastname . ' ' . $suffix; ?></td>
                                                    <td><?php echo $customer_details->ssn; ?></td>
                                                    <td><?php echo $customer_details->m_i; ?></td>
                                                    <td><?php echo $customer_details->street_address . ' ' . $customer_details->city . ' ' . $customer_details->zipcode; ?></td>
                                                    <td>
                    <?php
                    $issue_text = $issue->get_row('id', $res->issue_id);
                    if ($issue_text) {
                        echo $issue_text->issue_text;
                    }
                    ?>
                                                    </td>
                                                    <td><?php echo get_user_meta($customer_details->owner, 'Type_of_business', true); ?></td>
                                                    <td><?php echo get_user_meta($customer_details->owner, 'City', true); ?></td> 
                                                    <td><?php echo get_user_meta($customer_details->owner, 'Zip_Code', true); ?></td> 
                                                    <td><?php echo get_user_meta($customer_details->owner, 'State', true); ?></td> 
                                                </tr> 
                <?php }
                ?>


                                        <?php } ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
            <?php
        }
    }
    ?>
            </tbody>
        </table>
                <?php
                echo pagination($totalposts, $p, $lpm1, $prev, $next);
            }
            ?>
</div>

