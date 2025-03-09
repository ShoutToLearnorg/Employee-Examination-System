<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start session only if it's not already active
}


require_once 'connectDb.php';

// Retrieve the user ID and user type from the session
$user_id = $_SESSION["user_id"];
$user_type = $_SESSION["user_type"];

// Retrieve the user's name
$name = "";
switch ($user_type) {
    case "admin":
        $table = "Admin";
        break;
    case "examiner":
        $table = "Examiner";
        break;
    case "employee":
        $table = "Employee";
        break;
    default:
        $table = "";
}

if (!empty($table)) {
    $stmt = $conn->prepare("SELECT ${table}_name FROM $table WHERE ${table}_id = :user_id");
    $stmt->bindParam(":user_id", $user_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        $name = $result["${table}_name"];
    }
}
?>

<!-- Sidebar -->
<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark position-fixed vh-100" style="width: 280px;">
    <!-- Welcome Message -->
    <div class="d-flex align-items-center mb-3">
        <i class="bi bi-person-circle me-2" style="font-size: 1.8rem;"></i>
        <span class="fs-5">Welcome, <strong><?php echo htmlspecialchars($name); ?></strong></span>
    </div>
    <hr>

    <!-- Navigation -->
    <ul class="nav nav-pills flex-column mb-auto">
        <li>
            <a href="index.php" class="nav-link text-white">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>
        <?php if ($user_type == "admin"): ?>
            <li><a href="examiner.php" class="nav-link text-white"><i class="bi bi-person-badge me-2"></i> Examiner</a></li>
            <li><a href="manage_examiner.php" class="nav-link text-white"><i class="bi bi-person-check me-2"></i> Manage Examiner</a></li>
            <li><a href="employee.php" class="nav-link text-white"><i class="bi bi-people me-2"></i> Employee</a></li>
            <li><a href="add_emp.php" class="nav-link text-white"><i class="bi bi-person-plus me-2"></i> Add Employee Bulk</a></li>
            <li><a href="manage_employee.php" class="nav-link text-white"><i class="bi bi-person-gear me-2"></i> Manage Employee</a></li>
        <?php endif; ?>

        <?php if ($user_type == "examiner"): ?>
            <li><a href="add_test.php" class="nav-link text-white"><i class="bi bi-file-earmark-plus me-2"></i> Create Test</a></li>
            <li><a href="manage_test.php" class="nav-link text-white"><i class="bi bi-folder-check me-2"></i> Manage Test</a></li>
            <li><a href="question.php" class="nav-link text-white"><i class="bi bi-question-circle me-2"></i> Add Question</a></li>
            <li><a href="viewQuestion.php" class="nav-link text-white"><i class="bi bi-eye me-2"></i> View Questions</a></li>
            <li><a href="updateQuestion.php" class="nav-link text-white"><i class="bi bi-pencil-square me-2"></i> Update Question</a></li>
        <?php endif; ?>

        <?php if ($user_type == "admin" || $user_type == "examiner"): ?>
            <li><a href="not_given_exam.php" class="nav-link text-white"><i class="bi bi-x-circle me-2"></i> Not Attempted</a></li>
        <?php endif; ?>

        <?php if ($user_type == "employee"): ?>
            <li><a href="show_test.php" class="nav-link text-white"><i class="bi bi-list-check me-2"></i> Show Test</a></li>
        <?php endif; ?>

        <?php if ($user_type == "admin"): ?>
            <li><a href="show_result.php" class="nav-link text-white"><i class="bi bi-clipboard-data me-2"></i> Show Result</a></li>
            <li><a href="delete_ansdb.php" class="nav-link text-white"><i class="bi bi-trash me-2"></i> Delete Test</a></li>
        <?php endif; ?>
    </ul>

    <hr>

    <!-- Profile Dropdown -->
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://github.com/mdo.png" alt="Profile" width="32" height="32" class="rounded-circle me-2">
            <strong><?php echo htmlspecialchars($name); ?></strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li><a class="dropdown-item" href="#">Settings</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
        </ul>
    </div>
</div>
