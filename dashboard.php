<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/includes/Task.php';

requireLogin();

$task = new Task();
$userId = getUserId();

// Get filter and search parameters
$filter = $_GET['filter'] ?? 'all';
$search = $_GET['search'] ?? '';

// Get tasks
$tasks = $task->getUserTasks($userId, $filter, $search);
$stats = $task->getStats($userId);

// Handle AJAX requests for task operations
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    switch ($_POST['action']) {
        case 'toggle':
            $result = $task->toggleComplete($_POST['task_id'], $userId);
            echo json_encode($result);
            exit();
            
        case 'delete':
            $result = $task->deleteTask($_POST['task_id'], $userId);
            echo json_encode($result);
            exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/task-manager-system/assets/css/style.css">
</head>
<body class="dashboard">
    <!-- Navbar -->
    <nav class="navbar navbar-dark shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">
                <i class="bi bi-list-check"></i> Task Manager
            </a>
            <div class="d-flex align-items-center text-white">
                <span class="me-3"><i class="bi bi-person-circle"></i> <?= htmlspecialchars(getUsername()) ?></span>
                <a href="logout.php" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card stats-card total">
                    <div class="card-body">
                        <h6 class="text-muted">Total Tasks</h6>
                        <h2 class="mb-0"><?= $stats['total'] ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card stats-card completed">
                    <div class="card-body">
                        <h6 class="text-muted">Completed</h6>
                        <h2 class="mb-0 text-success"><?= $stats['completed'] ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card stats-card incomplete">
                    <div class="card-body">
                        <h6 class="text-muted">Incomplete</h6>
                        <h2 class="mb-0 text-warning"><?= $stats['incomplete'] ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Bar -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6 mb-2 mb-md-0">
                        <a href="add_task.php" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Add New Task
                        </a>
                    </div>
                    <div class="col-md-6">
                        <form method="GET" action="" class="d-flex">
                            <input type="text" name="search" class="form-control me-2" 
                                   placeholder="Search tasks..." value="<?= htmlspecialchars($search) ?>">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <ul class="nav nav-pills mb-4">
            <li class="nav-item">
                <a class="nav-link <?= $filter === 'all' ? 'active' : '' ?>" href="?filter=all">
                    All Tasks
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $filter === 'incomplete' ? 'active' : '' ?>" href="?filter=incomplete">
                    Incomplete
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $filter === 'completed' ? 'active' : '' ?>" href="?filter=completed">
                    Completed
                </a>
            </li>
        </ul>

        <!-- Tasks List -->
        <div class="row" id="tasksList">
            <?php if (empty($tasks)): ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle"></i> 
                        <?php if (!empty($search)): ?>
                            No tasks found matching your search.
                        <?php else: ?>
                            No tasks yet. Click "Add New Task" to get started!
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($tasks as $taskItem): ?>
                    <div class="col-md-6 col-lg-4 mb-3" data-task-id="<?= $taskItem['id'] ?>">
                        <div class="card task-card <?= $taskItem['is_completed'] ? 'completed' : '' ?>">
                            <div class="card-body">
                                <h5 class="card-title task-title"><?= htmlspecialchars($taskItem['title']) ?></h5>
                                <?php if (!empty($taskItem['description'])): ?>
                                    <p class="card-text text-muted"><?= nl2br(htmlspecialchars($taskItem['description'])) ?></p>
                                <?php endif; ?>
                                <small class="text-muted d-block mb-2">
                                    <i class="bi bi-calendar"></i> <?= date('M j, Y', strtotime($taskItem['created_at'])) ?>
                                </small>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm <?= $taskItem['is_completed'] ? 'btn-warning' : 'btn-success' ?> btn-action toggle-btn"
                                            data-task-id="<?= $taskItem['id'] ?>">
                                        <i class="bi bi-check-circle"></i> 
                                        <?= $taskItem['is_completed'] ? 'Undo' : 'Complete' ?>
                                    </button>
                                    <a href="edit_task.php?id=<?= $taskItem['id'] ?>" class="btn btn-sm btn-primary btn-action">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <button class="btn btn-sm btn-danger btn-action delete-btn"
                                            data-task-id="<?= $taskItem['id'] ?>">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle task completion
        document.querySelectorAll('.toggle-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const taskId = this.dataset.taskId;
                const formData = new FormData();
                formData.append('action', 'toggle');
                formData.append('task_id', taskId);
                
                fetch('dashboard.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                });
            });
        });

        // Delete task
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if (!confirm('Are you sure you want to delete this task?')) {
                    return;
                }
                
                const taskId = this.dataset.taskId;
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('task_id', taskId);
                
                fetch('dashboard.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                });
            });
        });
    </script>
</body>
</html>
