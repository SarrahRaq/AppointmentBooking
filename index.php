<?php
$page_title = "Home";
include 'header.php';
?>
<div class="hero text-center">
    <h1>Book Your Appointment Online</h1>
    <p class="lead">Pick a service, choose a date and time, and we'll take care of the rest.</p>
    <?php if (isset($_SESSION['user_id'])) { ?>
        <a class="btn btn-pink btn-lg"
           href="<?php echo ($_SESSION['role'] == 'admin') ? 'admin.php' : 'dashboard.php'; ?>">Go to my account</a>
    <?php } else { ?>
        <a class="btn btn-pink btn-lg me-2" href="register.php">Get Started</a>
        <a class="btn btn-outline-pink btn-lg" href="login.php">Login</a>
    <?php } ?>
</div>

<div class="row text-center mt-4 g-3">
    <div class="col-md-4"><div class="card p-3"><h5>1. Register</h5><p class="mb-0">Create your free account.</p></div></div>
    <div class="col-md-4"><div class="card p-3"><h5>2. Book</h5><p class="mb-0">Choose a service, date and time.</p></div></div>
    <div class="col-md-4"><div class="card p-3"><h5>3. Get Confirmed</h5><p class="mb-0">Track your appointment status.</p></div></div>
</div>
<?php include 'footer.php'; ?>
