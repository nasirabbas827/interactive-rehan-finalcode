<?php
include 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$topic_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

// Handle New Reply
if (isset($_POST['post_reply'])) {
    $msg = mysqli_real_escape_string($conn, $_POST['message']);
    if (!empty($msg)) {
        mysqli_query($conn, "INSERT INTO forum_posts (topic_id, user_id, message) VALUES ($topic_id, $user_id, '$msg')");
        header("Location: forum-view.php?id=$topic_id");
        exit;
    }
}

// Get Topic Info
$topic_res = mysqli_query($conn, "SELECT t.*, u.full_name FROM forum_topics t JOIN users u ON t.user_id = u.id WHERE t.id = $topic_id");
$topic = mysqli_fetch_assoc($topic_res);

// Get All Posts
$posts = mysqli_query($conn, "SELECT p.*, u.full_name, u.role FROM forum_posts p JOIN users u ON p.user_id = u.id WHERE p.topic_id = $topic_id ORDER BY p.created_at ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($topic['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .post-card { border-left: 4px solid #dee2e6; margin-bottom: 20px; }
        .post-agent { border-left-color: #28a745; background: #f1f8f4; }
        .meta { font-size: 0.85rem; color: #6c757d; }
    </style>
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>

<div class="container py-5">
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="forum.php">Forum</a></li><li class="breadcrumb-item active">Discussion</li></ol></nav>
    
    <div class="bg-dark text-white p-4 rounded mb-4">
        <h1><?= htmlspecialchars($topic['title']) ?></h1>
        <p class="mb-0">Started by <?= htmlspecialchars($topic['full_name']) ?></p>
    </div>

    <?php while($post = mysqli_fetch_assoc($posts)): ?>
        <div class="card shadow-sm post-card <?= ($post['role'] !== 'user') ? 'post-agent' : '' ?>">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <strong>
                        <?= htmlspecialchars($post['full_name']) ?> 
                        <?php if($post['role'] !== 'user'): ?>
                            <span class="badge bg-success ml-2">Mentor</span>
                        <?php endif; ?>
                    </strong>
                    <span class="meta"><?= date('M d, Y H:i', strtotime($post['created_at'])) ?></span>
                </div>
                <p class="mb-0"><?= nl2br(htmlspecialchars($post['message'])) ?></p>
            </div>
        </div>
    <?php endwhile; ?>

    <div class="card shadow border-0 mt-5">
        <div class="card-body">
            <h5>Leave a Reply</h5>
            <form method="POST">
                <textarea name="message" class="form-control mb-3" rows="4" placeholder="Share your thoughts or ask a question..." required></textarea>
                <button type="submit" name="post_reply" class="btn btn-success">Post Reply</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>