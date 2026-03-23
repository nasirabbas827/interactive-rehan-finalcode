<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$query = "SELECT r.*, c.category_name, u.full_name as agent_name, u.email as agent_email 
          FROM resources r 
          LEFT JOIN categories c ON r.category_id = c.id 
          LEFT JOIN users u ON r.agent_id = u.id 
          WHERE r.id = $id";
$result = mysqli_query($conn, $query);
$resource = mysqli_fetch_assoc($result);

if (!$resource) {
    die("Resource not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($resource['title']) ?> - Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .resource-header { background: linear-gradient(135deg, #28a745, #20c997); color: white; padding: 60px 0; }
        .content-card { margin-top: -50px; border-radius: 15px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="resource-header text-center">
    <div class="container">
        <span class="badge bg-light text-success mb-2"><?= strtoupper($resource['content_type']) ?></span>
        <h1><?= htmlspecialchars($resource['title']) ?></h1>
        <p><i class="fas fa-tag"></i> <?= htmlspecialchars($resource['category_name'] ?? 'General') ?></p>
    </div>
</div>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card content-card">
                <div class="card-body p-5">
                    <h3>Description</h3>
                    <hr>
                    <p class="lead text-muted" style="white-space: pre-wrap;"><?= htmlspecialchars($resource['description']) ?></p>
                    
                    <div class="mt-5 p-4 bg-light rounded d-flex justify-content-between align-items-center">
                        <div>
                            <h5>Shared by: <?= htmlspecialchars($resource['agent_name']) ?></h5>
                            <p class="mb-0 text-muted">Posted on: <?= date('M d, Y', strtotime($resource['created_at'])) ?></p>
                        </div>
                        <?php if(!empty($resource['file_path'])): ?>
                            <a href="../agent/<?= $resource['file_path'] ?>" class="btn btn-success btn-lg">
                                <i class="fas fa-download"></i> Download Resource
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mt-4 text-center">
                        <a href="dashboard-user.php" class="btn btn-link text-success">
                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>