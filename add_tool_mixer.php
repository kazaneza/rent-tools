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

    $motor1 = mysqli_real_escape_string($db, $_POST['motor']); //attribute of mixer
    $motor2 = mysqli_real_escape_string($db, $_POST['motorFraction']);
    $motor = (float)$motor1 + (float)$motor2;
    $drum = mysqli_real_escape_string($db, $_POST['drum']);

    $amp = mysqli_real_escape_string($db, $_POST['ampNum']);
    $ampUnit = mysqli_real_escape_string($db, $_POST['ampUnit']);

    if ($ampUnit == 'milli'){
        $amp = floatval($amp)/1000;
    } else if ($ampUnit == 'kilo'){
        $amp = floatval($amp)*1000;
    }

    include('./add_tool_queryPower.php');

    if ($motor >0 & $drum > 0) {
        $query = "INSERT INTO Mixer (tool_id, motor_rating, drum_size) VALUES ('$tool_id', $motor, $drum)";
        $results = mysqli_query($db, $query);

        if ($results == true) {
            array_push($query_msg, "Writing tool info to Mixer");
        } else {
            array_push($error_msg, "Query Error: Unable write to Mixer..." . $query);
            array_push($error_msg,  'Error# '. mysqli_errno($db) . ": " . mysqli_error($db));
        }
    }
    else{
        array_push($error_msg, "Query Error: Mixer requires Motor Rating and Drum Size to be non-zero...");
    }

    if($showQueries){
        array_push($query_msg, "tool ID being used: ". $tool_id);
    }
}

?>

<!DOCTYPE html>  <!-- HTML 5 -->
<head>
    <?php include("lib/header.php"); ?>
    <title>AddTool Mixer:Sub-Type Attributes</title>
    <link href="./css/table_new.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="main_container"><?php include("lib/menuNavClerk.php"); ?>
    <div class="center_content">
        <div class="title_name">Add Tool Type & Subtype Attributes</div>
        <form id="mixer" name="mixer" method="post" action="add_tool_mixer.php">

        <h3 style="margin-left:10px">Add Mixer Attributes</h3>
            <table style="margin-left:10px">
                <tr><td class='heading'>Speed Min(rpm):</td><td class='heading'>Speed Max(rpm):</td></tr>
                <tr><td><input name="speedMin" type="number" value="300" min="300" max="9000" required/></td>
                    <td><input name="speedMax" type="number" min="300" max="9000"/></td></tr></table>
            <table style="margin-left:10px">
                <tr><td class="heading">Motor Rating (hp):</td><td></td></tr>
                <tr><td><input name="motor" type="number" value="0" min="0" max="5" step="1"></td>
                    <td><select name="motorFraction"><option value="0">0</option><option value=".125">1/8</option>
                            <option value=".25">1/4</option><option value=".375">3/8</option><option value=".5">1/2</option>
                            <option value=".625">5/8</option><option value=".75">3/4</option><option value=".875">7/8</option></td></tr>
                    <tr><td class="heading">Drum Size (cu ft)</td></tr>
                <td><input name="drum" type="number" min="1" max="10" step=".1" required></td></tr>

                <?php if (isset($_POST['motor']) && ($_POST['motor'] == '0' && $_POST['motorFraction'] == '0')): ?>
                    <script>
                        alert("Error writing Mixer tool to database. Motor rating must be non-zero");
                    </script>
                <?php endif; ?>
            </table>

            <?php if ($_SESSION['power'] == 'electric'): ?>
            <table style="margin-left:10px">
                <tr><td class='heading'>Amp Rating:</td><td class='heading'>Amp Units:</td></tr>
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
        <form action="add_mixer_accessories.php">
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
