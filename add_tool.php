<?php
/**
 * Add Tool  carol cheung ccheung39@gatech.edu
 * 
 * https://www.w3.org/TR/WCAG20-TECHS/SCR19.html
 */
include('lib/common.php');
// written by carol cheung ccheung39@gatech.edu

if (!isset($_SESSION['email'])) {
    header('Location: clerk_menu.php');
    exit();
}

include('lib/show_queries.php');

//array_push($query_msg,"session email is: ". $_SESSION['email']);

if( $_SERVER['REQUEST_METHOD'] == 'POST') {

    //reset all fields to allow adding more tools
    if (isset($_POST['reset']) && $_POST['reset'] == 'Reset'){
        $_SESSION['power'] = null;
        $_SESSION['subtype'] = null;
        $_SESSION['category'] = null;
        $_SESSION['tool_id'] = null;
    }

    //to check all posting variables
/*     foreach ($_POST as $key => $value){
        array_push($query_msg, "posting keys: ". $key);
        array_push($query_msg, "posting value: ". $value);
    }

    //to check all session variables
    foreach ($_SESSION as $key => $value){
        array_push($query_msg, "posting keys: ". $key);
        array_push($query_msg, "posting value: ". $value);
    }*/

    if (isset($_POST['category_type']) && $_POST['category_type']!= null){
        $enteredEmail = mysqli_real_escape_string($db, $_SESSION['email']);
        $category = mysqli_real_escape_string($db, $_POST['category_type']);
        $powersource = mysqli_real_escape_string($db, $_POST['set_power']);

        $query = "SELECT DISTINCT sub_type_name FROM SubType WHERE category_name='$category'";
        $resultSubtype = mysqli_query($db, $query);
        $result = $resultSubtype;

        if (is_bool($result) && (mysqli_num_rows($result) == 0)){
            array_push($error_msg, "Query Error: No subtypes for this category...");
        }
        if (!isset($_SESSION['power']) || $_SESSION['power']== null){
            $_SESSION['power'] = $powersource;
        }
        if (!isset($_SESSION['category']) || $_SESSION['category']== null){
            $_SESSION['category'] = $category;
        }

    } else if(isset($_POST['subtypeDropdn']) && strcasecmp($_POST['subtypeDropdn'],'dummy')!==0){

        $subtype = mysqli_real_escape_string($db, $_POST['subtypeDropdn']);
        $query = "SELECT sub_option FROM SubType WHERE sub_type_name='$subtype'";
        $resultOption = mysqli_query($db, $query);
        $result = $resultOption;
        if (is_bool($result) && (mysqli_num_rows($result) == 0)){
            array_push($error_msg, "Query Error: No suboptions for this ". $subtype ."...");
        }

        $_SESSION['subtype'] = $subtype;
        array_push($query_msg, "In PHP subtype: " . $_SESSION['subtype']);
    } else if(isset($_POST['optionDropdn']) && (strcasecmp($_POST['optionDropdn'],'dummy')!=0) && $_POST['weight']>0){
        $material = null;
        $clerkID = $_SESSION['id'];
        $powersource = $_SESSION['power'];
        $subtype = $_SESSION['subtype'];
        $suboption = mysqli_real_escape_string($db, $_POST['optionDropdn']);
        $widthDiam1 = mysqli_real_escape_string($db, $_POST['widthdiam']);
        $widthDiam2 = mysqli_real_escape_string($db, $_POST['wdDropdn']);
        $widthDiamUnit = mysqli_real_escape_string($db, $_POST['wdunit']);
        $length1 = mysqli_real_escape_string($db, $_POST['length']);
        $length2 = mysqli_real_escape_string($db, $_POST['lDropdn']);
        $lengthUnit = mysqli_real_escape_string($db, $_POST['lunit']);
        $weight = mysqli_real_escape_string($db, $_POST['weight']);
        $originalPrice = mysqli_real_escape_string($db, $_POST['originalPrice']);
        $manufacturer = mysqli_real_escape_string($db, $_POST['manufacturer']);
        $material = mysqli_real_escape_string($db, $_POST['material']);

        $widthDiam = floatval($widthDiam1) + floatval($widthDiam2);
        if ($widthDiamUnit == 'ft'){
            $widthDiam = 12*$widthDiam;
        }
        $length = floatval($length1) + floatval($length2);
        if ($widthDiamUnit == 'ft'){
            $length = 12*$length;
        }
        $weight = floatval($weight);

        $queryTool = "INSERT INTO Tool (sub_option,sub_type_name,width_diameter,length,weight," .
            "manufacturer,clerk_id,original_price,power_source,material) VALUES ('$suboption','$subtype'," .
            "'$widthDiam','$length','$weight','$manufacturer','$clerkID','$originalPrice','$powersource','$material');";
        $resultsTool = mysqli_query($db, $queryTool);



        if (is_bool($resultsTool)){
            if ($resultsTool == true){
                $_SESSION['tool_id'] = mysqli_insert_id($db);
                array_push($query_msg,"Writing to database Tool ID: ".$_SESSION['tool_id']);

                if ($_SESSION['category'] == 'hand'){
                    $tool_id = $_SESSION['tool_id'];
                    $query = "INSERT INTO Hand (tool_id) VALUES ('$tool_id')";
                    $results = mysqli_query($db, $query);
                    if ($results == true){
                        array_push($query_msg,"Writing tool attributes to Hand");
                    } else{
                        array_push($error_msg, "Query Error: Unable write to Hand...". $query);
                        array_push($error_msg,  'Error# '. mysqli_errno($db) . ": " . mysqli_error($db));
                    }
                }

            } else{
                array_push($error_msg, "Query Error: Unable to add tool to database...");
                array_push($error_msg,  'Error# '. mysqli_errno($db) . ": " . mysqli_error($db));
            }
        }

        if ($_SESSION['power'] == 'null' || !isset($_SESSION['power'])){
            print "<alert('Submit invalid: power source must be selected.')>";
        } else if ($_SESSION['suboption'] == 'null' || !isset($_SESSION['suboption'])) {
            print "<alert('Submit invalid: Suboption must be selected.')>";
        } else if ($_SESSION['subtype'] == 'null' || !isset($_SESSION['subtype'])) {
            print "<alert('Submit invalid: Sub-type must be selected.')>";
        }

        array_push($query_msg, "query: ". $queryTool);
        if( mysqli_errno($db) > 0 ) {
            array_push($error_msg,  'Error# '. mysqli_errno($db) . ": " . mysqli_error($db));
        }
    }

    /*******************************  HAND TOOL ATTRIBUTES ****************************/

    if (isset($_POST['screwSize']) && $_POST['screwSize'] >0) {
        $tool_id = $_SESSION['tool_id'];
        $screwSize = mysqli_real_escape_string($db, $_POST['screwSize']);
        $query = "INSERT INTO Screwdriver (tool_id, screw_size) VALUES ('$tool_id',$screwSize)";
        $results = mysqli_query($db, $query);
        if ($results == true) {
            array_push($query_msg, "Writing attributes to Screwdriver");
        } else {
            array_push($error_msg, "Query Error: Unable write to Screwdriver..." . $query);
            array_push($error_msg, 'Error# ' . mysqli_errno($db) . ": " . mysqli_error($db));
        }
    }
    elseif ((isset($_POST['saeSize']) and $_POST['saeSize'] !== null) OR (isset($_POST['saeFraction']) and $_POST['saeFraction'] !== null)) {
        $tool_id = $_SESSION['tool_id'];
        $driveSize = mysqli_real_escape_string($db, $_POST['driveSize']);
        $saeSize = mysqli_real_escape_string($db, $_POST['saeSize']);
        $driveFraction = mysqli_real_escape_string($db, $_POST['driveFraction']);
        $saeFraction = mysqli_real_escape_string($db, $_POST['saeFraction']);
        $drive = (float)$driveSize + (float)$driveFraction;
        $sae = (float)$saeSize + (float)$saeFraction;
        $deep = mysqli_real_escape_string($db, $_POST['deep']);
        if (empty($deep)) {
            $query = "INSERT INTO Socket (tool_id, drive_size, sae_size) VALUES ($tool_id,$drive,$sae)";
        } else {
            $deep = (bool)$deep;
            $query = "INSERT INTO Socket (tool_id, drive_size, sae_size, deep_socket) VALUES " .
                "($tool_id, $drive, $sae,$deep)";
        }
        $results = mysqli_query($db, $query);
        if ($results == true) {
            array_push($query_msg, "Writing attributes to Socket");
        } else {
            array_push($error_msg, "Query Error: Unable write to Socket..." . $query);
            array_push($error_msg, 'Error# ' . mysqli_errno($db) . ": " . mysqli_error($db));
        }
    }
    elseif (isset($_POST['driveSize']) && $_POST['driveSize'] !== null) {
        $tool_id = $_SESSION['tool_id'];
        $driveSize = mysqli_real_escape_string($db, $_POST['driveSize']);
        $query = "INSERT INTO Ratchet (tool_id, drive_size) VALUES ($tool_id,$driveSize)";
        $results = mysqli_query($db, $query);
        if ($results == true) {
            array_push($query_msg, "Writing attributes to Ratchet");
        } else {
            array_push($error_msg, "Query Error: Unable write to Ratchet..." . $query);
            array_push($error_msg, 'Error# ' . mysqli_errno($db) . ": " . mysqli_error($db));
        }
    }
    elseif (isset($_POST['adjustable']) && $_POST['adjustable'] !== null) {
        $tool_id = $_SESSION['tool_id'];
        $adjustable = mysqli_real_escape_string($db, $_POST['adjustable']);
        $adjustable = (bool)$adjustable;
        $query = "INSERT INTO Pliers (tool_id, adjustable) VALUES ('$tool_id',$adjustable)";
        $results = mysqli_query($db, $query);
        if ($results == true) {
            array_push($query_msg, "Writing attributes to Pliers");
        } else {
            array_push($error_msg, "Query Error: Unable write to Pliers..." . $query);
            array_push($error_msg, 'Error# ' . mysqli_errno($db) . ": " . mysqli_error($db));
        }
    }
    elseif (isset($_POST['capacity']) && $_POST['capacity'] >0) {
        $tool_id = $_SESSION['tool_id'];
        $capacity = mysqli_real_escape_string($db, $_POST['capacity']);
        if (isset($_POST['gauge']) && $_POST['gauge'] !== null){
            $gauge = (int)$_POST['gauge'];
            $query = "INSERT INTO Gun (tool_id, capacity, gauge_rating) VALUES ('tool_id',$capacity,$gauge)";
        } else{
            $query = "INSERT INTO Gun (tool_id, capacity) VALUES ('tool_id',$capacity)";
        }
        $results = mysqli_query($db, $query);
        if ($results == true) {
            array_push($query_msg, "Writing attributes to Gun");
        } else {
            array_push($error_msg, "Query Error: Unable write to Gun..." . $query);
            array_push($error_msg, 'Error# ' . mysqli_errno($db) . ": " . mysqli_error($db));
        }
    }
    elseif (isset($_POST['antiVibration']) && $_POST['antiVibration'] !== null) {
        $tool_id = $_SESSION['tool_id'];
        $antiVibration = mysqli_real_escape_string($db, $_POST['antiVibration']);
        $antiVibration = (bool)$antiVibration;
        $query = "INSERT INTO Hammer (tool_id, anti_vibration) VALUES ('$tool_id',$antiVibration)";
        $results = mysqli_query($db, $query);
        if ($results == true) {
            array_push($query_msg, "Writing attributes to Hammer");
        } else {
            array_push($error_msg, "Query Error: Unable write to Hammer..." . $query);
            array_push($error_msg, 'Error# ' . mysqli_errno($db) . ": " . mysqli_error($db));
        }
    }

    if($showQueries) {
        //array_push($query_msg, "subtype: " . $subtype);
    }
}
?>

