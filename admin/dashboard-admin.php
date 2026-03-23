<?php
include 'config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// --- FETCH QUICK STATS ---
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE role = 'user'"))['count'];
$total_agents = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE role = 'agent'"))['count'];
$pending_approvals = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE status = 'pending' AND role = 'agent'"))['count'];
$total_resources = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM resources"))['count'];

// Fetch 5 Most Recent Pending Agents
$pending_list = mysqli_query($conn, "SELECT id, full_name, email, created_at FROM users WHERE role = 'agent' AND status = 'pending' ORDER BY created_at DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Entrepreneur Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f7f6; }
        .stat-card { border: none; border-radius: 15px; transition: transform 0.3s; }
        .stat-card:hover { transform: translateY(-5px); }
        .icon-box { width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
        .sidebar-link { text-decoration: none; color: #333; display: block; padding: 10px; border-radius: 8px; transition: 0.2s; }
        .sidebar-link:hover { background: #e9ecef; color: #198754; }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="fw-bold text-dark">System Overview</h1>
            <p class="text-muted">Welcome back, Administrator. Here is what's happening on the portal today.</p>
        </div>
        <div class="col-md-4 text-md-end align-self-center">
             <span class="badge bg-success p-2">Server Status: Online</span>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card stat-card shadow-sm p-3">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-primary text-white me-3"><i class="fas fa-users"></i></div>
                    <div>
                        <h3 class="mb-0 fw-bold"><?= $total_users ?></h3>
                        <small class="text-muted">Total Users</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card shadow-sm p-3">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-success text-white me-3"><i class="fas fa-user-tie"></i></div>
                    <div>
                        <h3 class="mb-0 fw-bold"><?= $total_agents ?></h3>
                        <small class="text-muted">Total Agents</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card shadow-sm p-3">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-warning text-dark me-3"><i class="fas fa-clock"></i></div>
                    <div>
                        <h3 class="mb-0 fw-bold"><?= $pending_approvals ?></h3>
                        <small class="text-muted">Pending Approvals</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card shadow-sm p-3">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-info text-white me-3"><i class="fas fa-folder-open"></i></div>
                    <div>
                        <h3 class="mb-0 fw-bold"><?= $total_resources ?></h3>
                        <small class="text-muted">Resources</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-dark">Recently Registered Agents</h5>
                    <a href="manage-agents.php" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(mysqli_num_rows($pending_list) > 0): ?>
                                    <?php while($row = mysqli_fetch_assoc($pending_list)): ?>
                                    <tr>
                                        <td class="align-middle"><?= htmlspecialchars($row['full_name']) ?></td>
                                        <td class="align-middle"><?= htmlspecialchars($row['email']) ?></td>
                                        <td class="align-middle small"><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                                        <td>
                                            <a href="manage-agents.php?id=<?= $row['id'] ?>&status=approved" class="btn btn-sm btn-success">Approve</a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="4" class="text-center py-4 text-muted">No pending approvals at the moment.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
    <div class="card shadow-sm border-0 p-3">
        <h5 class="fw-bold mb-3 border-bottom pb-2">Management Console</h5>
        <nav>
            <a href="manage-agents.php" class="sidebar-link mb-2">
                <i class="fas fa-user-shield me-2"></i> Manage Agents
            </a>

            <a href="manage-content.php" class="sidebar-link mb-2">
                <i class="fas fa-file-alt me-2"></i> Manage Content
            </a>

           

            <a href="forum.php" class="sidebar-link mb-2">
                <i class="fas fa-users me-2"></i> Forum Moderation
            </a>

            <hr>

            <a href="logout.php" class="sidebar-link text-danger">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </nav>
    </div>
</div>
    </div>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>