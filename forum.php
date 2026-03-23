<?php
include 'config.php';

// Handle New Topic Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_topic'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $initial_message = mysqli_real_escape_string($conn, $_POST['message']);

    if (!empty($title) && !empty($initial_message)) {
        // 1. Insert into forum_topics
        $topic_sql = "INSERT INTO forum_topics (user_id, title) VALUES ('$user_id', '$title')";
        if (mysqli_query($conn, $topic_sql)) {
            $topic_id = mysqli_insert_id($conn);
            
            // 2. Insert the first post into forum_posts
            $post_sql = "INSERT INTO forum_posts (topic_id, user_id, message) VALUES ('$topic_id', '$user_id', '$initial_message')";
            mysqli_query($conn, $post_sql);
            
            $success = "Discussion started successfully!";
        }
    }
}

// Fetch all topics with post counts
$query = "SELECT t.*, u.full_name, 
          (SELECT COUNT(*) FROM forum_posts WHERE topic_id = t.id) as reply_count,
          (SELECT MAX(created_at) FROM forum_posts WHERE topic_id = t.id) as last_activity
          FROM forum_topics t
          JOIN users u ON t.user_id = u.id
          ORDER BY last_activity DESC";

$topics = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Forum - EntrePortal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .forum-card { border: none; border-radius: 10px; transition: 0.2s; }
        .forum-card:hover { background-color: #f1f8f3; }
        .topic-icon { 
            width: 45px; height: 45px; 
            background: #28a745; color: white; 
            display: flex; align-items: center; justify-content: center; 
            border-radius: 8px; 
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-8">
            <h2 class="fw-bold mb-4"><i class="fas fa-comments text-success me-2"></i> Discussion Forum</h2>
        </div>
        <div class="col-md-4 text-md-end mb-4">
            <?php if(isset($_SESSION['user_id'])): ?>
                <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#newTopicModal">
                    <i class="fas fa-plus me-1"></i> Start New Discussion
                </button>
            <?php else: ?>
                <a href="login.php" class="btn btn-outline-success">Login to Post</a>
            <?php endif; ?>
        </div>
    </div>

    <?php if(isset($success)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $success ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3" style="width: 60%;">Topic</th>
                            <th class="text-center">Replies</th>
                            <th class="pe-4">Last Activity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($topics) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($topics)): ?>
                            <tr onclick="window.location='view-topic.php?id=<?= $row['id'] ?>'" style="cursor: pointer;">
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="topic-icon me-3">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark"><?= htmlspecialchars($row['title']) ?></h6>
                                            <small class="text-muted">Started by <?= htmlspecialchars($row['full_name']) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge rounded-pill bg-light text-dark border"><?= $row['reply_count'] ?></span>
                                </td>
                                <td class="pe-4 text-muted">
                                    <small><i class="far fa-clock me-1"></i> <?= date('M d, g:i a', strtotime($row['last_activity'])) ?></small>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted">
                                    No discussions yet. Be the first to start one!
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="newTopicModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="forum.php" method="POST">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Start New Discussion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Topic Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. How to get seed funding in Pakistan?" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Your Message</label>
                        <textarea name="message" class="form-control" rows="5" placeholder="Describe your topic in detail..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="create_topic" class="btn btn-success px-4">Post Discussion</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>