<!DOCTYPE html>  <!-- HTML 5 -->
<head>
<?php include("./lib/header.php"); ?>
<title>Add Tool</title>
<link href="./css/table_new.css" rel="stylesheet" type="text/css">
<script>
    //this function seems to only work here
    function sendCategory(idVal) {
        var category = idVal;
        //alert("var " + category);

        if(category === "power"){
            //alert("turning on power for " + category + " tools");
            document.getElementById("set_power").options[0].disabled = true;
            document.getElementById("set_power").options[1].disabled = false;
            document.getElementById("set_power").options[2].disabled = false;
            document.getElementById("set_power").options[3].disabled = false;
        }
        else if (category !== "power"){
            document.getElementById("set_power").options[0].disabled = false;
            document.getElementById("set_power").options[1].disabled = true;
            document.getElementById("set_power").options[2].disabled = true;
            document.getElementById("set_power").options[3].disabled = true;
        }
    }
</script>
</head>
<body>
<div id="main_container"><?php include("./lib/menuNavClerk.php"); ?>
    <div class="center_content">
        <div class="title_name">Add Tool</div>
        <form name="categoryform" method="post" action="add_tool.php" enctype="multipart/form-data">
        <p id="set_category" style="margin-left:10px"><b>Category:&nbsp</b>
            <input type="radio" name="category_type" id="hand" value="hand" onclick="sendCategory(this.id)" />Hand Tools
            <input type="radio" name="category_type" id="garden" value="garden" onclick="sendCategory(this.id)" />Garden Tools
            <input type="radio" name="category_type" id="ladder" value="ladder" onclick="sendCategory(this.id)" />Ladder Tools
            <input type="radio" name="category_type" id="power" value="power" onclick="sendCategory(this.id)" />Power Tools
            <script>
                // to recheck the chosen category since page gets reloaded and removes 'checked'
                var cat_set = <?php echo json_encode($category, JSON_HEX_TAG); ?>;
                if (cat_set !== 'null') {
                    document.getElementById(cat_set).checked = true;
                    //alert(cat_set);
                }
            </script>

            <p style="margin-left:10px"><b>Power Source:&nbsp</b>
                <select id="set_power" name="set_power" onchange="readPower()">
                    <option value="manual">Manual</option>
                    <option value="gas" disabled>Gas</option>
                    <option value="cordless" disabled>Cordless</option>
                    <option value="electric" disabled>Electric</option>
                </select>
            <!--<p id="selected_power" style="margin-left:10px"></p>-->
            <input type="submit" value="Submit" style="margin-left:10px;" />
            </p></form>
        <script>
            // to re-select power after re-loading
            var pwr_set = null;
            pwr_set = <?php echo json_encode($_SESSION['power'], JSON_HEX_TAG); ?>;
            //alert("set drop down. pwr_set=" + pwr_set);
            if(pwr_set == 'gas'){
                document.getElementById("set_power").options[1].defaultSelected = true;
            } else if(pwr_set == 'cordless'){
                document.getElementById("set_power").options[2].defaultSelected = true;

            } else if (pwr_set == 'electric'){
                document.getElementById("set_power").options[3].defaultSelected = true;
            }
            /*if (pwr_set != null){
                document.getElementById("subtypefields").style.display="block";
            }*/
        </script>

