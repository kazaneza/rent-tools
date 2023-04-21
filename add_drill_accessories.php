<?php

include('lib/common.php');
// written by carol cheung ccheung39@gatech.edu

if (!isset($_SESSION['email'])) {
    header('Location: add_tool.php');
    exit();
}
include('lib/show_queries.php');


if( $_SERVER['REQUEST_METHOD'] == 'POST') {

    /*foreach ($_POST as $key => $value) {
        array_push($query_msg, "posting keys: " . $key);
        array_push($query_msg, "posting value: " . $value);
    }*/

    $lenArray = count($_POST['accNum']);
    $tool_id = $_SESSION['tool_id'];
    for ($i=0;$i<$lenArray;$i++){
        $accAmt = (int)$_POST['accNum'][$i];
        $accType = $_POST['accessType'][$i];
        //array_push($query_msg, "Accessory: ". $accType ." ". $accAmt);

        include('add_tool_queryAccessories.php');
    }
/*    foreach ($_POST['accNum'] as $eachValue) {
        array_push($query_msg, "eachValue: " . $eachValue);
    }
    foreach ($_POST['accessType'] as $eachValue) {
        array_push($query_msg, "Accessory: " . $eachValue);
    }
    if($showQueries){
        array_push($query_msg, "tool_id: ". $tool_id);
        array_push($query_msg, "description: ". $accType);
        array_push($query_msg, "number: ". $accAmt);
    }*/
}
?>

<!DOCTYPE html>  <!-- HTML 5 -->
<head>
    <?php include("lib/header.php"); ?>
    <title>AddTool:Drill Accessories</title>
    <link href="./css/table_new.css" rel="stylesheet" type="text/css">


</head>
<body>
<div id="main_container"><?php include("lib/menuNavClerk.php"); ?>
    <div class="center_content">
        <div class="title_name">Add Drill Accessories</div>
        <h3 style="margin-left:10px">Add Accessories</h3>
        <p style="margin-left:10px">Enter number of accessories to add:&nbsp
            <input style="margin-left:10px" id="nAccessories" name="nAccessories" type="number" value="0" min="0" max="8" /></p>
        <div id="addAccessories" style="display:none">
            <p style="margin-left:10px"><b>Accessory Quantity:              Accessory Description:</b></p>
            <form id="accessories" action="add_drill_accessories.php" method="post">
            <div id="accessoryFields" style="margin-left:10px"></div>
        </div>

        <input style="margin-left:10px" type="submit" value="Submit Tool Accessories to Database"/>
        </form>
    </div>
    <?php include("lib/error.php"); ?>
    <div class="clear"></div>
    <?php include("lib/footer.php"); ?>
</div>
<script>
    //code adapted from: http://jsfiddle.net/girlie_mac/CcqU7/ and
    //http://www.girliemac.com/blog/2011/11/27/html5-input-events/
    var numAccessoryFields = document.getElementById("nAccessories"),
        accessoryDiv = document.getElementById("addAccessories"),
        accessoryFields = document.getElementById("accessoryFields");

    nAccessories.addEventListener("input", function(e){
        accessoryDiv.style.display = "block";
        var num = numAccessoryFields.value;

        //count pre-filled fields
        var numNode = accessoryFields.childNodes.length,
            numDisplay = num-numNode;

        //populate fields
        var html1 = "<input type='number' name=",
            html2 = " value='1' min='1' max='5' />";

        <?php if($_SESSION['power'] == 'cordless'): ?>
            var htmlSelect = "<select name='accessType[]' required><option value='Drill Bits'>Drill Bits</option>" +
                "<option value='Soft Case'>Soft Case</option>" +
                "<option value='Hard Case'>Hard Case</option><option value='D/C Battery Charger'>D/C Battery Charger</option>" +
                "<option value='Safety Hat'>Safety Hat</option><option value='Safety Pants'>Safety Pants</option>" +
                "<option value='Safety Goggles'>Safety Goggles</option><option value='Safety Vest'>Safety Vest</option>" +
                "</select>";
        <?php else: ?>
            var htmlSelect = "<select name='accessType[]' required><option value='Drill Bits'>Drill Bits</option>" +
                "<option value='Soft Case'>Soft Case</option>" +
                "<option value='Hard Case'>Hard Case</option>" +
                "<option value='Safety Hat'>Safety Hat</option><option value='Safety Pants'>Safety Pants</option>" +
                "<option value='Safety Goggles'>Safety Goggles</option><option value='Safety Vest'>Safety Vest</option>" +
                "</select>";
        <?php endif; ?>

        if (numDisplay >=0){
            for (var i=0; i<numDisplay; i++){
                var div = document.createElement("div");
                //accString = "accNum" + String(i);
                accString = "accNum[]";
                numString = html1 + "'" + accString + "'" + html2 + htmlSelect;
                //alert("this is what is getting printed in Accessory number tag: "+numString);
                div.innerHTML = numString
                accessoryFields.appendChild(div);
            }
        } else{
            var numDelete = Math.abs(numDisplay);
            for (var i=0; i<numDelete; i++){
                accessoryFields.removeChild(accessoryFields.lastChild);
            }
        }
    }, false);
</script>
</body>
</html>


