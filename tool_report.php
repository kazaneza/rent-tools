<?php
include('lib/common.php');
// written by yao yao and carol ccheung39@gatech.edu and Homer homerm3@gatech.edu

if (!isset($_SESSION['email'])) {
    header('Location: clerk_menu.php');
    exit();
}

?>

<?php include("lib/header.php"); ?>
<title>Tool Inventory Report</title>
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

<body>
<div id="main_container"><?php include("lib/menuNavClerk.php"); ?>
    <div class="center_content">
        <div class="center_left">
            <div class="title">Tool Inventory Report</div>
            <p><br></p>
            <form action="tool_report.php" method="post" enctype="multipart/form-data">
                <div style="font-family:sans-serif;font-size:13px;width:60px;float:left;margin-left:30px"><p><b>Type: </b></div>
                <div style="width:620px;margin-left:40px">
                    <input type="radio" name="category" value="all" checked/> <label for="radio">All Tools</label>
                    <input type="radio" name="category" value="hand" /> <label for="radio">Hand Tool</label>
                    <input type="radio" name="category" value="garden" /> <label for="radio">Garden Tool</label>
                    <input type="radio" name="category" value="ladder" /> <label for="radio">Ladder Tool</label>
                    <input type="radio" name="category" value="power" /> <label for="radio">Power Tool</label>
                </div>
                <br>
                <div style="font-family:sans-serif;font-size:13px;width:620px;float:left;margin-left:30px""><p></p><p><b>Custom Search: </b>
                    <input type="text" name="keyword" placeholder="keyword" value="<?php if ($row['keyword']) { print $row['keyword']; } ?>" />
                        <b>&nbsp&nbspInput Date: </b>
                        <input type="date" name="input_date" value="<?php if ($row['input_date']) { print $row['input_date']; } ?>" />
                    <input type="submit" value="Search" style="margin-left:10px;"/>
                    <p><br></p>
                </div>
                <!--<div class="center_left">
                    <div class="profile_section"; style="overflow-x:auto"; > -->
                <table>
                    <tr>
                        <td class="heading">Tool ID</td>
                        <td class="heading">Current Status</td>
                        <td class="heading" style="text-align:center">Date</td>
                        <td class="heading" width="180px">Description</td>
                        <td class="heading">Rental Profit</td>
                        <td class="heading">Total Cost</td>
                        <td class="heading">Total Profit</td>
                    </tr>

                    <?php
                    $keyword = mysqli_real_escape_string($db, $_POST['keyword']);
                    $category = mysqli_real_escape_string($db, $_POST['category']);
                    $date = mysqli_real_escape_string($db, $_POST['input_date']);

                    if (!empty($date)) {
                            $query = "select T.tool_id, 
(CASE WHEN (SELECT sold_date from Purchase where tool_id = t.tool_id) IS NOT NULL THEN 'Sold'
      WHEN (SELECT for_sale_date from Purchase where tool_id = t.tool_id) IS NOT NULL THEN 'For-Sale'
      WHEN (SELECT start_date from Service where tool_id = t.tool_id) <= '$date' AND
		   (SELECT end_date from Service where tool_id = t.tool_id) >= '$date' THEN 'In-Repair'
      WHEN '$date' BETWEEN R.start_date AND R.end_date THEN 'Rented'
  ELSE 'Available'
  END) as status,
(CASE WHEN (SELECT sold_date from Purchase where tool_id = t.tool_id) IS NOT NULL THEN 
   (SELECT DATE_FORMAT(sold_date,'%Y-%m-%d') from Purchase where tool_id = t.tool_id) 
      WHEN (SELECT for_sale_date from Purchase where tool_id = t.tool_id) IS NOT NULL THEN
   (SELECT DATE_FORMAT(for_sale_date,'%Y-%m-%d') from Purchase where tool_id = t.tool_id)    
      WHEN (SELECT start_date from Service where tool_id = t.tool_id) <= '$date' AND
		   (SELECT end_date from Service where tool_id = t.tool_id) >= '$date' THEN
   (SELECT DATE_FORMAT(start_date,'%Y-%m-%d') from Service where tool_id = t.tool_id)       
      WHEN '$date' BETWEEN R.start_date AND R.end_date THEN DATE_FORMAT(R.start_date, '%Y-%m-%d')
   END ) as return_date,
  
(CASE WHEN T.power_source='manual' THEN CONCAT(T.sub_option,' ',T.sub_type_name) ELSE CONCAT(T.power_source,' ',T.sub_option,' ',T.sub_type_name) END) AS short_description,
(CASE WHEN RT.reservation_id IS NOT NULL THEN (CASE WHEN '$date' > R.end_date THEN ROUND(SUM(0.15*T.original_price * (DATEDIFF(DATE(R.end_date),DATE(R.start_date))+1)), 2) ELSE 0.00 END) ELSE 0.00 END) as rental_profit,
ROUND(T.original_price, 2) as total_cost,  


(CASE WHEN RT.reservation_id IS NOT NULL THEN (CASE WHEN '$date' > R.end_date THEN ROUND(((0.15*T.original_price * (DATEDIFF(DATE(R.end_date),DATE(R.start_date))+1))-T.original_price),2) ELSE ROUND(0.00-T.original_price, 2) END) ELSE ROUND(0.00-T.original_price, 2) END) as total_profit 


FROM Tool T LEFT OUTER JOIN ReservationIncludeTool RT ON T.tool_id = RT.tool_id 
     LEFT OUTER JOIN Reservation R ON R.reservation_id = RT.reservation_id  ";

##IF THEN HERE
                        if ($category != 'all') {
                                $query = $query . " WHERE (SELECT DISTINCT category_name FROM SubType where sub_type_name = T.sub_type_name) = '$category' ";
                            }

                            if (!empty($keyword)) {
                                $query = $query . " AND T.tool_id in (SELECT T2.tool_id
FROM tool T2 NATURAL LEFT JOIN garden NATURAL LEFT JOIN hand NATURAL LEFT JOIN ladder NATURAL LEFT JOIN power NATURAL LEFT JOIN
screwdriver NATURAL LEFT JOIN socket NATURAL LEFT JOIN ratchet NATURAL LEFT JOIN wrench NATURAL LEFT JOIN pliers
NATURAL LEFT JOIN gun NATURAL LEFT JOIN hammer NATURAL LEFT JOIN pruner NATURAL LEFT JOIN striking NATURAL LEFT JOIN
digger NATURAL LEFT JOIN rakes NATURAL LEFT JOIN wheelbarrows NATURAL LEFT JOIN drill NATURAL LEFT JOIN saw NATURAL LEFT JOIN
sander NATURAL LEFT JOIN aircompressor NATURAL LEFT JOIN mixer NATURAL LEFT JOIN generator NATURAL LEFT JOIN straight
NATURAL LEFT JOIN step NATURAL LEFT JOIN subtype ST2
WHERE T2.tool_id like '%$keyword%' OR T2.sub_option like '%$keyword%' OR T2.sub_type_name like '%$keyword%' OR
blade_length like '%$keyword%' OR drive_size like '%$keyword%' OR length like '%$keyword%' OR
width_diameter like '%$keyword%' OR weight like '%$keyword%' OR original_price like '%$keyword%' OR
power_source like '%$keyword%' OR manufacturer like '%$keyword%' OR
material like '%$keyword%' OR handle_material like '%$keyword%' OR step_count like '%$keyword%'
OR weight_capacity like '%$keyword%' OR  volt_rating like '%$keyword%' OR amp_rating like '%$keyword%'
OR min_rpm_rating like '%$keyword%' OR max_rpm_rating like '%$keyword%' OR screw_size like '%$keyword%'
OR sae_size like '%$keyword%' OR deep_socket like '%$keyword%' OR capacity like '%$keyword%' OR
gauge_rating like '%$keyword%' OR blade_material like '%$keyword%' OR head_weight like '%$keyword%'
OR blade_width like '%$keyword%' OR tine_count like '%$keyword%' OR bin_material like '%$keyword%'
OR bin_volume like '%$keyword%' OR wheel_count like '%$keyword%' OR min_torque_rating like '%$keyword%'
OR max_torque_rating like '%$keyword%' OR blade_size like '%$keyword%' OR tank_size like '%$keyword%'
OR pressure_rating like '%$keyword%' OR motor_rating
like '%$keyword%' OR drum_size like '%$keyword%' OR power_rating like '%$keyword%' OR
gas_power like '%$keyword%' OR rubber_feet like '%$keyword%' OR pail_shelf like '%$keyword%' OR ST2.category_name like '%$keyword%') ";
                         }

 $query = $query . " group by T.tool_id, R.reservation_id, RT.reservation_id ORDER BY total_profit desc";


                            $result = mysqli_query($db, $query);
                            include('lib/show_queries.php');

                            if (is_bool($result) && (mysqli_num_rows($result) == 0)) {
                                array_push($error_msg, "Query ERROR: No tools report..." . __FILE__ . " line:" . __LINE__);
                            }
                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                $tool_id= urlencode($row['tool_id']);
                                $status = urlencode($row['status']);
                                print "<tr>";
                                print "<td style='text-align:center'>" . $row['tool_id'] . "</td>";
                                if ($status == 'Rented') {
                                    print "<td>"."<P style=\"background-color:yellow;text-align:center;color:black;\">Rented</P>"."</td>";
                                }
                                if ($status == 'Available') {
                                    print "<td>"."<P style=\"background-color:green;text-align:center;color:white;\">Available</P>"."</td>";
                                }
                                if ($status == 'In-Repair') {
                                    print "<td>"."<P style=\"background-color:red;text-align:center;color:white;\">In-Repair</P>"."</td>";
                                }
                                if ($status == 'For-Sale') {
                                    print "<td>"."<P style=\"background-color:grey;text-align:center;color:white;\">For-Sale</P>"."</td>";
                                }
                                if ($status == 'Sold') {
                                    print "<td>"."<P style=\"background-color:black;text-align:center;color:white;\">Sold</P>"."</td>";
                                }

                                print "<td>" . $row['return_date']. "</td>";
                                print "<td><a href=\"Dropoff_Tooldetail.php?tool_id=$tool_id\" target=\"_blank\">" .$row['short_description'] ."</a></td>";
                                print "<td style='text-align:center'>" . $row['rental_profit'] . "</td>";
                                print "<td style='text-align:center'>" . $row['total_cost'] . "</td>";
                                print "<td style='text-align:center'>" . $row['total_profit'] . "</td>";
                                print "</tr>";
//                            print "<tr><td>" . $row['customer_id'] . "</td><td>" . $row['first_name'] ."</td><td>" . $row['first_name'] . "</td><td>" . $row['middle_name'] . "</td><td>" .
//                                $row['last_name'] . "</td><td>" . $row['email'] . "</td><td>" . $row['primary_phone'] . "</td><td>" . $row['TotalReservation'] .
//                                "</td><td>" . $row['TotalTool'] . "</td></tr>";
                            }
                        } else {
                            array_push($error_msg, "Need to give a input date to check whether the tools available");
                        }
                        ?>

                    </table>
                </div>
            </form>
        <?php include("lib/error.php"); ?>
        <div class="clear"></div>
    </div>
    <?php include("lib/footer.php"); ?>

</div>
</body>
</html>
