<?php
include('lib/common.php');
$cat=$_GET['cat']; // This line is added to take care if your global variable is off
$query1="Select Distinct category_name From subtype";
?>

<SCRIPT language=JavaScript>
    function reload(form){
        var val=form.cat.options[form.cat.options.selectedIndex].value;

        self.location='Make_Reservation.php?cat=' + val ;
    }
</script>

<?php include("lib/header.php"); ?>
<title>Tools-4-Rent Make Reservation</title>
<style>
.error {color: #FF0000;}
table{width:780px;}
table,th,td{border:1px solid black;
    border-collapse:collapse;
    text-align:left;
    margin:10px;
}
th,td{padding:10px;
    font-size=10px;}
</style>
</head>
<body style="margin-left:10px">
<div id="main_container">
    <?php include("lib/menuNavCustomer.php");?>

    <div class="center_content">

                <form action="Make_Reservation.php" method="post" enctype="multipart/form-data">
                    <div class="title"; style="font-size:200%; margin-top:30px; margin-bottom:30px";>Tools-4-Rent Make Reservation</div>

                    <?php
                        echo "<label style='margin-left:10px'>Type:</label>";
                        echo "<form method=post name=f1 action='Make_Reservation.php'>";
                        echo "<select name='cat' onchange=\"reload(this.form)\"><option value='All Tools'>All Tools</option>";
                        $stmt= mysqli_query($db, $query1);
                        if($stmt){
                            while ($row2 = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
                                if($row2['category_name']==@$cat){echo "<option selected value='$row2[category_name]'>$row2[category_name]</option>";}
                                else{echo  "<option value='$row2[category_name]'>$row2[category_name]</option>";}
                            }
                        }else{
                            echo "error";
                        }
                        echo "</select>";
                        ?>

                            <label> Power Source:</label>
                            <select name="power_source">
                                <option value="All"> All </option>
                                <option value="Manual"> Manual </option>
                                <option value="electric"> electric </option>
                                <option value="cordless"> cordless </option>
                                <option value="gas"> gas </option>
                            </select>
                        <br>

                        <?php
                        echo "<label style='margin-left:10px'>Sub_Type:</label>";
                        echo "<select name='subtype'><option value='All'>Select one</option>";
                        $query2 = "SELECT DISTINCT sub_type_name from subtype Where category_name='$cat'";
                        $stmt2 = mysqli_query($db, $query2);
                        $count = mysqli_num_rows($stmt2);
                        if (!empty($stmt2) && ($count > 0) ){
                            while ($row3 = mysqli_fetch_array($stmt2, MYSQLI_ASSOC)) {
                                echo "<option value='$row3[sub_type_name]'>$row3[sub_type_name]</option>";
                            }}
                        else{$query3 = "SELECT DISTINCT sub_type_name from subtype";
                            $stmt3 = mysqli_query($db, $query3);
                            while ($row4 = mysqli_fetch_array($stmt3, MYSQLI_ASSOC)) {
                                echo "<option value='$row4[sub_type_name]'>$row4[sub_type_name]</option>";
                            }}

                        echo "</select>";
                        ?>
                        <br>

                    <div style="margin-bottom:50px";>
                        <label style="margin-left:10px">Start Date:(YYYY-MM-DD)</label>
                        <input type="text" name="start_date" value=""><br>
                        <label style="margin-left:10px">End Date:(YYYY-MM-DD)</label>
                        <input type="text" name="end_date" value=""><br>
                        <label style="margin-left:10px">Custom Search(Keyword) :</label>
                        <input type="text" name="keyword" value=""><br>

<input type="submit" value="Search" style="margin-left:10px">
                    </div>


<?php
if( $_SERVER['REQUEST_METHOD'] == 'POST') {
    $startdate = $_POST['start_date'];
    if (empty($startdate)) {
        echo "Please enter the Start Date.";
    }

    $enddate = $_POST['end_date'];
    if (empty($enddate)) {
        echo "Please enter the End Date.";
    }

    if((strtotime($enddate)-strtotime($startdate))/(60 * 60 * 24)+1<1){
        echo "End Date should not be prior to Start Date";}
    $category=$_POST['cat'];
    $subtype=$_POST['subtype'];
    $powersource=$_POST['power_source'];
    $keyword=$_POST['keyword'];
}

if( $_SERVER['REQUEST_METHOD'] == 'POST' AND !empty($startdate) AND !empty($enddate) AND (strtotime($enddate)-strtotime($startdate))/(60 * 60 * 24)+1>=1) {
    $_SESSION['startdate'] = $startdate;
    $_SESSION['enddate'] = $enddate;
    $_SESSION['category'] = $category;
    $_SESSION['powersource'] = $powersource;
    $_SESSION['subtype'] = $subtype;
    $_SESSION['keyword'] = $keyword;
}


if(!empty($startdate) AND !empty($enddate) AND (strtotime($enddate)-strtotime($startdate))/(60 * 60 * 24)+1>=1) {

    if($category=="All Tools"){
    if(($subtype=='Saw' or $subtype=='Sander' or $subtype=='Drill') and ($powersource=="Manual" or $powersource=="gas")){
        echo "We don't have the tool you searched. No ".$subtype. "'s power source is ". $powersource. ".";
    }
    elseif(($subtype=='Mixer' or $subtype=='Air-Compressor') and ($powersource=="Manual" or $powersource=="cordless")){
            echo "We don't have the tool you searched. No ".$subtype. "'s power source is ". $powersource. ".";
        }
    elseif($subtype=='Generator' and $powersource!="gas"){
            echo "We don't have the tool you searched. All ".$subtype. " using gas as power source.";
        }
    elseif($subtype!='All' and $subtype!='Saw' and $subtype!='Sander' and $subtype!='Mixer' and $subtype!='Generator' and $subtype!='Drill' and $subtype!='Air-Compressor' and $powersource!="Manual" and $powersource!="All"){
        echo "We don't have the tool you searched. We only have manual ".$subtype;
    }
    else {
        echo "<P style=\"color:darkred;\">Your Search Criteria </P>";
        echo "Type:".$category. "<br>". " Power Source:" .$powersource. "<br>". " Sub_Type:". $subtype. "<br>";
        echo "Start_Date:" . $startdate ."  ".  "  End_Date:" . $enddate. "<br>";
        echo "Keyword: ". $keyword. "<br>";
        echo "<hr>";
        echo "<a href=\"Make_Reservation_AvailableTools.php\" target=\"_blank\" style=\"border:2px solid orange; background-color:orange; color:white;\">Confirm and Find Available Tools</a>";
    }
    }

    if($category!="All Tools"){
        if($category!='Power' AND $powersource!="Manual" AND $powersource!="All")
        {echo "We don't have the tool you searched. All ".$category. " tools are manual.";}
        elseif($category=='Power' AND $powersource=="Manual")
        {echo "We don't have the tool you searched. No ".$category. " tools are manual.";}
        elseif($category=='Power' AND $powersource!="Manual"){
            if(($subtype=='Saw' or $subtype=='Sander' or $subtype=='Drill') and $powersource=="gas"){
                echo "We don't have the tool you searched. No ".$subtype. "'s power source is ". $powersource. ".";
            }
            elseif(($subtype=='Mixer' or $subtype=='Air-Compressor') and $powersource=="cordless"){
                echo "We don't have the tool you searched. No ".$subtype. "'s power source is ". $powersource. ".";
            }
            elseif($subtype=='Generator' and $powersource!="gas"){
                echo "We don't have the tool you searched. All ".$subtype. " using gas as power source.";
            }
        }
        else{
            echo "<P style=\"color:darkred;margin-left:10px\">Your Search Criteria </P>";
            echo "<p style='margin-left:10px'>Type:".$category. "<br>". " Power Source:" .$powersource. "<br>". " Sub_Type:". $subtype. "<br>";
            echo "<p style='margin-left:10px'>Start_Date:" . $startdate ."  ".  "  End_Date:" . $enddate. "<br>";
            echo "<p style='margin-left:10px'>Keyword: ". $keyword. "<br>";
            echo "<hr>";
            echo "<a href=\"Make_Reservation_AvailableTools.php\" target=\"_blank\" style=\"margin-left:10px;border:2px solid orange; background-color:orange; color:white;\">Confirm and Find Available Tools</a>";
        }
    }

}

?>



</body>
</html>
