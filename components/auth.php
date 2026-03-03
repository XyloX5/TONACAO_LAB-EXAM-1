<?php
// components/auth.php
// Authentication & Authorization helpers

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Require the user to be logged in.
 * If not, redirect to login page.
 */
function requireLogin(): void {
    if (empty($_SESSION['user_id'])) {
        header('Location: ' . getBaseUrl() . '/login.php');
        exit;
    }
}

/**
 * Require the user to have the 'admin' role.
 * If not, redirect to profile page.
 */
function requireAdmin(): void {
    requireLogin();
    if ($_SESSION['role'] !== 'admin') {
        header('Location: ' . getBaseUrl() . '/profile.php?error=access_denied');
        exit;
    }
}

/**
 * Check if current session user is admin.
 */
function isAdmin(): bool {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Check if logged in.
 */
function isLoggedIn(): bool {
    return !empty($_SESSION['user_id']);
}

/**
 * Get current session user's ID.
 */
function currentUserId(): int {
    return (int) ($_SESSION['user_id'] ?? 0);
}

/**
 * Get the base URL path for redirects (public/ folder).
 */
function getBaseUrl(): string {
    // Adjust if your project lives in a subdirectory
    $script = $_SERVER['SCRIPT_NAME'];
    $dir = dirname($script);
    return rtrim($dir, '/');
}

/**
 * Sanitize output for HTML.
 */
function h(mixed $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/**
 * Flash message helpers (store in session, display once).
 */
function setFlash(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
