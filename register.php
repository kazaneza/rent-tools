<?php
//$connect = mysqli_connect('http://127.0.0.1:8080', 'root', 'gatech123');
//if (!$connect) {
//    die('Failed to connect to database');
//}

include('lib/common.php');


if( $_SERVER['REQUEST_METHOD'] == 'POST') {
    $enteredFirstName = mysqli_real_escape_string($db, $_POST['first_name']);
    $enteredMiddleName = mysqli_real_escape_string($db, $_POST['middle_name']);
    $enteredLastName = mysqli_real_escape_string($db, $_POST['last_name']);
    $enteredHomePhone = mysqli_real_escape_string($db, $_POST['home_phone']);
    $enteredWorkPhone = mysqli_real_escape_string($db, $_POST['work_phone']);
    $enteredCellPhone = mysqli_real_escape_string($db, $_POST['cell_phone']);
    $enteredPrimaryPhone = mysqli_real_escape_string($db, $_POST['radioPrimaryPhone']);
    $enteredUsername = mysqli_real_escape_string($db, $_POST['username']);
    $enteredEmail = mysqli_real_escape_string($db, $_POST['email']);
    $enteredPassword = mysqli_real_escape_string($db, $_POST['password']);
    $enteredRetypePassword = mysqli_real_escape_string($db, $_POST['retype_password']);
    $enteredStreet = mysqli_real_escape_string($db, $_POST['street']);
    $enteredCity = mysqli_real_escape_string($db, $_POST['city']);
    $enteredState = mysqli_real_escape_string($db, $_POST['state']);
    $enteredZipCode = mysqli_real_escape_string($db, $_POST['zip_code']);
    $enteredNameOnCreditCard = mysqli_real_escape_string($db, $_POST['name_on_card']);
    $enteredCardNumber = mysqli_real_escape_string($db, $_POST['card_number']);
    $enteredMonth = mysqli_real_escape_string($db, $_POST['month']);
    $enteredYear = mysqli_real_escape_string($db, $_POST['year']);
    $enteredCVC = mysqli_real_escape_string($db, $_POST['cvc']);
    $enteredCVC = (int)$enteredCVC;
    $enteredCardNumber = (int)$enteredCardNumber;

    array_push($query_msg, $enteredPrimaryPhone."-".$enteredEmail.$enteredZipCode.$enteredState.$enteredCity.$enteredStreet.$enteredUsername);
    array_push($query_msg, $enteredFirstName.$enteredMiddleName.$enteredLastName.$enteredHomePhone.$enteredWorkPhone.$enteredCellPhone);
    array_push($query_msg, $enteredPassword."-".$enteredRetypePassword."-".$enteredMonth."-".$enteredYear."-".$enteredCVC);

    if (empty($enteredFirstName)) {
        $enteredFirstName = NULL;
    }
    if (empty($enteredMiddleName)) {
        $enteredMiddleName = NULL;
    }
    if (empty($enteredLastName)) {
        $enteredLastName = NULL;
    }

    if (empty($enteredHomePhone)) {
        $HomePhoneNumber = NULL;
    } else if (strlen($enteredHomePhone) < 14) {
        $enteredHomePhone = intval(preg_replace('/[^0-9]+/', '',$enteredHomePhone),10);
        $HomePhoneNumber = (substr((string)$enteredHomePhone, 0, 3)) . "-" .
            (substr((string)$enteredHomePhone, 3, 3)) . "-" .
            (substr((string)$enteredHomePhone, 6, 4));
    } else if (strlen($enteredHomePhone) >= 14) {
        $enteredHomePhone = intval(preg_replace('/[^0-9]+/', '',$enteredHomePhone),10);
        $HomePhoneNumber = (substr((string)$enteredHomePhone, 0, 3)) . "-" .
            (substr((string)$enteredHomePhone, 3, 3)) . "-" .
            (substr((string)$enteredHomePhone, 6, 4)) . "x" .
            (substr((string)$enteredHomePhone, 10));
    } else {
        $HomePhoneNumber = NULL;
    }

    if (empty($enteredWorkPhone)) {
        $WorkPhoneNumber = NULL;
    } else if (strlen($enteredWorkPhone) < 14) {
        $enteredWorkPhone = intval(preg_replace('/[^0-9]+/', '',$enteredWorkPhone),10);
        $WorkPhoneNumber = (substr((string)$enteredWorkPhone, 0, 3)) . "-" .
            (substr((string)$enteredWorkPhone, 3, 3)) . "-" .
            (substr((string)$enteredWorkPhone, 6, 4));
    } else if (strlen($enteredWorkPhone) >= 14) {
        $enteredWorkPhone = intval(preg_replace('/[^0-9]+/', '',$enteredWorkPhone),10);
        $WorkPhoneNumber = (substr((string)$enteredWorkPhone, 0, 3)) . "-" .
            (substr((string)$enteredWorkPhone, 3, 3)) . "-" .
            (substr((string)$enteredWorkPhone, 6, 4)) . "x" .
            (substr((string)$enteredWorkPhone, 10));
    } else {
        $WorkPhoneNumber = NULL;
    }

    if (empty($enteredCellPhone)) {
        $CellPhoneNumber = NULL;
    } else if (strlen($enteredCellPhone) < 14) {
        $enteredCellPhone = intval(preg_replace('/[^0-9]+/', '',$enteredCellPhone),10);
        $CellPhoneNumber = (substr((string)$enteredCellPhone, 0, 3)) . "-" .
            (substr((string)$enteredCellPhone, 3, 3)) . "-" .
            (substr((string)$enteredCellPhone, 6, 4));
    } else if (strlen($enteredCellPhone) >= 14) {
        $enteredCellPhone = intval(preg_replace('/[^0-9]+/', '',$enteredCellPhone),10);
        $CellPhoneNumber = (substr((string)$enteredCellPhone, 0, 3)) . "-" .
            (substr((string)$enteredCellPhone, 3, 3)) . "-" .
            (substr((string)$enteredCellPhone, 6, 4)) . "x" .
            (substr((string)$enteredCellPhone, 10));
    } else {
        $CellPhoneNumber = NULL;
    }

    if (empty($enteredStreet)) {
        $enteredStreet = NULL;
    }
    if (empty($enteredCity)) {
        $enteredCity = NULL;
    }
    if (empty($enteredState)) {
        $enteredState = NULL;
    }
    if (empty($enteredZipCode)) {
        $enteredZipCode = NULL;
    }

    if (empty($enteredUsername)) {
        array_push($error_msg, "Please enter Username.");
    }
    if (empty($enteredPassword)) {
        array_push($error_msg, "Please enter Password.");
    }
    if (empty($enteredEmail)) {
        array_push($error_msg, "Please enter Email.");
    }
    if (empty($enteredNameOnCreditCard) or empty($enteredCardNumber) or empty($enteredMonth) or empty($enteredMonth) or empty($enteredYear) or empty($enteredCVC)) {
        array_push($error_msg, "Please enter Credit Card information.");
    } else {
        $realCreditCard = True;
    }

    if ($enteredPassword != $enteredRetypePassword) {
        array_push($error_msg, "Retyped password is not same as password.");
    }


    if ($enteredPrimaryPhone == 'home') {
        if ($HomePhoneNumber == NULL) {
            array_push($error_msg, "Please enter valid primary phone (home).");
        } else {
            $PrimaryPhoneNumber = $HomePhoneNumber;
        }
    }
    if ($enteredPrimaryPhone == 'work') {
        if ($WorkPhoneNumber == NULL) {
            array_push($error_msg, "Please enter valid primary phone (work).");
        } else {
            $PrimaryPhoneNumber = $WorkPhoneNumber;
        }
    }
    if ($enteredPrimaryPhone == 'cell') {
        if ($CellPhoneNumber == NULL) {
            array_push($error_msg, "Please enter valid primary phone (cell).");
        } else {
            $PrimaryPhoneNumber = $CellPhoneNumber;
        }
    }

    if (preg_match('/^\d{9}$/', $enteredZipCode)) {
        $realZip = (substr((string)$enteredZipCode, 0, 5)) . "-" . (substr((string)$enteredZipCode, 5, 4));
    } else {
        $realZip = Null;
    }

    /*array_push($error_msg, $enteredEmail.$realZip.$enteredState.$enteredCity.$enteredStreet.$enteredUsername);
    array_push($error_msg, $enteredFirstName.$enteredMiddleName.$enteredLastName.$CellPhoneNumber.$WorkPhoneNumber.$HomePhoneNumber);
    array_push($error_msg, $enteredPassword."-".$enteredRetypePassword."-".$enteredMonth."-".$enteredYear."-".$PrimaryPhoneNumber);
    array_push($error_msg, $enteredNameOnCreditCard."-".$enteredCardNumber."-".$enteredCVC);*/

    $query = "SELECT username FROM User WHERE username='$enteredUsername'";
    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');

    if (!is_bool($result) && (mysqli_num_rows($result) > 0)) {
        array_push($error_msg, "Username already exists...Select different username. <br>" . __FILE__ . " line:" . __LINE__);
    } else {
        $newUser = True;
    }

    if (($newUser == True) && !empty($enteredUsername) && ($realCreditCard == True) && ($PrimaryPhoneNumber != null) and !empty($enteredEmail) and !empty($enteredPassword)){
        $queryUser = "INSERT INTO user (username, email, password, first_name, middle_name, last_name) " .
            "VALUES ('$enteredUsername','$enteredEmail','$enteredPassword','$enteredFirstName','$enteredMiddleName','$enteredLastName');";
        $resultsUser = mysqli_query($db, $queryUser);
        $query = $queryUser;
        include('lib/show_queries.php');
        $queryCustomer = "INSERT INTO customer (username, home_phone, work_phone, cell_phone, primary_phone, state," .
            " city, street, zip_code, name_on_card, card_number, expiration_month, expiration_year, cvc )" .
            " VALUES ('$enteredUsername','$HomePhoneNumber','$WorkPhoneNumber','$CellPhoneNumber','$PrimaryPhoneNumber'," .
            "'$enteredState', '$enteredCity', '$enteredStreet', '$realZip', '$enteredNameOnCreditCard', '$enteredCardNumber', '$enteredMonth', '$enteredYear', '$enteredCVC');";
        $resultsCustomer = mysqli_query($db, $queryCustomer);
        $query = $queryCustomer;
        include('lib/show_queries.php');
    }


    if (is_bool($resultsUser) and is_bool($resultsCustomer)) {
        if (($resultsUser == true) and ($resultsCustomer == true)) {
            array_push($query_msg, "Writing new customer info to database");
            header('Location: login.php');
        } else {
            array_push($error_msg, "Query Error: Unable to register new customer to database...");
        }
    }

    array_push($query_msg, "query: " . $queryTool);
    if (mysqli_errno($db) > 0) {
        array_push($error_msg, 'Error# ' . mysqli_errno($db) . ": " . mysqli_error($db));
    }
}
?>


