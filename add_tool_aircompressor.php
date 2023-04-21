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

    $volt = mysqli_real_escape_string($db, $_POST['volt']);

    $speedMin = mysqli_real_escape_string($db, $_POST['speedMin']);
    $speedMax = mysqli_real_escape_string($db, $_POST['speedMax']);

    $tanksize = mysqli_real_escape_string($db, $_POST['tankSize']); //attribute of air compressor
    $pressure = mysqli_real_escape_string($db, $_POST['pressure']);

    $amp = mysqli_real_escape_string($db, $_POST['ampNum']);
    $ampUnit = mysqli_real_escape_string($db, $_POST['ampUnit']);

    if ($ampUnit == 'milli'){
        $amp = floatval($amp)/1000;
    } else if ($ampUnit == 'kilo'){
        $amp = floatval($amp)*1000;
    }

    include('./add_tool_queryPower.php');

    if (empty($pressure)){
        $query = "INSERT INTO AirCompressor (tool_id,tank_size) VALUES ('$tool_id',$tanksize)";
    } else {
        $query = "INSERT INTO AirCompressor (tool_id,tank_size, pressure_rating) VALUES ('$tool_id',$tanksize,$pressure)";
    }
    $results = mysqli_query($db, $query);
    if ($results == true) {
        array_push($query_msg, "Writing tool info to Air Compressor");
    } else {
        array_push($error_msg, "Query Error: Unable write to Air Compressor...". $query);
        array_push($error_msg,  'Error# '. mysqli_errno($db) . ": " . mysqli_error($db));
    }

    if($showQueries){
        array_push($query_msg, "tool ID being used: ". $tool_id);
    }
}

?>

<!DOCTYPE html>  <!-- HTML 5 -->
<head>
    <?php include("lib/header.php"); ?>
    <title>AddTool Air Compressor:Sub-Type Attributes</title>
    <link href="./css/table_new.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="main_container"><?php include("lib/menuNavClerk.php"); ?>
    <div class="center_content">
        <div class="title_name">Add Tool Type & Subtype Attributes</div>
        <form id="aircompresor" name="aircompressor" method="post" action="add_tool_aircompressor.php">

        <h3 style="margin-left:10px">Add Air Compressor Attributes</h3>
            <table style="margin-left:10px">
                <tr><td class='heading'>Speed Min(rpm):</td><td class='heading'>Speed Max(rpm):</td></tr>
                <tr><td><input name="speedMin" type="number" value="300" min="300" max="9000" required/></td>
                    <td><input name="speedMax" type="number" min="300" max="9000"/></td></tr></table>
            <table style="margin-left:10px">
                <tr><td class="heading">Tank Size (gal):</td><td class="heading">Pressure Rating (psi):</td></tr>
                <tr><td><input name="tankSize" type="number" value="1" min="1" max="300" step=".1" required></td>
                    <td><input name="pressure" type="number" min="45" max="175" step=".1"</td></tr>
            </table>

            <?php if ($_SESSION['power'] == 'electric'): ?>
            <table style="margin-left:10px">
                <tr><td class='heading'>Amperage Rating (Amp):</td><td class='heading'>Amp Units:</td></tr>
                <tr><td><input name="ampNum" type="number" value="1" min="1" max="999" step=".1" required/></td>
                    <td><select name="ampUnit"><option value="milli">mA</option><option value="amps">Amps</option>
                        <option value="kilo">kA</option></select> </td></tr><tr></tr>
                <tr><td class='heading'>Volt Rating (V):</td></tr>
                <tr><td><select name="volt" id="volt" required><option value="110">110</option><option value="120">120</option>
                            <option value="220">220</option><option value="220">240</option></td></tr>
            </table>
            <?php endif; ?>

            <input style="margin-left:10px;margin-top:10px" type="submit" value="Submit Tool Attributes to Database">
        </form>
        <hr>
        <form action="add_aircompressor_accessories.php">
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
