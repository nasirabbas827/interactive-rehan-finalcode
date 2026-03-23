<?php
include 'config.php';

// Security: Ensure only agents can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'agent') {
    header("Location: login.php");
    exit;
}

$agent_user_id = $_SESSION['user_id'];

// 1. Get the Agent's Table ID and Stats
$stats_query = mysqli_query($conn, "SELECT id FROM agents WHERE user_id = $agent_user_id");
$agent_data = mysqli_fetch_assoc($stats_query);
$agent_id = $agent_data['id'];

// 2. Fetch Average Rating
$avg_query = mysqli_query($conn, "SELECT AVG(rating) as average, COUNT(id) as total FROM feedback WHERE agent_id = $agent_id");
$stats = mysqli_fetch_assoc($avg_query);
$avg_rating = round($stats['average'], 1);
$total_reviews = $stats['total'];

// 3. Fetch All Individual Feedbacks
$feedback_sql = "SELECT f.*, u.full_name, u.email 
                 FROM feedback f 
                 JOIN users u ON f.user_id = u.id 
                 WHERE f.agent_id = $agent_id 
                 ORDER BY f.created_at DESC";
$feedback_result = mysqli_query($conn, $feedback_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Feedback - Mentor Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f7f6; }
        .rating-num { font-size: 3rem; fw-bold; color: #198754; }
        .star-active { color: #ffc107; }
        .star-inactive { color: #dee2e6; }
        .feedback-card { border: none; border-radius: 12px; }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm text-center p-4 feedback-card bg-white">
                <h5 class="text-muted">Overall Rating</h5>
                <div class="rating-num"><?= $avg_rating ?: '0.0' ?></div>
                <div class="mb-2">
                    <?php 
                    for($i=1; $i<=5; $i++) {
                        echo ($i <= $avg_rating) ? '<i class="fas fa-star star-active"></i>' : '<i class="fas fa-star star-inactive"></i>';
                    }
                    ?>
                </div>
                <p class="text-muted small">Based on <?= $total_reviews ?> reviews</p>
                <hr>
                <a href="dashboard-agent.php" class="btn btn-outline-secondary btn-sm">Back to Dashboard</a>
            </div>
        </div>

        <div class="col-lg-8">
            <h3 class="mb-4">Recent Reviews</h3>
            
            <?php if (mysqli_num_rows($feedback_result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($feedback_result)): ?>
                    <div class="card shadow-sm mb-3 feedback-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-0 fw-bold"><?= htmlspecialchars($row['full_name']) ?></h6>
                                    <small class="text-muted"><?= $row['email'] ?></small>
                                </div>
                                <div class="text-warning">
                                    <?php for($i=1; $i<=5; $i++) echo ($i <= $row['rating']) ? '★' : '☆'; ?>
                                </div>
                            </div>
                            <p class="mt-3 mb-1 text-dark italic">"<?= nl2br(htmlspecialchars($row['message'])) ?>"</p>
                            <div class="text-end">
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    Posted on: <?= date('d M, Y', strtotime($row['created_at'])) ?>
                                </small>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="card p-5 text-center text-muted feedback-card">
                    <i class="fas fa-comment-slash fa-3x mb-3"></i>
                    <p>You haven't received any feedback yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>