<?php include("lib/header.php"); ?>
<title>Customer Registration Form</title>
<style>
    table{
        max-width:750px;
        width=100%;
        table-layout:fixed;
    }
    table,th,td{
        text-align:left;
    }
    th,td{
        padding-right:10px;
        font-size=10px;
        word-wrap:break-word;
        max-width:100px;
    }

</style>

</head>
<body>
<div id="main_container">
    <div id="header">
        <div class="logo">
            <img src="img/FQHR3714.PNG" style="opacity:0.5;background-color:;" border="0" alt="" title="Tools4Rent Logo"/>
        </div>
    </div>
    <div id="register_section" style="background-color:#E9E5E2;">
            <form action="register.php" method="post" enctype="multipart/form-data">
                <div class="title">Customer Registration Form</div>
                <table width="100%">
                    <tr>
                        <td class="item_label" style="width:100px;padding-left:10px">First Name: </td>
                        <td>
                            <input type="text" style="width:130px" name="first_name" value="<?php if ($row['first_name']) { print $row['first_name']; } ?>" />
                        </td>
                        <td class="item_label" style="width:120px;padding-left:22px;text-align:right">Middle Name: </td>
                        <td>
                            <input type="text" style="width:130px" name="middle_name" value="<?php if ($row['middle_name']) { print $row['middle_name']; } ?>" />
                        </td>
                        <td class="item_label" style="width:100px;padding-left:22px;text-align:right">Last Name: </td>
                        <td>
                            <input type="text" style="width:130px" name="last_name" value="<?php if ($row['last_name']) { print $row['last_name']; } ?>" />
                        </td>
                    </tr>

                    <tr>
                        <td class="item_label" style="width:100px;padding-left:10px">Home Phone: </td>
                        <td>
                            <input type="text" style="width:130px" name="home_phone" value="<?php if ($row['home_phone']) { print $row['home_phone']; } ?>" placeholder="123-456-7890"/>
                        </td>
                        <td class="item_label" style="width:120px;padding-left:27px;text-align:right">Work Phone: </td>
                        <td>
                            <input type="text" style="width:130px" name="work_phone" value="<?php if ($row['work_phone']) { print $row['work_phone']; } ?>" placeholder="123-456-7890"/>
                        </td>
                        <td class="item_label" style="width:100px;padding-left:27px;text-align:right">Cell Phone: </td>
                        <td>
                            <input type="text" style="width:130px" name="cell_phone" value="<?php if ($row['cell_phone']) { print $row['cell_phone']; } ?>" placeholder="123-456-7890"/>
                        </td>
                    </tr>
                </table>
                <br>
                <table>
                    <tr>
                        <td class="item_label" style="width:200px;text-align:right">Primary Phone: </td>
                        <td>
                            <label><input type="radio" name="radioPrimaryPhone" value="home" checked/>Home Phone</label>
                        </td>
                        <td>
                            <label><input type="radio" name="radioPrimaryPhone" value="work" checked/>Work Phone</label>
                        </td>
                        <td>
                            <input type="radio" name="radioPrimaryPhone" value="cell" checked/> <label for="radio">Cell Phone</label>
                        </td>
                    </tr>
                </table>
                <br><hr><br>
                <table>
                    <tr>
                        <td class="item_label" style="width:100px;padding-left:10px">Username: </td>
                        <td>
                            <input type="text" style="width:130px" name="username" value="<?php if ($row['username']) { print $row['username']; } ?>" placeholder="fLastname" required/>
                        </td>
                        <td class="item_label" style="width:150px;text-align:right">Email Address: </td>
                        <td>
                            <input type="text" style="width:130px" name="email" value="<?php if ($row['email']) { print $row['email']; } ?>" placeholder="req@ui.red" required/>
                        </td></tr>
                    <tr>
                        <td class="item_label" style="width:100px;padding-left:10px">Password: </td>
                        <td>
                            <input type="text" style="width:130px" name="password" value="<?php if ($row['password']) { print $row['password']; } ?>" placeholder="required" required/>
                        </td>
                        <td class="item_label" style="text-align:right">Re-type password: </td>
                        <td>
                            <input type="text" style="width:130px" name="retype_password" value="<?php if ($row['retype_password']) { print $row['retype_password']; } ?>" placeholder="required" required/>
                        </td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td class="item_label" style="padding-left:10px">Street Address: </td>
                        <td>
                            <input type="text" name="street" value="<?php if ($row['street']) { print $row['street']; } ?>" />
                        </td><td></td><td></td>
                    </tr>

                    <tr>
                        <td class="item_label" style="padding-left:10px;text-align:right">City: </td>
                        <td>
                            <input type="text" style="width:130px" name="city" value="<?php if ($row['city']) { print $row['city']; } ?>" />
                        </td>
                        <td class="item_label" style="padding-left:17px;text-align:right">State:
                        <select name="state"><?php echo StateDropdown('TX', 'abbrev'); ?></select>
                        </td>
                        <td class="item_label" style="text-align:right">Zip Code: </td>
                        <td>
                            <input type="number" style="width:100px" name="zip_code" value="<?php if ($row['zip_code']) { print $row['zip_code']; } ?>" />
                        </td>
                    </tr>
                </table>
                <br><hr>
                <div class="title">Credit Card</div>
                <table>
                    <tr>
                        <td class="item_label" style="width:180px;margin-left:10px">Name on Credit Card: </td>
                        <td>
                            <input type="text" style="width:130px" name="name_on_card" value="<?php if ($row['name_on_card']) { print $row['name_on_card']; } ?>" required/>
                        </td>
                        <td class="item_label" style="width:200px;padding-left:27px;text-align:right">Credit Card Number: </td>
                        <td>
                            <input type="text" name="card_number" value="<?php if ($row['card_number']) { print $row['card_number']; } ?>" placeholder="1234567890123456" required/>
                        </td>
                    </tr>

                    <tr>
                        <td class="item_label" style="text-align:right">Expiration Month: </td>
                        <td>
                            <select size="1" name="month" required>
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
                        <td class="item_label" style="text-align:right">Expiration Year: </td>
                        <td>
                            <?php
                            // set start and end year range
                            $yearArray = range(2000, 2050);
                            ?>
                            <!-- displaying the dropdown list -->
                            <select name="year" required>
                                <option value="">Select Year</option>
                                <?php
                                foreach ($yearArray as $year) {
                                    // if you want to select a particular year
                                    $selected = ($year == 2017) ? 'selected' : '';
                                    echo '<option '.$selected.' value="'.$year.'">'.$year.'</option>';
                                }
                                ?>
                            </select>
                        </td>
                        <td class="item_label" style="text-align:right">CVC: </td>
                        <td>
                            <input type="test" style="width:70px"name="cvc" value="<?php if ($row['cvc']) { print $row['cvc']; } ?>" placeholder="123" required/>
                        </td>
                    </tr>
                </table>
                <div>
                    <input type="submit" value="Register" style="margin-left:10px;"/>
                </div>
                <br>
            </form>
    </div>
        <?php include("lib/error.php"); ?>

        <div class="clear"></div>
        </div>


    <!--
    <div class="map">
    <iframe style="position:relative;z-index:999;" width="820" height="600" src="https://maps.google.com/maps?q=801 Atlantic Drive, Atlanta - 30332&t=&z=14&ie=UTF8&iwloc=B&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"><a class="google-map-code" href="http://www.embedgooglemap.net" id="get-map-data">801 Atlantic Drive, Atlanta - 30332</a><style>#gmap_canvas img{max-width:none!important;background:none!important}</style></iframe>
    </div> -->

    <?php include("lib/footer.php"); ?>

