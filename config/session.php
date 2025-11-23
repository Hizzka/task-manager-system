<?php
/**
 * Session Configuration
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is logged in
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Require login - redirect to login page if not logged in
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /task-manager-system/login.php');
        exit();
    }
}

/**
 * Get current user ID
 * @return int|null
 */
function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current username
 * @return string|null
 */
function getUsername() {
    return $_SESSION['username'] ?? null;
}

/**
 * Set user session after login
 */
function setUserSession($userId, $username) {
    $_SESSION['user_id'] = $userId;
    $_SESSION['username'] = $username;
}

/**
 * Destroy user session (logout)
 */
function destroyUserSession() {
    session_unset();
    session_destroy();
}
