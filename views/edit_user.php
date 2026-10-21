<?php
require_once '../config/database.php';
require_once '../includes/header.php'; 
require_once '../includes/sidebar.php';

$conn = getDatabaseConnection();
$user_id = $_GET['id'] ?? 0;
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role_id = $_POST['role_id'];
    $dept_id = $_POST['department_id'];
    
    $stmt = $conn->prepare("UPDATE users SET role_id = ?, department_id = ? WHERE id = ?");
    $stmt->bind_param("iii", $role_id, $dept_id, $user_id);
    if ($stmt->execute()) {
        $message = "<div class='alert alert-success' style='color:green; margin-bottom:15px;'>User updated successfully.</div>";
    }
}

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$roles = $conn->query("SELECT * FROM roles");
$depts = $conn->query("SELECT * FROM departments");
?>

<main class="main-content">
    <header class="top-navbar">
        <h3>Edit User: <?php echo htmlspecialchars($user['full_name']); ?></h3>
    </header>
    
    <div class="content-area">
        <div class="card p-4 w-50">
            <?php echo $message; ?>
            
            <form method="POST">
                <div class="form-group mb-3">
                    <label>System Role</label>
                    <select name="role_id">
                        <?php while($r = $roles->fetch_assoc()): ?>
                            <option value="<?php echo $r['id']; ?>" <?php echo $user['role_id'] == $r['id'] ? 'selected' : ''; ?>>
                                <?php echo ucfirst($r['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group mb-4">
                    <label>Assign Department</label>
                    <select name="department_id">
                        <option value="">Unassigned</option>
                        <?php while($d = $depts->fetch_assoc()): ?>
                            <option value="<?php echo $d['id']; ?>" <?php echo $user['department_id'] == $d['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($d['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn-primary" style="width: auto;">Save Changes</button>
                    <a href="users.php" class="btn-secondary" style="width: auto; text-decoration: none;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>