<body class="bg-secondary">
    <?php include 'header.php'; ?>
    
    <div class="d-flex">
        <!-- Sidebar -->
            <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <div class="container-fluid" style="margin-left: 300px;">
            <div class="row justify-content-center mt-5">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-header bg-info">
                            <h2>Create Examiner</h2>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="form-group">
                                    <label for="examiner_id">Examiner ID:</label>
                                    <input type="number" class="form-control" id="examiner_id" name="examiner_id" required>
                                </div>
                                <div class="form-group">
                                    <label for="examiner_name">Examiner Name:</label>
                                    <input type="text" class="form-control" id="examiner_name" name="examiner_name" required>
                                </div>
                                <div class="form-group">
                                    <label for="business_vertical">Business Vertical:</label>
                                    <select class="form-control" id="business_vertical" name="business_vertical" required>
                                        <?php foreach ($businessVerticals as $vertical) { ?>
                                            <option value="<?php echo $vertical['Business_vertical']; ?>"><?php echo $vertical['Business_vertical']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="broad_category">Broad Category:</label>
                                    <select class="form-control" id="broad_category" name="broad_category" required>
                                        <?php foreach ($broadCategories as $category) { ?>
                                            <option value="<?php echo $category['Broad_category']; ?>"><?php echo $category['Broad_category']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="department_name">Department Name:</label>
                                    <select class="form-control" id="department_name" name="department_name" required>
                                        <?php foreach ($departments as $department) { ?>
                                            <option value="<?php echo $department['Department_name']; ?>"><?php echo $department['Department_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="sub_department">Subdepartment:</label>
                                    <select class="form-control" id="sub_department" name="sub_department" required>
                                        <?php foreach ($subDepartments as $subDepartment) { ?>
                                            <option value="<?php echo $subDepartment['Sub_department']; ?>"><?php echo $subDepartment['Sub_department']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password:</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Create Examiner</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- End Content -->
    </div> <!-- End d-flex -->
</body>
