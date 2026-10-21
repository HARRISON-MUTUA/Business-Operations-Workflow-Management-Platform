<?php
require_once '../config/database.php';
require_once '../includes/header.php'; 
require_once '../includes/sidebar.php';

$conn = getDatabaseConnection();
$message = "";
$org_id = 1; // Assuming single organization setup for this project

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_branch'])) {
    $branch_name = $_POST['branch_name'];
    $stmt = $conn->prepare("INSERT INTO branches (organization_id, name) VALUES (?, ?)");
    $stmt->bind_param("is", $org_id, $branch_name);
    if ($stmt->execute()) {
        $message = "<div class='alert alert-success' style='color:green; margin-bottom:10px;'>Branch added successfully.</div>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_dept'])) {
    $branch_id = $_POST['branch_id'];
    $dept_name = $_POST['dept_name'];
    $stmt = $conn->prepare("INSERT INTO departments (branch_id, name) VALUES (?, ?)");
    $stmt->bind_param("is", $branch_id, $dept_name);
    if ($stmt->execute()) {
        $message = "<div class='alert alert-success' style='color:green; margin-bottom:10px;'>Department added successfully.</div>";
    }
}

$branches = $conn->query("SELECT * FROM branches WHERE organization_id = $org_id");
$all_branches = $conn->query("SELECT * FROM branches WHERE organization_id = $org_id");
$departments = $conn->query("
    SELECT d.id, d.name as dept_name, b.name as branch_name 
    FROM departments d 
    JOIN branches b ON d.branch_id = b.id 
    WHERE b.organization_id = $org_id
");
?>

<main class="main-content">
    <header class="top-navbar">
        <h3>Organization Setup</h3>
    </header>
    
    <div class="content-area">
        <?php echo $message; ?>
        
        <div class="row">
            <!-- Forms Column -->
            <div class="col-md-4">
                <div class="card p-4 mb-4">
                    <h5 class="mb-3">1. Add New Branch</h5>
                    <form method="POST">
                        <div class="form-group">
                            <label>Branch Name</label>
                            <input type="text" name="branch_name" placeholder="e.g. Westlands Branch" required>
                        </div>
                        <button type="submit" name="add_branch" class="btn-primary">Add Branch</button>
                    </form>
                </div>

                <div class="card p-4">
                    <h5 class="mb-3">2. Add New Department</h5>
                    <form method="POST">
                        <div class="form-group">
                            <label>Select Branch</label>
                            <select name="branch_id" required>
                                <option value="">-- Select Branch --</option>
                                <?php while($b = $branches->fetch_assoc()): ?>
                                    <option value="<?php echo $b['id']; ?>"><?php echo htmlspecialchars($b['name']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Department Name</label>
                            <input type="text" name="dept_name" placeholder="e.g. Finance" required>
                        </div>
                        <button type="submit" name="add_dept" class="btn-primary">Add Department</button>
                    </form>
                </div>
            </div>

            <!-- Table Column -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Existing Departments</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Department</th>
                                    <th>Branch</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($departments->num_rows > 0): ?>
                                    <?php while($d = $departments->fetch_assoc()): ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($d['dept_name']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($d['branch_name']); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="2" class="text-center py-3 text-muted">No departments found.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>