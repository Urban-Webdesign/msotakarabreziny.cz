<?php
// RECURSIVE DELETE FOLDER FUNCTION
function rrmdir($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir."/".$object))
                    rrmdir($dir. DIRECTORY_SEPARATOR .$object);
                else
                    unlink($dir. DIRECTORY_SEPARATOR .$object);
            }
        }
        rmdir($dir);
    }
}

// SCRIPT TO DELETE ALL ARTICLES THAT ARE OLDER THAN 1 YEAR
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

// Delete old records from ARTICLES table
$expiration_date = time() - ($n_years * 31536000);
$sql = 'DELETE FROM articles WHERE created<?';
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $expiration_date);

if ($stmt->execute() === TRUE) {
    echo "Records deleted successfully";
} else {
    echo "Error deleting records: " . $conn->error;
}

// DELETE ALL UNUSED GALLERIES
$sql2 = 'SELECT id FROM galleries WHERE id NOT IN (SELECT gallery_id FROM articles WHERE gallery_id>0) ORDER BY id ASC';
$result = $conn->query($sql2);

if ($result->num_rows > 0) {
    // delete folders
    while ($row = $result->fetch_assoc()) {
        if ($row['id'] !== '1') {
            rrmdir("/var/www/html/www/upload/galleries/" . $row['id']);
            echo 'Folder <b>' . $row['id'] . '</b> was successfully deleted<br>';

            // delete from database
            $sql3 = 'DELETE FROM galleries WHERE id=?';
            $stmt = $conn->prepare($sql3);
            $stmt->bind_param("i", $row['id']);
            if ($stmt->execute() === TRUE) {
                echo "Database record <b>" . $row['id'] . "</b> deleted successfully<br>";
            } else {
                echo "Error deleting record: " . $conn->error;
            }
        }
    }

} else {
    echo "0 results";
}

$conn->close();