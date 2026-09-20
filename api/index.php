<?php
/**
 * Vercel Serverless Gateway Router for Library Management System
 */

$requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$requestUri = trim($requestUri, '/');

$rootDir = dirname(__DIR__);

// If root URL accessed
if ($requestUri === '' || $requestUri === 'index.php') {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
    if (isset($_SESSION['user_id'])) {
        $role = $_SESSION['role'] ?? 'guest';
        if ($role === 'superadmin') { header('Location: /superadmin/dashboard.php'); exit; }
        elseif ($role === 'admin') { header('Location: /admin/dashboard.php'); exit; }
        elseif ($role === 'staff') { header('Location: /staff/dashboard.php'); exit; }
        elseif ($role === 'member') { header('Location: /member/dashboard.php'); exit; }
    }
    require $rootDir . '/guest/index.php';
    exit;
}

// Target file path
$targetFile = $rootDir . '/' . $requestUri;

// If target is directory, look for index.php or dashboard.php
if (is_dir($targetFile)) {
    if (file_exists($targetFile . '/index.php')) {
        $targetFile .= '/index.php';
    } elseif (file_exists($targetFile . '/dashboard.php')) {
        $targetFile .= '/dashboard.php';
    }
}

// Append .php if missing and file exists
if (!file_exists($targetFile) && file_exists($targetFile . '.php')) {
    $targetFile .= '.php';
}

// Execute file if exists
if (file_exists($targetFile) && is_file($targetFile) && pathinfo($targetFile, PATHINFO_EXTENSION) === 'php') {
    // Change working directory to target script directory so relative requires like '../config/db.php' work seamlessly
    chdir(dirname($targetFile));
    require $targetFile;
    exit;
}

// Static assets fallback
$ext = pathinfo($targetFile, PATHINFO_EXTENSION);
$mimeTypes = [
    'css' => 'text/css',
    'js' => 'application/javascript',
    'png' => 'image/png',
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'svg' => 'image/svg+xml',
    'ico' => 'image/x-icon',
];

if (file_exists($targetFile) && isset($mimeTypes[$ext])) {
    header('Content-Type: ' . $mimeTypes[$ext]);
    readfile($targetFile);
    exit;
}

// 404 Fallback
http_response_code(404);
echo "404 - Page Not Found: " . htmlspecialchars($requestUri);
exit;
