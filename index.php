<?php
include 'config.php';

// 1. Fetch Dynamic Categories from database
$cat_query = "SELECT * FROM categories LIMIT 4";
$categories = mysqli_query($conn, $cat_query);

// 2. Fetch Featured Business Ideas (Resources with content_type 'idea')
// Joining with users to get the Agent's name if needed
$idea_query = "SELECT r.*, c.category_name 
               FROM resources r 
               LEFT JOIN categories c ON r.category_id = c.id 
               WHERE r.content_type = 'idea' 
               ORDER BY r.created_at DESC LIMIT 3";
$featured_ideas = mysqli_query($conn, $idea_query);

// 3. Fetch Total User Count for Call to Action
$user_count_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
$user_data = mysqli_fetch_assoc($user_count_query);
$total_users = $user_data['total'] + 12000; // Adding a base number for marketing effect
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrepreneur Portal - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #28a745;
            --dark: #212529;
        }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        }
        .hero {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
            height: 80vh;
            color: white;
            display: flex;
            align-items: center;
        }
        .navbar-brand {
            font-weight: 700;
            color: var(--primary) !important;
        }
        .card-hover {
            transition: all 0.3s;
            border-radius: 15px;
            overflow: hidden;
        }
        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(40,167,69,0.2);
        }
        .section-title {
            position: relative;
            display: inline-block;
            font-weight: 700;
        }
        .section-title:after {
            content: '';
            position: absolute;
            width: 60%;
            height: 3px;
            background: var(--primary);
            bottom: -8px;
            left: 20%;
        }
        .category-icon {
            width: 70px;
            height: 70px;
            background: rgba(40,167,69,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 auto 15px;
            color: var(--primary);
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<section class="hero text-center">
    <div class="container">
        <h1 class="display-3 fw-bold mb-4">Turn Your Idea Into Reality</h1>
        <p class="lead mb-5 fs-4">The complete platform for aspiring entrepreneurs.<br>Mentorship • Resources • Community • Funding</p>
        <?php if(!isset($_SESSION['user_id'])): ?>
            <a href="register.php" class="btn btn-success btn-lg px-5 py-3 me-3 shadow">Start Free Journey</a>
            <a href="login.php" class="btn btn-outline-light btn-lg px-5 py-3">Login to Portal</a>
        <?php else: ?>
            <a href="dashboard-user.php" class="btn btn-success btn-lg px-5 py-3 shadow">Go to My Dashboard</a>
        <?php endif; ?>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Explore Business Categories</h2>
        </div>
        <div class="row g-4">
            <?php 
            if(mysqli_num_rows($categories) > 0) {
                while($cat = mysqli_fetch_assoc($categories)) {
                    // Logic to assign icons based on name (optional)
                    $icon = "fa-briefcase";
                    if(stripos($cat['category_name'], 'tech') !== false) $icon = "fa-laptop";
                    if(stripos($cat['category_name'], 'health') !== false) $icon = "fa-heartbeat";
                    if(stripos($cat['category_name'], 'finance') !== false) $icon = "fa-coins";
                    if(stripos($cat['category_name'], 'commerce') !== false) $icon = "fa-shopping-cart";
            ?>
            <div class="col-md-3 col-6">
                <a href="resources.php?category=<?= $cat['id'] ?>" class="text-decoration-none">
                    <div class="card card-hover h-100 text-center border-0 shadow-sm p-3">
                        <div class="card-body">
                            <div class="category-icon">
                                <i class="fas <?= $icon ?> fa-2x"></i>
                            </div>
                            <h5 class="text-dark"><?= htmlspecialchars($cat['category_name']) ?></h5>
                            <p class="text-muted small mb-0"><?= htmlspecialchars(substr($cat['description'], 0, 40)) ?>...</p>
                        </div>
                    </div>
                </a>
            </div>
            <?php 
                }
            } else {
                echo "<p class='text-center'>No categories found. Please add them in the admin panel.</p>";
            }
            ?>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Featured Business Ideas</h2>
        </div>
        <div class="row g-4">
            <?php if(mysqli_num_rows($featured_ideas) > 0): ?>
                <?php while($idea = mysqli_fetch_assoc($featured_ideas)): ?>
                <div class="col-md-4">
                    <div class="card card-hover h-100 shadow border-0">
                                              <div class="card-body">
                            <span class="badge bg-success mb-2"><?= $idea['category_name'] ?? 'General' ?></span>
                            <h5 class="card-title fw-bold"><?= htmlspecialchars($idea['title']) ?></h5>
                            <p class="card-text text-muted small"><?= htmlspecialchars(substr($idea['description'], 0, 100)) ?>...</p>
                            <a href="view-resource.php?id=<?= $idea['id'] ?>" class="btn btn-sm btn-outline-success rounded-pill px-3">Read Full Plan</a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted">
                    <p>New business ideas are coming soon!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="py-5 text-white bg-success text-center shadow-lg">
    <div class="container py-4">
        <h3 class="fw-bold">Join <?= number_format($total_users) ?>+ Aspiring Entrepreneurs</h3>
        <p class="lead opacity-75">Get instant access to mentors, templates, and funding opportunities.</p>
        <?php if(!isset($_SESSION['user_id'])): ?>
            <a href="register.php" class="btn btn-light btn-lg px-5 rounded-pill fw-bold text-success">Register Now – It's Free</a>
        <?php else: ?>
            <a href="forum.php" class="btn btn-light btn-lg px-5 rounded-pill fw-bold text-success">Go to Forum</a>
        <?php endif; ?>
    </div>
</section>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>