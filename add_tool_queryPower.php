<?php
if (empty($speedMax) == 1 && $_SESSION['power'] == 'gas') {
    $query = "INSERT INTO Power (tool_id,min_rpm_rating) VALUES ('$tool_id','$speedMin')";
} else if (empty($speedMax) == 1){
    $query = "INSERT INTO Power (tool_id,min_rpm_rating,volt_rating,amp_rating) VALUES " .
        "('$tool_id','$speedMin','$volt','$amp')";
} else if ($_SESSION['power'] == 'gas'){
    $query = "INSERT INTO Power (tool_id,min_rpm_rating,max_rpm_rating) VALUES ('$tool_id','$speedMin','$speedMax')";
} else{
    $query = "INSERT INTO Power (tool_id,min_rpm_rating,max_rpm_rating,volt_rating,amp_rating) VALUES " .
        "('$tool_id','$speedMin','$speedMax','$volt','$amp')";
}

$results = mysqli_query($db, $query);
if ($results == true){
    array_push($query_msg,"Writing tool info to Power");
} else{
    array_push($error_msg, "Query Error: Unable write to Power...". $query);
    array_push($error_msg,  'Error# '. mysqli_errno($db) . ": " . mysqli_error($db));
}
?>