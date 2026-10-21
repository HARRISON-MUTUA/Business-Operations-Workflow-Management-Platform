<?php
require_once '../config/database.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $org_name = $_POST['org_name'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $conn = getDatabaseConnection();
    
    try {
        $conn->begin_transaction();
        $stmt = $conn->prepare("INSERT INTO organizations (name) VALUES (?)");
        $stmt->bind_param("s", $org_name);
        $stmt->execute();
        $org_id = $conn->insert_id;

        $stmt = $conn->prepare("INSERT INTO branches (organization_id, name) VALUES (?, 'Headquarters')");
        $stmt->bind_param("i", $org_id);
        $stmt->execute();
        $branch_id = $conn->insert_id;

        $stmt = $conn->prepare("INSERT INTO departments (branch_id, name) VALUES (?, 'Administration')");
        $stmt->bind_param("i", $branch_id);
        $stmt->execute();
        $dept_id = $conn->insert_id;

        $stmt = $conn->prepare("INSERT INTO users (organization_id, department_id, role_id, full_name, email, password) VALUES (?, ?, 1, ?, ?, ?)");
        $stmt->bind_param("iisss", $org_id, $dept_id, $full_name, $email, $password);
        $stmt->execute();

        $conn->commit();
        $message = "<div class='alert alert-success' style='color: green; margin-bottom: 15px;'>Registration successful! <a href='login.php'>Login here</a></div>";
    } catch (Exception $e) {
        $conn->rollback();
        $message = "<div class='alert alert-danger' style='color: red; margin-bottom: 15px;'>Error: Email might already exist.</div>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - Business Workflow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-container">
            <div class="auth-left" style="background: linear-gradient(135deg, #111827 0%, #374151 100%);">
                <h1>Start your journey.</h1>
                <p>Create your business account to begin managing workflows.</p>
            </div>
            <div class="auth-right">
                <div class="auth-box">
                    <h2>Create Account</h2>
                    
                    <?php echo $message; ?>
                    
                    <form method="POST">
                        <div class="form-group">
                            <label>Organization Name</label>
                            <input type="text" name="org_name" placeholder="e.g. Acme Corp" required>
                        </div>
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="full_name" placeholder="John Doe" required>
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="email" placeholder="admin@acme.com" required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" placeholder="••••••••" required>
                        </div>
                        <button type="submit" class="btn-primary">Register Business</button>
                    </form>
                    
                    <div class="auth-links">
                        <a href="login.php">Already have an account? Sign in</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>