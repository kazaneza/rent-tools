## CREATE USER 'newuser'@'localhost' IDENTIFIED BY 'password';
CREATE USER IF NOT EXISTS gatechUser@localhost IDENTIFIED BY 'gatech123';

DROP DATABASE IF EXISTS `cs6400_fa17_team040`; 
SET default_storage_engine=InnoDB;
SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE DATABASE IF NOT EXISTS cs6400_fa17_team040 
    DEFAULT CHARACTER SET utf8mb4 
    DEFAULT COLLATE utf8mb4_unicode_ci;
USE cs6400_fa17_team040;

GRANT SELECT, INSERT, UPDATE, DELETE, FILE ON *.* TO 'gatechUser'@'localhost';
GRANT ALL PRIVILEGES ON `gatechuser`.* TO 'gatechUser'@'localhost';
GRANT ALL PRIVILEGES ON `cs6400_fa17_team040`.* TO 'gatechUser'@'localhost';
FLUSH PRIVILEGES;

CREATE TABLE User (
	username VARCHAR(50) NOT NULL, 
	email VARCHAR(50) NOT NULL,
	password VARCHAR(50) NOT NULL,
	first_name VARCHAR(50)  NULL,
	middle_name VARCHAR(50) NULL,
	last_name VARCHAR(50)  NULL,
PRIMARY KEY (username),
UNIQUE KEY emailID (email)
);



CREATE TABLE Customer(
	username VARCHAR(50) NOT NULL, 
	customer_id int NOT NULL AUTO_INCREMENT,
	home_phone VARCHAR(50) NULL,
	work_phone VARCHAR(50)  NULL,
	cell_phone VARCHAR(50) NULL,
	primary_phone VARCHAR(50) NOT NULL,
	state CHAR(2)  NULL,
	city VARCHAR(50)  NULL,
	street VARCHAR(50)  NULL,
	zip_code CHAR(10) NULL,
	name_on_card VARCHAR(50) NOT NULL,
	card_number bigint NOT NULL,
	expiration_month VARCHAR(50) NOT NULL,
	expiration_year INT NOT NULL,
	cvc CHAR(3) NOT NULL,
CONSTRAINT CustomerID PRIMARY KEY (customer_id),
UNIQUE KEY usernameID (username)
);


CREATE TABLE Clerk(
	clerk_id int NOT NULL AUTO_INCREMENT,
	username varchar(50) NOT NULL,
	date_of_hire datetime NOT NULL,
    clerk_login_check BIT NOT NULL default 0,
CONSTRAINT ClerkID PRIMARY KEY (clerk_id)
); 


CREATE TABLE Purchase(
	purchase_id int NOT NULL AUTO_INCREMENT,
	customer_id INT NULL,
	for_sale_date DATETIME  NULL,
	sold_date DATETIME NULL,
	clerk_id INT NOT NULL,
	tool_id INT NULL,
CONSTRAINT PurchaseID PRIMARY KEY (purchase_id)
);


CREATE TABLE Reservation(
	reservation_id int NOT NULL AUTO_INCREMENT,
	customer_id INT NOT NULL,
	pickup_clerk_id INT,
	drop_off_clerk_id INT,
	start_date DATETIME NOT NULL,
	end_date DATETIME NOT NULL,
CONSTRAINT ReservationID PRIMARY KEY (reservation_id)
);


CREATE TABLE ReservationIncludeTool(
	reservation_id INT NOT NULL,
	tool_id INT NOT NULL,
PRIMARY KEY (reservation_id,tool_id)
);


CREATE TABLE Service(
	service_id int NOT NULL AUTO_INCREMENT,
	clerk_id int NOT NULL,
	cost decimal(15,2) NOT NULL,
	start_date datetime NOT NULL,
	end_date datetime NOT NULL,
	tool_id int NOT NULL,
 CONSTRAINT ServiceID PRIMARY KEY (service_id)
 );
 
CREATE TABLE Tool (
	tool_id int NOT NULL AUTO_INCREMENT,   
	clerk_id int NOT NULL,
	length FLOAT NOT NULL,
	width_diameter FLOAT NOT NULL,
	weight FLOAT NULL,
	original_price decimal(15,2) NOT NULL,
	power_source varchar(50) NOT NULL,
	manufacturer varchar(50) NOT NULL,
	sub_option varchar(50) NOT NULL,
	material varchar(50) NULL,
	sub_type_name varchar(50) NOT NULL,
    quantity int not null default 1,
CONSTRAINT ToolID PRIMARY KEY (tool_id)
);



CREATE TABLE Category(
	category_name varchar(50) NOT NULL,
CONSTRAINT CategoryID PRIMARY KEY (category_name)
);


