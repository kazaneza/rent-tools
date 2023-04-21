<?php

$query = "INSERT INTO Garden (tool_id,handle_material) VALUES ('$tool_id','$handle')";
$results = mysqli_query($db, $query);
if ($results == true){
    array_push($query_msg,"Writing tool info to Garden");
} else{
    array_push($error_msg, "Query Error: Unable write to Garden...". $query);
    array_push($error_msg,  'Error# '. mysqli_errno($db) . ": " . mysqli_error($db));
}
?>