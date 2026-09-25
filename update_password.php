<?php
// Update admin password
$mysqli = new mysqli('localhost', 'root', 'password', 'newtechv_quizmaster');

if ($mysqli->connect_error) {
    echo 'Connection failed: ' . $mysqli->connect_error;
} else {
    $hash = md5('admin123');
    $sql = "UPDATE user_details SET password = '" . $mysqli->real_escape_string($hash) . "' WHERE user_name = 'admin'";
    
    if ($mysqli->query($sql)) {
        echo 'Password updated successfully for admin123. Hash: ' . $hash;
    } else {
        echo 'Error: ' . $mysqli->error;
    }
    
    $mysqli->close();
}
?>
