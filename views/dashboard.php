<?php
require_once '../config/database.php';
require_once '../includes/header.php'; 
require_once '../includes/sidebar.php';

$conn = getDatabaseConnection();
$total_users = $conn->query("SELECT COUNT(*) as count FROM users WHERE organization_id = 1")->fetch_assoc()['count'];
$total_requests = $conn->query("SELECT COUNT(*) as count FROM requests WHERE organization_id = 1")->fetch_assoc()['count'];
$pending_requests = $conn->query("SELECT COUNT(*) as count FROM requests WHERE status = 'pending'")->fetch_assoc()['count'];
$recent_requests = $conn->query("SELECT r.reference_no, r.request_type, r.status, r.created_at, u.full_name FROM requests r JOIN users u ON r.requester_id = u.id ORDER BY r.created_at DESC LIMIT 5");
?>

<main class="main-content">
    <header class="top-navbar d-flex justify-content-between align-items-center">
        <h3>Admin Overview</h3>
        <div class="user-profile d-flex align-items-center gap-3">
            <span><?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
            <span class="badge bg-primary">Admin</span>
        </div>
    </header>
    <div class="content-area">
        <div class="row mb-4">
            <div class="col-md-3"><div class="card p-3 text-center border-start border-primary border-4"><h2 class="text-primary"><?php echo $total_users; ?></h2><p class="text-muted mb-0">Total Users</p></div></div>
            <div class="col-md-3"><div class="card p-3 text-center border-start border-info border-4"><h2 class="text-info"><?php echo $total_requests; ?></h2><p class="text-muted mb-0">Total Requests</p></div></div>
            <div class="col-md-3"><div class="card p-3 text-center border-start border-warning border-4"><h2 class="text-warning"><?php echo $pending_requests; ?></h2><p class="text-muted mb-0">Pending Approvals</p></div></div>
            <div class="col-md-3"><div class="card p-3 text-center border-start border-success border-4"><h2 class="text-success">0</h2><p class="text-muted mb-0">Tasks In Progress</p></div></div>
        </div>
        <div class="card">
            <div class="card-header bg-white"><h5 class="mb-0">Recent Requests</h5></div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Ref Number</th><th>Requester</th><th>Type</th><th>Status</th><th>Date</th></tr></thead>
                    <tbody>
                        <?php while($row = $recent_requests->fetch_assoc()): ?>
                            <tr>
                                <td><strong><?php echo $row['reference_no']; ?></strong></td>
                                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                <td class="text-capitalize"><?php echo $row['request_type']; ?></td>
                                <td><span class="badge bg-<?php echo $row['status'] == 'pending' ? 'warning text-dark' : 'success'; ?>"><?php echo ucfirst($row['status']); ?></span></td>
                                <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<?php require_once '../includes/footer.php'; ?>