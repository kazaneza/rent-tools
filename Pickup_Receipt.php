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
<body style="margin-left:10px">
<div id="main_container"><?php include("lib/menuNavClerk.php"); ?>
    <div class="center_content">
        <div id="main_container">


            <div class="center_content">
<h1 style="margin-left:10px">Pickup Reservation</h1>
<hr>
<h2 style="margin-left:10px">Rental Contract</h2>


<?php
$CustomerExisting = $_SESSION['CustomerExisting'];
if ($CustomerExisting == 'new'){
    echo "<p style=\"margin-left:10px\">You have successfully updated your credit card information</p>";
}
$reservation_id=$_SESSION['reservation_id'];
$queryClerkID = "select clerk_id, U.username, first_name, middle_name, last_name from clerk C inner join user u on C.username = u.username WHERE U.email='{$_SESSION['email']}'";
$output = mysqli_query($db, $queryClerkID);
$row0 = mysqli_fetch_array($output, MYSQLI_ASSOC);
$storedID = $row0['clerk_id'];
$clerkfirst = $row0['first_name'];

$sql5 = "UPDATE Reservation ".
    "SET pickup_clerk_id = $storedID ".
    "WHERE reservation_id = $reservation_id";
$result4 = mysqli_query($db, $sql5);

$sql = "SELECT R.reservation_id, U.first_name, U.middle_name, U.last_name, round(sum(T.original_price*0.4),2) AS 'Total Deposit',
        round((datediff(end_date, start_date)+1)*sum(T.original_price*0.15),2) AS 'Total Rental Price' , RIGHT(C.card_number,4) 'credit card', date_format(start_date, '%m/%d/%Y') 'start date', date_format(end_date, '%m/%d/%Y') 'end date'
        from reservation R INNER JOIN reservationincludetool RT ON R.reservation_id= RT.reservation_id INNER JOIN tool T ON T.tool_id= RT.tool_id 
        INNER JOIN Customer C ON C.customer_id=R.customer_id INNER JOIN User U ON U.username=C.username
        WHERE R.reservation_id=$reservation_id";
$result = mysqli_query($db, $sql);
include('lib/show_queries.php');
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    print "<p style=\"margin-left:10px\">Pick-up Clerk: ";
    echo $clerkfirst . ' ' .$row0['middle_name'] . ' ' . $row0['last_name'];
    Print "<br>Reservation ID: ";
    Print $row['reservation_id'];
    print "<br>Customer Name: ";
    echo $row['first_name'] . ' ' .$row['middle_name'] . ' ' . $row['last_name'];
    print "<br>Last 4 digits of Credit Card: ";
    print $row['credit card'];
    print "<br>Start Date: ";
    print $row['start date'];
    print "<br>End Date: ";
    print  $row['end date'] ."</p>";

    $sql2="SELECT T.tool_id, round(T.original_price*0.4,2) AS 'Deposit Price', round((datediff(end_date,start_date)+1)*T.original_price*0.15,2) AS 'Rental Price',
                   (CASE WHEN T.power_source='manual'
                   THEN CONCAT(T.sub_option,' ',T.sub_type_name)
                   ELSE CONCAT(T.power_source,' ',T.sub_option,' ',T.sub_type_name) END) AS 'Short Description'
                   FROM reservation R INNER JOIN reservationincludetool RT ON R.reservation_id=RT.reservation_id INNER JOIN tool T ON RT.tool_id=T.tool_id
                   WHERE R.reservation_id=$reservation_id";
    $result2= mysqli_query($db, $sql2);
    include('lib/show_queries.php');

?>

<?php include("lib/header.php"); ?>
<style>
    table,th,td{border:1px solid black;
        border-collapse:collapse;
        text-align:left;
        margin:10px;
        width:800px;
    }
    th,td{padding:10px;
        font-size=10px;}

</style>
</head>
<body style="background-color:lightgrey;">
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
        print "<td><a href=\"Pickup_Tooldetail.php?tool_id=$tool_id\" target=\"_blank\">" . $row2['Short Description'] ."</a></td>";
        print "<td>" . $row2['Deposit Price'] . "</td>";
        print "<td>" . $row2['Rental Price'] . "</td>";
        print "</tr>";
    }
    ?>

    <?php
    print "<tr>";
    print "<td>" . "Totals" . "</td>";
    print "<td>" . " " . "</td>";
    print "<td>" . $row['Total Deposit'] . "</td>";
    print "<td>" . $row['Total Rental Price'] . "</td>";
    print "</tr>";
    $CustomerExisting = $_SESSION['CustomerExisting'];


    ?>
</table>

<hr>
<h3 style="margin-left:10px"> Signatures </h3>
<p style="margin-left:10px"> x____________________________________             Date：_____________________</p>
<p style="margin-left:10px"> Clerk- <?php echo $row0['first_name'] . ' ' . $row0['middle_name'] . ' ' . $row0['last_name'];?> </p>
<p style="margin-left:10px"> x____________________________________             Date：_____________________</p>
<p style="margin-left:10px"> Customer- <?php echo $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'];?> </p>
<br>
<a onclick="window.print();" target="_blank" style="margin-left:10px;cursor:pointer; border:2px solid orange; background-color:orange; color:white;" > Print Contract </a>
<br>

</body>
</html>