CREATE TABLE SubType(
	sub_type_name varchar(50) NOT NULL,
	category_name varchar(50) NOT NULL,
    sub_option varchar(50) NOT NULL,
	CONSTRAINT SubTypeID PRIMARY KEY (Sub_Type_name,sub_option)
);


CREATE TABLE Hand(
	tool_id int NOT NULL,
 CONSTRAINT HandID PRIMARY KEY (tool_id)
 ); 



CREATE TABLE Gun(
	tool_id int NOT NULL,
	capacity int NOT NULL,
	gauge_rating int NOT NULL,
 CONSTRAINT GunID PRIMARY KEY (tool_id)
 );




CREATE TABLE Hammer(
	tool_id int NOT NULL,
	anti_vibration bit NULL,
 CONSTRAINT HammerID PRIMARY KEY (tool_id));




CREATE TABLE Pliers(
	tool_id int NOT NULL,
	adjustable bit NULL,
 CONSTRAINT PliersID PRIMARY KEY (tool_id)
 );



CREATE TABLE Ratchet(
	tool_id int NOT NULL,
	drive_size float NOT NULL,
 CONSTRAINT RatchetID PRIMARY KEY (tool_id)
 );



CREATE TABLE Screwdriver(
	tool_id int NOT NULL,
	screw_size int NOT NULL,
 CONSTRAINT ScrewdriverID PRIMARY KEY (tool_id)
 );



CREATE TABLE Socket(
	tool_id int NOT NULL,
	drive_size float NOT NULL,
	sae_size float NOT NULL,
	deep_socket bit NULL,
 CONSTRAINT SocketID PRIMARY KEY (tool_id)
 );
 


CREATE TABLE Power(
	tool_id INT NOT NULL,
	volt_rating FLOAT NULL,
    amp_rating FLOAT NULL,
	min_rpm_rating FLOAT NOT NULL,
	max_rpm_rating FLOAT NULL,
CONSTRAINT PowerID PRIMARY KEY (tool_id)
);
 
CREATE TABLE Accessories(
	tool_id INT NOT NULL,
	accessory_description VARCHAR(100) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
CONSTRAINT AccessoriesID PRIMARY KEY (tool_id, accessory_description)
);
 

CREATE TABLE DC_Cordless(
	tool_id INT NOT NULL,
	battery_type VARCHAR(50) NOT NULL,
CONSTRAINT DC_CordlessID PRIMARY KEY (tool_id)
);
 


 CREATE TABLE Ladder(
	tool_id INT NOT NULL,
	step_count INT  NULL,
	weight_capacity FLOAT NULL,
CONSTRAINT LadderID PRIMARY KEY (tool_id)
);


CREATE TABLE Step(
	tool_id INT NOT NULL,
	pail_shelf BIT  NULL,
CONSTRAINT StepID PRIMARY KEY (tool_id)
);


CREATE TABLE Straight(
	tool_id INT NOT NULL,
	rubber_feet BIT  NULL,
CONSTRAINT StraightID PRIMARY KEY (tool_id)
);


CREATE TABLE Garden(
	tool_id INT NOT NULL,
	handle_material VARCHAR(50)  NULL,
CONSTRAINT GardenID PRIMARY KEY (tool_id)
);


CREATE TABLE Digger(
	tool_id INT NOT NULL,
	blade_width FLOAT NOT NULL,
	blade_length FLOAT NOT NULL,
CONSTRAINT DiggerID PRIMARY KEY (tool_id)
);


CREATE TABLE Pruner(
	tool_id INT NOT NULL,
	blade_material VARCHAR(50) NULL,
	blade_length FLOAT NOT NULL,
CONSTRAINT PrunerID PRIMARY KEY (tool_id)
);


CREATE TABLE Striking(
	tool_id INT NOT NULL,
	head_weight FLOAT  NULL,
	CONSTRAINT StrikingID PRIMARY KEY (tool_id)
);


CREATE TABLE Rakes(
	tool_id INT NOT NULL,
	tine_count INT  NULL,
	CONSTRAINT RakesID PRIMARY KEY (tool_id)
);


CREATE TABLE Wheelbarrows(
	tool_id INT NOT NULL,
	bin_material VARCHAR(50)  NULL,
	bin_volume FLOAT  NULL,
    wheel_count INT  NULL,
	CONSTRAINT WheelbarrowsID PRIMARY KEY (tool_id)
);


CREATE TABLE Mixer(
	tool_id INT NOT NULL,
	motor_rating VARCHAR(50)  NULL,
	drum_size INT  NULL,
	CONSTRAINT MixerID PRIMARY KEY (tool_id)
);


