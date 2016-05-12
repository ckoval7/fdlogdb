<?php
$servername = "localhost";
$root = "root";
$rootpass = "";
$username = "fdlogadmin";
$password = "adminpassword";
$test = "testing"; //testing purposes only, remove me

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
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
	
//Create table users:
try {
	$sql="CREATE TABLE IF NOT EXISTS users(
		uuid BIGINT NOT NULL AUTO_INCREMENT primary key,
		regdate DATETIME,
		call_sign varchar(12) unique index,
		first_name varchar(20),
		last_name varchar(20),
		salt VARCHAR(5),
		password TEXT,
		license_class varchar(10))";
	$conn->exec($sql);
    //echo "New record created successfully";
	echo "Table users added!<br>";
}
catch(PDOException $e) {
	echo $sql . "<br>" . $e->getMessage();
}

//Create table logbook:
try {
	$sql="CREATE TABLE IF NOT EXISTS logbook(
		logid INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		qso_time TIMESTAMP,
		logger_id FOREIGN KEY REFERENCES users(uuid),
		callsign VARCHAR(12),
		section VARCHAR(3),
		operating_class VARCHAR(4),
		band SMALLINT,
		mode VARCHAR(3),
		power SMALLINT)";
	$conn->exec($sql);
	echo "Table logbook added!<br>";
}
catch(PDOException $e) {
	echo $sql . "<br>" . $e->getMessage();
}

//Create table guestbook:
try {
	$sql="CREATE TABLE IF NOT EXISTS guestbook(
		guest_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		sign_time TIMESTAMP,
		callsign VARCHAR(12),
		first_name VARCHAR(20),
		last_name VARCHAR(20),
		comments TEXT)";
	$conn->exec($sql);
	echo "Table guestbook added!<br>";
}
catch(PDOException $e) {
	echo $sql . "<br>" . $e->getMessage();
}

//Create table inventory:
try {
	$sql="CREATE TABLE IF NOT EXISTS inventory(
		item_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		user_id FOREIGN KEY REFERENCES users(uuid),
		item_make VARCHAR(100),
		item_model VARCHAR(100),
		item_description TEXT,
		contact_number INT)";
	$conn->exec($sql);
	echo "Table inventory added!<br>";
}
catch(PDOException $e) {
	echo $sql . "<br>" . $e->getMessage();
}

$conn=null;
?>