<?php
include('lib/common.php');

// cart function
if(!isset($_GET['action'])){unset($_SESSION['cart']);$_SESSION['count_reserved_tool']=0;}

if(isset($_GET['action'])&& $_GET['action']=='add' && $_SESSION['count_reserved_tool']<10) {

    $id = intval($_GET['id']);

    $sql_s = "Select T.tool_id FROM tool T WHERE T.tool_id=$id";

    $query_s = mysqli_query($db, $sql_s);

    $row_s = mysqli_fetch_array($query_s, MYSQLI_ASSOC);

    $_SESSION['cart'][$row_s['tool_id']] = array("tool_id" => $row_s["tool_id"]);

    $_SESSION['count_reserved_tool']=$_SESSION['count_reserved_tool']+1;
}

if(isset($_GET['action'])&& $_GET['action']=='delete') {

    $id = intval($_GET['id']);

    unset($_SESSION['cart'][$id]);

    $_SESSION['count_reserved_tool']=$_SESSION['count_reserved_tool']-1;

}
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

            <div class="title"; style="font-size:200%; margin-left:10px; margin-top:30px; margin-bottom:30px";>Tools-4-Rent Make Reservation</div>



            <h2 style="margin-left:10px"> Available Tools For Rent </h2>



            <?php

            $startdate=$_SESSION['startdate'];

            $enddate=$_SESSION['enddate'];

            $category=$_SESSION['category'];

            $powersource=$_SESSION['powersource'];

            $subtype=$_SESSION['subtype'];

            $keyword=$_SESSION['keyword'];



            echo "<p style='margin-left:10px'>" . 'Start Date: '.$startdate ."<br>";

            echo 'End Date: '. $enddate . '<br>';

            echo 'Category: '. $category ."<br>";

            echo 'Powersource: '. $powersource ."<br>";

            echo 'Subtype: '. $subtype ."<br>";

            echo 'Keyword: '. $keyword."<br></p>";



            $sql="Select DISTINCT T.tool_id, (CASE WHEN T.power_source='manual'

                   THEN CONCAT(T.sub_option,' ',T.sub_type_name)

                   ELSE CONCAT(T.power_source,' ',T.sub_option,' ',T.sub_type_name) END) AS 'Short Description', 

                   ROUND(0.4*T.original_price,2) AS 'Deposit Price', ROUND(0.15*T.original_price,2) AS 'Rental Price' 

FROM tool T NATURAL LEFT JOIN reservationincludetool RT NATURAL LEFT JOIN reservation R NATURAL JOIN subtype ST

WHERE (R.end_date<'$startdate' OR R.start_date>'$enddate' OR R.start_date IS NULL)";

            if(isset($_SESSION['cart']) && $_SESSION['cart'] ==TRUE) {

            $sql = $sql . "AND T.tool_id Not IN (";

            foreach ($_SESSION['cart'] as $id => $value) {

                $sql_1 = $sql_1 . $id . ",";

            }
                $sql_1 = substr($sql_1, 0, -1);

                $sql = $sql . $sql_1 . ")";
            }

            if ($category!="All Tools"){$sql=$sql. " AND ST.category_name='$category' ";}

            if($powersource!='All'){$sql=$sql. " AND power_source='$powersource' "; }

            if ($subtype != 'All'){$sql= $sql. "AND T.sub_type_name='$subtype' ";}



            $sql=$sql . "AND T.tool_id in (SELECT T2.tool_id 

FROM tool T2 NATURAL LEFT JOIN garden NATURAL LEFT JOIN hand NATURAL LEFT JOIN ladder NATURAL LEFT JOIN power NATURAL LEFT JOIN

screwdriver NATURAL LEFT JOIN socket NATURAL LEFT JOIN ratchet NATURAL LEFT JOIN wrench NATURAL LEFT JOIN pliers

NATURAL LEFT JOIN gun NATURAL LEFT JOIN hammer NATURAL LEFT JOIN pruner NATURAL LEFT JOIN striking NATURAL LEFT JOIN

digger NATURAL LEFT JOIN rakes NATURAL LEFT JOIN wheelbarrows NATURAL LEFT JOIN drill NATURAL LEFT JOIN saw NATURAL LEFT JOIN

sander NATURAL LEFT JOIN aircompressor NATURAL LEFT JOIN mixer NATURAL LEFT JOIN generator NATURAL LEFT JOIN straight

NATURAL LEFT JOIN step NATURAL LEFT JOIN subtype ST2

WHERE T2.tool_id like '%".$keyword. "%' OR T2.sub_option like '%". $keyword. "%' OR T2.sub_type_name like '%". $keyword. "%' OR 

blade_length like '%". $keyword. "%' OR drive_size like '%". $keyword. "%' OR length like '%". $keyword. "%' OR 

