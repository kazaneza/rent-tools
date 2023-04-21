
<div id="header">
    <div class="logo"><img src="img/tools4rent_logo.png" style="opacity:0.6;background-color:E9E5E2;" border="0" alt="" title="Tools4Rent Logo"/></div>
<!--    <div class="logo"><img src="img/FQHR3714.PNG" style="opacity:0.5;background-color:E9E5E2;" border="0" alt="" title="Tools4Rent Logo"/></div> -->
</div>
<div class="nav_bar" style="margin-top:10px;">
				<ul>    
                    <li><a href="pickup.php" <?php if($current_filename=='clerk_menu.php') echo "class='active'"; ?>>Pick-up Reservation</a></li>
					<li><a href="Dropoff.php" <?php if($current_filename== 'clerk_menu.php') echo "class='active'"; ?>>Drop-off Reservation</a></li>
                    <li><a href="add_tool.php" <?php if($current_filename=='clerk_menu.php') echo "class='active'"; ?>>Add Tool</a></li>
                    <li><a href="reports.php" <?php if($current_filename=='clerk_menu.php') echo "class='active'"; ?>>Reports</a></li>
                    <li><a href="logout.php" <span class='glyphicon glyphicon-log-out'></span> Log Out</a></li>
				</ul>
			</div>