<?php
require_once '../config/database.php';
require_once '../includes/header.php'; 
require_once '../includes/sidebar.php';

$conn = getDatabaseConnection();
$search = $_GET['search'] ?? '';
$stmt = $conn->prepare("SELECT u.id, u.full_name, u.email, u.status, r.name as role FROM users u JOIN roles r ON u.role_id = r.id WHERE u.full_name LIKE ? OR u.email LIKE ?");
$search_term = "%" . $search . "%";
$stmt->bind_param("ss", $search_term, $search_term);
$stmt->execute();
$users = $stmt->get_result();
?>
<main class="main-content">
    <header class="top-navbar">
        <h3>User Management</h3>
    </header>
    
    <div class="content-area">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">System Users</h5>
                <form method="GET" class="d-flex w-50" style="gap: 10px;">
                    <input type="text" name="search" placeholder="Search by name or email..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="btn-primary" style="width: auto; white-space: nowrap;">Search</button>
                </form>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($users->num_rows > 0): ?>
                            <?php while($row = $users->fetch_assoc()): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($row['full_name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td class="text-capitalize"><?php echo $row['role']; ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $row['status'] == 'active' ? 'success' : 'danger'; ?>">
                                            <?php echo ucfirst($row['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <!-- Edit Button (Iteration 2) -->
                                            <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn-secondary" style="width: auto; padding: 4px 10px; font-size: 0.8rem; text-decoration: none;">
                                                Edit
                                            </a>
                                            
                                            <!-- Activate/Deactivate Button (Iteration 1) -->
                                            <form method="POST" action="toggle_user.php">
                                                <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                                <input type="hidden" name="current_status" value="<?php echo $row['status']; ?>">
                                                <button type="submit" class="btn-<?php echo $row['status'] == 'active' ? 'danger' : 'success'; ?>" style="width: auto; padding: 4px 10px; font-size: 0.8rem; border: 1px solid; background: transparent; color: <?php echo $row['status'] == 'active' ? '#dc3545' : '#198754'; ?>; border-radius: 4px;">
                                                    <?php echo $row['status'] == 'active' ? 'Deactivate' : 'Activate'; ?>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No users found matching your search.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<?php require_once '../includes/footer.php'; ?>