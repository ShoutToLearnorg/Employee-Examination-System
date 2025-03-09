<?php
// Include the database connection file
require 'connectDb.php';
session_start();

// Check if the admin is logged in, if not redirect to the login page
if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] !== "admin" || empty($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}


// Create a new MySQLi object and establish the database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the sub-department options for filtering
$subDeptQuery = "SELECT DISTINCT Sub_department FROM Employee";
$subDeptResult = $conn->query($subDeptQuery);

if ($subDeptResult) {
    $subDeptCount = $subDeptResult->num_rows;
} else {
    $subDeptCount = 0;
}

// Retrieve the filter options for Business Vertical
$businessVerticalQuery = "SELECT * FROM BusinessVertical";
$businessVerticalResult = $conn->query($businessVerticalQuery);

// Retrieve the results of each employee for each test
$sql = "SELECT e.employee_id, e.employee_name, e.Broad_category, e.Department_name, e.Sub_department, t.test_id, t.test_name, r.marks_obtained, r.percentage, r.submission_datetime
        FROM Employee e
        JOIN Result r ON e.employee_id = r.employee_id
        JOIN Test t ON r.test_id = t.test_id
        WHERE 1 = 1"; // Use this placeholder condition to make it easier to append additional conditions

// Apply filters if selected
if (isset($_GET['business_vertical'])) {
    $businessVertical = $_GET['business_vertical'];
    $sql .= " AND e.Business_vertical = '$businessVertical'";
}

if (isset($_GET['broad_category'])) {
    $broadCategory = $_GET['broad_category'];
    $sql .= " AND e.Broad_category = '$broadCategory'";
}

if (isset($_GET['department'])) {
    $department = $_GET['department'];
    $sql .= " AND e.Department_name = '$department'";
}

// Apply date filters if set
if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $startDate = $_GET['start_date'];
    $endDate = $_GET['end_date'];

    // Add the date filtering condition to the SQL query
    $sql .= " AND r.submission_datetime >= '$startDate' AND r.submission_datetime <= '$endDate'";
}

$sql .= " GROUP BY e.employee_id, t.test_id";

$result = $conn->query($sql);

if (isset($_GET['export'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="employee_test_results.csv"');

    $output = fopen('php://output', 'w');

    // Write header row
    fputcsv($output, ['Employee ID', 'Employee Name', 'Broad Category', 'Department', 'Sub-department', 'Test ID', 'Test Name', 'Marks Obtain', 'percentage', 'Submission Date and Time']);

    // Write data rows
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['employee_id'],
            $row['employee_name'],
            $row['Broad_category'],
            $row['Department_name'],
            $row['Sub_department'],
            $row['test_id'],
            $row['test_name'],
            $row['marks_obtained'],
            $row['percentage']
        ]);
    }

    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Employee Test Results</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Bootstrap Datepicker CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
</head>

<body class="bg-secondary">
    <?php include 'header.php'; ?>


    <div class="container">
        <div class="card">
            <div class="card-header bg-info">
                <h2 class="text-center">Test Results</h2>
            </div>
            <div class="card-body">
                <!-- Filter form -->
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="mb-3">
                                    <div class="form-row align-items-end">
                        <div class="col-md-3 mb-2">
                            <label for="business_vertical" class="mr-2">Business Vertical:</label>
                            <select class="form-control" id="business_vertical" name="business_vertical">
                                <option value="">All</option>
                                <?php
                                while ($row = $businessVerticalResult->fetch_assoc()) {
                                    $businessVertical = $row['Business_vertical'];
                                    $selected = isset($_GET['business_vertical']) && $_GET['business_vertical'] === $businessVertical ? "selected" : "";
                                    echo "<option value='$businessVertical' $selected>$businessVertical</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="broad_category" class="mr-2">Broad Category:</label>
                            <select class="form-control" id="broad_category" name="broad_category">
                                <option value="">All</option>
                                <?php
                                $broadCategoryQuery = "SELECT DISTINCT Broad_category FROM Employee";
                                $broadCategoryResult = $conn->query($broadCategoryQuery);
                                while ($row = $broadCategoryResult->fetch_assoc()) {
                                    $broadCategory = $row['Broad_category'];
                                    $selected = isset($_GET['broad_category']) && $_GET['broad_category'] === $broadCategory ? "selected" : "";
                                    echo "<option value='$broadCategory' $selected>$broadCategory</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="department" class="mr-2">Department:</label>
                            <select class="form-control" id="department" name="department">
                                <option value="">All</option>
                                <?php
                                $departmentQuery = "SELECT DISTINCT Department_name FROM Employee";
                                $departmentResult = $conn->query($departmentQuery);
                                while ($row = $departmentResult->fetch_assoc()) {
                                    $department = $row['Department_name'];
                                    $selected = isset($_GET['department']) && $_GET['department'] === $department ? "selected" : "";
                                    echo "<option value='$department' $selected>$department</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
    
      <label for="startDate">Start Date:</label>
      <input type="text" placeholder="Choose Date" class="form-control" id="start_date" name="start_date" value="<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>">
    </div>
    
    <div class="col-md-6">
    
      <label for="end_date">End Date:</label>
      <input type="text" id="end_date" class="form-control" name="end_date" value="<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>">
    </div>

    </div>




                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary btn-block">Filter</button>
                        </div>
                    </div>
                </form>

                <?php if ($result && $result->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-success table-striped table-hover">
                            <!-- Table Header -->
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Employee Name</th>
                                    <th>Broad Category</th>
                                    <th>Department</th>
                                    <th>Sub-department</th>
                                    <th>Test ID</th>
                                    <th>Test Name</th>
                                    <th>Marks Obtain</th>
                                    <th>Percentage</th>
                                    <th>Submission Date and Time</th>
                                </tr>
                            </thead>
                            <!-- Table Body -->
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $row['employee_id']; ?></td>
                                        <td><?php echo $row['employee_name']; ?></td>
                                        <td><?php echo $row['Broad_category']; ?></td>
                                        <td><?php echo $row['Department_name']; ?></td>
                                        <td><?php echo $row['Sub_department']; ?></td>
                                        <td><?php echo $row['test_id']; ?></td>
                                        <td><?php echo $row['test_name']; ?></td>
                                        <td><?php echo $row['marks_obtained']; ?></td>
                                        <td><?php echo $row['percentage']; ?></td>
                                        <td><?php echo $row['submission_datetime']; ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                    <a href="?business_vertical=<?php echo isset($_GET['business_vertical']) ? $_GET['business_vertical'] : ''; ?>&start_date=<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>&end_date=<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>&export=1" class="btn btn-success">Export to Excel</a>
                        <a href="index.php" class="btn btn-warning">Home</a>
                    </div>
                <?php else: ?>
                    <p>No results found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- Bootstrap and jQuery JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <!-- Bootstrap Datepicker JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#start_date, #end_date').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
      });
    });
  </script>
</body>

</html>


<?php
// Close the database connection
$conn->close();
?>