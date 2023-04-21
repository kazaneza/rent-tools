<?php
include('lib/common.php');
//carol cheung ccheung39@gatech.edu

if( $_SERVER['REQUEST_METHOD'] == 'POST') {

    $enteredEmail = mysqli_real_escape_string($db, $_POST['email']);
    $enteredPassword = mysqli_real_escape_string($db, $_POST['password']);
    $enteredCustomerClerk = mysqli_real_escape_string($db, $_POST['radioCustomerClerk']);

    if (empty($enteredEmail)) {
        array_push($error_msg,  "Please enter an email address.");
    }

    if (empty($enteredPassword)) {
        array_push($error_msg,  "Please enter a password.");
    }

    if ( !empty($enteredEmail) && !empty($enteredPassword) )   {

        $query = "SELECT password FROM User WHERE email='$enteredEmail'";
        $result = mysqli_query($db, $query);

        include('lib/show_queries.php');
        $count = mysqli_num_rows($result);

        if (!empty($result) && ($count > 0) ) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $storedPassword = $row['password'];
            $options = [
                'cost' => 8,
            ];
            //convert the plaintext passwords to their respective hashses
            // 'michael123' = $2y$08$kr5P80A7RyA0FDPUa8cB2eaf0EqbUay0nYspuajgHRRXM9SgzNgZO
            $storedHash = password_hash($storedPassword, PASSWORD_DEFAULT , $options);   //may not want this if $storedPassword are stored as hashes (don't rehash a hash)
            $enteredHash = password_hash($enteredPassword, PASSWORD_DEFAULT , $options);

            //access whether valid Customer or Clerk email/password combo
            $userType = NULL; $storedID = NULL;
            $queryUsername = "SELECT username FROM User WHERE email='$enteredEmail'";
            $output = mysqli_query($db, $queryUsername);
            $row0 = mysqli_fetch_array($output, MYSQLI_ASSOC);
            $storedUsername = $row0['username'];

            if ($enteredCustomerClerk == 'customer'){
                $queryCustomerID = "SELECT customer_id FROM CUSTOMER WHERE username='$storedUsername'";
                $output = mysqli_query($db, $queryCustomerID);
                $row0 = mysqli_fetch_array($output, MYSQLI_ASSOC);
                $storedID = $row0['customer_id'];
                if (isset($storedID)){
                    $userType = 'customer';
                }
            }
            elseif ($enteredCustomerClerk == 'clerk'){
                $queryClerkID = "SELECT clerk_id, clerk_login_check FROM CLERK WHERE username='$storedUsername'";
                $output = mysqli_query($db, $queryClerkID);
                $row0 = mysqli_fetch_array($output, MYSQLI_ASSOC);
                $storedID = $row0['clerk_id'];
                $loginCheck = $row0['clerk_login_check'];
                if (isset($storedID)){
                    $userType = 'clerk';
                }
            }

            if($showQueries){
                //array_push($query_msg, "radio button entered: ". $enteredCustomerClerk);
                //array_push($query_msg, "check email format: ". $enteredEmail);
                array_push($query_msg, "found username: ". $storedUsername);
                array_push($query_msg, "Plaintext entered password: ". $enteredPassword);
                //Note: because of salt, the entered and stored password hashes will appear different each time
                //array_push($query_msg, "Entered Hash:". $enteredHash);
                //array_push($query_msg, "Stored Hash:  ". $storedHash . NEWLINE);  //note: change to storedHash if tables store the plaintext password value
                //unsafe, but left as a learning tool uncomment if you want to log passwords with hash values
                //error_log('email: '. $enteredEmail  . ' password: '. $enteredPassword . ' hash:'. $enteredHash);
            }

            //depends on if you are storing the hash $storedHash or plaintext $storedPassword
            if (password_verify($enteredPassword, $storedHash) ) {
                if ($userType == 'customer'){
                    array_push($query_msg, "Password is Valid! Loading Customer Menu");
                    $_SESSION['email'] = $enteredEmail;
                    $_SESSION['id'] = $storedID;
                    $_SESSION['type'] = 'customer';

                    array_push($query_msg, "logging in... ");
                    header(REFRESH_TIME . 'url=customer_menu.php');
                }
                elseif ($userType == 'clerk'){
                    $_SESSION['email'] = $enteredEmail;
                    $_SESSION['id'] = $storedID;
                    $_SESSION['type'] = 'clerk';

                    if ($loginCheck == 0){
                        array_push($query_msg, "First time Clerk logging in. Please change password");
                        header(REFRESH_TIME . 'url=clerk_password.php?$enteredPassword');
                    } else {
                        array_push($query_msg, "Password is Valid! Logging in... Loading Clerk Menu");
                        header(REFRESH_TIME . 'url=clerk_menu.php');
                    }
                } else {
                    array_push($error_msg, "$enteredEmail is not a $enteredCustomerClerk");
                }

            } else {
                array_push($error_msg, "Login failed: " . $enteredEmail . NEWLINE);
                array_push($error_msg, "To demo enter: ". NEWLINE . "ccheung39@gatech.edu". NEWLINE ."carlin");
            }

        } else {
            if ($enteredCustomerClerk == 'customer') {
                array_push($error_msg, "The username entered does not exist: " . $enteredEmail . "...redirecting to Register Customer");
                header(REFRESH_TIME . 'url=register.php');
            } else{
                array_push($error_msg, "There is no clerk by that email");
            }
        }
    }
}
?>

<?php include("./lib/header.php"); ?>
<title>Tools-4-Rent! Login</title>
</head>
<body>
<div id="main_container">
    <div id="header">
        <div class="logo">
            <img src="./img/tools4rent_logo.PNG" style="opacity:0.5;background-color:E9E5E2;" border="0" alt="" title="Tools4Rent Logo"/>
        </div>
    </div>

    <div class="center_content">
        <div class="text_box">
            <form action="login.php" method="post" enctype="multipart/form-data">
                <div class="title">Tools-4-Rent! Login</div>
                <!--<input type="text" name="email" placeholder="ccheung39@gatech.edu" class="login_input"/> -->
                <div class="login_form_row">
                    <label class="login_label">Email:</label>
                    <!--<div><input type="text" name="email" value="ccheung39@gatech.edu" class="login_input"/> -->
                    <div><input type="email" name="email" placeholder="Jkyle7@tools4rent.com" class="login_input"/>
                    </div>
                <div class="login_form_row">
                    <label class="login_label">Password:</label>
<!--                    <!--<input type="password" name="password" value="12345C" class="login_input"/>-->
                        <input type="password" name="password" placeholder="Runner123" class="login_input"/>
                </div>
                <div class="login_form_row2col">
                    <label class="login_label">Select:</label>
                    <input type="radio" name="radioCustomerClerk" value="customer" checked/> <label for="radio">Customer</label>
                    <input type="radio" name="radioCustomerClerk" value="clerk"/> <label for="radio">Clerk</label>
                </div>
                <div>
                    <input type="submit" value="Login" class="login" style="margin-left:92px"/>
                </div>
                <!--<input type="image" src="img/login.gif" class="login"/> -->
            <form/>
        </div>
    </div>

    <?php include("lib/error.php"); ?>

    <div class="clear"></div>
</div>
    <?php include("lib/footer.php"); ?>

</div>
</body>
</html>