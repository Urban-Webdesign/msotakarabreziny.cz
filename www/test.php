<?php
/*
 * SCRIPT TO DELETE ALL ARTICLES THAT ARE OLDER THAN 1 YEAR
 */

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

$expiration_date = time() - (3*31536000);

$sql = 'DELETE FROM articles WHERE created<?';
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $expiration_date);

if ($stmt->execute() === TRUE) {
    echo "Records deleted successfully";
} else {
    echo "Error deleting records: " . $conn->error;
}

$conn->close();

/*
SCRIPT TO SELECT ALL ARTICLES THAT ARE NOT OLDER THAN 1 YEAR


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

$expiration_date = time() - (31536000);
echo $expiration_date . ' / ' . date("Y-m-d H:i:s", $expiration_date) . '<br>';

$sql = 'SELECT created, title FROM articles WHERE created>? ORDER BY created DESC';
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $expiration_date);
$stmt->execute();
$result = $stmt->get_result(); // get the mysqli result

if ($result->num_rows > 0) {
    // output data
    echo '<h3>' . $result->num_rows . '</h3>';
    while ($row = $result->fetch_assoc()) {
        echo date("Y-m-d H:i:s", $row['created']) . ' - ' . $row['title'] . '<hr>';
    }
} else {
    echo "0 results";
}

$conn->close();
*/