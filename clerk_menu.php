<?php
/**
 * Clerk Menu  carol cheung ccheung39@gatech.edu
 */
include('lib/common.php');

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

$query = "SELECT first_name, last_name FROM User WHERE User.email='{$_SESSION['email']}'";

$result = mysqli_query($db, $query);
include('lib/show_queries.php');

if ( !is_bool($result) && (mysqli_num_rows($result) > 0) ) {
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
} else {
    array_push($error_msg,  "Query ERROR: Failed to get Clerk name...<br>" . __FILE__ ." line:". __LINE__ );
}

include("lib/header.php"); ?>
<title>Tools-4-Rent! Clerk Menu</title>
</head>

<body>
<div id="main_container">
    <?php include("lib/menuNavClerk.php"); ?>
    <div class="center_content">
        <div class="center_left">
            <div class="title_name">
                <?php print $row['first_name'] . ' ' . $row['last_name']; ?>
            </div>
            <div class="features">

                <div class="profile_section">
                    <div class="subtitle">Clerk Menu</div>
                    <table>
                        <tr>
                            <td class="item_label"><a href="Pickup.php">Pick-Up Reservation</a></td></tr>
                        <tr>
                            <td class="item_label"><a href="Dropoff.php">Drop-Off Reservation</a></td></tr>
                        <tr>
                            <td class="item_label"><a href="add_tool.php">Add New Tool</a></td></tr>
                        <tr>
                            <td class="item_label"><a href="reports.php">Generate Reports</a></td></tr>
                        <tr>
                            <td class="item_label"><a href="logout.php">Logout</a></td></tr>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <?php include("lib/error.php"); ?>

        <div class="clear"></div>
    </div>

    <?php include("lib/footer.php"); ?>

</div>
</body>
</html>