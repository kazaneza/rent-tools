<?php
include('lib/common.php');
// written by carol cheung ccheung39@gatech.edu
$email = $_REQUEST['email'];


$query = "SELECT U.first_name, U.middle_name, U.last_name, U.email, " .
    "C.street, C.city, C.state, C.zip_code, C.home_phone, C.work_phone,C.customer_id," .
    "C.cell_phone FROM User U INNER JOIN Customer C ON U.username=C.username " .
    "WHERE U.email='$email'";

$result = mysqli_query($db, $query);
include('lib/show_queries.php');

if ( !is_bool($result) && (mysqli_num_rows($result) > 0) ) {
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $customerId = $row['customer_id'];
    $fullAddress = get_full_address($row);
    $fullName = get_full_name($row);
} else {
    array_push($error_msg,  "Query ERROR: Failed to get User profile...<br>" . __FILE__ ." line:". __LINE__ );
}

function get_full_address($customer){
    $address = $customer['street'] . ", " . $customer['city'] . ", " . $customer['state'] .
        " " . $customer['zip_code'];
    return $address;
}
function get_full_name($customer){
    $name = $customer['first_name'] . " " . $customer['middle_name'] . " " . $customer['last_name'];
    return $name;
}
?>

<?php include("lib/header.php"); ?>
<title>Customer Report View Profile</title>
<style>
    .divScroll{
        overflow-x:auto;
        overflow-y:auto;
        height:300px;
    }
    table{
        table-layout: fixed;
    }
</style>
</head>

<body>
<div id="main_container">
    <div class="center_content">
        <div class="center_left">
            <div class="subtitle">Customer Info</div>
            <ul>
                <li><b>E-mail: </b><?php print $row['email'] ?></li>
                <li><b>Full Name: </b><?php print $fullName ?></li>
                <li><b>Home Phone: </b><?php print $row['home_phone'] ?></li>
                <li><b>Work Phone: </b><?php print $row['work_phone'] ?></li>
                <li><b>Cell Phone: </b><?php print $row['cell_phone'] ?></li>
                <li><b>Address: </b><?php print $fullAddress ?></li>
            </ul>
            <div class="subtitle">Reservations</div>
        </div>
        <hr>

        <!--<div class="center_left">
            <div class="profile_section"; style="overflow-x:auto"; > -->
        <div class = "center content" style="divScroll">
            <table>
                <tr>
                    <td style="vertical-align:bottom;width:8px" class="heading">Reservation ID</td>
                    <td style="vertical-align:bottom;width:25px" class="heading">Tool Description</td>
                    <td style="vertical-align:bottom;width:17px" class="heading">Start Date</td>
                    <td style="vertical-align:bottom;width:17px" class="heading">End Date</td>
                    <td style="vertical-align:bottom;width:20px" class="heading">Pick-up Clerk</td>
                    <td style="vertical-align:bottom;width:20px" class="heading">Drop-off Clerk</td>
                    <td style="vertical-align:bottom;width:8px" class="heading">Days</td>
                    <td style="vertical-align:bottom;width:10px" class="heading">Total Deposit Price</td>
                    <td style="vertical-align:bottom;width:10px" class="heading">Total Rental Price</td>
                </tr>

                <?php
                $query = "SELECT R.reservation_id, DATE(R.start_date) StartDate, DATE(R.end_date) EndDate, CONCAT(UP.first_name, ' ', UP.middle_name,' '," .
                    "UP.last_name) name_PU_clerk, CONCAT(UD.first_name,' ',UD.middle_name,' ', UD.last_name) name_DO_clerk, " .
                    "(DATEDIFF(R.end_date, R.start_date)+1) ndays, SUM(0.4*T.original_price) TotDepositPrice, " .
                    "(DATEDIFF(R.end_date, R.start_date)+1)*SUM(0.15*T.original_price) TotRentPrice, CASE WHEN T.power_source = 'manual' THEN CONCAT(T.sub_option,' '," .
                    "T.sub_type_name) ELSE CONCAT(T.power_source,' ', T.sub_option,' ', T.sub_type_name) END `Description` " .
                    "FROM Reservation R INNER JOIN Customer C ON R.customer_id=C.customer_id " .
                    "INNER JOIN ReservationIncludeTool RI ON R.reservation_id=RI.reservation_id " .
                    "INNER JOIN Tool T ON RI.tool_id=T.tool_id INNER JOIN Clerk CP ON R.pickup_clerk_id=CP.clerk_id " .
                    "INNER JOIN User UP ON CP.username=UP.username INNER JOIN Clerk CD ON R.drop_off_clerk_id=CD.clerk_id " .
                    "INNER JOIN User UD ON CD.username=UD.username " .
                    "WHERE C.customer_id= '$customerId' GROUP BY R.reservation_id, T.power_source, T.sub_option, T.sub_type_name, " .
                    "R.start_date, R.end_date, UP.first_name, UP.middle_name, UP.last_name, UD.first_name, UD.middle_name, UD.last_name " .
                    "ORDER BY R.start_date";

                $result = mysqli_query($db, $query);
                include('lib/show_queries.php');

                if (is_bool($result) && (mysqli_num_rows($result) == 0)) {
                    array_push($error_msg, "Query ERROR: No reservation history for this customer..." . __FILE__ . " line:" . __LINE__);
                }
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    print "<tr><td>" . $row['reservation_id'] . "</td><td>" . $row['Description'] . "</td><td>" . $row['StartDate'] . "</td><td>" .
                        $row['EndDate'] . "</td><td>" . $row['name_PU_clerk'] . "</td><td>" . $row['name_DO_clerk'] . "</td><td>" . $row['ndays'] .
                        "</td><td>" . number_format($row['TotDepositPrice'],2) . "</td><td>" . number_format($row['TotRentPrice'],2) . "</td></tr>";
                }
                ?>

            </table>
        </div>
        <?php include("lib/error.php"); ?>
        <div class="clear"></div>
    </div>
    <?php include("lib/footer.php"); ?>

</div>
</body>
</html>