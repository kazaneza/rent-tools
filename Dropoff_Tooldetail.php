<?php
include('lib/common.php');
?>

<?php
function dec2frac($f) {
    $base = floor($f);
    if ($base) {
        $out = $base. "-";
        $f = $f - $base;
    }
    else $out="";
    if ($f != 0) {
        $d = 1;
        while (fmod($f, 1) != 0.0) {
            $f *= 2;
            $d *= 2;
        }
        $n = sprintf('%.0f', $f);
        $d = sprintf('%.0f', $d);
        $out =$out.$n . '/' . $d;
    } else{
        $out=substr($out,0,strlen($out)-1);
    }
    return $out;
}

$tool_id = $_REQUEST['tool_id'];
$sql1="SELECT ST.category_name, 
(CASE WHEN T.power_source='manual'
                   THEN CONCAT(T.sub_option,' ',T.sub_type_name)
                   ELSE CONCAT(T.power_source,' ',T.sub_option,' ',T.sub_type_name) END) AS 'Short Description', 
                   ROUND(0.4*T.original_price,2) AS 'Deposit Price', ROUND(0.15*T.original_price,2) AS 'Rental Price'
FROM tool T INNER JOIN subtype ST on T.sub_type_name=ST.sub_type_name
WHERE T.tool_id=$tool_id";

$result1= mysqli_query($db, $sql1);
$row1=mysqli_fetch_array($result1, MYSQLI_ASSOC);
include('lib/show_queries.php');

$sql2="SELECT accessory_description,quantity FROM accessories WHERE tool_id=$tool_id";
$result2= mysqli_query($db, $sql2);
include('lib/show_queries.php');

$sql3="SELECT * 
FROM tool NATURAL LEFT JOIN garden NATURAL LEFT JOIN hand NATURAL LEFT JOIN ladder NATURAL LEFT JOIN power 
NATURAL LEFT JOIN screwdriver NATURAL LEFT JOIN socket NATURAL LEFT JOIN ratchet NATURAL LEFT JOIN wrench 
NATURAL LEFT JOIN pliers NATURAL LEFT JOIN gun NATURAL LEFT JOIN hammer NATURAL LEFT JOIN pruner 
NATURAL LEFT JOIN striking NATURAL LEFT JOIN digger NATURAL LEFT JOIN rakes NATURAL LEFT JOIN wheelbarrows 
NATURAL LEFT JOIN drill NATURAL LEFT JOIN saw NATURAL LEFT JOIN sander NATURAL LEFT JOIN aircompressor 
NATURAL LEFT JOIN mixer NATURAL LEFT JOIN generator NATURAL LEFT JOIN straight NATURAL LEFT JOIN step 
NATURAL LEFT JOIN accessories NATURAL LEFT JOIN dc_cordless
WHERE tool.tool_id=$tool_id";
$result3= mysqli_query($db, $sql3);
include('lib/show_queries.php');
$row3=mysqli_fetch_array($result3, MYSQLI_ASSOC);
if($row3["power_source"]!='Manual'){
    $full_description= dec2frac($row3["width_diameter"]). ' in. W x '. dec2frac($row3["length"]). ' in. L ' . $row3['weight'].
        ' lb. ' .$row3["power_source"] .' '.$row3["sub_option"]. ' '. $row3["sub_type_name"]. ' '. $row3["volt_rating"]. "V ".
        $row3["amp_rating"]. "A " . $row3["min_rpm_rating"]. "RPM ";
}
else if ($row3["power_source"]=='Manual'){$full_description=dec2frac($row3["width_diameter"]). ' in. W x '. dec2frac($row3["length"]).
    ' in. L ' . $row3['weight']. ' lb. ' .$row3["sub_option"]. ' '. $row3["sub_type_name"].' ';}

