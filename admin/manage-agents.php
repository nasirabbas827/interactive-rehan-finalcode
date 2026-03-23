<?php
// manage-agents.php
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Handle Status Update
if (isset($_GET['id']) && isset($_GET['status'])) {
    $agent_id = (int)$_GET['id'];
    $new_status = mysqli_real_escape_string($conn, $_GET['status']);

    if (in_array($new_status, ['approved', 'rejected', 'pending'])) {
        $sql = "UPDATE users SET status = ? WHERE id = ? AND role = 'agent'";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $new_status, $agent_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $success_msg = "Agent status updated successfully!";
        } else {
            $error_msg = "Failed to update status!";
        }
    }
}

// Fetch ALL Agents
$query = "SELECT id, full_name, email, phone, location, bio, status, created_at 
          FROM users 
          WHERE role = 'agent' 
          ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Agents - Entrepreneur Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .page-header {
            background: linear-gradient(135deg, #212529, #343a40);
            color: white;
            padding: 2rem 0;
        }
        .table th { background-color: #f1f3f5; }
        .badge-pending { background-color: #ffc107; color: black; }
        .badge-approved { background-color: #28a745; }
        .badge-rejected { background-color: #dc3545; }
        .btn-group-xs > .btn, .btn-xs {
            padding: .25rem .4rem;
            font-size: .875rem;
            line-height: .5;
            border-radius: .2rem;
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="page-header text-center">
    <div class="container">
        <h1 class="display-5 fw-bold"><i class="fas fa-users-cog"></i> Manage Agents</h1>
        <p class="lead">View and Update Status of All Registered Agents / Mentors</p>
    </div>
</div>

<div class="container py-5">

    <?php if (isset($success_msg)): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $success_msg ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($error_msg)): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= $error_msg ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-list"></i> All Agents (<?= mysqli_num_rows($result) ?>)</h5>
        </div>
        <div class="card-body p-0">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="20%">Full Name</th>
                                <th>Email</th>
                                <th>Location</th>
                                <th>Registered Date</th>
                                <th>Status</th>
                                <th width="220px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($row['full_name']) ?></strong></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= htmlspecialchars($row['location'] ?: 'N/A') ?></td>
                                <td><?= date('d M, Y', strtotime($row['created_at'])) ?></td>
                                <td>
                                    <?php
                                    if ($row['status'] == 'approved') {
                                        echo '<span class="badge badge-approved px-3 py-2">APPROVED</span>';
                                    } elseif ($row['status'] == 'rejected') {
                                        echo '<span class="badge badge-rejected px-3 py-2">REJECTED</span>';
                                    } else {
                                        echo '<span class="badge badge-pending px-3 py-2">PENDING</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="view-profile.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm text-white" title="View Profile">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a href="manage-agents.php?id=<?= $row['id'] ?>&status=approved" 
                                           class="btn btn-success btn-sm" 
                                           onclick="return confirm('Approve this agent?')">
                                            Approve
                                        </a>

                                        <a href="manage-agents.php?id=<?= $row['id'] ?>&status=rejected" 
                                           class="btn btn-danger btn-sm" 
                                           onclick="return confirm('Reject this agent?')">
                                            Reject
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="p-5 text-center text-muted">
                    <i class="fas fa-user-slash fa-3x mb-3"></i>
                    <h5>No agents registered yet.</h5>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="dashboard-admin.php" class="btn btn-secondary">
            ← Back to Admin Dashboard
        </a>
    </div>

</div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>