<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once '../config/db.php';
require_once '../includes/functions.php';
requireRole(['superadmin']);

$msg = '';
$msgType = '';
$backupDir = __DIR__ . '/backups/';

if (!file_exists($backupDir)) {
    mkdir($backupDir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create_backup'])) {
        $dbName = "library_db"; // Replace with config db name if dynamic
        $user = "root";
        $pass = "";
        $host = "localhost";
        
        $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $filepath = $backupDir . $filename;
        
        // This command assumes mysqldump is in PATH. Adjust path if necessary.
        $command = "mysqldump --user={$user} --password={$pass} --host={$host} {$dbName} > \"{$filepath}\"";
        
        exec($command, $output, $returnVar);
        
        if ($returnVar === 0) {
            $msg = "Backup created successfully: " . $filename;
            $msgType = "success";
            logAction($pdo, $_SESSION['user_id'], 'backup', "Created database backup $filename");
        } else {
            $msg = "Error creating backup. Check server mysqldump configuration.";
            $msgType = "danger";
        }
    }
}

// Get DB info
$stmt = $pdo->query("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE()");
$tablesCount = $stmt->fetchColumn();

// List existing backups
$backups = [];
if ($handle = opendir($backupDir)) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != ".." && pathinfo($entry, PATHINFO_EXTENSION) == 'sql') {
            $backups[] = [
                'name' => $entry,
                'size' => round(filesize($backupDir . $entry) / 1024, 2), // KB
                'date' => date("Y-m-d H:i:s", filemtime($backupDir . $entry))
            ];
        }
    }
    closedir($handle);
}
usort($backups, function($a, $b) { return $b['date'] <=> $a['date']; });
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Database Backup</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --bg: #0f172a; --sidebar: #111827; --card-bg: rgba(30, 41, 59, 0.8); --primary: #4f46e5; --accent: #f59e0b; --danger: #ef4444; --text-main: #f8fafc; --text-muted: #94a3b8; --border: rgba(255, 255, 255, 0.1); }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background-color: var(--bg); color: var(--text-main); display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background-color: var(--sidebar); border-right: 1px solid var(--border); display: flex; flex-direction: column; }
        .sidebar-header { padding: 20px; text-align: center; border-bottom: 1px solid var(--border); }
        .sidebar-header h2 { font-size: 1.2rem; color: var(--text-main); }
        .nav-links { list-style: none; padding: 20px 0; flex: 1; }
        .nav-links li { padding: 0 20px; margin-bottom: 10px; }
        .nav-links a { display: flex; align-items: center; gap: 10px; text-decoration: none; color: var(--text-muted); padding: 12px 15px; border-radius: 8px; transition: all 0.3s; }
        .nav-links a:hover, .nav-links a.active { background-color: rgba(79, 70, 229, 0.1); color: var(--primary); }
        
        .main-content { flex: 1; padding: 30px; overflow-y: auto; }
        .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        
        .card { background: var(--card-bg); backdrop-filter: blur(10px); border-radius: 12px; border: 1px solid var(--border); padding: 25px; margin-bottom: 25px; }
        
        .btn { padding: 10px 20px; border-radius: 6px; border: none; cursor: pointer; font-size: 1rem; font-weight: 500; transition: all 0.3s; display: inline-flex; align-items: center; gap: 8px; color: white; text-decoration: none; }
        .btn-primary { background: var(--primary); }
        .btn-primary:hover { background: #4338ca; }
        .btn-danger { background: var(--danger); }
        .btn-sm { padding: 5px 10px; font-size: 0.8rem; }
        
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .alert-success { background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #10b981; }
        .alert-danger { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: var(--danger); }
        .alert-warning { background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.2); color: var(--accent); }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid var(--border); }
        th { color: var(--text-muted); font-weight: 500; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h2><i class="fas fa-book-open" style="color: var(--primary);"></i> Library Admin</h2>
        </div>
        <ul class="nav-links">
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="admins.php"><i class="fas fa-users-cog"></i> Admins & Staff</a></li>
            <li><a href="settings.php"><i class="fas fa-cogs"></i> System Settings</a></li>
            <li><a href="backup.php" class="active"><i class="fas fa-database"></i> Backup & Restore</a></li>
            <li><a href="audit_logs.php"><i class="fas fa-clipboard-list"></i> Audit Logs</a></li>
            <li><a href="reports.php"><i class="fas fa-chart-pie"></i> Advanced Reports</a></li>
            <li><a href="fine_config.php"><i class="fas fa-money-bill-wave"></i> Fine Config</a></li>
            <li><a href="../logout.php" style="color: var(--danger);"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
    
    <div class="main-content">
        <div class="topbar">
            <h1>Database Backup</h1>
        </div>
        
        <?php if($msg): ?>
            <div class="alert alert-<?php echo $msgType; ?>"><?php echo $msg; ?></div>
        <?php endif; ?>
        
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i> <strong>Important:</strong> Regular backups are crucial for preventing data loss. It is recommended to download your backups and store them securely off-server.
        </div>
        
        <div class="card" style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h3 style="margin-bottom: 10px;">Current Database Status</h3>
                <p style="color: var(--text-muted);">Total Tables: <?php echo $tablesCount; ?></p>
            </div>
            <form method="POST">
                <input type="hidden" name="create_backup" value="1">
                <button type="submit" class="btn btn-primary"><i class="fas fa-download"></i> Create New Backup</button>
            </form>
        </div>
        
        <div class="card">
            <h3>Existing Backups</h3>
            <table>
                <thead>
                    <tr>
                        <th>Filename</th>
                        <th>Size (KB)</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($backups as $b): ?>
                    <tr>
                        <td><?php echo $b['name']; ?></td>
                        <td><?php echo $b['size']; ?></td>
                        <td><?php echo $b['date']; ?></td>
                        <td>
                            <a href="backups/<?php echo $b['name']; ?>" download class="btn btn-primary btn-sm"><i class="fas fa-file-download"></i> Download</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($backups)): ?>
                    <tr><td colspan="4" style="text-align:center;">No backups found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="card">
            <h3 style="color: var(--danger); margin-bottom: 15px;"><i class="fas fa-upload"></i> Restore Database</h3>
            <p style="color: var(--text-muted); margin-bottom: 15px;">To restore a backup, it is highly recommended to use a database management tool like phpMyAdmin or MySQL CLI to prevent script timeouts with large files.</p>
            <form method="POST" enctype="multipart/form-data" action="restore_placeholder.php">
                <input type="file" name="sql_file" accept=".sql" style="color: white; margin-bottom: 15px; display:block;">
                <button type="submit" class="btn btn-danger" disabled title="Feature restricted for safety. Use direct DB access."><i class="fas fa-exclamation-triangle"></i> Restore (Disabled)</button>
            </form>
        </div>
    </div>
</body>
</html>
