<?php
include 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$my_id = $_SESSION['user_id'];
// The person I am talking to
$other_id = isset($_GET['with']) ? (int)$_GET['with'] : 0;

if ($other_id == 0) {
    die("Select a contact to view history.");
}

// Fetch the other person's name
$user_query = mysqli_query($conn, "SELECT full_name FROM users WHERE id = $other_id");
$other_user = mysqli_fetch_assoc($user_query);

// FETCH FULL HISTORY
// We want messages where (I sent to him) OR (He sent to me)
$history_sql = "SELECT m.*, u.full_name as sender_name 
                FROM messages m 
                JOIN users u ON m.sender_id = u.id 
                WHERE (m.sender_id = $my_id AND m.receiver_id = $other_id) 
                   OR (m.sender_id = $other_id AND m.receiver_id = $my_id) 
                ORDER BY m.created_at ASC";
$history = mysqli_query($conn, $history_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chat with <?= htmlspecialchars($other_user['full_name']) ?></title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        .chat-container { max-width: 700px; margin: 20px auto; background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .chat-box { height: 400px; overflow-y: auto; padding: 20px; display: flex; flex-direction: column; background: #f0f2f5; }
        .msg { max-width: 75%; padding: 10px 15px; border-radius: 18px; margin-bottom: 10px; position: relative; }
        .msg-me { align-self: flex-end; background: #28a745; color: white; border-bottom-right-radius: 2px; }
        .msg-them { align-self: flex-start; background: #e4e6eb; color: #050505; border-bottom-left-radius: 2px; }
        .time { font-size: 0.7rem; opacity: 0.7; display: block; margin-top: 5px; }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container">
    <div class="chat-container">
        <div class="bg-dark text-white p-3">
            <h5 class="mb-0"><i class="fas fa-user-circle"></i> <?= htmlspecialchars($other_user['full_name']) ?></h5>
        </div>

        <div class="chat-box" id="chatBox">
            <?php while($msg = mysqli_fetch_assoc($history)): ?>
                <?php $is_me = ($msg['sender_id'] == $my_id); ?>
                <div class="msg <?= $is_me ? 'msg-me' : 'msg-them' ?>">
                    <?= htmlspecialchars($msg['message']) ?>
                    <span class="time"><?= date('H:i', strtotime($msg['created_at'])) ?></span>
                </div>
            <?php endwhile; ?>
        </div>

        <div class="p-3 border-top">
            <form action="send-message-process.php" method="POST" class="d-flex">
                <input type="hidden" name="receiver_id" value="<?= $other_id ?>">
                <input type="text" name="message" class="form-control me-2" placeholder="Type a message..." required autocomplete="off">
                <button type="submit" name="send_msg" class="btn btn-success">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Auto-scroll to bottom of chat
    var chatBox = document.getElementById("chatBox");
    chatBox.scrollTop = chatBox.scrollHeight;
</script>
</body>
</html>