CREATE TABLE Drill(
	tool_id INT NOT NULL,
	adjustable_clutch BIT  NULL,
	min_torque_rating FLOAT  NULL,
	max_torque_rating FLOAT  NULL,
	CONSTRAINT DrillID PRIMARY KEY (tool_id)
);



CREATE TABLE Sander(
	tool_id INT NOT NULL,
	dust_bag BIT  NULL,
CONSTRAINT SanderID PRIMARY KEY (tool_id)
);


CREATE TABLE Saw(
	tool_id INT NOT NULL,
	blade_size FLOAT  NULL,
	CONSTRAINT SawID PRIMARY KEY (tool_id)
);


CREATE TABLE Generator(
	tool_id INT NOT NULL,
	power_rating FLOAT NOT NULL,
	gas_power BIT  NULL,
CONSTRAINT GeneratorID PRIMARY KEY (tool_id)
);


CREATE TABLE AirCompressor(
	tool_id INT NOT NULL,
	tank_size FLOAT  NULL,
	pressure_rating FLOAT  NULL,
	CONSTRAINT AirCompressorID PRIMARY KEY (tool_id)
);



CREATE TABLE Wrench(
    tool_id INT NOT NULL,
	drive_size FLOAT NOT NULL,
	CONSTRAINT WrenchID PRIMARY KEY (tool_id)
);


### Constraints   Foreign Keys: FK_ChildTable_childColumn_ParentTable_parentColumn

ALTER TABLE Reservation  ADD CONSTRAINT FK_Reservation_customer_id_Customer_customer_id FOREIGN KEY(customer_id)
REFERENCES Customer (customer_id);


ALTER TABLE Reservation  ADD CONSTRAINT FK_Reservation_pickup_clerk_id_Clerk_clerk_id FOREIGN KEY(pickup_clerk_id)
REFERENCES Clerk (clerk_id);


ALTER TABLE Reservation  ADD CONSTRAINT FK_Reservation_drop_off_clerk_id_Clerk_clerk_id FOREIGN KEY(drop_off_clerk_id)
REFERENCES Clerk (clerk_id);


ALTER TABLE ReservationIncludeTool  ADD CONSTRAINT FK_RIncludeTool_reservation_id_Reservation_reservation_id FOREIGN KEY(reservation_id)
REFERENCES Reservation (reservation_id);


ALTER TABLE Service  ADD CONSTRAINT FK_Service_order_clerk_id_Clerk_clerk_id FOREIGN KEY(clerk_id)
REFERENCES Clerk (clerk_id);


ALTER TABLE Purchase  ADD CONSTRAINT FK_Purchase_customer_id_Customer_customer_id FOREIGN KEY(customer_id)
REFERENCES Customer (customer_id);


ALTER TABLE Customer  ADD CONSTRAINT FK_Customer_username_User_username FOREIGN KEY(username)
REFERENCES `User` (username);


ALTER TABLE Clerk  ADD CONSTRAINT FK_Clerk_username_User_username FOREIGN KEY(username)
REFERENCES `User` (username);


ALTER TABLE Service  ADD CONSTRAINT FK_Service_tool_id_Tool_tool_id FOREIGN KEY(tool_id)
REFERENCES Tool (tool_id);


ALTER TABLE Tool  ADD CONSTRAINT FK_SubType_sub_type_name_Tool_sub_type_name FOREIGN KEY(sub_type_name)
REFERENCES SubType (sub_type_name);

ALTER TABLE Tool  ADD CONSTRAINT FK_SubType_Sub_option_Tool_sub_option FOREIGN KEY(sub_type_name,sub_option)
REFERENCES SubType (sub_type_name,sub_option);


ALTER TABLE SubType  ADD CONSTRAINT FK_SubType_category_name_category__category_name FOREIGN KEY(category_name)
REFERENCES category (category_name);


ALTER TABLE Tool  ADD CONSTRAINT FK_TooL_clerk_id_Clerk__clerk_id FOREIGN KEY(clerk_id)
REFERENCES Clerk (clerk_id);


ALTER TABLE Tool ADD CONSTRAINT CK_Tool_power_source CHECK  (power_source IN ('electric', 'cordless', 'gas', 'manual'));
##ALTER TABLE Accessories ADD CONSTRAINT CK_Accessories_battery_type CHECK  (power_source IN ('Li-Ion', 'NiCd', 'NiMH'));

ALTER TABLE Hand  ADD CONSTRAINT FK_Hand_tool_id_Tool_tool_id FOREIGN KEY(tool_id)
REFERENCES Tool (tool_id);


ALTER TABLE Gun  ADD CONSTRAINT FK_Gun_tool_id_Tool_tool_id FOREIGN KEY(tool_id)
REFERENCES Hand (tool_id);


