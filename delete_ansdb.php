<?php
require_once 'connectDb.php';

session_start();
// Check if the admin is logged in, if not redirect to the login page
if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] !== "admin" || empty($_SESSION["user_id"]) ) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Test ID and Employee ID to delete answers for
    $testId = $_POST["testId"];
    $employeeId = $_POST["employeeId"];

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Delete answers from the Answer table for the specified Test ID and Employee ID
        $sql = "DELETE FROM Answer WHERE test_id = :testId AND employee_id = :employeeId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":testId", $testId);
        $stmt->bindParam(":employeeId", $employeeId);
        $stmt->execute();

        // Delete rows from the Result table for the specified Test ID and Employee ID
        $sql = "DELETE FROM Result WHERE test_id = :testId AND employee_id = :employeeId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":testId", $testId);
        $stmt->bindParam(":employeeId", $employeeId);
        $stmt->execute();

        $count = $stmt->rowCount(); 
        echo "<script>$('#resultMessage').html('Deleted $count answer rows and result rows for Test ID: $testId and Employee ID: $employeeId');</script>";

    } catch (PDOException $e) {
        echo "<script>$('#resultMessage').html('Error: ' + " . json_encode($e->getMessage()) . ");</script>";
    }

    $conn = null;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Delete Answer Rows and Result Rows</title>
  <!-- Add Bootstrap CSS link -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-secondary">
<?php
include 'header.php';
include 'sidebar.php'; 
?>
<div class="container mt-5">
  <h2>Delete Answer Rows and Result Rows</h2>
  <form id="deleteForm">
    <div class="form-group">
      <label for="testId">Test ID:</label>
      <input type="text" class="form-control col-5" id="testId" name="testId" placeholder="Enter Test ID">
    </div>
    <div class="form-group">
      <label for="employeeId">Employee ID:</label>
      <input type="text" class="form-control col-5" id="employeeId" name="employeeId" placeholder="Enter Employee ID">
    </div>
    <button type="submit" class="btn btn-primary">Delete Answers and Results</button>
  </form>
  <div id="resultMessage" class="mt-3"></div>
</div>

<!-- Add Bootstrap JS and jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
  $(document).ready(function() {
    // Handle form submission
    $("#deleteForm").submit(function(event) {
      event.preventDefault();

      // Get form data
      const formData = {
        testId: $("#testId").val(),
        employeeId: $("#employeeId").val()
      };

      // Send data to the PHP script using AJAX
      $.ajax({
        type: "POST",
        url: "", // Leave this empty to submit to the same page
        data: formData,
        success: function(response) {
          $("#resultMessage").html(response);
        },
        error: function(xhr, status, error) {
          console.log(xhr.responseText);
          $("#resultMessage").html("Error occurred. Please try again later.");
        }
      });
    });
  });
</script>
</body>
</html>