if($row3["screw_size"]!= NULL){$full_description=$full_description . " #". $row3["screw_size"].' ';}
if($row3["drive_size"]!= NULL){$full_description=$full_description . dec2frac($row3["drive_size"]). " in. drive " ;}
if($row3["sae_size"]!= NULL){$full_description=$full_description . dec2frac($row3["sae_size"]). " in. sae " ;}
if($row3["adjustable"]){$full_description=$full_description . "ajustable " ;}
if($row3["gauge_rating"]!= NULL){$full_description=$full_description . $row3["gauge_rating"]. " G " ;}
if($row3["capacity"]!= NULL){$full_description=$full_description . $row3["capacity"]. " nails/staples " ;}
if($row3["handle_material"]!= NULL){$full_description=$full_description . $row3["handle_material"]. " handle " ;}
if($row3["blade_length"]!= NULL){$full_description=$full_description . dec2frac($row3["blade_length"]). " in. L blade " ;}
if($row3["head_weight"]!= NULL){$full_description=$full_description . $row3["head_weight"]. " lb.axe head weight " ;}
if($row3["blade_width"]!= NULL){$full_description=$full_description . dec2frac($row3["blade_width"]). " in. W blade " ;}
if($row3["tine_count"]!= NULL){$full_description=$full_description . $row3["tine_count"]. " tine " ;}
if($row3["bin_material"]!= NULL){$full_description=$full_description . $row3["bin_material"]. " bin " ;}
if($row3["wheel_count"]!= NULL){$full_description=$full_description . $row3["wheel_count"]. " wheeled " ;}
if($row3["min_torque_rating"]!= NULL){$full_description=$full_description . $row3["min_torque_rating"]. " ft-lb " ;}
if($row3["blade_size"]!= NULL){$full_description=$full_description . dec2frac($row3["blade_size"]). " in. blade " ;}
if($row3["dust_bag"]){$full_description=$full_description . "dust_bag " ;}
if($row3["tank_size"]!= NULL){$full_description=$full_description . $row3["tank_size"]. " gal tank " ;}
if($row3["motor_rating"]!= NULL){$full_description=$full_description . dec2frac($row3["motor_rating"]). " HP " ;}
if($row3["drum_size"]!= NULL){$full_description=$full_description . $row3["drum_size"]. " cu-ft " ;}
if($row3["power_rating"]!= NULL){$full_description=$full_description . $row3["power_rating"]. " Watt " ;}
if($row3["step_count"]!= NULL){$full_description=$full_description . $row3["step_count"]. "-step " ;}
if($row3["weight_capacity"]!= NULL){$full_description=$full_description . $row3["weight_capacity"]. " lb.capacity " ;}

$full_description=$full_description . "by ". $row3["manufacturer"];
?>

<?php include("lib/header.php"); ?>
<title>Tools-4-Rent Tool Detail</title>
</head>
<body>
<div id="main_container">

    <div id="header">

        <div class="logo">

            <img src="img/FQHR3714.PNG" style="opacity:0.5;background-color:E9E5E2;" border="0" alt="" title="Tools4Rent Logo"/>

        </div>

    </div>

    <div class="center_content">

        <div class="text_box_dropoff">

<h1>Tool Details</h1>
<hr>

<?php
echo "Tool ID:\n", $tool_id, "<br/>\n";
echo "Tool Type: \n", $row1['category_name'], "<br/>\n";
echo "Short Description: \n", $row1['Short Description'], "<br/>\n";
echo "Full Description: \n", $full_description, "<br/>\n";
echo "Deposit Price: \n $", $row1['Deposit Price'], "<br/>\n";
echo "Rental Price: \n $", $row1['Rental Price'], "<br/>\n";
echo "Accessories: \n";
if(mysqli_num_rows($result2)==0){echo "No Accessory";}
else {
    while ($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
        if ($row3["power_source"] == 'gas') {
            echo "(". $row2['quantity'].") ". $row2['accessory_description']. "<br>";
        } elseif ($row3["power_source"] == 'electric') {
            echo "(". $row2['quantity'].") ".$row3["volt_rating"] . "V " . $row3["amp_rating"] . "A " .
                $row2['accessory_description']. "<br>";
        } elseif ($row3["power_source"] == 'cordless') {
            echo "(". $row2['quantity'].") ". $row3["volt_rating"] . "V " . $row3["amp_rating"] . "A " .
                $row3["battery_type"] . " " . $row2['accessory_description']. "<br>";
        }
    }
}
?>



</body>
</html>



