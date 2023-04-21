<?php

include('lib/common.php');
// written by carol cheung ccheung39@gatech.edu

if (!isset($_SESSION['email'])) {
    header('Location: add_tool.php');
    exit();
}
include('lib/show_queries.php');

//access most recent added_tool via $_SESSION assignment in add_tool.php
$tool_id = $_SESSION['tool_id'];

if( $_SERVER['REQUEST_METHOD'] == 'POST') {

    foreach ($_POST as $key => $value) {
        array_push($query_msg, "posting keys: " . $key);
        array_push($query_msg, "posting value: " . $value);
    }

    if (isset($_POST['batteryType'])){
        $volt = mysqli_real_escape_string($db, $_POST['batteryVolt']);
    } else{
        $volt = mysqli_real_escape_string($db, $_POST['volt']);
    }

    $speedMin = mysqli_real_escape_string($db, $_POST['speedMin']);
    $speedMax = mysqli_real_escape_string($db, $_POST['speedMax']);

    $blade1 = mysqli_real_escape_string($db, $_POST['blade_size']); //attribute of Saw
    $blade2 = mysqli_real_escape_string($db, $_POST['bladeFraction']);
    $bladeSize = (float)$blade1 + (float)$blade2;

    $amp = mysqli_real_escape_string($db, $_POST['ampNum']);
    $ampUnit = mysqli_real_escape_string($db, $_POST['ampUnit']);

    if ($ampUnit == 'milli'){
        $amp = floatval($amp)/1000;
    } else if ($ampUnit == 'kilo'){
        $amp = floatval($amp)*1000;
    }

    include('./add_tool_queryPower.php');

    if (isset($_POST['batteryType'])){
        $batteryType = mysqli_real_escape_string($db, $_POST['batteryType']);
        $volt = mysqli_real_escape_string($db, $_POST['batteryVolt']);
        $batteryNum = mysqli_real_escape_string($db, $_POST['batteryQuantity']);

        //add tool to Accessories
        $query = "INSERT INTO Accessories (tool_id, accessory_description, quantity) VALUES ('$tool_id',".
            "'D/C Batteries',$batteryNum)";
        $results = mysqli_query($db, $query);

        if ($results == true){
            array_push($query_msg,"Writing battery info to Accessories");
        } else{
            array_push($error_msg, "Query Error: Unable write to Accessories...". $query);
            array_push($error_msg,  'Error# '. mysqli_errno($db) . ": " . mysqli_error($db));
        }
        //add tool to DC_Cordless table
        include('./add_tool_queryDC_Cordless.php');

    }
    if ($bladeSize > 0) {
        $query = "INSERT INTO Saw (tool_id,blade_size) VALUES ('$tool_id',$bladeSize)";
        $results = mysqli_query($db, $query);
        if ($results == true){
            array_push($query_msg,"Writing tool info to Saw");
        } else{
            array_push($error_msg, "Query Error: Unable write to Saw...");
            array_push($error_msg,  'Error# '. mysqli_errno($db) . ": " . mysqli_error($db));
        }
    } else {
        array_push($error_msg, "Query Error: Saw requires Blade Size to be non-zero...");
    }



    if($showQueries){
        array_push($query_msg, "tool ID being used: ". $tool_id);
    }
}

?>

<!DOCTYPE html>  <!-- HTML 5 -->
<head>
    <?php include("lib/header.php"); ?>
    <title>AddTool Saw:Sub-Type Attributes</title>
    <link href="./css/table_new.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="main_container"><?php include("lib/menuNavClerk.php"); ?>
    <div class="center_content">
        <div class="title_name">Add Saw Type & Subtype Attributes</div>
        <form id="saw" name="saw" method="post" action="add_tool_saw.php">

        <?php if ($_SESSION['power'] == 'cordless'): ?>
            <div id='batteries'>
            <h3 style="margin-left:10px">Enter Battery Information</h3>
            <table style="margin-left:10px"><tr><td class='heading'>Battery type:</td><td class='heading'>Quantity:</td><td class='heading'>DC Voltage:</td></tr>
            <tr><td><select name='batteryType' required><option value='Li-Ion'>Li-Ion</option><option value='NiCd'>NiCd</option>
            <option value='NiMH'>NiMH</option></select></td>
            <td><input id='batteryQuantity' name='batteryQuantity' type='number' value='1' min='1' max='5' required /></td>
                <td><input id='batteryVolt' name='batteryVolt' type='number' value='18' min='7.2' max='80' step=".1" required /></td></tr></table>
            </div>
            <hr>
        <?php endif; ?>
        <h3 style="margin-left:10px">Add Saw Attributes</h3>
            <table style="margin-left:10px">
                <tr><td class='heading'>Speed Min(rpm):</td><td class='heading'>Speed Max(rpm):</td></tr>
                <tr><td><input name="speedMin" type="number" value="300" min="300" max="9000" required/></td>
                    <td><input name="speedMax" type="number" min="300" max="9000"/></td></tr></table>
            <table style="margin-left:10px">
                <tr><td class='heading'>Amp Rating:</td><td class='heading'>Amp Units:</td></tr>
                <tr><td><input name="ampNum" type="number" value="1" min="1" max="999" step=".1" required/></td>
                    <td><select name="ampUnit"><option value="milli">mA</option><option value="amps">Amps</option>
                        <option value="kilo">kA</option></select> </td></tr></table>
            <table style="margin-left:10px">
                <tr><td class="heading">Blade Size:</td><td></td></tr>
                <tr><td><input name="blade_size" type="number" value="0" min="0" max="12" required></td></input></td>
                    <td><select name="bladeFraction"><option value="0">0</option><option value=".125">1/8</option>
                        <option value=".25">1/4</option><option value=".375">3/8</option><option value=".5">1/2</option>
                        <option value=".625">5/8</option><option value=".75">3/4</option><option value=".875">7/8</option>
                        </select></td></tr>
                <?php if (isset($_POST['blade_size']) && ($_POST['blade_size'] == '0' && $_POST['bladeFraction'] == '0')): ?>
                    <script>
                        alert("Error writing Saw tool to database. Blade Size must be non-zero");
                    </script>
                <?php endif; ?>
            </table>
            <table style="margin-left:10px">
            <?php if ($_SESSION['power'] == 'cordless'): ?>
                <tr><td class='heading'>Volt Rating:</td></tr>
                <tr><td><input type="text" name="volt" placeholder="Cordless Battery" disabled/></td>
            <?php else: ?>
                <tr><td class='heading'>Volt Rating (V):</td></tr>
                <tr><td><select name="volt" id="volt" required><option value="110">110</option><option value="120">120</option>
                            <option value="220">220</option><option value="220">240</option></td></tr>
            <?php endif; ?>
            </table>
            <input style="margin-left:10px;margin-top:10px" type="submit" value="Submit Tool Attributes to Database">
        </form>
        <hr>
        <form action="add_saw_accessories.php">
            <input style="margin-left:10px" type="submit" value="Add Accessories for This Power Tool">
        </form>
        <form action="clerk_menu.php">
            <input style="margin-left:10px" type="submit" value="Done Adding Tool. Go to Clerk Menu">
        </form>
    </div>


    <?php include("lib/error.php"); ?>
    <div class="clear"></div>
<?php include("lib/footer.php"); ?>
</div>
</body>
</html>
