<?php
// Database connection details
require_once 'connectDb.php';

// Function to calculate users who have not taken the test
function getNotTakenTestUsers($conn, $testId)
{
    $sqlAllUsers = "SELECT employee_id FROM Employee";
    $resultAllUsers = $conn->query($sqlAllUsers);
    $allUsers = [];
    if ($resultAllUsers->rowCount() > 0) {
        while ($row = $resultAllUsers->fetch(PDO::FETCH_ASSOC)) {
            $allUsers[] = $row['employee_id'];
        }
    }

    $sqlTakenTest = "SELECT DISTINCT employee_id FROM Result WHERE test_id = :testId";
    $stmtTakenTest = $conn->prepare($sqlTakenTest);
    $stmtTakenTest->bindParam(':testId', $testId);
    $stmtTakenTest->execute();
    $takenTestUsers = [];
    if ($stmtTakenTest->rowCount() > 0) {
        while ($row = $stmtTakenTest->fetch(PDO::FETCH_ASSOC)) {
            $takenTestUsers[] = $row['employee_id'];
        }
    }

    $notTakenTestUsers = array_diff($allUsers, $takenTestUsers);

    return $notTakenTestUsers;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Test Results</title>
    <!-- Add Bootstrap CSS link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <div class="row">
            <div class="card">
                <div class="col">
                    <h1>Test Results</h1>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Test ID</th>
                                <th>Users who have taken the test</th>
                                <th>Users who have not taken the test</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Query to get all test IDs
                            $sqlAllTestIds = "SELECT DISTINCT test_id FROM Result";
                            $resultAllTestIds = $conn->query($sqlAllTestIds);

                            if ($resultAllTestIds->rowCount() > 0) {
                                while ($row = $resultAllTestIds->fetch(PDO::FETCH_ASSOC)) {
                                    $testId = $row['test_id'];

                                    // Query to get users who have taken the test
                                    $sqlTakenTest = "SELECT DISTINCT employee_id FROM Result WHERE test_id = :testId";
                                    $stmtTakenTest = $conn->prepare($sqlTakenTest);
                                    $stmtTakenTest->bindParam(':testId', $testId);
                                    $stmtTakenTest->execute();
                                    $takenTestUsers = [];

                                    if ($stmtTakenTest->rowCount() > 0) {
                                        while ($takenTestRow = $stmtTakenTest->fetch(PDO::FETCH_ASSOC)) {
                                            $takenTestUsers[] = $takenTestRow['employee_id'];
                                        }
                                    }

                                    // Query to get the date of the test
                                    $sqlTestDate = "SELECT test_date FROM Test WHERE test_id = :testId";
                                    $stmtTestDate = $conn->prepare($sqlTestDate);
                                    $stmtTestDate->bindParam(':testId', $testId);
                                    $stmtTestDate->execute();
                                    $testDate = "";

                                    if ($stmtTestDate->rowCount() > 0) {
                                        $testDateRow = $stmtTestDate->fetch(PDO::FETCH_ASSOC);
                                        $testDate = $testDateRow['test_date'];
                                    }

                                    // Calculate users who have not taken the test
                                    $notTakenTestUsers = getNotTakenTestUsers($conn, $testId);
                                    $takenTestUsersList = implode(", ", $takenTestUsers);
                                    $notTakenTestUsersList = implode(", ", $notTakenTestUsers);
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo $testId; ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-primary" type="button" data-bs-toggle="modal"
                                                data-bs-target="#takenUsersModal-<?php echo $testId; ?>">
                                                Show Taken Users
                                            </button>
                                            <div class="modal fade" id="takenUsersModal-<?php echo $testId; ?>" tabindex="-1"
                                                aria-labelledby="takenUsersModalLabel-<?php echo $testId; ?>"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="takenUsersModalLabel-<?php echo $testId; ?>">
                                                                Taken Users for Test ID <?php echo $testId; ?></h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <?php echo $takenTestUsersList; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-primary" type="button" data-bs-toggle="modal"
                                                data-bs-target="#notTakenUsersModal-<?php echo $testId; ?>">
                                                Show Not Taken Users
                                            </button>
                                            <div class="modal fade" id="notTakenUsersModal-<?php echo $testId; ?>" tabindex="-1"
                                                aria-labelledby="notTakenUsersModalLabel-<?php echo $testId; ?>"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="notTakenUsersModalLabel-<?php echo $testId; ?>">
                                                                Not Taken Users for Test ID <?php echo $testId; ?>
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <?php echo $notTakenTestUsersList; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php echo $testDate; ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Bootstrap JS scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>