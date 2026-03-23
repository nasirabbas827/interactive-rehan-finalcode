<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Entrepreneur Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #f8f9fa, #e9ecef); }
        .register-card { max-width: 520px; margin: 50px auto; }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
    <div class="register-card card shadow">
        <div class="card-header bg-success text-white text-center py-4">
            <h3><i class="fas fa-user-plus"></i> Create Your Account</h3>
        </div>
        <div class="card-body p-5">
            <?php
            include 'config.php';

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $full_name = mysqli_real_escape_string($conn, trim($_POST['full_name']));
                $email     = mysqli_real_escape_string($conn, trim($_POST['email']));
                $password  = $_POST['password'];                    // Plain text as requested
                $phone     = mysqli_real_escape_string($conn, trim($_POST['phone']));
                $bio       = mysqli_real_escape_string($conn, trim($_POST['bio']));
                $location  = mysqli_real_escape_string($conn, trim($_POST['location']));
                $role      = $_POST['role'];   // user, agent, or admin

                $status = ($role == 'user') ? 'approved' : 'pending';

                $sql = "INSERT INTO users (full_name, email, password, phone, bio, location, role, status) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "ssssssss", $full_name, $email, $password, $phone, $bio, $location, $role, $status);
                
                if (mysqli_stmt_execute($stmt)) {
                    echo '<div class="alert alert-success">Registration Successful!';
                    if ($role == 'agent') echo ' Your account is pending admin approval.';
                    echo '</div>';
                } else {
                    echo '<div class="alert alert-danger">Error: ' . mysqli_error($conn) . '</div>';
                }
            }
            ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label>Full Name</label>
                    <input type="text" name="full_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required minlength="6">
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Phone</label>
                        <input type="tel" name="phone" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Location</label>
                        <input type="text" name="location" class="form-control">
                    </div>
                </div>
                <div class="mb-3">
                    <label>Bio / Short Introduction</label>
                    <textarea name="bio" class="form-control" rows="3"></textarea>
                </div>
                <div class="mb-4">
                    <label>I am registering as:</label>
                    <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check" name="role" id="user" value="user" checked>
                        <label class="btn btn-outline-success" for="user">Aspiring Entrepreneur (User)</label>
                        
                        <input type="radio" class="btn-check" name="role" id="agent" value="agent">
                        <label class="btn btn-outline-success" for="agent">Business Mentor / Agent</label>
                        
                        
                    </div>
                </div>
                <button type="submit" class="btn btn-success w-100 py-3">Create Account</button>
            </form>
        </div>
        <div class="card-footer text-center">
            Already have an account? <a href="login.php" class="text-success">Login here</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>