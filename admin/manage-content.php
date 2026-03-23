<?php
// manage-content.php
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$success = '';
$error = '';

// ==================== CREATE & UPDATE ====================
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $title = mysqli_real_escape_string($conn, trim($_POST['title']));
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));
    $type = mysqli_real_escape_string($conn, $_POST['type']);

    if ($id > 0) {
        // UPDATE
        $sql = "UPDATE content SET title=?, description=?, type=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssi", $title, $description, $type, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            $success = "Content updated successfully!";
        } else {
            $error = "Failed to update content!";
        }
    } else {
        // CREATE (INSERT)
        $sql = "INSERT INTO content (title, description, type) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $title, $description, $type);
        
        if (mysqli_stmt_execute($stmt)) {
            $success = "New content added successfully!";
        } else {
            $error = "Failed to add content!";
        }
    }
}

// ==================== DELETE ====================
if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    $sql = "DELETE FROM content WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $delete_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $success = "Content deleted successfully!";
    } else {
        $error = "Failed to delete content!";
    }
}

// ==================== FETCH ALL CONTENT ====================
$result = mysqli_query($conn, "SELECT * FROM content ORDER BY created_at DESC");

// For Edit - Fetch single record
$edit = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $edit_query = mysqli_query($conn, "SELECT * FROM content WHERE id = $edit_id");
    $edit = mysqli_fetch_assoc($edit_query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Content - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .page-header { background: linear-gradient(135deg, #212529, #343a40); color: white; padding: 2rem 0; }
        .card { box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="page-header text-center">
    <div class="container">
        <h1 class="display-5 fw-bold"><i class="fas fa-file-alt"></i> Manage Content</h1>
        <p class="lead">Create, Read, Update & Delete Tutorials and Events</p>
    </div>
</div>

<div class="container py-5">

    <?php if($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <!-- Add / Edit Form -->
    <div class="card mb-5">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><?= $edit ? 'Edit Content' : 'Add New Content' ?></h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">

                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" 
                               value="<?= htmlspecialchars($edit['title'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select" required>
                            <option value="tutorial" <?= ($edit['type'] ?? '') == 'tutorial' ? 'selected' : '' ?>>Tutorial</option>
                            <option value="event" <?= ($edit['type'] ?? '') == 'event' ? 'selected' : '' ?>>Event</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="5" required><?= htmlspecialchars($edit['description'] ?? '') ?></textarea>
                </div>

                <button type="submit" class="btn btn-success btn-lg">
                    <?= $edit ? 'Update Content' : 'Add Content' ?>
                </button>
                
                <?php if($edit): ?>
                    <a href="manage-content.php" class="btn btn-secondary btn-lg">Cancel</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Content List -->
    <div class="card">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">All Content (Tutorials & Events)</h5>
        </div>
        <div class="card-body p-0">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Created At</th>
                                <th width="160px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($row['title']) ?></strong></td>
                                <td>
                                    <span class="badge <?= $row['type']=='tutorial' ? 'bg-info' : 'bg-warning' ?>">
                                        <?= strtoupper($row['type']) ?>
                                    </span>
                                </td>
                                <td><?= substr(htmlspecialchars($row['description']), 0, 100) ?>...</td>
                                <td><?= date('d M, Y h:i A', strtotime($row['created_at'])) ?></td>
                                <td>
                                    <a href="manage-content.php?edit=<?= $row['id'] ?>" 
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="manage-content.php?delete=<?= $row['id'] ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Are you sure you want to delete this content?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="p-5 text-center text-muted">
                    <i class="fas fa-folder-open fa-3x mb-3"></i>
                    <h5>No content found. Add some content above.</h5>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>