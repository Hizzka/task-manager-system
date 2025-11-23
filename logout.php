<?php
require_once __DIR__ . '/config/session.php';

destroyUserSession();
header('Location: /task-manager-system/login.php');
exit();
