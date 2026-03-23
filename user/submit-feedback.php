<?php
include 'config.php';
session_start();

if (isset($_POST['submit_feedback']) && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $mentor_user_id = (int)$_POST['mentor_user_id'];
    $rating = (int)$_POST['rating'];
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // 1. Get the actual agent_id from the agents table
    $agent_query = mysqli_query($conn, "SELECT id FROM agents WHERE user_id = $mentor_user_id");
    $agent_data = mysqli_fetch_assoc($agent_query);

    if ($agent_data) {
        $agent_id = $agent_data['id'];

        // 2. Insert into feedback table
        $sql = "INSERT INTO feedback (user_id, agent_id, message, rating) 
                VALUES ('$user_id', '$agent_id', '$message', '$rating')";
        
        if (mysqli_query($conn, $sql)) {
            header("Location: view-profile.php?id=$mentor_user_id&msg=feedback_success");
        } else {
            header("Location: view-profile.php?id=$mentor_user_id&msg=error");
        }
    } else {
        die("Invalid Agent ID.");
    }
}