<?php
$mysqli = @new mysqli("127.0.0.1", "root", "", "db_laundry");
if ($mysqli->connect_error) {
    echo "Connection failed: " . $mysqli->connect_error;
} else {
    echo "Connected successfully!";
}
