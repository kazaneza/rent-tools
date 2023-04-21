<?php

include('lib/common.php');
// written by carol cheung ccheung39@gatech.edu

if (!isset($_SESSION['email'])) {
    header('Location: add_tool.php');
    exit();
}
include('lib/show_queries.php');


if( $_SERVER['REQUEST_METHOD'] == 'POST') {

    foreach ($_POST as $key => $value) {
        array_push($query_msg, "posting keys: " . $key);
        array_push($query_msg, "posting value: " . $value);
    }
    $enteredEmail = mysqli_real_escape_string($db, $_POST['email']);

    /*$query = "INSERT INTO Tool (sub_option,sub_type_name,width_diameter,length,weight,manufacturer,material" .
        "clerk_id) VALUES ($suboption,$subtype,$widthDiam,$length,$weight,$manufacturer,$material,$clerkID)";
    $results = mysqli_query($db, $query);
    if (is_bool($results) && (mysqli_num_rows($results) == 0)){
        array_push($error_msg, "Query Error: No subtypes for this category...");*/
    if($showQueries){
        array_push($query_msg, "category: ". $enteredEmail);
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
        <div class="title_name">Add Tool Accessories</div>
        <h3>Add Accessories</h3>
        <p>Enter number of accessories to add:&nbsp
            <input id="nAccessories" name="nAccessories" type="number" value="0" min="0" max="8" /></p>
        <div id="addAccessories" style="display:none">
            <p><b>Accessory Quantity:              Accessory Description:</b></p>
            <form>
            <div id="accessoryFields"></div>
        </div>

        <input type="submit" value="Submit Tool Accessories to Database"
        </form>
    </div>
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
        var html1 = "<input type='number' name='accNum'",
            html2 = " value='1' min='1' max='5' />";

        var htmlSelect = "<select name='AccessType' required><option value='Drill Bits'>Drill Bits</option>" +
            "<option value='Saw Blade'>Saw Blade</option><option value='Soft Case'>Soft Case</option>" +
            "<option value='Hard Case'>Hard Case</option><option value='D/C Battery Charger'>D/C Battery Charger</option>" +
            "<option value='Safety Hat'>Safety Hat</option><option value='Safety Pants'>Safety Pants</option>" +
            "<option value='Safety Goggles'>Safety Goggles</option><option value='Safety Vest'>Safety Vest</option>" +
            "<option value='Hose'>Hose</option><option value='Gas Tank'>Gas Tank</option></select>";

        if (numDisplay >=0){
            for (var i=0; i<numDisplay; i++){
                var div = document.createElement("div");
                div.innerHTML = html1 + String(i) + html2 + htmlSelect;
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


