<?php
include('lib/common.php');
?>

<?php include("lib/header.php"); ?>
<title>Tools-4-Rent Pick Up Reservation</title>
<style>
    table{width:780px;}
    table,th,td{border:1px solid black;
        border-collapse:collapse;
        text-align:left;
        margin:10px;
    }
    th,td{padding:10px;
        font-size=10px;}

</style>
</head>
<body>
<div id="main_container"><?php include("lib/menuNavClerk.php"); ?>
    <div class="center_content">
        <div id="main_container">


            <div class="center_content">

<h1 style="margin-left:10px">Tools-4-Rent PickUp Reservation</h1>

    <form action="Pickup.php" method="post" enctype="multipart/form-data">
            <label class="pickup_label" style="margin-left:30px">End Date of the Reservation(YYYY-MM-DD):</label>
            <input type="text" name="date1"  placeholder="For Demo purposes only" class="pickup_input"/>
            <input type="submit">

<?php
if( $_SERVER['REQUEST_METHOD'] == 'POST') {
    $CustomerExisting = mysqli_real_escape_string($db, $_POST['CustomerExisting']);
    $date1 = $_POST['date1'];
    $_SESSION['date1'] = $date1;

    $sql3 = "SELECT DISTINCT R.reservation_id , U.username, C.customer_id, R.start_date , R.end_date   
                    from Reservation R INNER JOIN reservationincludetool RT ON R.reservation_id= RT.reservation_id INNER JOIN tool T ON T.tool_id= RT.tool_id 
                    INNER JOIN Customer C ON C.customer_id=R.customer_id INNER JOIN User U ON U.username=C.username
                    WHERE R.start_date = '$date1'";
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
// using while loop to dispaly data from database
 while($row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC)) {

   ## echo "$row[0] - $row[1] - $row[2] - $row[3] - $row[4]<br>";
    $R_id = urlencode($row3['reservation_id']);
    print "<tr>";
    print "<td><a href=\"Pickup_Reservation_detail.php?R_id=$R_id\" target=\"_blank\">" . $row3['reservation_id'] ."</a></td>";
    ## print "<td>" . $row3['first_name'] . ' ' . $row3['middle_name'] . ' ' . $row3['last_name'] . "</td>";
     print "<td>" . $row3['username'] . "</td>";
    print "<td>" . $row3['customer_id'] . "</td>";
    print "<td>" . substr($row3['start_date'],0,10) . "</td>";
    print "<td>" . substr($row3['end_date'],0,10) . "</td>";
    print "</tr>";
}

?>
    </table>

    <hr>

        <div style="margin-left:10px;background-color; lightgrey;">
            <label>      </label>
    <input type="text" name="reservation_id" placeholder="Enter Reservation ID" >
        <input type="submit" value="Pick Up">

        </div>
    </form>

    <hr>

                <?php
                $clerk_email=$_SESSION['email'];
                if( $_SERVER['REQUEST_METHOD'] == 'POST') {

                    $reservation_id = $_POST['reservation_id'];
                    $queryClerkID = $_SESSION['clerk_id'];
                    $_SESSION['reservation_id'] = $reservation_id;
                    $_SESSION['customer_id'] = $customer_id;
                    $username1 = $_POST['username'];

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
                        $sql33 = "SELECT customer_id FROM reservation WHERE reservation_id=$reservation_id";
                        $result33 = mysqli_query($db, $sql33);
                        $res = mysqli_fetch_assoc($result33);

                        $customer_id = $res['customer_id'];
                        include('lib/show_queries.php');
                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                        if (is_null($row['reservation_id'])) {
                            echo "The Reservation does not exist. Please type in another reservation ID.";
                        } else {

                           // echo "The Customer ID h $customer_id and reservation id is $reservation_id";

                       header("Location: Pickup_Existing_new_credit.php");
                        }
                    }
                }

                ?>


</body>

</html>
