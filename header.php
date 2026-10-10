<?php include 'connection.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($page_title ?? 'PinkBook'); ?> | PinkBook Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="navbar navbar-expand-md navbar-pink">
    <div class="container">
        <a class="navbar-brand" href="index.php">&#127872; PinkBook</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav ms-auto align-items-md-center">
                <?php if (isset($_SESSION['user_id'])) { ?>
                    <?php if ($_SESSION['role'] == 'admin') { ?>
                        <li class="nav-item"><a class="nav-link" href="admin.php">All Appointments</a></li>
                    <?php } else { ?>
                        <li class="nav-item"><a class="nav-link" href="dashboard.php">My Appointments</a></li>
                    <?php } ?>
                    <li class="nav-item"><span class="nav-link">Hi, <?php echo e($_SESSION['fullname']); ?></span></li>
                    <li class="nav-item"><a class="btn btn-light btn-sm ms-md-2" href="logout.php">Logout</a></li>
                <?php } else { ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="btn btn-light btn-sm ms-md-2" href="register.php">Register</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>
<main class="container py-4">
