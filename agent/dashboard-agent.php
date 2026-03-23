<?php
include 'config.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'agent') {
    header("Location: login.php");
    exit;
}

$agent_user_id = $_SESSION['user_id'];

// Fetch Agent specific stats
$agent_query = mysqli_query($conn, "SELECT id FROM agents WHERE user_id = $agent_user_id");
$agent_data = mysqli_fetch_assoc($agent_query);
$agent_id = $agent_data['id'];

$total_resources = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM resources WHERE agent_id = $agent_user_id"))['count'];
$total_messages = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM messages WHERE receiver_id = $agent_user_id AND is_read = 0"))['count'];
$avg_rating = mysqli_fetch_assoc(mysqli_query($conn, "SELECT AVG(rating) as avg FROM feedback WHERE agent_id = $agent_id"))['avg'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Dashboard - Entrepreneur Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style> 
        body { background: #f8f9fa; } 
        .stat-card { border: none; border-radius: 12px; transition: 0.3s; }
        .stat-card:hover { transform: translateY(-5px); }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="fw-bold">Mentor Dashboard</h1>
            <p class="text-muted">Welcome back, <?= htmlspecialchars($_SESSION['full_name']); ?>! Manage your mentorship tools below.</p>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card stat-card shadow-sm p-4 bg-white border-start border-success border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Uploaded Resources</h6>
                        <h2 class="mb-0 fw-bold"><?= $total_resources ?></h2>
                    </div>
                    <i class="fas fa-file-invoice fa-2x text-success"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card shadow-sm p-4 bg-white border-start border-primary border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">New Messages</h6>
                        <h2 class="mb-0 fw-bold"><?= $total_messages ?></h2>
                    </div>
                    <i class="fas fa-envelope-open-text fa-2x text-primary"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card shadow-sm p-4 bg-white border-start border-warning border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Average Rating</h6>
                        <h2 class="mb-0 fw-bold"><?= number_format($avg_rating, 1) ?: 'N/A' ?></h2>
                    </div>
                    <i class="fas fa-star fa-2x text-warning"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm p-4 border-0 mb-4">
                <h5><i class="fas fa-rocket text-success me-2"></i> Quick Actions</h5>
                <hr>
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="manage-resources.php" class="btn btn-outline-dark w-100 py-3">
                            <i class="fas fa-plus-circle d-block mb-2"></i> Add New Resource
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="forum.php" class="btn btn-outline-dark w-100 py-3">
                            <i class="fas fa-comments d-block mb-2"></i> Participate in Forum
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm p-4 border-0">
                <h5>Account Settings</h5>
                <div class="list-group list-group-flush mt-3">
                    <a href="agent-profile.php" class="list-group-item list-group-item-action"><i class="fas fa-user-circle me-2"></i> Edit Profile</a>
                    <a href="agent-feedback.php" class="list-group-item list-group-item-action"><i class="fas fa-star me-2"></i> View All Feedback</a>
                    <a href="logout.php" class="list-group-item list-group-item-action text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>