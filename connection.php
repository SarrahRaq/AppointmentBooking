<?php
// connection.php - database connection (same style as Lecture 7)
session_start();

// ---- LOCAL (XAMPP) ----
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "appointmentdb";

// ---- INFINITYFREE (uncomment and edit when deploying) ----
// $servername = "sql113.infinityfree.com";
// $username   = "if0_43123708";
// $password   = "YOUR_VPANEL_PASSWORD";
// $database   = "if0_43123708_room_system";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

// Prevent XSS: always wrap output with e()
function e($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

// Check if a date/time is already booked (ignores cancelled ones)
function slot_taken($conn, $date, $time, $ignore_id = 0) {
    $stmt = mysqli_prepare($conn,
        "SELECT id FROM appointments
         WHERE appt_date = ? AND appt_time = ? AND status <> 'Cancelled' AND id <> ?");
    mysqli_stmt_bind_param($stmt, "ssi", $date, $time, $ignore_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    return mysqli_stmt_num_rows($stmt) > 0;
}

// Redirect to login if not logged in
function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}

// Services and time slots (simple arrays)
$services = array("Consultation", "Facial Treatment", "Hair Styling", "Nail Care", "Massage");

$time_slots = array();
for ($h = 9; $h <= 16; $h++) {
    $time_slots[] = sprintf("%02d:00:00", $h);
}
?>
