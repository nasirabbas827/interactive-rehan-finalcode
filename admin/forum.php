<?php
include 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Handle New Topic Creation (Only for Agents/Admins)
if (isset($_POST['create_topic']) && $role !== 'user') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    if (!empty($title)) {
        mysqli_query($conn, "INSERT INTO forum_topics (user_id, title) VALUES ('$user_id', '$title')");
        header("Location: forum.php?success=1");
        exit;
    }
}

$topics = mysqli_query($conn, "SELECT t.*, u.full_name as creator 
                               FROM forum_topics t 
                               JOIN users u ON t.user_id = u.id 
                               ORDER BY t.created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Discussion Forum</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .forum-card { border: none; border-radius: 10px; transition: 0.2s; }
        .forum-card:hover { background: #f1f8f4; transform: scale(1.01); }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-users text-success"></i> Community Forum</h2>
        <?php if($role !== 'user'): ?>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#topicModal">
                <i class="fas fa-plus"></i> Create New Topic
            </button>
        <?php endif; ?>
    </div>

    <div class="card shadow-sm border-0">
        <div class="list-group list-group-flush">
            <?php while($row = mysqli_fetch_assoc($topics)): ?>
                <a href="forum-view.php?id=<?= $row['id'] ?>" class="list-group-item list-group-item-action p-4 forum-card">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1 text-dark"><?= htmlspecialchars($row['title']) ?></h5>
                        <small class="text-muted"><?= date('M d, Y', strtotime($row['created_at'])) ?></small>
                    </div>
                    <p class="mb-1 text-muted small">Started by: <strong><?= htmlspecialchars($row['creator']) ?></strong></p>
                </a>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="topicModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST">
            <div class="modal-content">
                <div class="modal-header"><h5>Start a Discussion</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <label>Discussion Title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g., Tips for Raising Seed Funding" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="create_topic" class="btn btn-success">Post Topic</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>