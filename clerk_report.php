<?php
include('./lib/common.php');
if(!isset($_GET['action'])){
    $sort_item = 'CombinedTotal';
    $_SESSION['sort']['CombinedTotal']=array("sort_order"=>"DESC");
    $_SESSION['sort']["Clerk_ID"]=array("sort_order"=>"DESC");
    $_SESSION['sort']["first_name"]=array("sort_order"=>"DESC");
    $_SESSION['sort']["middle_name"]=array("sort_order"=>"DESC");
    $_SESSION['sort']["last_name"]=array("sort_order"=>"DESC");
    $_SESSION['sort']["email"]=array("sort_order"=>"DESC");
    $_SESSION['sort']["date_of_hire"]=array("sort_order"=>"DESC");
    $_SESSION['sort']["NumberPickups"]=array("sort_order"=>"AESC");
    $_SESSION['sort']["NumberDropoffs"]=array("sort_order"=>"AESC");
}
if(isset($_GET['action'])&& $_GET['action']=='sort') {
    $sort_item = $_REQUEST['sort_item'];
    if($_SESSION['sort'][$sort_item]["sort_order"]=="ASC"){
        $_SESSION['sort'][$sort_item]=array("sort_order"=>"DESC");
    }
    else{
        $_SESSION['sort'][$sort_item]=array("sort_order"=>"ASC");
    }
}
?>

<?php include("./lib/header.php"); ?>

<title>Clerk Report</title>

<style>
    table{
        table-layout: fixed;
        width:100%;
    }
</style>

</head>

<body>
<div id="main_container"><?php include("./lib/menuNavClerk.php"); ?>
    <div class="center_content">
        <div class="center_left">
            <div class="subtitle">Clerk Report</div><br>
        </div>

        <form action="clerk_report.php" method="post" enctype="multipart/form-data">
            <tr>
                <td class="item_label">Input Date: </td>
                <td><input type="date" name="input_date" value="<?php if ($row['input_date']) { print $row['input_date']; } ?>" />
                </td>
            </tr>

            <?php
            if($_POST['input_date']){
                $enteredDate = mysqli_real_escape_string($db, $_POST['input_date']);
            }
            else{
                $enteredDate = $_REQUEST['enteredDate'];
            }
            ?>

            <div>
                <input type="submit" value="Select" style="margin-left:10px;"/>
            </div>
            <br><hr><br>
            <table style="background:transparent">
                <tr>
                    <td style="text-align:center;width:5px"><a href="clerk_report.php?action=sort&sort_item=Clerk_ID&enteredDate=<?php echo $enteredDate ?>">Clerk ID </a></td>
                    <td style="width:7px"><a href="clerk_report.php?action=sort&sort_item=first_name&enteredDate=<?php echo $enteredDate ?>">First Name </a></td>
                    <td style="width:8px"><a href="clerk_report.php?action=sort&sort_item=middle_name&enteredDate=<?php echo $enteredDate ?>">Middle Name </a></td>
                    <td style="width:7px"><a href="clerk_report.php?action=sort&sort_item=last_name&enteredDate=<?php echo $enteredDate ?>">Last Name </a></td>
                    <td style="width:17px"><a href="clerk_report.php?action=sort&sort_item=email&enteredDate=<?php echo $enteredDate ?>">Email </a></td>
                    <td style="width:7px"><a href="clerk_report.php?action=sort&sort_item=date_of_hire&enteredDate=<?php echo $enteredDate ?>">Hire Date </a></td>
                    <td style="text-align:center;width:5px"><a href="clerk_report.php?action=sort&sort_item=NumberPickups&enteredDate=<?php echo $enteredDate ?>">Pickup </a></td>
                    <td style="text-align:center;width:5px"><a href="clerk_report.php?action=sort&sort_item=NumberDropoffs&enteredDate=<?php echo $enteredDate ?>">Dropoff </a></td>
                    <td style="text-align:center;width:5px"><a href="clerk_report.php?action=sort&sort_item=CombinedTotal&enteredDate=<?php echo $enteredDate ?>">Combined Total</a></td>
                </tr>

                <?php
                if (empty($enteredDate)){
                    $enteredDate = '2017-10-31';
                }
                $dateElements = explode('-', $enteredDate);
                $year = $dateElements[0];
                $month= $dateElements[1];
                $FirstDayofmonth=$year.'-'.$month.'-01';

                $view="Create View Reservation_Pick_Drop
                           AS SELECT reservation_id, start_date, pickup_clerk_id,drop_off_clerk_id
                           From reservation
                           Where DATEDIFF(DATE(start_date),'$FirstDayofmonth')>= 0 and DATEDIFF('$enteredDate', DATE(start_date)) >= 0";
                $query = "SELECT C.clerk_id, U.first_name, U.middle_name,U.last_name, U.email, DATE(date_of_hire) AS 'date_of_hire',
                              COUNT(DISTINCT RP.reservation_id) AS 'NumberPickups', 
                              COUNT(DISTINCT RD.reservation_id) AS 'NumberDropoffs',
                              COUNT(DISTINCT RP.reservation_id)+COUNT(DISTINCT RD.reservation_id) AS 'CombinedTotal'
                              FROM clerk C NATURAL JOIN user U LEFT OUTER JOIN Reservation_Pick_Drop RP 
                              on C.clerk_id=RP.pickup_clerk_id LEFT OUTER JOIN Reservation_Pick_Drop RD 
                              ON C.clerk_id=RD.drop_off_clerk_id  
                              GROUP BY C.clerk_id
                              ORDER BY ";
                if($sort_item == 'Clerk_ID'){
                    $query=$query."C.clerk_id ".$_SESSION['sort']['Clerk_ID']["sort_order"];
                }
                else{
                    $query=$query.$sort_item." ".$_SESSION['sort'][$sort_item]["sort_order"];
                }
                mysqli_query($db, $view);
                $result = mysqli_query($db, $query);
                include('lib/show_queries.php');
                if (is_bool($result) && (mysqli_num_rows($result) == 0)) {
                    array_push($error_msg, "Query ERROR: No reservation history for this customer..." . __FILE__ . " line:" . __LINE__);
                }
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    print "<tr><td style='text-align:center'>" . $row['clerk_id'] . "</td><td>" . $row['first_name'] . "</td><td>" . $row['middle_name'] . "</td><td>" .
                        $row['last_name'] . "</td><td>" . $row['email'] . "</td><td>" . $row['date_of_hire'] . "</td><td style='text-align:center'>" . $row['NumberPickups'] .
                        "</td><td style='text-align:center'>" . $row['NumberDropoffs'] . "</td><td style='text-align:center'>" . $row['CombinedTotal'] . "</td></tr>";
                }
                ?>
            </table>
        </form>
        <?php include("lib/error.php"); ?>
        <div class="clear"></div>
    </div>

    <?php
    $drop_view="Drop View Reservation_Pick_Drop";
    mysqli_query($db, $drop_view);
    include("lib/footer.php"); ?>
</div>
</body>
</html>