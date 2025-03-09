<body class="bg-secondary">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            

            <!-- Content Section -->
            <div class="col-10 d-flex flex-column mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-4 col-lg-3 mb-3">
                        <div class="card bg-success">
                            <div class="card-body text-center">
                                <h5 class="card-title">Examiner</h5>
                                <?php
                                    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM Examiner");
                                    $stmt->execute();
                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                    echo "<p class='card-text'>" . $row['count'] . "</p>";
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-lg-3 mb-3">
                        <div class="card bg-info">
                            <div class="card-body text-center">
                                <h5 class="card-title">Employee</h5>
                                <?php
                                    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM Employee");
                                    $stmt->execute();
                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                    echo "<p class='card-text'>" . $row['count'] . "</p>";
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-lg-3 mb-3">
                        <div class="card bg-warning">
                            <div class="card-body text-center">
                                <h5 class="card-title">Test</h5>
                                <?php 
                                    $stmt = $conn->prepare("SELECT COUNT(DISTINCT test_id) AS count FROM Test");
                                    $stmt->execute();
                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                    echo "<p class='card-text'>" . $row['count'] . "</p>";
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- End Content Section -->
        </div> <!-- End Row -->
    </div> <!-- End Container -->
</body>
