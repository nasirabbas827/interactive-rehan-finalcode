<?php
// navbar.php
include 'config.php';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow">
    <div class="container">
        <a class="navbar-brand fs-3" href="index.php">
            <i class="fas fa-rocket"></i> EntrePortal
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">

                <?php if (isset($_SESSION['user_id'])): 
                    $role = $_SESSION['role'] ?? 'user';
                    $dashboard_page = ($role == 'admin') ? 'dashboard-admin.php' : 
                                      ($role == 'agent' ? 'dashboard-agent.php' : 'dashboard-user.php');
                ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $dashboard_page; ?>">
                            <i class="fas fa-chart-line"></i> Dashboard
                        </a>
                    </li>

                    <?php if ($role == 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="manage-agents.php">
                            <i class="fas fa-user-shield"></i> Agents
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage-content.php">
                            <i class="fas fa-file-alt"></i> Content
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if ($role == 'agent'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="messages-agent.php">
                                <i class="fas fa-envelope-open-text"></i> Inquiries
                            </a>
                        </li>
                    <?php elseif ($role == 'user'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="messages-user.php">
                                <i class="fas fa-comments"></i> Messages
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link" href="forum.php">
                            <i class="fas fa-users"></i> Forum
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link text-success fw-bold" href="logout.php">
                            <i class="fas fa-sign-out-alt"></i> Logout (<?php echo htmlspecialchars($_SESSION['full_name']); ?>)
                        </a>
                    </li>

                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-success text-white px-4 ms-3" href="register.php">
                            Register
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<style>
    :root {
        --primary: #28a745;
        --dark: #212529;
    }

    .navbar-brand {
        font-weight: 700;
        color: var(--primary) !important;
    }
</style>