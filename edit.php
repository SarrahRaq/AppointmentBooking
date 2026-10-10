<?php
$page_title = "Edit Appointment";
include 'header.php';
require_login();

$id = (int) ($_GET['id'] ?? 0);
$error = "";

// Get the appointment
$stmt = mysqli_prepare($conn, "SELECT * FROM appointments WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$appt = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

// Only the owner (or admin) can edit
if (!$appt || ($appt['user_id'] != $_SESSION['user_id'] && $_SESSION['role'] != 'admin')) {
    die('<div class="alert alert-danger">Appointment not found. <a href="dashboard.php">Go back</a></div>');
}

$back = ($_SESSION['role'] == 'admin') ? "admin.php" : "dashboard.php";

// ---------- UPDATE ----------
if (isset($_POST['update'])) {
    $service = $_POST['service'];
    $date    = $_POST['appt_date'];
    $time    = $_POST['appt_time'];
    $notes   = trim($_POST['notes']);

    if (!in_array($service, $services) || !in_array($time, $time_slots) || $date == "") {
        $error = "Please complete the form.";
    } elseif ($date < date("Y-m-d")) {
        $error = "You cannot book a past date.";
    } elseif (slot_taken($conn, $date, $time, $id)) {
        $error = "That time slot is already taken.";
    } else {
        // Editing resets status to Pending so admin can re-confirm
        $stmt = mysqli_prepare($conn,
            "UPDATE appointments SET service=?, appt_date=?, appt_time=?, notes=?, status='Pending' WHERE id=?");
        mysqli_stmt_bind_param($stmt, "ssssi", $service, $date, $time, $notes, $id);
        mysqli_stmt_execute($stmt);
        header("Location: " . $back);
        exit;
    }
    // keep what the user typed if there was an error
    $appt['service'] = $service; $appt['appt_date'] = $date;
    $appt['appt_time'] = $time;  $appt['notes'] = $notes;
}
?>
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card">
            <div class="card-header card-header-pink">Edit Appointment</div>
            <div class="card-body">
                <?php if ($error) { echo '<div class="alert alert-danger">' . e($error) . '</div>'; } ?>

                <form method="POST" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label class="form-label">Service</label>
                        <select name="service" class="form-select" required>
                            <?php foreach ($services as $s) {
                                $sel = ($s == $appt['service']) ? "selected" : "";
                                echo '<option ' . $sel . '>' . e($s) . '</option>';
                            } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" name="appt_date" id="appt_date" class="form-control"
                               value="<?php echo e($appt['appt_date']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Time</label>
                        <select name="appt_time" class="form-select" required>
                            <?php foreach ($time_slots as $t) {
                                $sel = ($t == $appt['appt_time']) ? "selected" : "";
                                echo '<option value="' . $t . '" ' . $sel . '>' . date("g:i A", strtotime($t)) . '</option>';
                            } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" maxlength="255"><?php echo e($appt['notes']); ?></textarea>
                    </div>
                    <button type="submit" name="update" class="btn btn-pink">Save Changes</button>
                    <a href="<?php echo $back; ?>" class="btn btn-outline-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
