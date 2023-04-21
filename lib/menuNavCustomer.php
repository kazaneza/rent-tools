
			<div id="header">
                <div class="logo"><img src="img/tools4rent_logo.png" style="opacity:0.6;background-color:E9E5E2;" border="0" alt="" title="Tools4Rent Logo"/></div>
            </div>
			<div class="nav_bar" style="margin-top:40px;">
				<ul>    
                    <li><a href="view_profile.php" <?php if($current_filename=='view_profile.php') echo "class='active'"; ?>>View Profile</a></li>                       
					<li><a href="check_tool_availability.php" <?php if($current_filename=='edit_profile.php') echo "class='active'"; ?>>Check Tool Availability</a></li>
                    <li><a href="make_reservation.php" <?php if($current_filename=='view_friends.php') echo "class='active'"; ?>>Make Reservation</a></li>
                    <li><a href="logout.php" <span class='glyphicon glyphicon-log-out'></span> Log Out</a></li>
				</ul>
			</div>