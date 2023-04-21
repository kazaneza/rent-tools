<?php
include('lib/common.php');
$cat=$_GET['cat']; // This line is added to take care if your global variable is off
$query1="Select Distinct category_name From subtype";
?>

<SCRIPT language=JavaScript>
    function reload(form){
        var val=form.cat.options[form.cat.options.selectedIndex].value;

        self.location='check_tool_availability.php?cat=' + val ;
    }
</script>

<?php include("lib/header.php"); ?>
<title>Tools-4-Rent Check Tool Availability</title>
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

<script>
    //this function seems to only work here
    function sendCategory(idVal) {
        var category = idVal;

        //alert("var " + category);
        $cat = category;
        if(category === "power"){
            //alert("turning on power for " + category + " tools");
            document.getElementById("power_source").options[0].disabled = true;
            document.getElementById("power_source").options[1].disabled = true;
            document.getElementById("power_source").options[2].disabled = false;
            document.getElementById("power_source").options[3].disabled = false;

        }
        else if (category !== "power"){
            document.getElementById("power_source").options[0].disabled = false;
            document.getElementById("power_source").options[1].disabled = false;
            document.getElementById("power_source").options[2].disabled = true;
            document.getElementById("power_source").options[3].disabled = true;

        }
    }
</script>


</head>
<body>
<div id="main_container">
    <?php include("lib/menuNavCustomer.php");?>

    <div class="center_content">

        <form action="check_tool_availability.php" method="post" enctype="multipart/form-data">
            <div class="title"; style="font-size:200%; margin-top:30px; margin-bottom:30px";>Check Tool Availability</div>


            <?php
            echo "<label style='margin-left:10px'>Type:</label>";
            echo "<form method=post name=f1 action='check_tool_availability.php'>";
            echo "<select name='cat' onchange=\"reload(this.form);sendCategory(this.id)\" ><option value='All Tools'>All Tools</option>";
            $stmt= mysqli_query($db, $query1);

            if($stmt){
                while ($row2 = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
                    if($row2['category_name']==@$cat){echo "<option selected value='$row2[category_name]'>$row2[category_name]</option>";}
                    else{echo  "<option value='$row2[category_name]'>$row2[category_name]</option>";}
                    if ($row2['category_name']=='power') {
                        $_SESSION['power_source'] = $powersource;}

                }
            }else{
                echo "error";
            }
            echo "</select>";
            ?>

            <script>


                // to recheck the chosen category since page gets reloaded and removes 'checked'
                var cat_set = <?php echo json_encode($category, JSON_HEX_TAG); ?>;
                if (cat_set !== 'null') {
                    document.getElementById(cat_set).checked = true;
                    //alert(cat_set);
                }
            </script>

            <label> Power Source:</label>
            <select id="power_source" name="power_source" onchange="readPower()">
                <option value="All">All</option>
                <option value="manual">Manual</option>
                <option value="gas" >Gas</option>
                <option value="cordless" >Cordless</option>
                <option value="electric" >Electric</option>
            </select>
            <br>

            <script>
                // to re-select power after re-loading
                var pwr_set = null;
                pwr_set = <?php echo json_encode($_SESSION['power_source'], JSON_HEX_TAG); ?>;
                //alert("set drop down. pwr_set=" + pwr_set);
                if(pwr_set == 'gas'){
                    document.getElementById("power_source").options[1].defaultSelected = true;
                } else if(pwr_set == 'cordless'){
                    document.getElementById("power_source").options[2].defaultSelected = true;

                } else if (pwr_set == 'electric'){
                    document.getElementById("power_source").options[3].defaultSelected = true;
                }
                /*if (pwr_set != null){
                    document.getElementById("subtypefields").style.display="block";
                }*/
            </script>



            <br>

            <?php
            ##echo "Category IS: ",$cat;

            echo "<label style='margin-left:10px'>Sub_Type:</label>";
            echo "<select name='subtype'><option value='All'>Select one</option>";
            $query2 = "SELECT DISTINCT sub_type_name from subtype Where category_name='$cat'";
            $stmt2 = mysqli_query($db, $query2);
            $count = mysqli_num_rows($stmt2);
            if (!empty($stmt2) && ($count > 0) ){
                while ($row3 = mysqli_fetch_array($stmt2, MYSQLI_ASSOC)) {
                    echo "<option value='$row3[sub_type_name]'>$row3[sub_type_name]</option>";
                }}
            else{$query3 = "SELECT DISTINCT sub_type_name from subtype";
                $stmt3 = mysqli_query($db, $query3);
                while ($row4 = mysqli_fetch_array($stmt3, MYSQLI_ASSOC)) {
                    echo "<option value='$row4[sub_type_name]'>$row4[sub_type_name]</option>";
                }}

            echo "</select>";
            ?>
            <br>

            <div style="margin-left:10px;margin-bottom:50px";>
                <label>Start Date:</label>
                <input type="text" name="start_date" placeholder="YYYY-MM-DD"><br>
                <label>End Date:</label>
                <input type="text" name="end_date" placeholder="YYYY-MM-DD"><br>
                <label >Custom Search :</label>
                <input type="text" name="keyword" placeholder="Enter Keyword" value=""><br>

                <input type="submit" value="Search">
            </div>


            <?php
            if( $_SERVER['REQUEST_METHOD'] == 'POST') {
                $startdate = $_POST['start_date'];
                if (empty($startdate)) {
                    echo "Please enter the Start Date.";
                }

                $enddate = $_POST['end_date'];
                if (empty($enddate)) {
                    echo "Please enter the End Date.";
                }

                if((strtotime($enddate)-strtotime($startdate))/(60 * 60 * 24)+1<1){
                    echo "End Date should not be prior to Start Date";}
                $category=$_POST['cat'];
                $subtype=$_POST['subtype'];
                $powersource=$_POST['power_source'];
                $keyword=$_POST['keyword'];
            }

            if( $_SERVER['REQUEST_METHOD'] == 'POST' AND !empty($startdate) AND !empty($enddate) AND (strtotime($enddate)-strtotime($startdate))/(60 * 60 * 24)+1>=1) {
                $_SESSION['startdate'] = $startdate;
                $_SESSION['enddate'] = $enddate;
                $_SESSION['category'] = $category;
                $_SESSION['power_source'] = $powersource;
                $_SESSION['subtype'] = $subtype;
                $_SESSION['keyword'] = $keyword;
            }

            if(!empty($startdate) AND !empty($enddate) AND (strtotime($enddate)-strtotime($startdate))/(60 * 60 * 24)+1>=1) {




            $sql = "Select DISTINCT T.tool_id, (CASE WHEN T.power_source='manual'
                   THEN CONCAT(T.sub_option,' ',T.sub_type_name)
                   ELSE CONCAT(T.power_source,' ',T.sub_option,' ',T.sub_type_name) END) AS 'description', 
                   ROUND(0.15*T.original_price,2) AS 'rental_price', ROUND(0.4*T.original_price,2) AS 'deposit_price' 
                   FROM tool T NATURAL LEFT JOIN reservationincludetool RT NATURAL LEFT JOIN reservation R NATURAL JOIN subtype ST
                   WHERE (R.end_date<'$startdate' OR R.start_date>'$enddate' OR R.start_date IS NULL)";

            if ($category != "All Tools") {
                $sql = $sql . " AND ST.category_name='$category' ";
            }
            if ($powersource != 'All') {
                $sql = $sql . " AND power_source='$powersource' ";
            }
            if ($subtype != 'All') {
                $sql = $sql . "AND T.sub_type_name='$subtype' ";
            }

            $sql = $sql . "AND T.tool_id in (SELECT T2.tool_id 
