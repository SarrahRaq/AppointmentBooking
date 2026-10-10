<?php
$page_title = "Register";
include 'header.php';

$error = "";
$success = "";

if (isset($_POST['register'])) {
    $fullname = trim($_POST['fullname']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];

    // Server-side validation
    if ($fullname == "" || $email == "" || $password == "") {
        $error = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($password != $confirm) {
        $error = "Passwords do not match.";
    } else {
        // Is the email already used?
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = "That email is already registered.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn,
                "INSERT INTO users(fullname, email, password, role) VALUES(?, ?, ?, 'user')");
            mysqli_stmt_bind_param($stmt, "sss", $fullname, $email, $hashed);
            mysqli_stmt_execute($stmt);
            $success = "Account created! You can now log in.";
        }
    }
}
?>
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card">
            <div class="card-header card-header-pink">Create an Account</div>
            <div class="card-body">
                <?php if ($error) { echo '<div class="alert alert-danger">' . e($error) . '</div>'; } ?>
                <?php if ($success) { echo '<div class="alert alert-success">' . e($success) . ' <a href="login.php">Login</a></div>'; } ?>

                <form method="POST" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="fullname" class="form-control" required>
                        <div class="invalid-feedback">Please enter your name.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                        <div class="invalid-feedback">Please enter a valid email.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" minlength="6" required>
                        <div class="invalid-feedback">At least 6 characters.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="confirm" class="form-control" minlength="6" required>
                        <div class="invalid-feedback">Please confirm your password.</div>
                    </div>
                    <button type="submit" name="register" class="btn btn-pink w-100">Register</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
