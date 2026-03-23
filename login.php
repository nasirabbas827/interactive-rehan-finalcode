<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Entrepreneur Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body { 
            background: linear-gradient(135deg, #f8f9fa, #e9ecef); 
        }
        .login-card { 
            max-width: 460px; 
            margin: 80px auto; 
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
    <div class="login-card card shadow">
        <div class="card-header bg-dark text-white text-center py-4">
            <h3><i class="fas fa-sign-in-alt"></i> Welcome Back</h3>
        </div>
        <div class="card-body p-5">
            <?php
            include 'config.php';

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $email = mysqli_real_escape_string($conn, trim($_POST['email']));
                $pass  = $_POST['password'];
                $selected_role = $_POST['role'];   // Role selected by user

                $sql = "SELECT * FROM users WHERE email = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                
                if ($row = mysqli_fetch_assoc($result)) {
                    if ($pass === $row['password']) {           // Plain text password
                        
                        // Check if selected role matches database role
                        if ($row['role'] !== $selected_role) {
                            echo '<div class="alert alert-danger">You selected wrong role. Please select correct role.</div>';
                        }
                        elseif ($row['role'] == 'agent' && $row['status'] != 'approved') {
                            echo '<div class="alert alert-warning">Your agent account is still under review by admin.</div>';
                        } 
                        else {
                            $_SESSION['user_id']   = $row['id'];
                            $_SESSION['full_name'] = $row['full_name'];
                            $_SESSION['role']      = $row['role'];

                            // Redirect to respective dashboard
                            if ($row['role'] == 'admin') {
                                header("Location: admin/dashboard-admin.php");
                            } elseif ($row['role'] == 'agent') {
                                header("Location: agent/dashboard-agent.php");
                            } else {
                                header("Location: user/dashboard-user.php");
                            }
                            exit;
                        }
                    } else {
                        echo '<div class="alert alert-danger">Invalid password.</div>';
                    }
                } else {
                    echo '<div class="alert alert-danger">No account found with this email.</div>';
                }
            }
            ?>

            <form method="POST">
                <div class="mb-3">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <!-- Role Selection Radio Buttons -->
                <div class="mb-4">
                    <label class="form-label">Select Your Role</label>
                    <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check" name="role" id="role_user" value="user" checked>
                        <label class="btn btn-outline-success" for="role_user">Aspiring Entrepreneur (User)</label>

                        <input type="radio" class="btn-check" name="role" id="role_agent" value="agent">
                        <label class="btn btn-outline-success" for="role_agent">Business Mentor (Agent)</label>

                        <input type="radio" class="btn-check" name="role" id="role_admin" value="admin">
                        <label class="btn btn-outline-success" for="role_admin">Administrator</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100 py-3 fw-bold">Login</button>
            </form>
        </div>

        <div class="card-footer text-center py-3">
            Don't have an account? 
            <a href="register.php" class="text-success fw-bold">Register Now</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>