FROM tool T2 NATURAL LEFT JOIN garden NATURAL LEFT JOIN hand NATURAL LEFT JOIN ladder NATURAL LEFT JOIN power NATURAL LEFT JOIN
screwdriver NATURAL LEFT JOIN socket NATURAL LEFT JOIN ratchet NATURAL LEFT JOIN wrench NATURAL LEFT JOIN pliers
NATURAL LEFT JOIN gun NATURAL LEFT JOIN hammer NATURAL LEFT JOIN pruner NATURAL LEFT JOIN striking NATURAL LEFT JOIN
digger NATURAL LEFT JOIN rakes NATURAL LEFT JOIN wheelbarrows NATURAL LEFT JOIN drill NATURAL LEFT JOIN saw NATURAL LEFT JOIN
sander NATURAL LEFT JOIN aircompressor NATURAL LEFT JOIN mixer NATURAL LEFT JOIN generator NATURAL LEFT JOIN straight
NATURAL LEFT JOIN step NATURAL LEFT JOIN subtype ST2
WHERE T2.tool_id like '%" . $keyword . "%' OR T2.sub_option like '%" . $keyword . "%' OR T2.sub_type_name like '%" . $keyword . "%' OR 
blade_length like '%" . $keyword . "%' OR drive_size like '%" . $keyword . "%' OR length like '%" . $keyword . "%' OR 
width_diameter like '%" . $keyword . "%' OR weight like '%" . $keyword . "%' OR original_price like '%" . $keyword . "%' OR
power_source like '%" . $keyword . "%' OR manufacturer like '%" . $keyword . "%' OR
material like '%" . $keyword . "%' OR handle_material like '%" . $keyword . "%' OR step_count like '%" . $keyword . "%' 
OR weight_capacity like '%" . $keyword . "%' OR  volt_rating like '%" . $keyword . "%' OR amp_rating like '%" . $keyword . "%' 
OR min_rpm_rating like '%" . $keyword . "%' OR max_rpm_rating like '%" . $keyword . "%' OR screw_size like '%" . $keyword . "%' 
OR sae_size like '%" . $keyword . "%' OR deep_socket like '%" . $keyword . "%' OR capacity like '%" . $keyword . "%' OR 
gauge_rating like '%" . $keyword . "%' OR blade_material like '%" . $keyword . "%' OR head_weight like '%" . $keyword . "%' 
OR blade_width like '%" . $keyword . "%' OR tine_count like '%" . $keyword . "%' OR bin_material like '%" . $keyword . "%' 
OR bin_volume like '%" . $keyword . "%' OR wheel_count like '%" . $keyword . "%' OR min_torque_rating like '%" . $keyword . "%' 
OR max_torque_rating like '%" . $keyword . "%' OR blade_size like '%" . $keyword . "%' OR tank_size like '%" . $keyword . "%' 
OR pressure_rating like '%" . $keyword . "%' OR motor_rating
like '%" . $keyword . "%' OR drum_size like '%" . $keyword . "%' OR power_rating like '%" . $keyword . "%' OR 
gas_power like '%" . $keyword . "%' OR rubber_feet like '%" . $keyword . "%' OR pail_shelf like '%" . $keyword . "%' OR ST2.category_name like '%" . $keyword . "%') ORDER BY tool_id ";

            $result = mysqli_query($db, $sql);
            $num_rows = mysqli_num_rows($result);


            ?>

            <table>
                <tr>
                    <center><div class="subtitle" style="align-content:center">Your search found the following available tools for rent:</div></center>
                    <td class="heading">Tool ID</td>
                    <td class="heading">Description</td>
                    <td class="heading">Rental Price</td>
                    <td class="heading">Deposit Price</td>
                </tr>

                <?php
                    $_SESSION['power_source'] = $powersource;
                    $maxRows = 25;
                  ##  echo "POWER!!!!!!!  ", $powersource;
                  ##  echo "  CATEGORY!! ", $category;

                    if($category=="All Tools"){
                     if(($subtype=='Saw' or $subtype=='Sander' or $subtype=='Mixer' or $subtype=='Generator' or $subtype=='Drill' or $subtype=='Air-Compressor') and $powersource=="manual"){
                        echo "<p style='margin-left:10px;margin-right:10px;border:2px solid darkred; background-color:darkred; color:white'>We don't have the tool you searched. No ".$subtype. " is manual.";
                     }
                    elseif($subtype!='All' and $subtype!='Saw' and $subtype!='Sander' and $subtype!='Mixer' and $subtype!='Generator' and $subtype!='Drill' and $subtype!='Air-Compressor' and $powersource!="manual" and $powersource!="All"){
                        echo "<p style='margin-left:10px;margin-right:10px;border:2px solid darkred; background-color:darkred; color:white'>We don't have the tool you searched. We only have manual ".$subtype;
                     }
                     elseif(($subtype=='Drill' OR $subtype=='Saw' OR $subtype=='Sander') AND ($powersource!="electric" OR $powersource!="cordless" OR $powersource!="All")){
                         echo "<p style='margin-left:10px;margin-right:10px;border:2px solid darkred; background-color:darkred; color:white'>Warning: ". $subtype . " must use either electric or cordless power.";
                     }
                     elseif(($subtype=='Air-Compressor' OR $subtype=='Mixer') AND ($powersource!="electric" OR $powersource!="gas" OR $powersource!="All")){
                         echo "<p style='margin-left:10px;margin-right:10px;border:2px solid darkred; background-color:darkred; color:white'>Warning: ". $subtype . " must use either electric or gas power.";
                     }
                     elseif($subtype=='Generator' AND ($powersource!="gas" OR $powersource!="All")){
                         echo "<p style='margin-left:10px;margin-right:10px;border:2px solid darkred; background-color:darkred; color:white'>Warning: ". $subtype . " must use only gas power.";
                     }
                    else {

                    if ($num_rows > $maxRows) {
                        echo "<p style='margin-left:10px'>Please narrow your search.  Page will only list ". $maxRows ." tools.  Thank You!";
                        header("Refresh:0");
                    } else {
                        WHILE ($tool = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                            $tool_id = urlencode($tool["tool_id"]);
                            ?>

                            <tr>
                                <td><?php echo $tool["tool_id"]; ?></td>
                                <?php print "<td><a href=\"Dropoff_Tooldetail.php?tool_id=$tool_id\" target=\"_blank\">" . $tool["description"] . "</a></td>"; ?>
                                <td><?php echo "$" . number_format($tool["rental_price"],2); ?></td>
                                <td><?php echo "$" . number_format($tool["deposit_price"],2); ?></td>

                            </tr>
                        <?php }}}} ?>
                    <?php
                    if($category!="All Tools"){
                        if($category!='Power' AND $powersource!="manual" AND $powersource!="All"){echo "<p style='margin-left:10px;margin-right:10px;border:2px solid darkred; background-color:darkred; color:white'>We don't have the tool you searched. All ".$category. " tools are manual.";}
                        elseif($category=='Power' AND $powersource=="manual"){echo "<p style='margin-left:10px;margin-right:10px;border:2px solid darkred; background-color:darkred; color:white'>We don't have the tool you searched. No ".$category. " tools are manual.";}
                        elseif(($subtype=='Drill' OR $subtype=='Saw' OR $subtype=='Sander') AND ($powersource!="electric" OR $powersource!="cordless")){
                            echo "<p style='margin-left:10px;margin-right:10px;border:2px solid darkred; background-color:darkred; color:white'>Warning: ". $subtype . " must use either electric or cordless power.";
                        }
                        elseif(($subtype=='Air-Compressor' OR $subtype=='Mixer') AND ($powersource!="electric" OR $powersource!="gas" OR $powersource!="All")){
                            echo "<p style='margin-left:10px;margin-right:10px;border:2px solid darkred; background-color:darkred; color:white'>Warning: ". $subtype . " must use either electric or gas power.";
                        }
                        elseif($subtype=='Generator' AND ($powersource!="gas" OR $powersource!="All")){
                            echo "<p style='margin-left:10px;margin-right:10px;border:2px solid darkred; background-color:darkred; color:white'>Warning: ". $subtype . " must use only gas power.";
                        }
                    else{
                    if ($num_rows > $maxRows) {
                        echo "<p style='margin-left:10px'>Please narrow your search.  Page will only list ". $maxRows ." tools.  Thank You!";
                        header("Refresh:0");
                    } else {
                    WHILE ($tool = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    $tool_id = urlencode($tool["tool_id"]);
                    ?>

                    <tr>
                        <td><?php echo $tool["tool_id"]; ?></td>
                        <?php print "<td><a href=\"Dropoff_Tooldetail.php?tool_id=$tool_id\" target=\"_blank\">" . $tool["description"] . "</a></td>"; ?>
                        <td><?php echo "$" . number_format($tool["rental_price"],2); ?></td>
                        <td><?php echo "$" . number_format($tool["deposit_price"],2); ?></td>
                    </tr>
                    <?php }}}}

                        ?>

                </table>
        </form>
    </div>
<hr>


    <?php


$_SESSION["Total_Deposit"] = $Total_Deposit;
$_SESSION["Total_Rental"] = $Total_Rental;

}
?>
    <div>



    </div>


</body>
</html>
