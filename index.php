<?php
require_once __DIR__ . '/config/session.php';

// Redirect to dashboard if logged in, otherwise to login
if (isLoggedIn()) {
    header('Location: /task-manager-system/dashboard.php');
} else {
    header('Location: /task-manager-system/login.php');
}
exit();
