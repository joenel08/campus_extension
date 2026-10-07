<?php
// ---- Null-safe string/date helpers (PHP 8.1+ deprecation fixes) ----
function safe_ucfirst(?string $v): string { return ucfirst((string)($v ?? '')); }
function safe_date(?string $v, string $fmt = 'M d, Y'): string {
    if (!$v) return 'N/A';
    $ts = strtotime($v);
    return $ts ? date($fmt, $ts) : 'N/A';
}
function safe_datetime(?string $v): string { return safe_date($v, 'M d, Y H:i'); }

// ---- CSRF ----
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="'
        . htmlspecialchars(csrf_token(), ENT_QUOTES) . '">';
}
function verify_csrf(): void {
    $sent = $_POST['csrf_token'] ?? '';
    if (!$sent || !hash_equals(csrf_token(), $sent)) {
        http_response_code(419);
        exit('CSRF token mismatch.');
    }
}

// ---- Auth ----
function require_auth(): void {
    if (empty($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }
}

// ---- Input whitelisting ----
function only(array $allowed, $value, $default = null) {
    return in_array($value, $allowed, true) ? $value : $default;
}