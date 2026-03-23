<?php
// dashboard-user.php
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// ==================== SEARCH LOGIC ====================
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, trim($_GET['search'])) : '';
$category_filter = isset($_GET['category']) ? (int)$_GET['category'] : 0;

// Fetch Categories for Dropdown
$categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY category_name");

// Fetch Resources (with search and category filter)
$resource_sql = "SELECT r.*, c.category_name, u.full_name as agent_name 
                 FROM resources r 
                 LEFT JOIN categories c ON r.category_id = c.id 
                 LEFT JOIN users u ON r.agent_id = u.id 
                 WHERE 1=1";

if (!empty($search)) {
    $resource_sql .= " AND (r.title LIKE '%$search%' OR r.description LIKE '%$search%')";
}
if ($category_filter > 0) {
    $resource_sql .= " AND r.category_id = $category_filter";
}

$resource_sql .= " ORDER BY r.created_at DESC";
$resources = mysqli_query($conn, $resource_sql);

// Fetch Approved Agents/Mentors
$agents = mysqli_query($conn, "SELECT u.id AS mentor_user_id, u.full_name, u.email, u.phone, u.bio, u.location, 
                                      a.business_domain, a.experience_years, a.expertise, a.linkedin_profile 
                               FROM users u 
                               JOIN agents a ON u.id = a.user_id 
                               WHERE u.role = 'agent' AND u.status = 'approved' 
                               ORDER BY a.created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Entrepreneur Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .dash-header { background: linear-gradient(135deg, #28a745, #20c997); color: white; }
        .card-hover:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .search-box { max-width: 700px; margin: 0 auto; }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="dash-header py-5 text-center">
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</h1>
        <p class="lead">Your Journey to Entrepreneurship Starts Here</p>
    </div>
</div>

<div class="container py-5">

    <!-- Search Bar -->
    <div class="search-box mb-5">
        <form method="GET" class="input-group">
            <input type="text" name="search" class="form-control form-control-lg" 
                   placeholder="Search business ideas, templates, mentors..." 
                   value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-success btn-lg" type="submit">
                <i class="fas fa-search"></i> Search
            </button>
        </form>
    </div>

    <div class="row">
        
        <!-- Left Side: Resources -->
        <div class="col-lg-8">
            <h3 class="mb-4">
                <i class="fas fa-folder-open"></i> Available Resources
            </h3>

            <?php if (mysqli_num_rows($resources) > 0): ?>
                <div class="row g-4">
                    <?php while($row = mysqli_fetch_assoc($resources)): ?>
                    <div class="col-md-6">
                        <div class="card h-100 card-hover">
                            <div class="card-body">
                                <span class="badge bg-success mb-2"><?= strtoupper($row['content_type']) ?></span>
                                <?php if($row['category_name']): ?>
                                    <span class="badge bg-secondary"><?= htmlspecialchars($row['category_name']) ?></span>
                                <?php endif; ?>
                                
                                <h5 class="card-title mt-3"><?= htmlspecialchars($row['title']) ?></h5>
                                <p class="card-text text-muted"><?= substr(htmlspecialchars($row['description']), 0, 120) ?>...</p>
                                
                                <small class="text-muted">By: <?= htmlspecialchars($row['agent_name'] ?? 'Unknown') ?></small>
                            </div>
                            <div class="card-footer bg-white">
                                <?php if(!empty($row['file_path'])): ?>
                                    <a href="../agent/<?= $row['file_path'] ?>" target="_blank" class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                <?php endif; ?>
                                <a href="view-resource.php?id=<?= $row['id'] ?>" class="btn btn-success btn-sm float-end">View Details</a>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center py-5">
                    <i class="fas fa-search fa-3x mb-3"></i>
                    <h5>No resources found matching your search.</h5>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right Side: Mentors + Category Filter -->
        <div class="col-lg-4">
            
            <!-- Category Filter -->
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5><i class="fas fa-filter"></i> Filter by Category</h5>
                </div>
                <div class="card-body">
                    <form method="GET">
                        <select name="category" class="form-select" onchange="this.form.submit()">
                            <option value="0">All Categories</option>
                            <?php 
                            mysqli_data_seek($categories, 0);
                            while($cat = mysqli_fetch_assoc($categories)): ?>
                                <option value="<?= $cat['id'] ?>" <?= $category_filter == $cat['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['category_name']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Business Mentors -->
<div class="card shadow">
    <div class="card-header bg-dark text-white">
        <h5><i class="fas fa-user-tie"></i> Business Mentors</h5>
    </div>
    <div class="card-body">
        <?php if (mysqli_num_rows($agents) > 0): ?>
            <?php while($mentor = mysqli_fetch_assoc($agents)): ?>
            <div class="border-bottom pb-3 mb-3">
                <h6 class="mb-1"><?php echo htmlspecialchars($mentor['full_name']); ?></h6>
                <p class="text-success small mb-1"><strong><?php echo htmlspecialchars($mentor['business_domain']); ?></strong></p>
                <p class="small text-muted mb-2">
                    <?php echo $mentor['experience_years'] ? htmlspecialchars($mentor['experience_years']).' years experience' : ''; ?>
                </p>
                <p class="small"><?php echo substr(htmlspecialchars($mentor['expertise'] ?? $mentor['bio']), 0, 100); ?>...</p>
                
                <a href="view-profile.php?id=<?php echo $mentor['mentor_user_id']; ?>" class="btn btn-outline-success btn-sm">
                    View Full Profile
                </a>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-muted">No approved mentors available yet.</p>
        <?php endif; ?>
    </div>
</div>

        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