width_diameter like '%". $keyword. "%' OR weight like '%". $keyword. "%' OR original_price like '%". $keyword. "%' OR

power_source like '%". $keyword. "%' OR manufacturer like '%". $keyword. "%' OR

material like '%". $keyword. "%' OR handle_material like '%". $keyword. "%' OR step_count like '%". $keyword. "%' 

OR weight_capacity like '%". $keyword. "%' OR  volt_rating like '%". $keyword. "%' OR amp_rating like '%". $keyword. "%' 

OR min_rpm_rating like '%". $keyword. "%' OR max_rpm_rating like '%". $keyword. "%' OR screw_size like '%". $keyword. "%' 

OR sae_size like '%". $keyword. "%' OR deep_socket like '%". $keyword. "%' OR capacity like '%". $keyword. "%' OR 

gauge_rating like '%". $keyword. "%' OR blade_material like '%". $keyword. "%' OR head_weight like '%". $keyword. "%' 

OR blade_width like '%". $keyword. "%' OR tine_count like '%". $keyword. "%' OR bin_material like '%". $keyword. "%' 

OR bin_volume like '%". $keyword. "%' OR wheel_count like '%". $keyword. "%' OR min_torque_rating like '%". $keyword. "%' 

OR max_torque_rating like '%". $keyword. "%' OR blade_size like '%". $keyword. "%' OR tank_size like '%". $keyword. "%' 

OR pressure_rating like '%". $keyword. "%' OR motor_rating

like '%". $keyword. "%' OR drum_size like '%". $keyword. "%' OR power_rating like '%". $keyword. "%' OR 

gas_power like '%". $keyword. "%' OR rubber_feet like '%". $keyword. "%' OR pail_shelf like '%". $keyword. "%' OR ST2.category_name like '%". $keyword. "%') order by T.tool_id";



            $result= mysqli_query($db, $sql);

            ?>



            <table>

                <tr >

                    <th > Tool ID </th >

                    <th > Description </th >

                    <th > Deposit Price </th >

                    <th > Rental Price </th >

                    <th > Add to Cart </th >

                </tr >



                <?php

                WHILE($tool = mysqli_fetch_array($result, MYSQLI_ASSOC)){

                    $tool_id = urlencode($tool["tool_id"]);

                    ?>



                    <tr>

                        <td ><?php echo $tool["tool_id"]; ?></td >

                        <?php print "<td><a href=\"Dropoff_Tooldetail.php?tool_id=$tool_id\" target=\"_blank\">" . $tool["Short Description"] ."</a></td>";?>

                        <td ><?php echo "$".$tool["Deposit Price"]; ?></td >

                        <td ><?php echo "$".$tool["Rental Price"]; ?></td >

                        <td><a href="Make_Reservation_AvailableTools.php?page=products&action=add&id=<?php echo $tool["tool_id"] ?>">Add to Cart </a></td>

                    </tr>

                <?php } ?>

            </table>

        </form>

        <hr>

        <h3 style="margin-left:10px">Tools Added to Reservation</h3>

        <?php
        if(isset($_GET['action'])&& $_GET['action']=='add' && $_SESSION['count_reserved_tool']==10){
            echo "<p style='color: darkred'> Warning:In one reservation, you can not have more than 10 tools </p>";
        }
        ?>

        <table>

            <tr >

                <th > Tool ID </th >

                <th > Description </th >

                <th > Deposit Price </th >

                <th > Rental Price </th >

                <th > Remove from Cart </th >

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

                    $Total_Deposit=$Total_Deposit+$tool_c["Deposit Price"];

                    $Total_Rental=$Total_Rental+$tool_c["Rental Price"];

                    $tool_id = urlencode($tool_c["tool_id"]);

                    ?>



                    <tr>

                        <td ><?php echo $tool_c["tool_id"]; ?></td >

                        <?php print "<td><a href=\"Dropoff_Tooldetail.php?tool_id=$tool_id\" target=\"_blank\">" . $tool_c["Short Description"] ."</a></td>";?>

                        <td ><?php echo "$".$tool_c["Deposit Price"]; ?></td >

                        <td ><?php echo "$".$tool_c["Rental Price"]; ?></td >

                        <td><a href="Make_Reservation_AvailableTools.php?page=products&action=delete&id=<?php echo $tool_c["tool_id"] ?>">Delete</a></td>

                    </tr>

                <?php }

            }

            $_SESSION["Total_Deposit"]=$Total_Deposit;

            $_SESSION["Toatl_Rental"]=$Total_Rental;

            ?>

        </table>



        <a href="Make_Reservation_Summary.php" target="_blank" style="margin-left:10px; border:2px solid orange; background-color:orange; color:white;">Confirm and Go to Reservation Summary</a>



</body>

</html>
