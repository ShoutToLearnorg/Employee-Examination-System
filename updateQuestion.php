<?php
require_once 'connectDb.php';
// Start a session to manage user authentication
session_start();

// Check if the admin is logged in, if not redirect to the login page
if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] !== "examiner" || empty($_SESSION["user_id"]) ) {
    header("Location: login.php");
    exit();
}

// Handle form submission here
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $test_id = $_POST['test_id'];
    $question_id = $_POST['question_id'];
    $question_text = $_POST['question_text'];
    $option_1 = $_POST['option_1'];
    $option_2 = $_POST['option_2'];
    $option_3 = $_POST['option_3'];
    $option_4 = $_POST['option_4'];
    $correct_option = $_POST['correct_option']; // New correct option value

    // Update question, options, and correct_option in the database
    $sql = "UPDATE Question SET question_text = '$question_text', option_1 = '$option_1', option_2 = '$option_2', option_3 = '$option_3', option_4 = '$option_4', correct_option = '$correct_option' WHERE test_id = '$test_id' AND question_id = '$question_id'";
    if ($conn->query($sql) === TRUE) {
        $success_message = "Question, options, and correct option updated successfully!";
    } else {
        $error_message = "Error updating question, options, and correct option: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Update Question</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body class="bg-secondary">
    <?php
    include 'header.php';
    include 'sidebar.php';
    ?>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-info">
            <h1>Update Question, Options, and Correct Option</h1>
            <?php if (isset($success_message)) { ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $success_message; ?>
                </div>
            <?php } ?>
            <?php if (isset($error_message)) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error_message; ?>
                </div>
            <?php } ?>
            </div>
            <div class="card-body">
            <form method="post">
                <div class="form-group">
                    <label for="test_id">Test ID:</label>
                    <input type="text" class="form-control" id="test_id" name="test_id" required>
                </div>
                <div class="form-group">
                    <label for="question_id">Question ID:</label>
                    <input type="text" class="form-control" id="question_id" name="question_id" required>
                </div>

                <!-- Add more form fields for updating questions, options, and correct option -->
                <div class="form-group">
                    <label for="question_text">Question Text:</label>
                    <textarea class="form-control" id="question_text" name="question_text" required></textarea>
                </div>
                <div class="form-group">
                    <label for="option_1">Option 1:</label>
                    <input type="text" class="form-control" id="option_1" name="option_1" required>
                </div>
                <div class="form-group">
                    <label for="option_2">Option 2:</label>
                    <input type="text" class="form-control" id="option_2" name="option_2" required>
                </div>
                <div class="form-group">
                    <label for="option_3">Option 3:</label>
                    <input type="text" class="form-control" id="option_3" name="option_3" required>
                </div>
                <div class="form-group">
                    <label for="option_4">Option 4:</label>
                    <input type="text" class="form-control" id="option_4" name="option_4" required>
                </div>
                <div class="form-group">
                    <label for="correct_option">Correct Option:</label>
                    <input type="number" class="form-control" id="correct_option" name="correct_option" required>
                </div>

                <button type="submit" class="btn btn-primary">Update Question</button>
            </form>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>