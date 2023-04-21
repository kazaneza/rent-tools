<?php
include('lib/common.php');
?>

<?php include("lib/header.php"); ?>
<title>Tools-4-Rent Drop Off Receipt</title>

</head>

<body>
<div id="main_container">

    <?php include("lib/menuNavClerk.php");?>


    <div class="center_content">


<h1 style="margin-left:10px">Tools-4-Rent Dropoff Reservation</h1>
<hr>
<h2 style="margin-left:10px">Reservation Receipt</h2>

<?php
$clerk_email=$_SESSION['email'];
$reservation_id=$_SESSION['reservation_id'];

$query = "UPDATE reservation SET drop_off_clerk_id = (SELECT clerk_id 
                         FROM clerk CL INNER JOIN user U ON CL.username=U.username
                         WHERE U.email='$clerk_email')
WHERE reservation_id=$reservation_id";

mysqli_query($db, $query);

$sql = "SELECT R.reservation_id, U.first_name, U.middle_name, U.last_name, round(sum(T.original_price*0.4),2) AS 'Total Deposit',
        round((datediff(end_date, start_date)+1)*sum(T.original_price*0.15),2) AS 'Total Rental Price' 
        from reservation R INNER JOIN reservationincludetool RT ON R.reservation_id= RT.reservation_id INNER JOIN tool T ON T.tool_id= RT.tool_id 
        INNER JOIN Customer C ON C.customer_id=R.customer_id INNER JOIN User U ON U.username=C.username
        WHERE R.reservation_id=$reservation_id";
$result = mysqli_query($db, $sql);
include('lib/show_queries.php');
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    Print "<p style=\"margin-left:10px\">Reservation ID: ";
    Print $row['reservation_id'];
    print "<br>Customer Name: ";
    echo $row['first_name'] . $row['middle_name'] . $row['last_name'];
    print "<br>Total Deposit: $";
    print number_format($row['Total Deposit'],2);
    print "<br>Total Rental Price: $";
    print number_format($row['Total Rental Price'],2);
    print "<br>Total Due: $";
    print  number_format($row['Total Rental Price']-$row['Total Deposit'],2)."</p>";

    $sql2="SELECT T.tool_id, round(T.original_price*0.4,2) AS 'Deposit Price', round((datediff(end_date,start_date)+1)*T.original_price*0.15,2) AS 'Rental Price',
                   (CASE WHEN T.power_source='manual'
                   THEN CONCAT(T.sub_option,' ',T.sub_type_name)
                   ELSE CONCAT(T.power_source,' ',T.sub_option,' ',T.sub_type_name) END) AS 'Short Description'
                   FROM reservation R INNER JOIN reservationincludetool RT ON R.reservation_id=RT.reservation_id INNER JOIN tool T ON RT.tool_id=T.tool_id
                   WHERE R.reservation_id=$reservation_id";
    $result2= mysqli_query($db, $sql2);
    include('lib/show_queries.php');

    $sql3="SELECT first_name, middle_name, last_name FROM user WHERE email='$clerk_email'";
    $result3= mysqli_query($db, $sql3);
    include('lib/show_queries.php');
    $row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC);
?>

</body>
</html>

<?php include("lib/header.php"); ?>
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

<table>
    <tr >
        <th > Tool ID </th >
        <th > Tool Name </th >
        <th > Deposit Price </th >
        <th > Rental Price </th >
    </tr >
    <?php
    while($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC)){
        print "<tr>";
        print "<td>" . $row2['tool_id'] . "</td>";
        print "<td>" . $row2['Short Description'] ."</td>";
        print "<td> $" . number_format($row2['Deposit Price'],2) . "</td>";
        print "<td> $" . number_format($row2['Rental Price'],2) . "</td>";
        print "</tr>";
    }
    ?>

    <?php
    print "<tr>";
    print "<td>" . "Totals" . "</td>";
    print "<td>" . " " . "</td>";
    print "<td> $" . number_format($row['Total Deposit'],2) . "</td>";
    print "<td> $" . number_format($row['Total Rental Price'],2) . "</td>";
    print "</tr>";
    ?>
</table>

<hr>
<h3 style="margin-left:10px"> Signatures </h3>
<p style="margin-left:10px"> x____________________________________             Date：_____________________</p>
<p style="margin-left:10px"> Dropoff Clerk - <?php echo $row3['first_name'] . $row3['middle_name'] . $row3['last_name'];?> </p>
<p style="margin-left:10px"> x____________________________________             Date：_____________________</p>
<p style="margin-left:10px"> Customer- <?php echo $row['first_name'] . $row['middle_name'] . $row['last_name'];?> </p>

<a onclick="window.print();" target="_blank" style="margin-left:10px;cursor:pointer; border:2px solid orange; background-color:orange; color:white;" > Print Receipt </a>

</body>
</html>
