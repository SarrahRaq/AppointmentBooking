<?php
$page_title = "My Appointments";
include 'header.php';
require_login();

$user_id = $_SESSION['user_id'];
$error = "";
$success = "";

// ---------- CREATE: book an appointment ----------
if (isset($_POST['save'])) {
    $service = $_POST['service'];
    $date    = $_POST['appt_date'];
    $time    = $_POST['appt_time'];
    $notes   = trim($_POST['notes']);

    if (!in_array($service, $services) || !in_array($time, $time_slots) || $date == "") {
        $error = "Please complete the form.";
    } elseif ($date < date("Y-m-d")) {
        $error = "You cannot book a past date.";
    } elseif (slot_taken($conn, $date, $time)) {
        $error = "Sorry, that time slot is already taken. Please choose another.";
    } else {
        $stmt = mysqli_prepare($conn,
            "INSERT INTO appointments(user_id, service, appt_date, appt_time, notes) VALUES(?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "issss", $user_id, $service, $date, $time, $notes);
        mysqli_stmt_execute($stmt);
        $success = "Appointment booked! Waiting for confirmation.";
    }
}

// ---------- DELETE: remove my appointment ----------
if (isset($_POST['delete'])) {
    $id = (int) $_POST['id'];
    $stmt = mysqli_prepare($conn, "DELETE FROM appointments WHERE id = ? AND user_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $id, $user_id);
    mysqli_stmt_execute($stmt);
    $success = "Appointment deleted.";
}

// ---------- READ: my appointments ----------
$stmt = mysqli_prepare($conn,
    "SELECT * FROM appointments WHERE user_id = ? ORDER BY appt_date DESC, appt_time DESC");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<div class="row g-4">
    <!-- Booking form -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header card-header-pink">Book an Appointment</div>
            <div class="card-body">
                <?php if ($error) { echo '<div class="alert alert-danger">' . e($error) . '</div>'; } ?>
                <?php if ($success) { echo '<div class="alert alert-success">' . e($success) . '</div>'; } ?>

                <form method="POST" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label class="form-label">Service</label>
                        <select name="service" class="form-select" required>
                            <option value="">-- Choose --</option>
                            <?php foreach ($services as $s) { echo '<option>' . e($s) . '</option>'; } ?>
                        </select>
                        <div class="invalid-feedback">Please choose a service.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" name="appt_date" id="appt_date" class="form-control" required>
                        <div class="invalid-feedback">Please pick a date.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Time</label>
                        <select name="appt_time" class="form-select" required>
                            <option value="">-- Choose --</option>
                            <?php foreach ($time_slots as $t) {
                                echo '<option value="' . $t . '">' . date("g:i A", strtotime($t)) . '</option>';
                            } ?>
                        </select>
                        <div class="invalid-feedback">Please pick a time.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes (optional)</label>
                        <textarea name="notes" class="form-control" rows="2" maxlength="255"></textarea>
                    </div>
                    <button type="submit" name="save" class="btn btn-pink w-100">Book Now</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Appointment list -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header card-header-pink">My Appointments</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-pink align-middle">
                        <thead>
                            <tr><th>Service</th><th>Date</th><th>Time</th><th>Status</th><th></th></tr>
                        </thead>
                        <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?php echo e($row['service']); ?></td>
                                <td><?php echo date("M d, Y", strtotime($row['appt_date'])); ?></td>
                                <td><?php echo date("g:i A", strtotime($row['appt_time'])); ?></td>
                                <td><span class="badge status-<?php echo e($row['status']); ?>"><?php echo e($row['status']); ?></span></td>
                                <td class="text-end text-nowrap">
                                    <?php if ($row['status'] != 'Cancelled') { ?>
                                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-pink">Edit</a>
                                    <?php } ?>
                                    <form method="POST" class="d-inline confirm-form" data-message="Delete this appointment?">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="delete" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php if (mysqli_num_rows($result) == 0) { ?>
                            <tr><td colspan="5" class="text-center text-muted">No appointments yet.</td></tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
