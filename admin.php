<?php
$page_title = "Admin";
include 'header.php';
require_login();

// Role-based access control: admin only
if ($_SESSION['role'] != 'admin') {
    die('<div class="alert alert-danger">Access denied.</div>');
}

$allowed_status = array("Pending", "Confirmed", "Cancelled");

// ---------- UPDATE status ----------
if (isset($_POST['set_status'])) {
    $id = (int) $_POST['id'];
    $status = $_POST['status'];
    if (in_array($status, $allowed_status)) {
        $stmt = mysqli_prepare($conn, "UPDATE appointments SET status = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "si", $status, $id);
        mysqli_stmt_execute($stmt);
    }
}

// ---------- DELETE ----------
if (isset($_POST['delete'])) {
    $id = (int) $_POST['id'];
    $stmt = mysqli_prepare($conn, "DELETE FROM appointments WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
}

// ---------- READ: all appointments ----------
$sql = "SELECT a.*, u.fullname, u.email
        FROM appointments a JOIN users u ON a.user_id = u.id
        ORDER BY a.appt_date DESC, a.appt_time DESC";
$result = mysqli_query($conn, $sql);

// Simple counts for the top cards
$counts = array("Pending" => 0, "Confirmed" => 0, "Cancelled" => 0);
$rows = array();
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
    $counts[$row['status']]++;
}
?>
<h3 class="mb-3" style="color:#d63384;">All Appointments</h3>

<div class="row g-3 mb-4 text-center">
    <?php foreach ($counts as $label => $num) { ?>
        <div class="col-4">
            <div class="card p-3">
                <h2 class="mb-0"><?php echo $num; ?></h2>
                <small><?php echo $label; ?></small>
            </div>
        </div>
    <?php } ?>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-pink align-middle">
                <thead>
                    <tr><th>Client</th><th>Service</th><th>Date</th><th>Time</th><th>Notes</th><th>Status</th><th></th></tr>
                </thead>
                <tbody>
                <?php foreach ($rows as $row) { ?>
                    <tr>
                        <td><?php echo e($row['fullname']); ?><br><small class="text-muted"><?php echo e($row['email']); ?></small></td>
                        <td><?php echo e($row['service']); ?></td>
                        <td><?php echo date("M d, Y", strtotime($row['appt_date'])); ?></td>
                        <td><?php echo date("g:i A", strtotime($row['appt_time'])); ?></td>
                        <td><?php echo e($row['notes']); ?></td>
                        <td>
                            <form method="POST" class="d-flex gap-1">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <select name="status" class="form-select form-select-sm">
                                    <?php foreach ($allowed_status as $st) {
                                        $sel = ($st == $row['status']) ? "selected" : "";
                                        echo "<option $sel>$st</option>";
                                    } ?>
                                </select>
                                <button type="submit" name="set_status" class="btn btn-sm btn-pink">Save</button>
                            </form>
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-pink">Edit</a>
                            <form method="POST" class="d-inline confirm-form" data-message="Delete this appointment?">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
                <?php if (count($rows) == 0) { ?>
                    <tr><td colspan="7" class="text-center text-muted">No appointments yet.</td></tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
