<?php
/**
 * Created by PhpStorm.
 * User: cheun
 * Date: 11/15/2017
 * Time: 2:00 PM

 */
//add tool to DC_Cordless table
$query = "INSERT INTO DC_Cordless (tool_id, battery_type) VALUES ('$tool_id','$batteryType')";
$results = mysqli_query($db, $query);

if ($results == true){
    array_push($query_msg,"Writing tool info to DC_Cordless");
} else{
    array_push($error_msg, "Query Error: Unable write to DC_Cordless...". $query);
}
if( mysqli_errno($db) > 0 ) {
    array_push($error_msg,  'Error# '. mysqli_errno($db) . ": " . mysqli_error($db));
}
?>