<?php
require_once __DIR__ . '/../config/database.php';

class Task {
    private $pdo;
    
    public function __construct() {
        $this->pdo = getDBConnection();
    }
    
    /**
     * Get all tasks for a user
     * @param int $userId
     * @param string $filter ('all', 'completed', 'incomplete')
     * @param string $search
     * @return array
     */
    public function getUserTasks($userId, $filter = 'all', $search = '') {
        $sql = "SELECT * FROM tasks WHERE user_id = ?";
        $params = [$userId];
        
        // Apply filter
        if ($filter === 'completed') {
            $sql .= " AND is_completed = 1";
        } elseif ($filter === 'incomplete') {
            $sql .= " AND is_completed = 0";
        }
        
        // Apply search
        if (!empty($search)) {
            $sql .= " AND (title LIKE ? OR description LIKE ?)";
            $searchParam = '%' . $search . '%';
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * Get a single task by ID
     * @param int $taskId
     * @param int $userId
     * @return array|false
     */
    public function getTask($taskId, $userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
        $stmt->execute([$taskId, $userId]);
        return $stmt->fetch();
    }
    
    /**
     * Create a new task
     * @param int $userId
     * @param string $title
     * @param string $description
     * @return array ['success' => bool, 'message' => string]
     */
    public function createTask($userId, $title, $description = '') {
        if (empty($title)) {
            return ['success' => false, 'message' => 'Title is required'];
        }
        
        try {
            $stmt = $this->pdo->prepare("INSERT INTO tasks (user_id, title, description) VALUES (?, ?, ?)");
            $stmt->execute([$userId, $title, $description]);
            return ['success' => true, 'message' => 'Task created successfully'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Failed to create task'];
        }
    }
    
    /**
     * Update a task
     * @param int $taskId
     * @param int $userId
     * @param string $title
     * @param string $description
     * @return array ['success' => bool, 'message' => string]
     */
    public function updateTask($taskId, $userId, $title, $description = '') {
        if (empty($title)) {
            return ['success' => false, 'message' => 'Title is required'];
        }
        
        try {
            $stmt = $this->pdo->prepare("UPDATE tasks SET title = ?, description = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$title, $description, $taskId, $userId]);
            
            if ($stmt->rowCount() > 0) {
                return ['success' => true, 'message' => 'Task updated successfully'];
            } else {
                return ['success' => false, 'message' => 'Task not found or no changes made'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Failed to update task'];
        }
    }
    
    /**
     * Toggle task completion status
     * @param int $taskId
     * @param int $userId
     * @return array ['success' => bool, 'message' => string]
     */
    public function toggleComplete($taskId, $userId) {
        try {
            $stmt = $this->pdo->prepare("UPDATE tasks SET is_completed = NOT is_completed WHERE id = ? AND user_id = ?");
            $stmt->execute([$taskId, $userId]);
            
            if ($stmt->rowCount() > 0) {
                return ['success' => true, 'message' => 'Task status updated'];
            } else {
                return ['success' => false, 'message' => 'Task not found'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Failed to update task'];
        }
    }
    
    /**
     * Delete a task
     * @param int $taskId
     * @param int $userId
     * @return array ['success' => bool, 'message' => string]
     */
    public function deleteTask($taskId, $userId) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
            $stmt->execute([$taskId, $userId]);
            
            if ($stmt->rowCount() > 0) {
                return ['success' => true, 'message' => 'Task deleted successfully'];
            } else {
                return ['success' => false, 'message' => 'Task not found'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Failed to delete task'];
        }
    }
    
    /**
     * Get task statistics for a user
     * @param int $userId
     * @return array
     */
    public function getStats($userId) {
        $stmt = $this->pdo->prepare("
            SELECT 
                COUNT(*) as total,
                SUM(is_completed) as completed,
                SUM(CASE WHEN is_completed = 0 THEN 1 ELSE 0 END) as incomplete
            FROM tasks 
            WHERE user_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }
}