</div>
</body>
</html>

<?php

/**
 * States Dropdown
 *
 * @uses check_select
 * @param string $post, the one to make "selected"
 * @param string $type, by default it shows abbreviations. 'abbrev', 'name' or 'mixed'
 * @return string
 */
function StateDropdown($post=null, $type='abbrev') {
    $states = array(
        array('AK', 'Alaska'),
        array('AL', 'Alabama'),
        array('AR', 'Arkansas'),
        array('AZ', 'Arizona'),
        array('CA', 'California'),
        array('CO', 'Colorado'),
        array('CT', 'Connecticut'),
        array('DC', 'District of Columbia'),
        array('DE', 'Delaware'),
        array('FL', 'Florida'),
        array('GA', 'Georgia'),
        array('HI', 'Hawaii'),
        array('IA', 'Iowa'),
        array('ID', 'Idaho'),
        array('IL', 'Illinois'),
        array('IN', 'Indiana'),
        array('KS', 'Kansas'),
        array('KY', 'Kentucky'),
        array('LA', 'Louisiana'),
        array('MA', 'Massachusetts'),
        array('MD', 'Maryland'),
        array('ME', 'Maine'),
        array('MI', 'Michigan'),
        array('MN', 'Minnesota'),
        array('MO', 'Missouri'),
        array('MS', 'Mississippi'),
        array('MT', 'Montana'),
        array('NC', 'North Carolina'),
        array('ND', 'North Dakota'),
        array('NE', 'Nebraska'),
        array('NH', 'New Hampshire'),
        array('NJ', 'New Jersey'),
        array('NM', 'New Mexico'),
        array('NV', 'Nevada'),
        array('NY', 'New York'),
        array('OH', 'Ohio'),
        array('OK', 'Oklahoma'),
        array('OR', 'Oregon'),
        array('PA', 'Pennsylvania'),
        array('PR', 'Puerto Rico'),
        array('RI', 'Rhode Island'),
        array('SC', 'South Carolina'),
        array('SD', 'South Dakota'),
        array('TN', 'Tennessee'),
        array('TX', 'Texas'),
        array('UT', 'Utah'),
        array('VA', 'Virginia'),
        array('VT', 'Vermont'),
        array('WA', 'Washington'),
        array('WI', 'Wisconsin'),
        array('WV', 'West Virginia'),
        array('WY', 'Wyoming')
    );

    $options = '<option value=""></option>';

    foreach ($states as $state) {
        if ($type == 'abbrev') {
            $options .= '<option value="'.$state[0].'" '. check_select($post, $state[0], false) .' >'.$state[0].'</option>'."\n";
        } elseif($type == 'name') {
            $options .= '<option value="'.$state[1].'" '. check_select($post, $state[1], false) .' >'.$state[1].'</option>'."\n";
        } elseif($type == 'mixed') {
            $options .= '<option value="'.$state[0].'" '. check_select($post, $state[0], false) .' >'.$state[1].'</option>'."\n";
        }
    }

    echo $options;
}

/**
 * Check Select Element
 *
 * @param string $i, POST value
 * @param string $m, input element's value
 * @param string $e, return=false, echo=true
 * @return string
 */
function check_select($i,$m,$e=true) {
    if ($i != null) {
        if ( $i == $m ) {
            $var = ' selected="selected" ';
        } else {
            $var = '';
        }
    } else {
        $var = '';
    }
    if(!$e) {
        return $var;
    } else {
        return $var;
    }
}