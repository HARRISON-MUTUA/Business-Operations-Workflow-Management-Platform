            <aside class="sidebar">
            <h3>Business Workflow</h3>
            
            <a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                Dashboard
            </a>
            
            <a href="users.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'users.php' || basename($_SERVER['PHP_SELF']) == 'edit_user.php') ? 'active' : ''; ?>">
                Users
            </a>
            
            <a href="departments.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'departments.php' ? 'active' : ''; ?>">
                Departments
            </a>
            
            <hr style="border-color: #374151; margin: 1rem 0;">
            
            <a href="logout.php" class="text-danger">
                Logout
            </a>
        </aside>