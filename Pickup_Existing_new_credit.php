<?php
include('lib/common.php');

// moved PHP logic to launch at initial load of page
if(isset($_POST['CustomerExisting'])) {
    foreach ($_POST as $key => $value) {
        array_push($query_msg, "posting keys: " . $key);
        array_push($query_msg, "posting value: " . $value);
    }
    if ($_POST['CustomerExisting'] == 'existing'){

        header("Location: Pickup_Receipt.php");
    }
    else {
       // $customer_id = $_SESSION['customer_id'];
       // $customer_id = 2;
        $reservation_id=$_SESSION['reservation_id'];

        $sql33 = "SELECT customer_id FROM reservation WHERE reservation_id=$reservation_id";
        $result33 = mysqli_query($db, $sql33);
        $res = mysqli_fetch_assoc($result33);

        $customer_id = $res['customer_id'];
        array_push($query_msg,"Re-writing credit card: ". $customer_id);

        $name_on_card = mysqli_real_escape_string($db, $_POST['name_on_card']);
        $credit_card_number = mysqli_real_escape_string($db, $_POST['credit_card_number']);
        $CVC = mysqli_real_escape_string($db, $_POST['CVC']);
        $enteredMonth = mysqli_real_escape_string($db, $_POST['month']);
        $expiration_month = mysqli_real_escape_string($db, $_POST['selectmonth']);
        $expiration_year = mysqli_real_escape_string($db, $_POST['expiry']);
        ##$CustomerExisting = mysqli_real_escape_string($db, $_POST['CustomerExisting']);
        $_SESSION['customerExisting'] = $CustomerExisting;

        $sql4 = "UPDATE CUSTOMER ".
                "SET name_on_card = '$name_on_card', ".
                "card_number = $credit_card_number, ".
                "cvc = '$CVC',".
                "expiration_month = '$enteredMonth', ".
                "expiration_year = $expiration_year ".
                "WHERE customer_id = $customer_id ";


        $result3 = mysqli_query($db, $sql4);
        if ($result3 == true){
            array_push($query_msg,"Re-writing credit card: ". $sql4);

            echo "Credit card has successfully been updated!";

            ##header("Location: Pickup_Receipt.php");
            header("Location: pickup_card_updated.php");
        } else{
            array_push($error_msg, "Query Error: Unable write to Customer Table..");
            array_push($error_msg,  'Error# '. mysqli_errno($db) . ": " . mysqli_error($db));
        }

                array_push($query_msg,"Re-writing work? ". $result3);


        //assumes correct rewrite. When it works uncomment next line:
        //header('./pickup_receipt.php');
    }
}

?>


<?php include("lib/header.php"); ?>
<title>Tools-4-Rent Pick Up Reservation</title>
<style>
    table{width:780px;}
    table,th,td{
        text-align:left;
        margin:10px;
    }
    th,td{padding:10px;
        font-size=10px;}