ALTER TABLE Ratchet  ADD CONSTRAINT FK_Ratchet_tool_id_Tool_tool_id FOREIGN KEY(tool_id)
REFERENCES Hand (tool_id);


ALTER TABLE Screwdriver  ADD CONSTRAINT FK_Screwdriver_tool_id_Tool_tool_id FOREIGN KEY(tool_id)
REFERENCES Hand (tool_id);


ALTER TABLE Hammer  ADD CONSTRAINT FK_Hammer_tool_id_Tool_tool_id FOREIGN KEY(tool_id)
REFERENCES Hand (tool_id);


ALTER TABLE Pliers  ADD CONSTRAINT FK_Pliers_tool_id_Tool_tool_id FOREIGN KEY(tool_id)
REFERENCES Hand (tool_id);


ALTER TABLE Socket  ADD CONSTRAINT FK_Socket_tool_id_Tool_tool_id FOREIGN KEY(tool_id)
REFERENCES Hand (tool_id);


ALTER TABLE Wrench  ADD CONSTRAINT FK_Wrench_tool_id_Tool_tool_id FOREIGN KEY(tool_id)
REFERENCES Hand (tool_id);


ALTER TABLE Power ADD CONSTRAINT FK_Power_tool_id_Tool_tool_id FOREIGN KEY(tool_id)
REFERENCES Tool (tool_id);


ALTER TABLE Accessories ADD CONSTRAINT FK_Accessories_tool_id_Power_tool_id FOREIGN KEY(tool_id)
REFERENCES Power (tool_id);


ALTER TABLE DC_Cordless ADD CONSTRAINT FK_DC_Cordless_tool_id_Accessories_tool_id FOREIGN KEY(tool_id)
REFERENCES Accessories (tool_id);


ALTER TABLE Ladder  ADD CONSTRAINT FK_Ladder_tool_id_Tool_tool_id FOREIGN KEY(tool_id)
REFERENCES Tool (tool_id);


ALTER TABLE Step  ADD CONSTRAINT FK_Step_tool_id_Ladder_tool_id FOREIGN KEY(tool_id)
REFERENCES Ladder (tool_id);


ALTER TABLE Straight  ADD CONSTRAINT FK_Straight_tool_id_Ladder_tool_id FOREIGN KEY(tool_id)
REFERENCES Ladder (tool_id);


ALTER TABLE Garden  ADD CONSTRAINT FK_Garden_tool_id_Tool_tool_id FOREIGN KEY(tool_id)
REFERENCES Tool (tool_id);

ALTER TABLE Digger  ADD CONSTRAINT FK_Digger_tool_id_Garden_tool_id FOREIGN KEY(tool_id)
REFERENCES Garden (tool_id);

ALTER TABLE Pruner  ADD CONSTRAINT FK_Pruner_tool_id_Garden_tool_id FOREIGN KEY(tool_id)
REFERENCES Garden (tool_id);


ALTER TABLE Striking  ADD CONSTRAINT FK_Striking_tool_id_Garden_tool_id FOREIGN KEY(tool_id)
REFERENCES Garden (tool_id);


ALTER TABLE Rakes  ADD CONSTRAINT FK_Rakes_tool_id_Garden_tool_id FOREIGN KEY(tool_id)
REFERENCES Garden (tool_id);


ALTER TABLE Wheelbarrows  ADD CONSTRAINT FK_Wheelbarrows_tool_id_Garden_tool_id FOREIGN KEY(tool_id)
REFERENCES Garden (tool_id);


ALTER TABLE Mixer  ADD CONSTRAINT FK_Mixer_tool_id_Power_tool_id FOREIGN KEY(tool_id)
REFERENCES Power (tool_id);


ALTER TABLE Drill  ADD CONSTRAINT FK_Drill_tool_id_Power_tool_id FOREIGN KEY(tool_id)
REFERENCES Power (tool_id);


ALTER TABLE Sander  ADD CONSTRAINT FK_Sander_tool_id_Power_tool_id FOREIGN KEY(tool_id)
REFERENCES Power (tool_id);


ALTER TABLE Saw  ADD CONSTRAINT FK_Saw_tool_id_Power_tool_id FOREIGN KEY(tool_id)
REFERENCES Power (tool_id);


ALTER TABLE Generator  ADD CONSTRAINT FK_Generator_tool_id_Power_tool_id FOREIGN KEY(tool_id)
REFERENCES Power (tool_id);


ALTER TABLE AirCompressor  ADD CONSTRAINT FK_AirCompressor_tool_id_Power_tool_id FOREIGN KEY(tool_id)
REFERENCES Power (tool_id);