<!--        <div id="subtypefields" class="toolatts" style="display:none"> -->
        <div id="subtypefields" class="toolatts">
            <form name="subtypeform" method="post" action="add_tool.php" enctype="multipart/form-data">
            <p style="margin-left:10px"><b>Sub-Type:&nbsp</b><select name='subtypeDropdn' id='set_subtype' onchange="this.form.submit()">
                <option value='dummy'>click</option>
                <?php
                if (isset($_SESSION['subtype'])){
                    print "<option value=". $_SESSION['subtype'] .">". $_SESSION['subtype'] ."</option>";
                }
                else if (isset($category) && isset($_SESSION['power'])) {
                    if (strcasecmp($category,"power") !==0){
                        while ($row = mysqli_fetch_array($resultSubtype, MYSQLI_ASSOC)) {
                            print "<option value=" . $row['sub_type_name'] . ">" . $row['sub_type_name'] . "</option>";
                        }
                    } else {
                        if (strcasecmp($category, "power") == 0 && strcasecmp($powersource, "gas") == 0) {
                            print "<option value='Air-Compressor'>Air Compressor</option>";
                            print "<option value='Mixer'>Mixer</option>";
                            print "<option value='Generator'>Generator</option>";
                        } elseif (strcasecmp($category, "power") == 0 && strcasecmp($powersource, "cordless") == 0) {
                            print "<option value='Drill'>Drill</option>";
                            print "<option value='Saw'>Saw</option>";
                            print "<option value='Sander'>Sander</option>";
                            //array_push($query_msg, "category: " . $category . " power: " . $powersource);
                        } else {
                            while ($row = mysqli_fetch_array($resultSubtype, MYSQLI_ASSOC)) {
                                //array_push($query_msg, "else2 subtypes: " . $row['sub_type_name']);
                                if (strcasecmp($row['sub_type_name'], 'Generator') !== 0) {
                                    print "<option value=" . $row['sub_type_name'] . ">" . $row['sub_type_name'] . "</option>";
                                }
                            }
                        }
                    }
                }
                else{ array_push($error_msg, "Please select Category and Power Source first");
                }?>
                </select></p>
                <br>
                <p style="margin-left:10px; margin-top:8px"><b> Sub-Option:&nbsp</b><select name="optionDropdn" id="set_option" required>
                        <!--<option value='dummy'>click</option>-->
                <?php
                     if (isset($subtype)){
                         //array_push($query_msg, "(in suboption) subtype: ". $subtype);
                         while ($row = mysqli_fetch_array($resultOption, MYSQLI_ASSOC)){
                             //array_push($query_msg, "(in suboption) suboption: " . $row['sub_option']);
                             print "<option value='" . $row['sub_option'] . "'>" . $row['sub_option'] . "</option>";
                         }
                     }
                ?>
                </select>
                <br><br>
            <p id="toolAtts" style="margin-left:10px;align-items:left">Width/diameter: &nbsp
                <input type="text" id="wdtext" name=widthdiam size="4" style="margin-left:10px"; placeholder="required" required>
                <select name="wdDropdn">
                        <!-- style="display:none" -->
                    <option value="0">0</option><option value=".125">1/8</option><option value=".25">1/4</option>
                    <option value=".375">3/8</option><option value=".5">1/2</option><option value=".625">5/8</option>
                    <option value=".75">3/4</option><option value=".875">7/8</option>
                </select>
                    <select name="wdunit">
                        <option value="inch">inch</option>
                        <option value="ft">ft</option>
                    </select>
                Length: &nbsp<input type="text" id="lengthtext" name=length size="4" placeholder="required" required>
                <select name="lDropdn">
                    <!-- style="display:none" -->
                    <option value="0">0</option><option value=".125">1/8</option><option value=".25">1/4</option>
                    <option value=".375">3/8</option><option value=".5">1/2</option><option value=".625">5/8</option>
                    <option value=".75">3/4</option><option value=".875">7/8</option>
                </select>
                    <select name="lunit">
                        <option value="inch">inch</option><option value="ft">ft</option>
                    </select>

                Weight (lb): <input type="text" id="weighttext" name=weight size="8" placeholder="required" required><br><br>
                Manufacturer: <input type="text" id="manuftext" name=manufacturer size="20" placeholder="required" required>
                Material: <input type="text" id="materialtext" name=material size="20" placeholder="enter">
                Original Price: $<input type="text" id="pricetext" name="originalPrice" size="10" placeholder="0.00(required)" required>
                <br><br>
                <input type="submit" value="Add To Database" style="margin-left:10px;" />
                <!--    <input type="reset" value="Start Over"/> -->
            </form>
            <form action="add_tool.php" method="post">
                <input type="submit" name=reset value="Reset" style="margin-left:10px">
            </form>



            <form name="handform" method="post" action="add_tool.php" enctype="multipart/form-data">
            <?php if(isset($_SESSION['subtype']) && $_SESSION['subtype'] == 'Screwdriver'): ?>
                <h3 style="margin-left:10px">Screwdriver</h3>
                <p style="margin-left:10px">
                    Screw Size (#):&nbsp<input name="screwSize" type="number" value="1" min="1" max="50" required>
                </p>
            <?php elseif(isset($_SESSION['subtype']) && $_SESSION['subtype'] == 'Socket'): ?>
                <h3 style="margin-left:10px">Socket</h3>
                <p style="margin-left:10px">
                    Drive Size (inch):&nbsp <input name="driveSize" type="number" value="0" min="0" max="4" step=".1"><select name="driveFraction" required>
                        <option value="0">0</option><option value=".125">1/8</option><option value=".25">1/4</option>
                        <option value=".375">3/8</option><option value=".5">1/2</option><option value=".625">5/8</option>
                        <option value=".75">3/4</option><option value=".875">7/8</option>
                    </select>
                    <br>
                    Sae Size (inch):&nbsp<input name="saeSize" type="number" value="0" min="0" max="7" step=".1"><select name="saeFraction" required>
                        <option value="0">0</option><option value=".125">1/8</option><option value=".25">1/4</option>
                        <option value=".375">3/8</option><option value=".5">1/2</option><option value=".625">5/8</option>
                        <option value=".75">3/4</option><option value=".875">7/8</option>
                    </select>
                    <br>
                    Deep Socket (T/F):&nbsp<select name="deep"><option value=""></option><option value="true">True</option><option value="false">False</option>
                    </select>
                </p>
            <?php elseif(isset($_SESSION['subtype']) && $_SESSION['subtype'] == 'Ratchet'): ?>
                <h3 style="margin-left:10px">Ratchet</h3>
                <p style="margin-left:10px">
                    Drive Size (inch):&nbsp<select name="driveFraction" required>
                        <option value=".125">1/8</option><option value=".25">1/4</option>
                        <option value=".375">3/8</option><option value=".5">1/2</option><option value=".625">5/8</option>
                        <option value=".75">3/4</option><option value=".875">7/8</option>
                    </select>

                </p>
            <?php elseif(isset($_SESSION['subtype']) && $_SESSION['subtype'] == 'Pliers'): ?>
                <h3 style="margin-left:10px">Pliers</h3>
                <p style="margin-left:10px">
                    Adjustable (T/F):&nbsp<select name="adjustable" required>
                        <option value="true">True</option><option value="false">False</option>
                    </select>
                </p>
            <?php elseif(isset($_SESSION['subtype']) && $_SESSION['subtype'] == 'Hammer'): ?>
                <h3 style="margin-left:10px">Hammer</h3>
                <p style="margin-left:10px">
                    Anti-Vibration (T/F):&nbsp<select name="antiVibration" required><option value="true">True</option>
                        <option value="false">False</option>
                    </select>
                </p>
            <?php elseif(isset($_SESSION['subtype']) && $_SESSION['subtype'] == 'Gun'): ?>
                <h3 style="margin-left:10px">Gun</h3>
                <p style="margin-left:10px">
                    Nail/Staple Capacity ():&nbsp<input type="number" name="capacity" min="1" max="2000" required>
                    <br>
                    Gauge Rating:&nbsp<select name="gauge"><option value="18">18</option>
                        <option value="20">20</option><option value="22">22</option><option value="24">24</option>
                    </select>
                </p>
            <?php endif; ?>

            <?php if(isset($_SESSION['subtype']) && $_SESSION['category'] == 'hand'): ?>
                <input type="submit" value="Enter Hand Tool Attributes" style="margin-left:10px">
            <?php endif; ?>
            </form>

            <?php if(isset($_SESSION['tool_id'])): ?>
            <h5 style="margin-left:10px">Tool attributes successfully added to database </h5>
            <?php endif; ?>

            <?php if(isset($_SESSION['tool_id']) && isset($_SESSION['category']) && $_SESSION['category'] !== 'hand' && $_SESSION['category'] !== null): ?>
            <form name="toparser" action="add_tool_parser.php">
                <input type="submit" value="Enter Subtype Attributes" style="margin-left:10px">
            </form>
            <?php endif; ?>
            <br>
            <br>

        </div>

    <?php include("lib/error.php"); ?>
    <div class="clear"></div>
    <?php include("lib/footer.php"); ?>
    </div>
</div>
</body>
</html>

