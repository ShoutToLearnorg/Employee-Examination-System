<?php 
// Start or resume the session
session_start();

// Check if the user is not logged in
if (!isset($_SESSION["user_id"]) || empty($_SESSION["user_id"])) {
    // Redirect to the login page
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
        /* Ensure the sidebar sticks to the left with no gap */
        .sidebar-container {
            width: 280px; /* Adjust width if needed */
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background-color: #343a40; /* Dark background */
        }

        .content-container {
            margin-left: 280px; /* Same as sidebar width to prevent overlap */
            width: calc(100% - 280px);
            padding: 20px;
        }
    </style>
</head>
<body class="bg-secondary">

<?php include 'header.php'; ?>

<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar-container">
        <?php include 'sidebar.php'; ?>
    </div>

    <!-- Content -->
    <div class="content-container">
        <?php include 'count.php'; ?>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
