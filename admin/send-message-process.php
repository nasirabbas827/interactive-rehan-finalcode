<?php
include 'config.php';
session_start();

if (isset($_POST['send_msg']) && isset($_SESSION['user_id'])) {
    $sender_id = $_SESSION['user_id'];
    $receiver_id = (int)$_POST['receiver_id'];
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $sql = "INSERT INTO messages (sender_id, receiver_id, message) VALUES ('$sender_id', '$receiver_id', '$message')";
    
    if (mysqli_query($conn, $sql)) {
        // Redirect back to the chat history
        header("Location: view-messages.php?with=$receiver_id");
    } else {
        header("Location: dashboard-user.php?error=msg_failed");
    }
}