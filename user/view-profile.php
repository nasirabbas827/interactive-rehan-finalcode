<?php
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch Mentor Details
$query = "SELECT u.*, a.id as agent_table_id, a.business_domain, a.experience_years, a.expertise, a.linkedin_profile 
          FROM users u 
          JOIN agents a ON u.id = a.user_id 
          WHERE u.id = $id AND u.role = 'agent'";
$result = mysqli_query($conn, $query);
$mentor = mysqli_fetch_assoc($result);

if (!$mentor) {
    die("Mentor profile not found.");
}

// Fetch Feedback for this Mentor
$agent_id = $mentor['agent_table_id'];
$feedback_query = mysqli_query($conn, "SELECT f.*, u.full_name 
                                       FROM feedback f 
                                       JOIN users u ON f.user_id = u.id 
                                       WHERE f.agent_id = $agent_id 
                                       ORDER BY f.created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($mentor['full_name']) ?> - Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .profile-header { background: #343a40; color: white; padding: 80px 0 120px 0; }
        .profile-card { margin-top: -80px; border-radius: 15px; overflow: hidden; border: none; }
        .stat-box { border-right: 1px solid #eee; }
        .stat-box:last-child { border-right: none; }
        .star-rating { color: #ffc107; }
        .feedback-item { border-bottom: 1px solid #eee; padding: 15px 0; }
        .feedback-item:last-child { border-bottom: none; }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="profile-header text-center">
    <div class="container">
        <h1>Mentor Profile</h1>
    </div>
</div>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            
            <?php if(isset($_GET['msg']) && $_GET['msg'] == 'feedback_success'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Thank you! Your feedback has been submitted.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow profile-card">
                <div class="card-body p-0">
                    <div class="p-4 text-center bg-white">
                        <h2 class="fw-bold mb-1"><?= htmlspecialchars($mentor['full_name']) ?></h2>
                        <p class="text-success fs-5 mb-3"><?= htmlspecialchars($mentor['business_domain']) ?></p>
                        
                        <div class="d-flex justify-content-center border-top border-bottom py-3 mb-4">
                            <div class="px-4 stat-box">
                                <strong><?= $mentor['experience_years'] ?>+</strong>
                                <div class="small text-muted">Years Exp.</div>
                            </div>
                            <div class="px-4 stat-box">
                                <strong><?= htmlspecialchars($mentor['location'] ?? 'Remote') ?></strong>
                                <div class="small text-muted">Location</div>
                            </div>
                        </div>

                        <div class="text-start px-md-5">
                            <h5 class="fw-bold"><i class="fas fa-user-tag text-success"></i> Expertise</h5>
                            <p><?= nl2br(htmlspecialchars($mentor['expertise'])) ?></p>

                            <h5 class="fw-bold mt-4"><i class="fas fa-info-circle text-success"></i> Bio</h5>
                            <p class="text-muted"><?= nl2br(htmlspecialchars($mentor['bio'])) ?></p>
                        </div>

                        <div class="mt-5 pb-4">
                            <button type="button" class="btn btn-primary px-4 me-2" data-bs-toggle="modal" data-bs-target="#messageModal">
                                <i class="fas fa-paper-plane"></i> Send Message
                            </button>

                            <button type="button" class="btn btn-warning px-4 me-2" data-bs-toggle="modal" data-bs-target="#feedbackModal">
                                <i class="fas fa-star"></i> Give Feedback
                            </button>

                            <a href="mailto:<?= $mentor['email'] ?>" class="btn btn-success px-4">
                                <i class="fas fa-envelope"></i> Email
                            </a>
                            
                            <?php if(!empty($mentor['linkedin_profile'])): ?>
                                <a href="<?= htmlspecialchars($mentor['linkedin_profile']) ?>" target="_blank" class="btn btn-outline-primary px-4 ms-2">
                                    <i class="fab fa-linkedin"></i> LinkedIn
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow border-0 mt-4 rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 border-bottom pb-2">User Reviews</h5>
                    <?php if(mysqli_num_rows($feedback_query) > 0): ?>
                        <?php while($f = mysqli_fetch_assoc($feedback_query)): ?>
                            <div class="feedback-item">
                                <div class="d-flex justify-content-between">
                                    <strong><?= htmlspecialchars($f['full_name']) ?></strong>
                                    <div class="star-rating">
                                        <?php for($i=1; $i<=5; $i++) echo ($i <= $f['rating']) ? '★' : '☆'; ?>
                                    </div>
                                </div>
                                <p class="text-muted mt-2 mb-1"><?= nl2br(htmlspecialchars($f['message'])) ?></p>
                                <small class="text-muted" style="font-size: 0.75rem;"><?= date('F j, Y', strtotime($f['created_at'])) ?></small>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-muted text-center py-3">No reviews yet. Be the first to give feedback!</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <a href="dashboard-user.php" class="btn btn-link text-muted text-decoration-none">
                    <i class="fas fa-chevron-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="messageModal" tabindex="-1">
  <div class="modal-dialog">
    <form action="send-message-process.php" method="POST">
        <div class="modal-content text-start">
          <div class="modal-header">
            <h5 class="modal-title">Message to <?= htmlspecialchars($mentor['full_name']) ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="receiver_id" value="<?= $id ?>">
            <div class="mb-3">
                <label class="form-label">Your Message</label>
                <textarea name="message" class="form-control" rows="4" placeholder="Type your message here..." required></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="send_msg" class="btn btn-success">Send Message</button>
          </div>
        </div>
    </form>
  </div>
</div>

<div class="modal fade" id="feedbackModal" tabindex="-1">
  <div class="modal-dialog">
    <form action="submit-feedback.php" method="POST">
        <div class="modal-content text-start">
          <div class="modal-header">
            <h5 class="modal-title">Rate Your Mentor</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="mentor_user_id" value="<?= $id ?>">
            <div class="mb-3">
                <label class="form-label">Rating</label>
                <select name="rating" class="form-select" required>
                    <option value="5">5 - Excellent</option>
                    <option value="4">4 - Very Good</option>
                    <option value="3">3 - Good</option>
                    <option value="2">2 - Fair</option>
                    <option value="1">1 - Poor</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Your Experience</label>
                <textarea name="message" class="form-control" rows="4" placeholder="How was your session?"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="submit_feedback" class="btn btn-warning">Submit Feedback</button>
          </div>
        </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?php include 'footer.php'; ?>
</body>
</html>