</style>
</head>
<body>
<div id="main_container"><?php include("lib/menuNavClerk.php"); ?>
    <div class="center_content">
        <div id="main_container">


            <hr>
            <h2 style="margin-left:10px">Pickup Reservation</h2>

            <?php
            $reservation_id=$_SESSION['reservation_id'];
            //$customer_id = $_SESSION['customer_id'];
            $_SESSION['reservation_id'] = $reservation_id;
            $username1 = $_POST['username'];
            $sql = "SELECT R.reservation_id, U.first_name, U.middle_name, U.last_name, round(sum(T.original_price*0.4),2) AS 'Total Deposit',
        round((datediff(end_date, start_date)+1)*sum(T.original_price*0.15),2) AS 'Total Rental Price' 
        from reservation R INNER JOIN reservationincludetool RT ON R.reservation_id= RT.reservation_id INNER JOIN tool T ON T.tool_id= RT.tool_id 
        INNER JOIN Customer C ON C.customer_id=R.customer_id INNER JOIN User U ON U.username=C.username
        WHERE R.reservation_id=$reservation_id";
                    $result = mysqli_query($db, $sql);

                    include('lib/show_queries.php');
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);


                        echo "<p style='margin-left:10px'> Reservation ID: " . $row['reservation_id'] . "</p>";
                        echo "<p style='margin-left:10px'>Customer Name: " .$row['first_name']. " ". $row['middle_name'] ." ". $row['last_name']. "</p>";
                        echo "<p style='margin-left:10px'>Total Deposit: $" .number_format($row['Total Deposit'],2). "</p>";
                        echo "<p style='margin-left:10px'>Total Rental Price: $" .number_format($row['Total Rental Price'],2). "</p>";
                        $due = $row['Total Rental Price']-$row['Total Deposit'];
                        echo "<p style='margin-left:10px'>Total Due: $".number_format($due,2). "</p>";




                    $sql2="SELECT T.tool_id, round(T.original_price*0.4,2) AS 'Deposit Price', round((datediff(end_date,start_date)+1)*T.original_price*0.15,2) AS 'Rental Price',
                   (CASE WHEN T.power_source='manual'
                   THEN CONCAT(T.sub_option,' ',T.sub_type_name)
                   ELSE CONCAT(T.power_source,' ',T.sub_option,' ',T.sub_type_name) END) AS 'Short Description'
                   FROM reservation R INNER JOIN reservationincludetool RT ON R.reservation_id=RT.reservation_id INNER JOIN tool T ON RT.tool_id=T.tool_id
                   WHERE R.reservation_id=$reservation_id";
                    $result2= mysqli_query($db, $sql2);
                    include('lib/show_queries.php');
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

            ?>


            <div class="login_form_row2col">
            <!--    <form name="categoryform" method="post" action="pickup_receipt.php" enctype="multipart/form-data"> -->
                <form name="categoryform" method="post" action="Pickup_Existing_new_credit.php" enctype="multipart/form-data">
                <label class="login_label">Credit Card:</label><br>

                <input type="radio" name="CustomerExisting" value="existing" checked/> <label for="radio">Existing</label>
                <input type="radio" name="CustomerExisting" value="new"/> <label for="radio">New</label>
                    <br><br><p style="margin-left:10px">
                    If you would like to use a new credit card, select new and then enter Updated Credit Card Information. <br>
                        Otherwise, just click "Confirm Pick up" for receipt</p>

                    <center> <h2> ** THIS WILL OVERWRITE THE PRIOR CUSTOMERS CREDIT CARD INFORMATION **</h2><br></center>

                    <tr>

                        <td></td>
                        <td>
                            <input style="margin-left:10px" type="text" name="name_on_card" placeholder="Name on Credit Card" value="<?php if ($row['name_on_card']) { print $row['name_on_card']; } ?>" />
                        </td>

                        <td>
                            <input type="text" name="credit_card_number" placeholder="Credit Card #" value="<?php if ($row['credit_card_number']) { print $row['credit_card_number']; } ?>" />
                        </td>

                        <td>
                            <input type="number" name="CVC" placeholder="CVC" min="100" max="999" value="<?php if ($row['CVC']) { print $row['CVC']; } ?>" />

                        </td>
                     </tr>

                    </tr>


                    <td class="item_label">Expiration Month: </td>
                    <td>
                        <select size="1" name="month">
                            <option selected value="January">January</option>
                            <option value="February">February</option>
                            <option value="March">March</option>
                            <option value="April">April</option>
                            <option value="May">May</option>
                            <option value="June">June</option>
                            <option value="July">July</option>
                            <option value="August">August</option>
                            <option value="September">September</option>
                            <option value="October">October</option>
                            <option value="November">November</option>
                            <option value="December">December</option>
                        </select>
                    </td>

                <select name="expiry">
                    <option value=''>Year</option>
                    <?php
                    for ($i = date('Y'); $i <= date('Y')+5; $i++) {
                        echo '<option value="'.$i.'">'.$i.'</option>';
                    }

                    ?>
                </select>
            </div>
            <table><tr></tr><tr><td>
          <input type= "submit" name = "subs" value="Confim Pick Up"></td></tr>
            </table>
            <br>
            </form>
            </div>


    </div>
    <?php include("lib/error.php"); ?>

    <div class="clear"></div>
</div>
</body>
</html>


