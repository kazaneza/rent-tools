<?php
/**
 * Created by PhpStorm.
 * User: ccheung39@gatech.edu
 * Date: 11/15/2017
 * Time: 2:05 PM
 */

$query = "INSERT INTO Accessories (tool_id, quantity, accessory_description) VALUES ".
    "('$tool_id',$accAmt,'$accType')";
$results = mysqli_query($db, $query);
if ($results == true){
    array_push($query_msg,"Writing ". $accAmt ." ". $accType ." to database");
} else{
    array_push($error_msg, "Query Error: " . $query);
    array_push($error_msg,  'Error# '. mysqli_errno($db) . ": " . mysqli_error($db));
}
?>