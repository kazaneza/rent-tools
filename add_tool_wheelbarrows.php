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

/*    foreach ($_POST as $key => $value) {
        array_push($query_msg, "posting keys: " . $key);
        array_push($query_msg, "posting value: " . $value);
    }*/

    $handle = mysqli_real_escape_string($db, $_POST['handle']);

    include('./add_tool_queryGarden.php');

    $wheelCount = mysqli_real_escape_string($db, $_POST['wheelCount']);
    $binMaterial = mysqli_real_escape_string($db, $_POST['binMaterial']);
    $binVolume = mysqli_real_escape_string($db, $_POST['binVolume']);

    if ($binVolume >0) {
        $query = "INSERT INTO Wheelbarrows (tool_id, wheel_count, bin_volume, bin_material) VALUES".
            " ('$tool_id',$wheelCount,$binVolume,'$binMaterial')";
    } else{
        $query = "INSERT INTO Wheelbarrows (tool_id, wheel_count, bin_material) VALUES".
            " ('$tool_id',$wheelCount,'$binMaterial')";
    }

    $results = mysqli_query($db, $query);
    if ($results == true){
        array_push($query_msg,"Writing tool info to Wheelbarrows");
    } else{
        array_push($error_msg, "Query Error: Unable write to Wheelbarrows...". $query);
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
    <title>AddTool:Sub-Type Attributes</title>
    <link href="./css/table_new.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="main_container"><?php include("lib/menuNavClerk.php"); ?>
    <div class="center_content">
        <div class="title_name">Add Wheelbarrows Type & Subtype Attributes</div>
        <form id="wheelbarrows" name="wheelbarrows" method="post" action="add_tool_wheelbarrows.php">

        <table style="margin-left:10px">
                <tr><td class='heading'>Handle Material:</td><td class="heading">Bin Material</td></tr>
                <tr><td><input name="handle" type="text" required></td>
                    <td><input name="binMaterial" type="text" required></td></tr>
            </table>
            <table style="margin-left:10px">
                <tr><td class='heading'>Wheel Count</td><td class="heading">Bin Volume (cu ft)></td></tr>
                <tr><td><input name="wheelCount" type="number" value="1" min="1" max="8" step="1" required></td>
                    <td><input name="binVolume" type="number" min="1" max="10" step=".1"></td></tr>

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
