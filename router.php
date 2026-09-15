<?php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $uri;

// 1. Static file check (CSS, JS, images, font, pdf, etc.)
if ($uri !== '/' && file_exists($file) && !is_dir($file)) {
    return false; // serve directly
}

// 1b. Static asset request with clean prefix (e.g. /task/css/style.css -> /css/style.css, /seeker/images/... -> /images/...)
if (preg_match('#^/(?:task|seeker|lister|employee|employer|admin)/(.+\.(?:css|js|png|jpg|jpeg|gif|svg|ico|woff|woff2|ttf|eot|pdf))$#i', $uri, $mAsset)) {
    $realAsset = __DIR__ . '/' . $mAsset[1];
    if (file_exists($realAsset) && !is_dir($realAsset)) {
        $ext = strtolower(pathinfo($realAsset, PATHINFO_EXTENSION));
        $mimes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject',
            'pdf' => 'application/pdf'
        ];
        if (isset($mimes[$ext])) {
            header('Content-Type: ' . $mimes[$ext]);
        }
        readfile($realAsset);
        exit;
    }
}

// 2. Clean routes with params
if (preg_match('#^/task/([A-Za-z0-9_-]+)/?$#', $uri, $m)) {
    $_GET['jobid'] = $m[1];
    chdir(__DIR__);
    require __DIR__ . '/explore-job.php';
    exit;
}
if ($uri === '/task' || $uri === '/task/') {
    if (isset($_GET['id'])) {
        $_GET['jobid'] = $_GET['id'];
    }
    chdir(__DIR__);
    require __DIR__ . '/explore-job.php';
    exit;
}

if (preg_match('#^/seeker/([A-Za-z0-9_-]+)/?$#', $uri, $m)) {
    $_GET['empid'] = $m[1];
    chdir(__DIR__);
    require __DIR__ . '/employee-detail.php';
    exit;
}
if ($uri === '/seeker' || $uri === '/seeker/') {
    if (isset($_GET['id'])) {
        $_GET['empid'] = $_GET['id'];
    }
    chdir(__DIR__);
    require __DIR__ . '/employee-detail.php';
    exit;
}

if (preg_match('#^/lister/([A-Za-z0-9_-]+)/?$#', $uri, $m)) {
    $_GET['ref'] = $m[1];
    chdir(__DIR__);
    require __DIR__ . '/company.php';
    exit;
}
if ($uri === '/lister' || $uri === '/lister/') {
    chdir(__DIR__);
    require __DIR__ . '/company.php';
    exit;
}

if ($uri === '/tasks' || $uri === '/tasks/') {
    chdir(__DIR__);
    require __DIR__ . '/job-list.php';
    exit;
}
if ($uri === '/task-seekers' || $uri === '/task-seekers/') {
    chdir(__DIR__);
    require __DIR__ . '/employees.php';
    exit;
}
if ($uri === '/task-listers' || $uri === '/task-listers/') {
    chdir(__DIR__);
    require __DIR__ . '/employers.php';
    exit;
}
if ($uri === '/contact' || $uri === '/contact/') {
    chdir(__DIR__);
    require __DIR__ . '/contact.php';
    exit;
}
if ($uri === '/login' || $uri === '/login/') {
    chdir(__DIR__);
    require __DIR__ . '/login.php';
    exit;
}
if ($uri === '/register' || $uri === '/register/') {
    chdir(__DIR__);
    require __DIR__ . '/register.php';
    exit;
}
if ($uri === '/logout' || $uri === '/logout/') {
    chdir(__DIR__);
    require __DIR__ . '/logout.php';
    exit;
}

// 3. Extensionless PHP files or directory index (/admin, /employee, /employer, /employee/qualifications, etc.)
$cleanPath = rtrim($uri, '/');
$dirIndex = __DIR__ . $cleanPath . '/index.php';
$phpFile = __DIR__ . $cleanPath . '.php';

if ($cleanPath !== '' && is_dir(__DIR__ . $cleanPath) && file_exists($dirIndex)) {
    if (substr($uri, -1) !== '/' && $cleanPath !== '/employee/my_cv') {
        $qs = !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '';
        header("Location: " . $cleanPath . "/" . $qs);
        exit;
    }
    chdir(dirname($dirIndex));
    require $dirIndex;
    exit;
} elseif ($cleanPath !== '' && file_exists($phpFile)) {
    chdir(dirname($phpFile));
    require $phpFile;
    exit;
}

// 4. Direct PHP file in subfolder (e.g. /employee/app/new-dp.php, /employer/my-jobs.php)
if ($uri !== '/' && file_exists($file) && !is_dir($file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
    chdir(dirname($file));
    require $file;
    exit;
}

// 5. Default index
if ($uri === '/' || $uri === '') {
    chdir(__DIR__);
    require __DIR__ . '/index.php';
    exit;
}

return false;
