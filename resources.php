<?php
include 'config.php';

// Get filter parameters from URL
$type_filter = isset($_GET['type']) ? mysqli_real_escape_string($conn, $_GET['type']) : '';
$cat_filter = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Build the SQL Query
$sql = "SELECT r.*, c.category_name, u.full_name as agent_name 
        FROM resources r 
        LEFT JOIN categories c ON r.category_id = c.id 
        LEFT JOIN users u ON r.agent_id = u.id 
        WHERE 1=1";

if ($type_filter) {
    $sql .= " AND r.content_type = '$type_filter'";
}
if ($cat_filter > 0) {
    $sql .= " AND r.category_id = $cat_filter";
}
if ($search_query) {
    $sql .= " AND (r.title LIKE '%$search_query%' OR r.description LIKE '%$search_query%')";
}

$sql .= " ORDER BY r.created_at DESC";
$resources = mysqli_query($conn, $sql);

// Fetch all categories for the sidebar
$categories_list = mysqli_query($conn, "SELECT * FROM categories");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resources - EntrePortal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; }
        .resource-card { border: none; border-radius: 12px; transition: 0.3s; }
        .resource-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .badge-type { position: absolute; top: 15px; right: 15px; }
        .sidebar-item { border-radius: 8px; margin-bottom: 5px; transition: 0.2s; }
        .sidebar-item:hover { background: #e9ecef; }
        .active-cat { background: #28a745 !important; color: white !important; }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm border-0 p-3">
                <h5 class="fw-bold mb-3"><i class="fas fa-filter text-success me-2"></i> Filters</h5>
                
                <form action="resources.php" method="GET">
                    <div class="mb-4">
                        <label class="small fw-bold text-muted text-uppercase">Search</label>
                        <div class="input-group">
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Keywords..." value="<?= htmlspecialchars($search_query) ?>">
                            <button class="btn btn-success btn-sm" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="small fw-bold text-muted text-uppercase">Content Type</label>
                        <select name="type" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">All Types</option>
                            <option value="idea" <?= $type_filter == 'idea' ? 'selected' : '' ?>>Business Ideas</option>
                            <option value="template" <?= $type_filter == 'template' ? 'selected' : '' ?>>Templates</option>
                            <option value="framework" <?= $type_filter == 'framework' ? 'selected' : '' ?>>Frameworks</option>
                            <option value="startup_plan" <?= $type_filter == 'startup_plan' ? 'selected' : '' ?>>Startup Plans</option>
                        </select>
                    </div>
                </form>

                <label class="small fw-bold text-muted text-uppercase mb-2">Categories</label>
                <div class="list-group list-group-flush">
                    <a href="resources.php" class="list-group-item list-group-item-action sidebar-item <?= $cat_filter == 0 ? 'active-cat' : '' ?>">All Categories</a>
                    <?php while($cat = mysqli_fetch_assoc($categories_list)): ?>
                        <a href="resources.php?category=<?= $cat['id'] ?>&type=<?= $type_filter ?>" 
                           class="list-group-item list-group-item-action sidebar-item <?= $cat_filter == $cat['id'] ? 'active-cat' : '' ?>">
                            <?= htmlspecialchars($cat['category_name']) ?>
                        </a>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0">
                    <?php 
                        if($type_filter == 'idea') echo "Business Ideas";
                        elseif($type_filter == 'template') echo "Startup Templates";
                        else echo "Entrepreneurial Resources";
                    ?>
                </h3>
                <span class="text-muted small"><?= mysqli_num_rows($resources) ?> items found</span>
            </div>

            <div class="row g-4">
                <?php if(mysqli_num_rows($resources) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($resources)): 
                        // Map icons to content types
                        $type_icon = "fa-file";
                        if($row['content_type'] == 'idea') $type_icon = "fa-lightbulb";
                        if($row['content_type'] == 'template') $type_icon = "fa-file-invoice";
                        if($row['content_type'] == 'framework') $type_icon = "fa-project-diagram";
                    ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm resource-card">
                            <div class="card-body p-4">
                                <span class="badge bg-light text-success border border-success mb-3">
                                    <i class="fas <?= $type_icon ?> me-1"></i> <?= ucfirst(str_replace('_', ' ', $row['content_type'])) ?>
                                </span>
                                <h5 class="card-title fw-bold"><?= htmlspecialchars($row['title']) ?></h5>
                                <p class="card-text text-muted small">
                                    <?= htmlspecialchars(substr($row['description'], 0, 120)) ?>...
                                </p>
                                
                                <div class="d-flex align-items-center mt-3 pt-3 border-top">
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block">Posted by:</small>
                                        <small class="fw-bold"><?= htmlspecialchars($row['agent_name']) ?></small>
                                    </div>
                                    <a href="view-resource.php?id=<?= $row['id'] ?>" class="btn btn-success btn-sm rounded-pill px-3">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No resources found matching your criteria.</p>
                        <a href="resources.php" class="btn btn-outline-success btn-sm">Clear All Filters</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>