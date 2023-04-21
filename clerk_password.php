<?php
include('./lib/common.php');
include('./lib/show_queries.php');

//carol cheung ccheung39@gatech.edu
//
$clerkEmail = $_SESSION['email'];
$clerkID = $_SESSION['id'];

/*if (isset($_GET['enteredPassword'])){
    $oldPassword = $_GET['enteredPassword'];
    array_push($query_msg, "old password: ". $oldPassword);
}
array_push($query_msg, "if old password is not transmitting: ". $oldPassword);*/

//get username and old password
$queryUser = "SELECT username, password FROM `User` WHERE email='$clerkEmail'";
$result = mysqli_query($db,$queryUser);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
if ( !is_bool($result) && (mysqli_num_rows($result) > 0) ){
    $userName = $row['username'];
    $oldPassword = $row['password'];
} else{
    array_push($error_msg,  "Query ERROR: Failed to get username & password...". $queryUser . __FILE__ ." line:". __LINE__ );
}

if( $_SERVER['REQUEST_METHOD'] == 'POST') {
    $oldPassword = mysqli_real_escape_string($db, $_POST['oldPassword']);
    $newPassword1 = mysqli_real_escape_string($db, $_POST['password1']);
    $newPassword2 = mysqli_real_escape_string($db, $_POST['password2']);

    if (!isset($newPassword1) or !isset($newPassword2) or empty($newPassword1) or empty($newPassword2)) {
        array_push($error_msg, "Please enter password twice.");
    } elseif (strcmp($newPassword1, $newPassword2) != 0) {
        array_push($error_msg, "Passwords do not match. Please re-enter your password");
        header(REFRESH_TIME . 'url=clerk_password.php');
    } elseif (strcasecmp($newPassword1, $oldPassword) == 0) {
        array_push($error_msg, "New password must be different.Please re-enter your password");
        header(REFRESH_TIME . 'url=clerk_password.php');
    } else {

//        $intAt = mb_stripos($clerkEmail, '@');
//        $userName = mb_substr($clerkEmail, 0, $intAt - 1);

        $queryPwd = "UPDATE `User` SET password = '$newPassword1' WHERE email='$clerkEmail'";
        $result = mysqli_query($db, $queryPwd);
        $query1 = "UPDATE Clerk SET clerk_login_check=1 WHERE username='$userName'";
        $result1 = mysqli_query($db, $query1);

        if ($result == true && $result1 == true) {

            array_push($query_msg, "Password has been changed. Redirecting to clerk menu");
            header(REFRESH_TIME . 'url=clerk_menu.php');
        } else {
            array_push($error_msg, "Error updating new password. Fatal error. Returning to log-in");
            array_push($error_msg, 'Error# ' . mysqli_errno($db) . ": " . mysqli_error($db));
            header(REFRESH_TIME . 'url=login.php');
        }
    }
}
    if($showQueries){
        array_push($query_msg, "clerk id & email: ". $clerkID . "; " . $clerkEmail);
        array_push($query_msg, "old password: ". $oldPassword ." has been changed to ". $newPassword2);
    }
?>

<?php include("./lib/header.php"); ?>
<title>Clerk Password Reset</title>
<style>
    table{width:400px;}
    table,th,td{
        text-align:left;
        margin-left:60px;
        margin-top:20px;
    }
    th,td{padding:10px;
        font-size=10px;}

</style>
</head>
<body>
<div id="main_container">
    <div id="header">
        <div class="logo">
            <img src="./img/tools4rent_logo.PNG" style="opacity:0.5;background-color:E9E5E2;" border="0" alt="" title="Tools4Rent Logo"/>
        </div>
    </div>

    <div class="center_content">
            <form action="clerk_password.php" method="post" enctype="multipart/form-data">
                <div class="title">Clerk First Time Logging In Must Change Password</div>
                <h2 style="margin-left:10px">Enter New Password</h2>
                <div style="margin-left:40px">
                    <table>
                    <tr><td style="text-align:right"><b>Old Password:</b></td>
                    <td><input type="password" name="oldPassword" value="<?php echo htmlspecialchars($oldPassword);?>" /></td>
                    </tr>
                    <tr><td style="text-align:right"><b>Enter Password:</b></td>
                        <td><input type="password" name="password1" placeholder="enter password once" /></td>
                    </tr>
                    <tr><td style="text-align:right"><b>Confirm Password:</b></td>
                    <td><input type="password" name="password2" placeholder="enter password twice" /></td>
                    </tr>
                </div>
                <div>
                    <tr><td></td><td><input type="submit" src="./img/login.gif" value="Submit New Password" /></td></tr>
                </div>
                </table>
            <form/>
        </div>
    <?php include("lib/error.php"); ?>
    <div class="clear"></div>
    <?php include("lib/footer.php"); ?>
</div>
</body>
</html>