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

    $headWeight = mysqli_real_escape_string($db, $_POST['headWeight']);

    $query = "INSERT INTO Striking (tool_id, head_weight) VALUES ('$tool_id',$headWeight)";

    $results = mysqli_query($db, $query);
    if ($results == true){
        array_push($query_msg,"Writing tool info to Striking");
    } else{
        array_push($error_msg, "Query Error: Unable write to Striking...". $query);
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
        <div class="title_name">Add Striking Type & Subtype Attributes</div>
        <form id="striking" name="striking" method="post" action="add_tool_striking.php">

        <table style="margin-left:10px">
                <tr><td class='heading'>Handle Material:</td></tr>
                <tr><td><input name="handle" type="text" required></td></tr>
            </table>
            <table style="margin-left:10px">
                <tr><td class='heading'>Head Weight (lbs)</td><td></td></tr>
                <tr><td><input name="headWeight" type="number" min=".1" max="100" step=".1" required></td></tr>
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
