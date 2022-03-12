<?php
/*
 * SCRIPT TO DELETE ALL ARTICLES THAT ARE OLDER THAN 2 YEARS
 */
$n_years = 2;
$servername = "db";
$username = "root";
$password = "root";
$dbname = "msotakarabreziny";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully<br>";

// Delete from database
$expiration_date = time() - ($n_years * 31536000);
$sql = 'DELETE FROM articles WHERE created<?';
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $expiration_date);

if ($stmt->execute() === TRUE) {
    echo "Records deleted successfully";
} else {
    echo "Error deleting records: " . $conn->error;
}

$conn->close();