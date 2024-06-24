<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "phishing_tracker";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_type = $_POST['event_type'];

    if ($event_type === 'link_click') {
        $stmt = $conn->prepare("UPDATE user_interactions SET link_click_count = link_click_count + 1 WHERE id = 1");
    } elseif ($event_type === 'login_button_click') {
        $stmt = $conn->prepare("UPDATE user_interactions SET login_click_count = login_click_count + 1 WHERE id = 1");
    } else {
        $stmt = $conn->prepare("INSERT INTO user_interactions (event_type) VALUES (?)");
        $stmt->bind_param("s", $event_type);
    }

    if ($stmt->execute()) {
        echo "Event logged successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch counts to display in the HTML
$result = $conn->query("SELECT link_click_count, login_click_count FROM user_interactions WHERE id = 1");
$row = $result->fetch_assoc();
$link_click_count = $row['link_click_count'];
$login_click_count = $row['login_click_count'];

$conn->close();
?>