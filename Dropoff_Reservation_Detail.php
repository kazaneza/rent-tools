<?php
include('lib/common.php');
?>

<?php include("lib/header.php"); ?>
    <title>Tools-4-Rent Drop Off</title>
    <style>
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

    <div id="main_container">

        <?php include("lib/menuNavClerk.php");?>


        <div class="center_content">

            <div class="text_box_dropoff">

                <h1>Dropoff Reservation Detail</h1>
                <hr>

<?php
$R_id = $_REQUEST['R_id'];

$sql = "SELECT R.reservation_id, U.first_name, U.middle_name, U.last_name, round(sum(T.original_price*0.4),2) AS 'Total Deposit',
round((datediff(end_date, start_date)+1)*sum(T.original_price*0.15),2) AS 'Total Rental Price'
from reservation R INNER JOIN reservationincludetool RT ON R.reservation_id= RT.reservation_id INNER JOIN tool T ON T.tool_id= RT.tool_id
INNER JOIN Customer C ON C.customer_id=R.customer_id INNER JOIN User U ON U.username=C.username
WHERE R.reservation_id=$R_id";
$result = mysqli_query($db, $sql);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
echo "<p> Reservation ID: " . $row['reservation_id'] . "</p><br>";
echo "<p>Customer Name: " .$row['first_name']. " ". $row['middle_name'] ." ". $row['last_name']. "</p><br>";
echo "<p>Total Deposit: $" .$row['Total Deposit']. "</p><br>";
echo "<p>Total Rental Price: $" .$row['Total Rental Price']. "</p><br>";
$due = $row['Total Rental Price']-$row['Total Deposit'];
echo "<p>Total Due: $".number_format($due,2). "</p><br>";

$sql2="SELECT (CASE WHEN T.power_source='manual'
               THEN CONCAT(T.sub_option,' ',T.sub_type_name)
               ELSE CONCAT(T.power_source,' ',T.sub_option,' ',T.sub_type_name) END) AS 'Short Description'
               FROM reservation R INNER JOIN reservationincludetool RT ON R.reservation_id=RT.reservation_id INNER JOIN tool T ON RT.tool_id=T.tool_id
               WHERE R.reservation_id=$R_id";
$result2= mysqli_query($db, $sql2);
echo "<p> Tool Name List: </p><br>";
while($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC)){
echo "<p>" .$row2['Short Description']. "</p><br>";
}

?>



    </body>
</html>

