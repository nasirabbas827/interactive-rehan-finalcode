<?php
include 'config.php';

// Fetch approved agents by joining users and agents tables
$query = "SELECT u.id, u.full_name, u.location, u.bio, a.business_domain, a.expertise, a.experience_years 
          FROM users u 
          INNER JOIN agents a ON u.id = a.user_id 
          WHERE u.role = 'agent' AND u.status = 'approved'
          ORDER BY u.created_at DESC";

$mentors = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Mentors - EntrePortal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; }
        .mentor-card {
            border: none;
            border-radius: 15px;
            transition: 0.3s;
            overflow: hidden;
        }
        .mentor-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        .mentor-img-wrapper {
            width: 100px;
            height: 100px;
            background: #e9ecef;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            color: #28a745;
            border: 3px solid #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .expertise-badge {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
            font-size: 0.75rem;
            border-radius: 20px;
            padding: 5px 12px;
            margin: 2px;
            display: inline-block;
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<section class="py-5 bg-success text-white text-center mb-5">
    <div class="container">
        <h1 class="fw-bold">Expert Mentors & Agents</h1>
        <p class="lead">Connect with industry leaders to scale your business ideas.</p>
    </div>
</section>

<div class="container mb-5">
    <div class="row g-4">
        <?php if(mysqli_num_rows($mentors) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($mentors)): ?>
            <div class="col-lg-4 col-md-6">
                <div class="card mentor-card h-100 shadow-sm text-center p-4">
                    <div class="mentor-img-wrapper">
                        <i class="fas fa-user-tie fa-3x"></i>
                    </div>
                    
                    <h5 class="fw-bold mb-1"><?= htmlspecialchars($row['full_name']) ?></h5>
                    <p class="text-success small fw-bold mb-3"><?= htmlspecialchars($row['business_domain']) ?></p>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block mb-2">
                            <i class="fas fa-briefcase me-1"></i> <?= $row['experience_years'] ?> Years Experience
                        </small>
                        <small class="text-muted d-block">
                            <i class="fas fa-map-marker-alt me-1"></i> <?= htmlspecialchars($row['location'] ?: 'Remote') ?>
                        </small>
                    </div>

                    <div class="mb-4">
                        <?php 
                        $tags = explode(',', $row['expertise']);
                        foreach(array_slice($tags, 0, 3) as $tag): if(!empty($tag)): ?>
                            <span class="expertise-badge"><?= htmlspecialchars(trim($tag)) ?></span>
                        <?php endif; endforeach; ?>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="view-profile.php?id=<?= $row['id'] ?>" class="btn btn-success rounded-pill">
                            View Profile
                        </a>
                         
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <i class="fas fa-users-slash fa-4x text-muted mb-3"></i>
                <h3>No mentors available yet.</h3>
                <p class="text-muted">Check back later or join as an agent yourself!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>