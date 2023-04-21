<?php
include('lib/common.php');
$startdate=$_SESSION['startdate'];
$enddate=$_SESSION['enddate'];
$numofdays=floor((strtotime($enddate)-strtotime($startdate))/(60 * 60 * 24)+1);
$Total_Deposit=$_SESSION["Total_Deposit"];
$Total_Rental=$_SESSION["Toatl_Rental"];
?>

<?php include("lib/header.php"); ?>
<title>Tools-4-Rent Make Reservation</title>
<style>
    .error {color: #FF0000;}
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
<div id="main_container">

    <?php include("lib/menuNavCustomer.php");?>

    <div class="center_content">

        <form action="Make_Reservation_AvailableTools.php" method="post" enctype="multipart/form-data">
            <div class="title"; style="margin-left:10px; font-size:200%; margin-top:30px; margin-bottom:30px";>Tools-4-Rent Make Reservation</div>

            <h2 style="margin-left:10px"> Reservation Summary</h2>
            <?php echo "<p style='margin-left:10px'> <b>Reservation Dates:</b> From " . $startdate. " to ". $enddate ."<br>";?>
            <?php echo "<b>Number of Days Rented:</b> " . $numofdays ."<br>";?>
            <?php echo "<b>Total Deposit Price:</b> $" . number_format($Total_Deposit,2) ."<br>";?>
            <?php echo "<b>Total Rental Price:</b> $" . number_format($Total_Rental*$numofdays,2) ."<br></p>";?>
            <hr>

            <h2 style="margin-left:10px"> Tools</h2>
            <table>
                <tr >
                    <th > Tool ID </th >
                    <th > Description </th >
                    <th > Deposit Price </th >
                    <th > Rental Price </th >
                </tr >

            <?php
            if(isset($_SESSION['cart'])){
            $sql_c= "Select T.tool_id, (CASE WHEN T.power_source='manual'
            THEN CONCAT(T.sub_option,' ',T.sub_type_name)
            ELSE CONCAT(T.power_source,' ',T.sub_option,' ',T.sub_type_name) END) AS 'Short Description',
            ROUND(0.4*T.original_price,2) AS 'Deposit Price', ROUND(0.15*T.original_price,2) AS 'Rental Price'
            FROM tool T WHERE T.tool_id IN (";
            foreach($_SESSION['cart'] as $id => $value) {
            $sql_c1=$sql_c1.$id.",";
            }

            $sql_c1=substr($sql_c1,0,-1);
            $sql_c=$sql_c.$sql_c1.")";
            $result_c= mysqli_query($db, $sql_c);


            WHILE($tool_c = mysqli_fetch_array($result_c, MYSQLI_ASSOC)){
                $tool_id = urlencode($tool_c["tool_id"]);
            ?>

            <tr>
                <td ><?php echo $tool_c["tool_id"]; ?></td >
                <?php print "<td><a href=\"Dropoff_Tooldetail.php?tool_id=$tool_id\" target=\"_blank\">" . $tool_c["Short Description"] ."</a></td>";?>
                <td ><?php echo "$".number_format($tool_c["Deposit Price"],2); ?></td >
                <td ><?php echo "$".number_format($tool_c["Rental Price"]*$numofdays,2); ?></td >
            </tr>
            <?php } ?>
                <tr>
                    <td ><?php echo "Totals"; ?></td >
                    <td ><?php echo " "; ?></td >
                    <td ><?php echo "$".number_format($Total_Deposit,2); ?></td >
                    <td ><?php echo "$".number_format($Total_Rental*$numofdays,2); ?></td >
                </tr>

            <?php
            }
            ?>
            </table>
        </form>

        <a href="Make_Reservation_Confirmation.php" target="_blank" style="margin-left:10px;border:2px solid orange; background-color:orange; color:white;">Confirm and Submit</a>
        <a href="Make_Reservation.php" target="_blank" style="border:2px solid darkred; background-color:darkred; color:white;">Reset</a>



</body>
</html>
