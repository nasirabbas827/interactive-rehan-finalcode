<?php
// manage-resources.php
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'agent') {
    header("Location: login.php");
    exit;
}

$agent_id = $_SESSION['user_id'];
$success = '';
$error = '';

// ==================== CREATE & UPDATE ====================
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $category_id = (int)$_POST['category_id'];
    $title = mysqli_real_escape_string($conn, trim($_POST['title']));
    $content_type = mysqli_real_escape_string($conn, $_POST['content_type']);
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));
    
    $file_path = "";

    // Handle File Upload
    if (isset($_FILES['resource_file']) && $_FILES['resource_file']['error'] == 0) {
        $target_dir = "uploads/";
        
        // Create directory if not exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name = time() . '_' . basename($_FILES['resource_file']['name']);
        $target_file = $target_dir . $file_name;
        $file_ext = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Allow only certain file types
        $allowed_types = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png'];

        if (in_array($file_ext, $allowed_types)) {
            if (move_uploaded_file($_FILES['resource_file']['tmp_name'], $target_file)) {
                $file_path = $target_file;
            } else {
                $error = "Failed to upload file!";
            }
        } else {
            $error = "Only PDF, DOC, DOCX, PPT, JPG, PNG files are allowed!";
        }
    }

    // If editing and no new file uploaded, keep old file
    if ($id > 0 && empty($file_path)) {
        $old = mysqli_query($conn, "SELECT file_path FROM resources WHERE id = $id AND agent_id = $agent_id");
        if ($old_row = mysqli_fetch_assoc($old)) {
            $file_path = $old_row['file_path'];
        }
    }

    if (empty($error)) {
        if ($id > 0) {
            // UPDATE
            $sql = "UPDATE resources SET category_id=?, title=?, content_type=?, description=?, file_path=? 
                    WHERE id=? AND agent_id=?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "isssssi", $category_id, $title, $content_type, $description, $file_path, $id, $agent_id);
        } else {
            // INSERT
            $sql = "INSERT INTO resources (agent_id, category_id, title, content_type, description, file_path) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "iissss", $agent_id, $category_id, $title, $content_type, $description, $file_path);
        }

        if (mysqli_stmt_execute($stmt)) {
            $success = $id > 0 ? "Resource updated successfully!" : "Resource added successfully!";
        } else {
            $error = "Database operation failed!";
        }
    }
}

// ==================== DELETE ====================
if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    
    // Optional: Delete physical file
    $file_query = mysqli_query($conn, "SELECT file_path FROM resources WHERE id = $delete_id AND agent_id = $agent_id");
    if ($file_row = mysqli_fetch_assoc($file_query)) {
        if (!empty($file_row['file_path']) && file_exists($file_row['file_path'])) {
            unlink($file_row['file_path']);
        }
    }

    $sql = "DELETE FROM resources WHERE id = ? AND agent_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $delete_id, $agent_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $success = "Resource deleted successfully!";
    }
}

// Fetch Agent's Resources
$resources = mysqli_query($conn, "SELECT r.*, c.category_name 
                                  FROM resources r 
                                  LEFT JOIN categories c ON r.category_id = c.id 
                                  WHERE r.agent_id = $agent_id 
                                  ORDER BY r.created_at DESC");

// Fetch for Edit
$edit = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $edit_query = mysqli_query($conn, "SELECT * FROM resources WHERE id = $edit_id AND agent_id = $agent_id");
    $edit = mysqli_fetch_assoc($edit_query);
}

// Fetch Categories for Dropdown
$categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY category_name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Resources - Agent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .card { box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .file-name { font-size: 0.9rem; color: #666; }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container py-5">
    <h1 class="text-center mb-4">Manage My Resources</h1>

    <?php if($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
    <?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

    <!-- Add / Edit Form -->
    <div class="card mb-5">
        <div class="card-header bg-success text-white">
            <h5><?= $edit ? 'Edit Resource' : 'Add New Resource' ?></h5>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Category <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            <?php 
                            mysqli_data_seek($categories, 0);
                            while($cat = mysqli_fetch_assoc($categories)): ?>
                                <option value="<?= $cat['id'] ?>" <?= ($edit['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['category_name']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Content Type <span class="text-danger">*</span></label>
                        <select name="content_type" class="form-select" required>
                            <option value="idea" <?= ($edit['content_type'] ?? '') == 'idea' ? 'selected' : '' ?>>Business Idea</option>
                            <option value="framework" <?= ($edit['content_type'] ?? '') == 'framework' ? 'selected' : '' ?>>Framework</option>
                            <option value="template" <?= ($edit['content_type'] ?? '') == 'template' ? 'selected' : '' ?>>Template</option>
                            <option value="startup_plan" <?= ($edit['content_type'] ?? '') == 'startup_plan' ? 'selected' : '' ?>>Startup Plan</option>
                            <option value="financial_support" <?= ($edit['content_type'] ?? '') == 'financial_support' ? 'selected' : '' ?>>Financial Support</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($edit['title'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($edit['description'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label>Upload File (PDF, DOC, DOCX, JPG, PNG)</label>
                    <input type="file" name="resource_file" class="form-control">
                    
                    <?php if($edit && !empty($edit['file_path'])): ?>
                        <div class="mt-2 file-name">
                            Current File: <strong><?= basename($edit['file_path']) ?></strong><br>
                            <small class="text-muted">Upload new file to replace</small>
                        </div>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-success btn-lg">
                    <?= $edit ? 'Update Resource' : 'Upload Resource' ?>
                </button>
                <?php if($edit): ?>
                    <a href="manage-resources.php" class="btn btn-secondary btn-lg">Cancel</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Resources List -->
    <div class="card">
        <div class="card-header bg-dark text-white">
            <h5>My Uploaded Resources (<?= mysqli_num_rows($resources) ?>)</h5>
        </div>
        <div class="card-body p-0">
            <?php if (mysqli_num_rows($resources) > 0): ?>
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Type</th>
                            <th>File</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($resources)): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($row['title']) ?></strong></td>
                            <td><?= htmlspecialchars($row['category_name'] ?? 'Uncategorized') ?></td>
                            <td><span class="badge bg-info"><?= strtoupper($row['content_type']) ?></span></td>
                            <td>
                                <?php if(!empty($row['file_path'])): ?>
                                    <a href="<?= $row['file_path'] ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download"></i> View
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">No file</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d M, Y', strtotime($row['created_at'])) ?></td>
                            <td>
                                <a href="manage-resources.php?edit=<?= $row['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="manage-resources.php?delete=<?= $row['id'] ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Are you sure you want to delete this resource?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="p-5 text-center text-muted">
                    <i class="fas fa-folder-open fa-3x mb-3"></i>
                    <p>You have not uploaded any resources yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>