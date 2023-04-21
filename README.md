# PHP-tools4rent
Web APP for tools rent

## Stuff that doesn’t work
-	View Profile only shows completed reservations. NO reservations in progress
-	Custom search term in Tool Inventory Report doesn’t work. Only Screwdriver sort of works
o	Not work Air-Compressor, Pliers, Gun
-	Pick up reservation doesn’t check if clerk has already picked up. All reservations for that date are shown even if the pick up already occurred

## CS 6400 Database Systems Phase 3 Demo
Tools-4-Rent! Script
### 1	Team Introduction
  We have together in this demo: list names of everybody in attendance. We all worked on pieces of this demo using both writing, updating, and inserting MySqli statements via PHP. We used elements of HTML5, javascript, and PHP to get this to work. We’re pleased to show you our working database web application
### 2	MyPHP
  Let’s first take a look at what kind of Tools, Customers, and Reservations are in the database. We have 64 tools from different categories and subtypes. We have 10 Customers and 25 Reservations. [Optional] Now let’s take a quick look and see for all Reservations, what Customers go with them and what Tools (put in query).
Now let’s take a look how we interact with the web application to manipulate the information in the database.
### 3	Login
Requirements
•	Enter website by entering Email and password and selecting Customer
•	If there’s no registered, they’re automatically taken to the page to Register
•	If Customer logs in as Clerk, ERROR
•	Clerk logs in with correct Email and password and selecting Clerk
•	If first time logging in, must change password
•	If enter incorrect password or login as Customer, ERROR
### 4	Login as Regular Clerk
If the Clerk logs in but with the incorrect password [show] or email [show], an error is shown. If the Clerk logs in as Customer, also an error results. Now the Clerk logs in with the correct password.
First show the Tool Report in Reports link. For any given “today” date [2017-10-13], we show the status of all tools in the inventory, their calculated profits and costs. Scroll to bottom to show all tools and their status. In power tools, The Clerk will now add tools, so let’s first check out the bottom list. [Note the bottom few tools]
Now this first time Clerk’s role is to put in some new tools
### 5	Add Tool
In the interest of time, I’ll show the Clerk adding 3 tools. 
Let’s first add a Garden Tool. Note the interface requires selecting a Category type and allowable power sources are the only ones that can be selected [Click Garden & Manual]. The subtype menu does not get populated until the Submit button is clicked. We select a Digger. Only when the subtype is selected does the suboption menu get filled. Let’s put in values for attributes for the Digger Tool. [INSERT ONLY REQUIRED ones] We aren’t allowed to submit these attributes until all required values are given. We are notified that the Digger attributes have been inserted and can now continue adding Digger attributes. We allow input via the number field and pull-down menu. Once we submit, all attributes are added successfully.
Let’s add another tool. In between adding new tools or in the middle of adding a new tool, we make a mistake, we can RESET the page to start over. Let’s add a Ladder/Hand [NEED TO DECIDE] tool. Notice, again the tool attributes will get filled in [INSERT MATERIAL TOO]. We also insert at least 1 option attribute. 
Let’s add an electric Air Compressor, cordless Sander with accessories, and gas Generator with accessories. [NEED TO INPUT more text about what we select]
Now that we’ve added a bunch of tools, let’s check out the reports.
### 6	Reports
6.1	Add Tool Report
Now that we’ve added tools, let’s see if we can find them in the tool report. Select Dec15 date and scroll down to see if tools are listed. The profit is negative like many tools that haven’t been rented yet. 
6.2	Clerk Report
From the Reports Menu, let’s check out Clerk report. From the 1st of the month to the date selected [Nov 15], we can see what clerks have picked up and dropped off. At the end of the month [Nov 30], let’s see who won Clerk of the month. This is our sortable table, which is actually really cool. Any column heading is clicked and it sorts in ascending order. Click again and it reorders the table based on that columns descending order.
To see how the database works, let’s make note of all the clerks who have dealt with 1 transaction in Dec. [Note this clerk is not listed and note what names are listed].
Let’s go back to Reports Menu and select Customer Report
6.3	Customer Report
Here we want to look at the past 30 days, what customers have rented tools. If we want to check out the customers in the past month, we just selected the last day of that month [Nov]. We can look at a Customer’s profile, say Sky Yao, and see that he only made 1 rental with a lot of tools. Or Todd Johnson who made multiple rentals. Or Kevin Taylor who made only 1 rental. Let’s take a look at the month of Dec. There are only 3 customers who have made reservations so far.
Let’s back out and check out the interface for a Customer now that we see what tools and reports are in the app. <Logout>
### 7	Login as New Customer
Let’s pretend we’re a new customer. If we input even just a customer email that isn’t in the database, we’re directly taken to the Register page.
### 8	Register Customer
All the optional fields are displayed for the Customer to input, but there are actually only a few that are required. Primary phone is a radio button to allow only one to be selected. If we only put in Email and attempt to Submit, we get errors. If we don’t include any phone, we get an error. 
We can insert extensions or parentheses into the phone. Since the credit card number is assumed valid [choose all 4s], we just check that the CVC number is 3 digits. If we attempt to register with required fields missing, an error is shown. Let’s put in the minimum. Upon submit, the Customer information is inserted into the database.
Customer is sent back to the Login screen to now with her stored Email and password and taken to the Customer Menu. We can look at View Profile for this sparsely populated Customer to verify what was just written to the database.
Let’s log in again as a New Customer and this time show that all input fields including optional ones get written to the database. [Enter all fields and submit]. Log in as that new Customer and View Profile to view all the new Customer’s complete data.
### 9	Login as Active Customer
Let’s log in as an active Customer with lots of reservations. If the password is incorrect, we’ll show the error handling or if we mistakenly click Clerk, we also get an error. With valid Email and Password, we enter the system and View Profile to show the history of this Customer’s reservations.
### 10	View Profile
We’re able to see all Customer information as well as a record of all the past and current reservations. Reservations that haven’t been picked up don’t have any Clerk information and those that are in their current reservation window have only drop off clerk name missing.
Let’s go see if the tools we want to rent are available.
### 11	Check Tool Availability
The Customer searches for tools through a variety of fields to see if tools are available to be rented. First category is required to find out what subtype tools are available. If selected tool options are invalid, an error message results. 
INVALID CASE 1: Electric garden tools
INVALID CASE 2: Generator on electric power
Custom search: Gun, Rakes, Drill, or Step [what others?
Drop down search: Striking, Drill + Cordless, 
Valid options then populate a table. Once the search button is selected, the fields are queried back to the database to read tools that haven’t been rented. Those available tools in their entirety are listed in the table below. If we click on tool Description hyperlink, we are taken to a new page that lists the tool’s detail: the id, type, short and full description, deposit and rental price and any associated accessories. 
If more than 20 tools result from the query, we get another error message. 
Let’s now Make a tool Reservation
### 12	Make Reservation
Customer goes through same initial steps as Check Tool Availability. Let’s first reserve manual tools from 2017-12-06 to 2017-12-20. 
In this case, the search criteria shown first and the customer confirms the criteria to view available tools to rent. Then the Customer clicks on the ADD TO CART button to add tools to the Reserve List. 
The ADDED tools are placed in a new table at the bottom of the page under the Tools Added to Reservation heading. We can add tools [from bottom of screen] and remove tools from our cart.
Note: We have elected to not add keep track of the multiple quantities of the same tool ID in the database. Therefore, if a tool of a specific type is rented, another one may not also be rented. This requirement was relaxed in Piazza.
When the Customer is satisfied with the tools to reserve, click on CONFIRM AND GO TO RESERVATION SUMMARY. 
The RESERVATION SUMMARY shows both the dates and number of days in the rental, the total deposit and rental price. 
Suppose the Customer prefers different tools to rent, she can select RESET. Let’s reserve 3 tools in either LADDER or POWER tools for 2017-12-10 to 2017-15. Reserve Tool ID 29 [multiposition step ladder] or ID 8 [electric sander sheet]. Also choose one of the newly added LADDER or POWER_w_ACCESSORY tools.
To complete the transaction, if Customer confirms and submits the reservation, a RESERVATION CONFIRMATION is shown which updates Reservation table with the Customer ID and Reservation dates to Reservation table and generates a new Reservation ID number with the reserved tools, and the Total Rental Price and Deposit Price are listed in both the table and header section.
The Customer is able to click on PRINT RESERVATION to print the confirmation at her local printer.
DO NOT CHECK IF RESERVATION SHOWS UP IN VIEW PROFILE (Doesn’t work)
### 13	Logout
This concludes the activities of the Customer. She can select LOG OUT to end her session at Tools4Rent.
Let’s now see the Clerk’s side of this transaction. This time, a new Clerk will be logging in for the first time
### 14	First time Clerk login
With the correct password: we’re required to change the password. If passwords are not entered, don’t match, or is same as original; errors result. Otherwise password is changed in User table and clerk check is changed to denote Clerk has logged in already. After new password has been written, Clerk is taken directly to Clerk Main Menu.
Pretend it is the day of that reservation’s pickup. At Clerk Main Menu, the Clerk select Pick Up Reservation. 
### 15	Pick-up Reservation
Let’s first check out different Pick Up days to see the different reservations. Click on a reservation [2017-11-28] to see the Reservation details, including what tools and accessories, if any. 
Now let’s look up reservations for Pick Up on the same day as the reservation we just reserved: DATE. we see if we select the Reservation ID. For a more interesting date, let’s choose. Enter that Reservation # to be PICKed UP. The Pick Up Reservation Summary page lists all tools that are reserved, the deposit and rental prices and customer’s name. Here, Clerk is allowed to change the Customer’s credit card.
Enter new credit card information [123..] and Confirm Pickup. We get verification that the new credit card updated the Customer. Click Confirm Pickup again to load Pickup Receipt. When Pick Up is confirmed, Pick Up Receipt page is shown, which can also be printed similar to Reservation Receipt.
Suppose it’s the date of Drop Off for the same Reservation we’ve been doing.
### 16	Drop-off Reservation
Clerk selects Drop Off from the navigation bar. Enter the date [2017-12-15] of drop-off and a table showing all reservations due for Drop Off. Again, we select any Reservation ID for any details about that reservation. Let’s check the tool that all power accessories have been returned.
When submit is clicked, the Dropoff link will write to the database the clerk id number who accounted for the drop off and the final Drop off Receipt is available for print.
If we want to do another Drop off transaction, select 2017-12-13 or (11-07 – has Tool ID 47 to show all accessories to check that those are also being returned).
Now let’s go review the reports again with all the changes we’ve made in this demo.
### 17	Report Menu
17.1	Clerk Report
We can now easily see this new Clerk’s recent transactions by selecting the last date of that month. Then Clerk ID in descending order. Here’s our new Clerk with his 2 transactions.
Let’s check out the changes to Customer Report
17.2	Customer Report
Our new customer, Leo Mark, is also now in the Customer for that month but isn’t in prior months.
17.3	Tool Report
Let’s see the changes to the tools of interest. The tools 
