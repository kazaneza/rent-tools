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

    $speedMin = mysqli_real_escape_string($db, $_POST['speedMin']);
    $speedMax = mysqli_real_escape_string($db, $_POST['speedMax']);

    $powerRate = mysqli_real_escape_string($db, $_POST['powerRating']); //attribute of generator

    include('./add_tool_queryPower.php');

    $query = "INSERT INTO Generator (tool_id, power_rating) VALUES ('$tool_id', $powerRate)";
    $results = mysqli_query($db, $query);

    if ($results == true) {
        array_push($query_msg, "Writing tool info to Generator");
    } else {
        array_push($error_msg, "Query Error: Unable write to Generator..." . $query);
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
    <title>AddTool Generator:Sub-Type Attributes</title>
    <link href="./css/table_new.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="main_container"><?php include("lib/menuNavClerk.php"); ?>
    <div class="center_content">
        <div class="title_name">Add Tool Type & Subtype Attributes</div>
        <form id="generator" name="generator" method="post" action="add_tool_generator.php">

        <h3 style="margin-left:10px">Add Generator Attributes</h3>
            <table style="margin-left:10px">
                <tr><td class='heading'>Speed Min(rpm):</td><td class='heading'>Speed Max(rpm):</td></tr>
                <tr><td><input name="speedMin" type="number" value="500" min="500" max="5000" step="10" required/></td>
                    <td><input name="speedMax" type="number" min="500" max="5000" step="10"/></td></tr></table>
            <table style="margin-left:10px">
                <tr><td class="heading">Power Rating (W):</td>
                    <td><input name="powerRating" type="number" value="2000" min="70" max="10000" step="10" required></td></tr>
            </table>

            <input style="margin-left:10px;margin-top:px" type="submit" value="Submit Tool Attributes to Database">
        </form>
        <hr>
        <form action="add_generator_accessories.php">
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
