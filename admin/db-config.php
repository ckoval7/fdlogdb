<?php
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
}
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
	
//Create datgabase fdlogdb:
try {
	$sql="CREATE DATABASE IF NOT EXISTS fdlogdb";
	$conn->exec($sql);
    //echo "New record created successfully";
	echo "Database fdlogdb created!<br>";
}
catch(PDOException $e) {
	echo $sql . "<br>" . $e->getMessage();
}

//Create fdlogadmin user:
try {
	$sql="CREATE USER IF NOT EXISTS 'fdlogadmin'@'localhost' IDENTIFIED BY 'adminpassword'"; //Change this to a variable later
	$conn->exec($sql);
    //echo "New record created successfully";
	echo "Databse user fdlogadmin added!<br>";
}
catch(PDOException $e) {
	echo $sql . "<br>" . $e->getMessage();
}

//Create fdlogread user: Read only access for making queries
try {
	$sql="CREATE USER IF NOT EXISTS 'fdlogread'@'localhost' IDENTIFIED BY 'password'"; //Change this to a variable later
	$conn->exec($sql);
	echo "Read only user added!<br>";
}
catch(PDOException $e) {
	echo $sql . "<br>" . $e->getMessage();
}

//Create fdlogwrite user:
try {
	$sql="CREATE USER IF NOT EXISTS 'fdlogwrite'@'localhost' IDENTIFIED BY 'adminpassword'"; //Change this to a variable later
	$conn->exec($sql);
    echo "Write user added!<br>";
}
catch(PDOException $e) {
	echo $sql . "<br>" . $e->getMessage();
}
//Grant admin all privileges:
try {
	$sql="GRANT ALL PRIVILEGES ON fdlogdb.* TO 'fdlogadmin'@'localhost'";
	$conn->exec($sql);
    echo "Admin privileges granted to fdlogadmin!<br>";
}
catch(PDOException $e) {
	echo $sql . "<br>" . $e->getMessage();
}

//Grant readonly to fdlogread:
try {
	$sql="GRANT SELECT ON fdlogdb.* TO 'fdlogread'@'localhost'";
	$conn->exec($sql);
    //echo "New record created successfully";
	echo "Read privileges granted to fdlogread!<br>";
}
catch(PDOException $e) {
	echo $sql . "<br>" . $e->getMessage();
}

//Grant write access to fdlogwrite:
try {
	$sql="GRANT CREATE, INSERT, SELECT, UPDATE ON fdlogdb.* TO 'fdlogwrite'@'localhost'";
	
	$conn->exec($sql);
    //echo "New record created successfully";
	echo "Write access granted to fdlogwrite!<br>";
}
catch(PDOException $e) {
	echo $sql . "<br>" . $e->getMessage();
}
//Flush privileges and close connection:
$sql="FLUSH PRIVILEGES";
$conn = NULL;
echo "<br>Disconnected from root.<br>";

//Log in as fdlogadmin:	
try {
    $conn = new PDO("mysql:host=$servername;dbname=fdlogdb", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<br>Connected successfully as <b>",$username,"</b><br><br>";
	
//Create table users:
	$sql="CREATE TABLE IF NOT EXISTS users(
		uuid BIGINT NOT NULL AUTO_INCREMENT primary key,
		regdate DATETIME,
		call_sign VARCHAR(12) UNIQUE,
		first_name VARCHAR(20),
		last_name VARCHAR(20),
		password TEXT,
		license_class VARCHAR(10),
		user_level VARCHAR(5))";
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
		mode VARCHAR(3),
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
		start_time DATETIME,
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
		comments TEXT)";
	$conn->exec($sql);
	echo "Table guestbook added!<br>";
	
//Create admin user:
	$pass_options = ['cost' => 12];
	$pass = password_hash("password", PASSWORD_BCRYPT, $pass_options);
	$sql="INSERT INTO users (call_sign, password, user_level) VALUES ('admin', '$pass', 'admin')";
	$conn->exec($sql);
	echo '<br> Account "admin" created with password "password".';
}
catch(PDOException $e) {
	echo $sql . "<br>" . $e->getMessage();
}

$conn=null;
?>