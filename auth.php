<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if a user is logged in
if (!function_exists('isLoggedIn')) {
    function isLoggedIn(): bool {
        return isset($_SESSION['user']['id']);
    }
}

// Get the current logged-in user ID
if (!function_exists('currentUserId')) {
    function currentUserId(): int {
        return $_SESSION['user']['id'] ?? 0;
    }
}

// Log in a user (username & password)
if (!function_exists('loginUser')) {
    function loginUser($username, $password, $conn): bool {
        $stmt = $conn->prepare("SELECT id, password FROM user WHERE username = ?");
        if (!$stmt) return false;
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = ['id' => $user['id'], 'username' => $username];
            return true;
        }
        return false;
    }
}

// Log out the current user
if (!function_exists('logoutUser')) {
    function logoutUser(): void {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time()-42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }
        session_destroy();
    }
}
