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
                <h1 style="margin-left:10px">Customer Information</h1>
<?php
                $reservation_id=$_SESSION['reservation_id'];
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
                echo $row['first_name'] . ' ' .$row['middle_name'] . ' ' . $row['last_name'];
                print "<br>Total Deposit: ";
                print number_format($row['Total Deposit'],2);
                print "<br>Total Rental Price: ";
                print number_format($row['Total Rental Price'],2);
                print "<br>Total Due: ";
                print  number_format($row['Total Rental Price']-$row['Total Deposit'],2) . '</p>';
          ?>
                <hr>
                <center><h2>Credit Card was successfully updated!</h2></center>

                 <center>  <input type="button" value="Click for receipt" class="homebutton" id="btnHome"
                           onClick="document.location.href= 'pickup_receipt.php'" /></center>



</body>
</html>
