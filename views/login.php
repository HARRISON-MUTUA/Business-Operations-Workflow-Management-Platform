<?php
session_start();
require_once '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $conn = getDatabaseConnection();
    $stmt = $conn->prepare("SELECT u.id, u.password, u.full_name, r.name as role FROM users u JOIN roles r ON u.role_id = r.id WHERE u.email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['full_name'] = $row['full_name'];
            header("Location: dashboard.php");
            exit();
        }
    }
    $error = "Email or password incorrect";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Business Workflow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-container">
            <div class="auth-left">
                <h1>Manage Work. Better.</h1>
                <p>Log in to access your dashboard, approve requests, and view workflow progress.</p>
            </div>
            <div class="auth-right">
                <div class="auth-box">
                    <h2>Sign In</h2>
                    
                    <?php if(isset($error)) echo "<div class='alert alert-danger' style='color: red; margin-bottom: 15px;'>$error</div>"; ?>
                    
                    <form method="POST">
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="email" placeholder="admin@acme.com" required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" placeholder="••••••••" required>
                        </div>
                        <button type="submit" class="btn-primary">Sign In</button>
                    </form>
                    
                    <div class="auth-links">
                        <a href="index.php">Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>