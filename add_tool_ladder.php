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

    $stepCount = mysqli_real_escape_string($db, $_POST['stepCount']);
    $weightCapacity = mysqli_real_escape_string($db, $_POST['weightCapacity']);
    $rubberFeet = mysqli_real_escape_string($db, $_POST['rubberFeet']);
    $pailShelf = mysqli_real_escape_string($db, $_POST['pailShelf']);
    $subtype = $_SESSION['subtype'];

    foreach ($_POST as $key => $value) {
        array_push($query_msg, "posting keys: " . $key);
        array_push($query_msg, "posting value: " . $value);
    }

    if (empty($stepCount)) {
        $stepCount = 'null';
    }
    if (empty($weightCapacity)) {
        $weightCapacity = 'null';
    }
    $query = "INSERT INTO Ladder (tool_id, step_count, weight_capacity) VALUES ('$tool_id',$stepCount,$weightCapacity)";
    $results = mysqli_query($db, $query);
    if ($results == true){
        array_push($query_msg,"Writing tool info to Ladder");
    } else{
        array_push($error_msg, "Query Error: Unable write to Ladder...". $query);
        array_push($error_msg,  'Error# '. mysqli_errno($db) . ": " . mysqli_error($db));
    }

    if ($subtype == 'Straight') {
        if (empty($rubberFeet)) {
            $rubberFeet = null;
        } else {
            $rubberFeet = (bool)$rubberFeet;
        }
        $query = "INSERT INTO Straight (tool_id, rubber_feet) VALUES ('$tool_id',$rubberFeet)";
        $results = mysqli_query($db, $query);
        if ($results == true) {
            array_push($query_msg, "Writing tool info to Straight Ladder");
        } else {
            array_push($error_msg, "Query Error: Unable write to Straight..." . $query);
            array_push($error_msg, 'Error# ' . mysqli_errno($db) . ": " . mysqli_error($db));
        }
    }
    if ($subtype == 'Step') {
        if (empty($pailShelf)) {
            $pailShelf = null;
        } else {
            $pailShelf = (bool)$pailShelf;
        }
        $query = "INSERT INTO Step (tool_id, pail_shelf) VALUES ('$tool_id',$pailShelf)";
        $results = mysqli_query($db, $query);
        if ($results == true) {
            array_push($query_msg, "Writing tool info to Step Ladder");
        } else {
            array_push($error_msg, "Query Error: Unable write to Step..." . $query);
            array_push($error_msg, 'Error# ' . mysqli_errno($db) . ": " . mysqli_error($db));
        }
    }
    if($showQueries){
        array_push($query_msg, "tool ID being used: ". $tool_id);
    }
}

?>

<!DOCTYPE html>  <!-- HTML 5 -->
<head>
    <?php include("lib/header.php"); ?>
    <title>AddTool:Ladder Attributes</title>
    <link href="./css/table_new.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="main_container"><?php include("lib/menuNavClerk.php"); ?>
    <div class="center_content">
        <div class="title_name">Add Ladder Type & Subtype Attributes</div>
        <form id="ladder" name="ladder" method="post" action="add_tool_ladder.php">

            <table style="margin-left:10px">
                <tr><td class="heading">Step Count:</td><td class="heading">Weight Capacity (lbs):</td></tr>
                <tr><td><input name="stepCount" type="number" max="100"></td>
                <td><input name="weightCapacity" type="number" max="400"</td></tr>
            </table>
            <h3 style="margin-left:10px">Straight Ladder</h3>
            <table style="margin-left:10px">
                <tr><td class='heading'>Rubber Feet (T/F):</td></tr>
                <tr><td><select name="rubberFeet"><option value=""></option><option value="true">True</option><option value="false">False</option>
                        </select></td></tr>
            </table>
            <h3 style="margin-left:10px">Step Ladder</h3>
            <table style="margin-left:10px">
                <tr><td class='heading'>Pail Shelf (T/F):</td></tr>
                <tr><td><select name="pailShelf"><option value=""></option><option value="true">True</option><option value="false">False</option>
                        </select></td></tr>
            </table>
            <input style="margin-left:10px; margin-top:10px" type="submit" value="Submit Tool Attributes to Database">
        </form>
        <hr>
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
