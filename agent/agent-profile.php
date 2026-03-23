<?php
// agent-profile.php
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'agent') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Check if agent profile already exists
$check = mysqli_query($conn, "SELECT * FROM agents WHERE user_id = $user_id");
$profile = mysqli_fetch_assoc($check);

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $business_domain = mysqli_real_escape_string($conn, trim($_POST['business_domain']));
    $experience_years = (int)$_POST['experience_years'];
    $expertise = mysqli_real_escape_string($conn, trim($_POST['expertise']));
    $linkedin_profile = mysqli_real_escape_string($conn, trim($_POST['linkedin_profile']));

    if ($profile) {
        // UPDATE existing profile
        $sql = "UPDATE agents SET business_domain=?, experience_years=?, expertise=?, linkedin_profile=? 
                WHERE user_id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sissi", $business_domain, $experience_years, $expertise, $linkedin_profile, $user_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $success = "Profile updated successfully!";
            $profile = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM agents WHERE user_id = $user_id"));
        } else {
            $error = "Failed to update profile!";
        }
    } else {
        // INSERT new profile
        $sql = "INSERT INTO agents (user_id, business_domain, experience_years, expertise, linkedin_profile, profile_completed) 
                VALUES (?, ?, ?, ?, ?, 1)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "isiss", $user_id, $business_domain, $experience_years, $expertise, $linkedin_profile);
        
        if (mysqli_stmt_execute($stmt)) {
            $success = "Profile created successfully!";
            $profile = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM agents WHERE user_id = $user_id"));
        } else {
            $error = "Failed to create profile!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Agent Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .profile-card { max-width: 800px; margin: 40px auto; }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container py-5">
    <div class="profile-card card shadow">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0"><i class="fas fa-user-edit"></i> My Agent Profile</h4>
        </div>
        <div class="card-body p-5">

            <?php if($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>
            <?php if($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Business Domain / Expertise Area <span class="text-danger">*</span></label>
                        <input type="text" name="business_domain" class="form-control" 
                               value="<?= htmlspecialchars($profile['business_domain'] ?? '') ?>" required 
                               placeholder="e.g. Fintech, E-commerce, HealthTech">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Years of Experience</label>
                        <input type="number" name="experience_years" class="form-control" 
                               value="<?= $profile['experience_years'] ?? '' ?>" min="0" placeholder="e.g. 5">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Your Expertise / Specializations</label>
                    <textarea name="expertise" class="form-control" rows="4" placeholder="Describe your skills, past businesses, achievements..."><?= htmlspecialchars($profile['expertise'] ?? '') ?></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label">LinkedIn Profile (Optional)</label>
                    <input type="url" name="linkedin_profile" class="form-control" 
                           value="<?= htmlspecialchars($profile['linkedin_profile'] ?? '') ?>" 
                           placeholder="https://linkedin.com/in/yourprofile">
                </div>

                <button type="submit" class="btn btn-success btn-lg px-5">
                    <?= $profile ? 'Update Profile' : 'Save Profile' ?>
                </button>
            </form>

        </div>
        <div class="card-footer text-muted">
            Note: You can update your profile anytime after saving.
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>