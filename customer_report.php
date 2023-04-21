<?php

include('lib/common.php');

if (!isset($_SESSION['email'])) {

    header('Location: clerk_menu.php');

    exit();

}



?>



<?php include("lib/header.php"); ?>

<title>Customer Report</title>

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

<div id="main_container"><?php include("lib/menuNavClerk.php"); ?>

    <div class="center_content">

        <div class="center_left">

            <div class="subtitle">Customer Report</div>



            <!--            <ul>-->

            <!--                <li><b>The list of clerks where their total pickups and dropoffs are shown for the past month. </b>--><?php //print $row['email'] ?><!--</li>-->

            <!--            </ul>-->

        </div>


        <form action="customer_report.php" method="post" enctype="multipart/form-data">

            <tr>

                <td class="item_label">Input Date: </td>

                <td>

                    <input type="date" name="input_date" value="<?php if ($row['input_date']) { print $row['input_date']; } ?>" />

                </td>

            </tr>

            <div>

                <input type="submit" value="Select" style="margin-left:10px;"/>

            </div>

            <!--<div class="center_left">

                <div class="profile_section"; style="overflow-x:auto"; > -->

                <table>

                    <tr>

                        <td class="heading" style="text-align:center;width:6px;vertical-align:bottom">Customer ID</td>

                        <td class="heading" style="width:7px;vertical-align:bottom">View Profile</td>

                        <td class="heading" style="width:9px;vertical-align:bottom">First Name</td>

                        <td class="heading" style="width:5px;vertical-align:bottom">Middle Name</td>

                        <td class="heading" style="width:8px;vertical-align:bottom">Last Name</td>

                        <td class="heading" style="width:16px;vertical-align:bottom">Email</td>

                        <td class="heading" style="width:10px;vertical-align:bottom">Phone</td>

                        <td class="heading" style="text-align:center;width:7px;vertical-align:bottom">Total Reserved</td>

                        <td class="heading" style="text-align:center;width:7px;vertical-align:bottom">Total Rented</td>

                    </tr>



                    <?php

                    $enteredDate = mysqli_real_escape_string($db, $_POST['input_date']);

                    if (empty($enteredDate)){
                        $enteredDate = "2017-10-31";
                    }

                    if (!empty($enteredDate)) {

                        $query = "SELECT C.customer_id, U.first_name, U.middle_name, U.last_name, U.email, C.primary_phone, count(distinct(R.reservation_id)) TotalReservation, count(RI.tool_id) TotalTool

                                  FROM Reservation R INNER JOIN Customer C ON R.customer_id = C.customer_id

                                  INNER JOIN User U ON C.username = U.username

                                  INNER JOIN ReservationIncludeTool RI ON R.reservation_id = RI.reservation_id

                                  WHERE DATEDIFF('$enteredDate',DATE(R.start_date)) <= 30 and DATEDIFF('$enteredDate',DATE(R.start_date)) >= 0

                                  GROUP BY C.customer_id, U.first_name, U.middle_name, U.last_name, U.email, C.primary_phone

                                  ORDER BY concat(COUNT(RI.tool_id), U.last_name) ASC";





                        $result = mysqli_query($db, $query);

                        include('lib/show_queries.php');



                        if (is_bool($result) && (mysqli_num_rows($result) == 0)) {

                            array_push($error_msg, "Query ERROR: No reservation history for this customer..." . __FILE__ . " line:" . __LINE__);

                        }

                        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

                            $email = urlencode($row['email']);

                            print "<tr>";

                            print "<td style='text-align:center'>" . $row['customer_id'] . "</td>";

                            print "<td><a href=\"customer_report_view_profile.php?email=$email\" target=\"_blank\">" ."View Profile" ."</a></td>";

                            print "<td>" . $row['first_name'] . "</td>";

                            print "<td>" . $row['middle_name'] . "</td>";

                            print "<td>" . $row['last_name'] . "</td>";

                            print "<td>" . $row['email'] . "</td>";

                            print "<td>" . $row['primary_phone'] . "</td>";

                            print "<td style='text-align:center'>" . $row['TotalReservation'] . "</td>";

                            print "<td style='text-align:center'>" . $row['TotalTool'] . "</td>";

                            print "</tr>";

//                            print "<tr><td>" . $row['customer_id'] . "</td><td>" . $row['first_name'] ."</td><td>" . $row['first_name'] . "</td><td>" . $row['middle_name'] . "</td><td>" .

//                                $row['last_name'] . "</td><td>" . $row['email'] . "</td><td>" . $row['primary_phone'] . "</td><td>" . $row['TotalReservation'] .

//                                "</td><td>" . $row['TotalTool'] . "</td></tr>";

                        }

                    }

                    ?>



                </table>

        </form>

        <?php include("lib/error.php"); ?>

        <div class="clear"></div>

    </div>

    <?php include("lib/footer.php"); ?>



</div>

</body>

</html>
