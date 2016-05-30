<?php
session_start();
$servername = "localhost";
$root = "root";
$rootpass = "";
$username = "fdlogadmin";
$password = "adminpassword";

//Connect as root and create database:
try {
	$conn = new PDO('mysql:host=localhost;', $root, $rootpass);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully as root<br>";
	
//Create datgabase fdlogdb:
	$sql="CREATE DATABASE IF NOT EXISTS fdlogdb";
	$conn->exec($sql);
    //echo "New record created successfully";
	echo "Database fdlogdb created!<br>";
	
//Create fdlogadmin user:
	$sql="CREATE USER IF NOT EXISTS 'fdlogadmin'@'localhost' IDENTIFIED BY 'adminpassword'"; //Change this to a variable later
	$conn->exec($sql);
    //echo "New record created successfully";
	echo "Databse user fdlogadmin added!<br>";
	
//Create fdlogread user: Read only access for making queries
	$sql="CREATE USER IF NOT EXISTS 'fdlogread'@'localhost' IDENTIFIED BY 'password'"; //Change this to a variable later
	$conn->exec($sql);
	echo "Read only user added!<br>";

//Create fdlogwrite user:
	$sql="CREATE USER IF NOT EXISTS 'fdlogwrite'@'localhost' IDENTIFIED BY 'adminpassword'"; //Change this to a variable later
	$conn->exec($sql);
    echo "Write user added!<br>";
	
//Grant admin all privileges:
	$sql="GRANT ALL PRIVILEGES ON fdlogdb.* TO 'fdlogadmin'@'localhost'";
	$conn->exec($sql);
    echo "Admin privileges granted to fdlogadmin!<br>";

//Grant readonly to fdlogread:
	$sql="GRANT SELECT ON fdlogdb.* TO 'fdlogread'@'localhost'";
	$conn->exec($sql);
    //echo "New record created successfully";
	echo "Read privileges granted to fdlogread!<br>";

//Grant write access to fdlogwrite:
	$sql="GRANT CREATE, INSERT, SELECT, UPDATE ON fdlogdb.* TO 'fdlogwrite'@'localhost'";
	
	$conn->exec($sql);
    //echo "New record created successfully";
	echo "Write access granted to fdlogwrite!<br>";

//Flush privileges and close connection:
	$sql="FLUSH PRIVILEGES";
	$conn = NULL;
	echo "<br>Disconnected from root.<br>";
}
catch(PDOException $e) {
	echo $sql . "<br>" . $e->getMessage();
}
//Log in as fdlogadmin:	
try {
    $conn = new PDO("mysql:host=$servername;dbname=fdlogdb", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<br>Connected successfully as <b>",$username,"</b><br><br>";
	
//Create table fd_config:
	$sql="CREATE TABLE IF NOT EXISTS fd_config(
		config_name VARCHAR(255) PRIMARY KEY,
		small_string VARCHAR(255),
		large_string TEXT,
		number BIGINT)";
	$conn->exec($sql);
	echo "Table fd_config added!<br>";
	
	$sql="INSERT INTO fd_config(config_name, small_string) VALUES ('fd_callsign', 'NOCALL')";
	$conn->exec($sql);

//Create table users:
	$sql="CREATE TABLE IF NOT EXISTS users(
		uuid BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		regdate DATETIME DEFAULT CURRENT_TIMESTAMP,
		call_sign VARCHAR(12) UNIQUE,
		first_name VARCHAR(20),
		last_name VARCHAR(20),
		password TEXT,
		license_class VARCHAR(10),
		user_level VARCHAR(6))";
	$conn->exec($sql);
	echo "Table users added!<br>";

//Create table logbook:
	$sql="CREATE TABLE IF NOT EXISTS logbook(
		logid INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		qso_time TIMESTAMP,
		callsign VARCHAR(12),
		section VARCHAR(3),
		operating_class VARCHAR(4),
		logger_id BIGINT,
		band SMALLINT,
		mode VARCHAR(10),
		power SMALLINT,
		FOREIGN KEY (logger_id) REFERENCES users(uuid))";
	$conn->exec($sql);
	echo "Table logbook added!<br>";

//Create table inventory:
	$sql="CREATE TABLE IF NOT EXISTS inventory(
		item_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		user_id BIGINT NOT NULL,
		item_make VARCHAR(100),
		item_model VARCHAR(100),
		item_type VARCHAR(10),
		item_description TEXT,
		contact_number INT,
		FOREIGN KEY (user_id) REFERENCES users(uuid))";
	$conn->exec($sql);
	echo "Table inventory added!<br>";

//Create table station_list:
	$sql="CREATE TABLE IF NOT EXISTS station_list(
		station_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		radio_id INT NOT NULL UNIQUE,
		power_source VARCHAR(255),
		is_vhf BOOL NOT NULL,
		is_gota BOOL NOT NULL,
		FOREIGN KEY (radio_id) REFERENCES inventory(item_id))";
	$conn->exec($sql);
	echo "Table station_list added!<br>";

//Create table active_stations:
	$sql="CREATE TABLE IF NOT EXISTS active_stations(
		user_id BIGINT NOT NULL,
		station_id INT NOT NULL,
		start_time DATETIME DEFAULT CURRENT_TIMESTAMP,
		stop_time DATETIME NULL,
		band SMALLINT,
		mode VARCHAR(3),
		FOREIGN KEY (user_id) REFERENCES users(uuid),
		FOREIGN KEY (station_id) REFERENCES station_list(station_id))";
	$conn->exec($sql);
	echo "Table active_stations added!<br>";

//Create table guestbook:

	$sql="CREATE TABLE IF NOT EXISTS guestbook(
		guest_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		sign_time TIMESTAMP NOT NULL,
		callsign VARCHAR(12) NOT NULL,
		first_name VARCHAR(20) NOT NULL,
		last_name VARCHAR(20) NOT NULL,
		comments TEXT,
		log_time TIMESTAMP)";
	$conn->exec($sql);
	echo "Table guestbook added!<br>";
	
//Create table images:

	$sql="CREATE TABLE IF NOT EXISTS images(
		image_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		upload_time TIMESTAMP NOT NULL,
		user_id BIGINT NOT NULL,
		file_location TEXT NOT NULL,
		display_html TEXT NOT NULL,
		description TEXT,
		FOREIGN KEY (user_id) REFERENCES users(uuid))";
	$conn->exec($sql);
	echo "Table images added!<br>";
	
//Create admin user:
	$pass_options = ['cost' => 12];
	$pass = password_hash("password", PASSWORD_BCRYPT, $pass_options);
	$sql="INSERT INTO users (call_sign, password, user_level) VALUES ('admin', '$pass', 'admin')";
	$conn->exec($sql);
	echo '<br> Account "admin" created with password "password".';
	echo '<br> You will be rediected to the homepage in 5 seconds.';
	echo '<META http-equiv="refresh" content="5;URL=/index.php">';
}
catch(PDOException $e) {
	echo $sql . "<br>" . $e->getMessage();
}

echo '<br><a href="../index.php">Home</a>';

$conn=null;
?>