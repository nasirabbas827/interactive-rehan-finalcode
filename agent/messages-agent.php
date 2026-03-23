<?php
include 'config.php';

// Security: Ensure only agents can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'agent') {
    header("Location: login.php");
    exit;
}

$agent_id = $_SESSION['user_id'];

// SQL Logic: Get the LATEST message from each unique student
$query = "SELECT m.*, u.full_name as student_name, u.email as student_email
          FROM messages m
          JOIN users u ON (m.sender_id = u.id OR m.receiver_id = u.id)
          WHERE (m.receiver_id = $agent_id OR m.sender_id = $agent_id)
          AND u.id != $agent_id
          AND m.id IN (
              SELECT MAX(id) FROM messages 
              WHERE receiver_id = $agent_id OR sender_id = $agent_id 
              GROUP BY IF(sender_id = $agent_id, receiver_id, sender_id)
          )
          ORDER BY m.created_at DESC";

$conversations = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agent Inbox - Mentor Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f7f6; }
        .inbox-card { border: none; border-radius: 12px; transition: 0.3s; }
        .inbox-card:hover { background-color: #f8f9fa; transform: translateX(5px); }
        .unread-dot { width: 10px; height: 10px; background: #28a745; border-radius: 50%; display: inline-block; }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-comments text-success"></i> Student Inquiries</h2>
                <span class="badge bg-dark"><?= mysqli_num_rows($conversations) ?> Conversations</span>
            </div>

            <?php if (mysqli_num_rows($conversations) > 0): ?>
                <div class="card shadow-sm">
                    <div class="list-group list-group-flush">
                        <?php while($conv = mysqli_fetch_assoc($conversations)): 
                            // Determine the "Other User ID" for the link
                            $student_id = ($conv['sender_id'] == $agent_id) ? $conv['receiver_id'] : $conv['sender_id'];
                        ?>
                            <a href="view-messages.php?with=<?= $student_id ?>" class="list-group-item list-group-item-action p-4 inbox-card">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-1"><?= htmlspecialchars($conv['student_name']) ?></h5>
                                            <p class="mb-1 text-muted">
                                                <?= (strlen($conv['message']) > 60) ? substr(htmlspecialchars($conv['message']), 0, 60).'...' : htmlspecialchars($conv['message']) ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted d-block"><?= date('M d, H:i', strtotime($conv['created_at'])) ?></small>
                                        <span class="btn btn-sm btn-outline-success mt-2">Open Chat</span>
                                    </div>
                                </div>
                            </a>
                        <?php endwhile; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-center py-5 bg-white rounded shadow-sm">
                    <i class="fas fa-comment-slash fa-4x text-muted mb-3"></i>
                    <h4>No messages yet</h4>
                    <p class="text-muted">When students reach out to you, they will appear here.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>