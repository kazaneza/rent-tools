<?php
include('lib/common.php');
?>

<?php include("lib/header.php"); ?>
<title>Tools-4-Rent Drop Off</title>
<style>
    table{width:780px;}
    table,th,td{
        text-align:left;
        margin:10px;
    }
    th,td{padding:10px;
        font-size=10px;}

</style>
</head>
<body>

<div id="main_container">

    <?php include("lib/menuNavClerk.php");?>

    <div class="center_content">

<h1 style="margin-left:10px">Tools-4-Rent Dropoff Reservation</h1>
                <form action="Dropoff.php" method="post" enctype="multipart/form-data">
                    <label style="margin-left:10px"> End Date of the Reservation(YYYY-MM-DD):</label>
                    <input type="text" name="End_Date" value="">
                    <input type="submit">

                    <?php
                    if( $_SERVER['REQUEST_METHOD'] == 'POST') {
                        $end_date = $_POST['End_Date'];
                        $sql3 = "SELECT R.reservation_id, U.first_name, U.middle_name, U.last_name, R.customer_id, DATE(R.start_date) AS start_date, 
                          DATE(R.end_date) AS end_date FROM reservation R NATURAL JOIN customer C NATURAL JOIN user U 
                          WHERE end_date='$end_date'";
                        $result3 = mysqli_query($db, $sql3);
                    }
                    ?>

                    <table>
                        <tr >
                            <th > Reservation ID </th >
                            <th > Customer </th >
                            <th > Customer ID </th >
                            <th > Start Date </th >
                            <th > End Date </th >
                        </tr >

                        <?php
                        while($row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC)) {
                            $R_id = urlencode($row3['reservation_id']);
                            print "<tr>";
                            print "<td><a href=\"Dropoff_Reservation_Detail.php?R_id=$R_id\" target=\"_blank\">" . $row3['reservation_id'] . "</a></td>";
                            print "<td>" . $row3['first_name'] . " " . $row3['middle_name'] . " " . $row3['last_name'] . "</td>";
                            print "<td>" . $row3['customer_id'] . "</td>";
                            print "<td>" . $row3['start_date'] . "</td>";
                            print "<td>" . $row3['end_date'] . "</td>";
                            print "</tr>";
                        }
                        ?>
                    </table>


                    <hr>
                        <label style="margin-left:10px">Reservation ID:</label>
                        <input type="text" name="reservation_id" value="">
<input type="submit">
                </form>

                <hr>
                <h2 style="margin-left:10px">Reservation Details</h2>

<?php
$clerk_email=$_SESSION['email'];
if( $_SERVER['REQUEST_METHOD'] == 'POST') {
    $reservation_id = $_POST['reservation_id'];
    $_SESSION['reservation_id'] = $reservation_id;
    if (empty($reservation_id)) {
        array_push($error_msg, "Please enter a Reservation ID.");
    }
    if (!empty($reservation_id)) {
        $sql = "SELECT R.reservation_id, U.first_name, U.middle_name, U.last_name, round(sum(T.original_price*0.4),2) AS 'Total Deposit',
        round((datediff(end_date, start_date)+1)*sum(T.original_price*0.15),2) AS 'Total Rental Price' 
        from reservation R INNER JOIN reservationincludetool RT ON R.reservation_id= RT.reservation_id INNER JOIN tool T ON T.tool_id= RT.tool_id 
        INNER JOIN Customer C ON C.customer_id=R.customer_id INNER JOIN User U ON U.username=C.username
        WHERE R.reservation_id=$reservation_id";
        $result = mysqli_query($db, $sql);
        include('lib/show_queries.php');
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        if (is_null($row['reservation_id'])){
            echo "The Reservation does not exist. Please type in another reservation ID.";}
        else{
            echo "<p style=\"margin-left:10px\"> Reservation ID: " . $row['reservation_id'] . "</p>";
            echo "<p style=\"margin-left:10px\">Customer Name: " .$row['first_name']. " ". $row['middle_name'] ." ". $row['last_name']. "</p>";
            echo "<p style=\"margin-left:10px\">Total Deposit: $" .number_format($row['Total Deposit'],2). "</p>";
            echo "<p style=\"margin-left:10px\">Total Rental Price: $" .number_format($row['Total Rental Price'],2). "</p>";
            $due = $row['Total Rental Price']-$row['Total Deposit'];
            echo "<p style=\"margin-left:10px\">Total Due: $".number_format($due,2). "</p>";
        }

            $sql2="SELECT T.tool_id, round(T.original_price*0.4,2) AS 'Deposit Price', round((datediff(end_date,start_date)+1)*T.original_price*0.15,2) AS 'Rental Price',
                   (CASE WHEN T.power_source='manual'
                   THEN CONCAT(T.sub_option,' ',T.sub_type_name)
                   ELSE CONCAT(T.power_source,' ',T.sub_option,' ',T.sub_type_name) END) AS 'Short Description'
                   FROM reservation R INNER JOIN reservationincludetool RT ON R.reservation_id=RT.reservation_id INNER JOIN tool T ON RT.tool_id=T.tool_id
                   WHERE R.reservation_id=$reservation_id";
            $result2= mysqli_query($db, $sql2);
            include('lib/show_queries.php');
        }
    }
?>

<table>
<tr >
    <th > Tool ID </th >
    <th > Tool Name </th >
    <th > Deposit Price </th >
    <th > Rental Price </th >
</tr >
    <?php
        while($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC)){
            $tool_id = urlencode($row2['tool_id']);
            print "<tr>";
            print "<td>" . $row2['tool_id'] . "</td>";
            print "<td><a href=\"Dropoff_Tooldetail.php?tool_id=$tool_id\" target=\"_blank\">" . $row2['Short Description'] ."</a></td>";
            print "<td>" . "$" . number_format($row2['Deposit Price'],2) . "</td>";
            print "<td>" . "$". number_format($row2['Rental Price'],2) . "</td>";
            print "</tr>";
        }
    ?>

    <?php
    print "<tr>";
    print "<td>" . "<b>Totals</b>" . "</td>";
    print "<td>" . " " . "</td>";
    print "<td>". "$" . number_format($row['Total Deposit'],2) . "</td>";
    print "<td>". "$" . number_format($row['Total Rental Price'],2) . "</td>";
    print "</tr>";
    ?>
</table>


<a href="Dropoff_Receipt.php" target="_blank" style="margin-left:10px;border:2px solid orange; background-color:orange; color:white;">Drop Off</a>



</body>
</html>
