<?php
include 'config.php';

if (!isset($_GET['id'])) {
    header("Location: forum.php");
    exit;
}

$topic_id = (int)$_GET['id'];

// Handle New Reply Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['post_reply'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    if (!empty($message)) {
        $sql = "INSERT INTO forum_posts (topic_id, user_id, message) VALUES ('$topic_id', '$user_id', '$message')";
        mysqli_query($conn, $sql);
        $success = "Reply posted!";
    }
}

// Fetch Topic Details
$topic_query = mysqli_query($conn, "SELECT t.*, u.full_name FROM forum_topics t JOIN users u ON t.user_id = u.id WHERE t.id = $topic_id");
$topic = mysqli_fetch_assoc($topic_query);

if (!$topic) {
    die("Topic not found.");
}

// Fetch all posts for this topic
$posts_query = mysqli_query($conn, "SELECT p.*, u.full_name, u.role FROM forum_posts p JOIN users u ON p.user_id = u.id WHERE p.topic_id = $topic_id ORDER BY p.created_at ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($topic['title']) ?> - EntrePortal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; }
        .post-card { border: none; border-radius: 12px; margin-bottom: 20px; }
        .author-info { border-right: 1px solid #eee; }
        .reply-box { border-radius: 15px; border: 2px solid #e9ecef; transition: 0.3s; }
        .reply-box:focus { border-color: #28a745; box-shadow: none; }
        .badge-agent { background-color: #e8f5e9; color: #2e7d32; }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="forum.php" class="text-success text-decoration-none">Forum</a></li>
            <li class="breadcrumb-item active text-truncate" style="max-width: 300px;"><?= htmlspecialchars($topic['title']) ?></li>
        </ol>
    </nav>

    <h2 class="fw-bold mb-4"><?= htmlspecialchars($topic['title']) ?></h2>

    <div class="row">
        <div class="col-lg-10 mx-auto">
            
            <?php while($post = mysqli_fetch_assoc($posts_query)): ?>
                <div class="card post-card shadow-sm">
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <div class="col-md-3 bg-light p-4 text-center author-info rounded-start">
                                <div class="mb-2">
                                    <i class="fas fa-user-circle fa-3x text-secondary"></i>
                                </div>
                                <h6 class="fw-bold mb-1"><?= htmlspecialchars($post['full_name']) ?></h6>
                                <?php if($post['role'] == 'agent'): ?>
                                    <span class="badge badge-agent mb-2"><i class="fas fa-check-circle me-1"></i> Mentor</span>
                                <?php endif; ?>
                                <div class="text-muted small">
                                    <i class="far fa-clock me-1"></i> <?= date('M d, Y', strtotime($post['created_at'])) ?>
                                </div>
                            </div>
                            <div class="col-md-9 p-4">
                                <div class="d-flex justify-content-between mb-3">
                                    <small class="text-muted">#<?= $post['id'] ?></small>
                                </div>
                                <p style="white-space: pre-wrap; line-height: 1.6;"><?= htmlspecialchars($post['message']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>

            <div class="mt-5">
                <h4 class="fw-bold mb-3">Post a Reply</h4>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <form action="view-topic.php?id=<?= $topic_id ?>" method="POST">
                        <div class="card border-0 shadow-sm p-3">
                            <textarea name="message" class="form-control reply-box mb-3" rows="5" placeholder="Share your thoughts or answer the question..." required></textarea>
                            <div class="text-end">
                                <button type="submit" name="post_reply" class="btn btn-success px-5 rounded-pill shadow-sm">
                                    <i class="fas fa-paper-plane me-2"></i> Post Reply
                                </button>
                            </div>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="alert alert-warning text-center rounded-pill">
                        Please <a href="login.php" class="fw-bold text-dark">Login</a> to participate in this discussion.
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>