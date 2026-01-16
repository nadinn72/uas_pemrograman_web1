<?php
// index.php - File utama routing
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define base path and URL
define('BASE_PATH', dirname(__FILE__));
define('BASE_URL', '/uas_web1');

// Simple autoload for controllers and models
spl_autoload_register(function($className) {
    $paths = [
        'controllers/' . $className . '.php',
        'models/' . $className . '.php',
        'config/' . $className . '.php'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Get URL from query string (configure .htaccess to pass URL as 'url' parameter)
$url = isset($_GET['url']) ? $_GET['url'] : 'home';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);

// Split URL into parts
$urlParts = explode('/', $url);

// Determine controller and action
$controllerName = !empty($urlParts[0]) ? ucfirst($urlParts[0]) : 'Home';
$controllerName .= 'Controller';
$action = !empty($urlParts[1]) ? $urlParts[1] : 'index';

// Get any additional parameters
$params = array_slice($urlParts, 2);

// Define public pages (no login required)
$publicControllers = ['Auth']; // AuthController doesn't require login
$publicActions = [
    'Auth' => ['login', 'register', 'logout', 'processLogin', 'processRegister']
];

// Check if login is required
$loginRequired = true;

// Check if current controller is in public list
if (in_array(str_replace('Controller', '', $controllerName), $publicControllers)) {
    $controllerBaseName = str_replace('Controller', '', $controllerName);
    
    // Check if current action is in public actions for this controller
    if (isset($publicActions[$controllerBaseName]) && 
        in_array($action, $publicActions[$controllerBaseName])) {
        $loginRequired = false;
    }
}

// Special case: home page can be public
if ($controllerName === 'HomeController' && $action === 'index') {
    $loginRequired = false;
}

// Redirect to login if required
if ($loginRequired && !isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/auth/login');
    exit();
}

// Load controller file
$controllerFile = 'controllers/' . $controllerName . '.php';

// Special handling for HomeController - create simple one if doesn't exist
if ($controllerName === 'HomeController' && !file_exists($controllerFile)) {
    // Create simple HomeController on the fly or redirect to films
    header('Location: ' . BASE_URL . '/films');
    exit();
}

// Check if controller file exists
if (!file_exists($controllerFile)) {
    http_response_code(404);
    echo "<h1>404 - Page Not Found</h1>";
    echo "<p>Controller <strong>$controllerName</strong> not found.</p>";
    echo "<p>File: <code>$controllerFile</code> does not exist.</p>";
    echo "<p><a href='" . BASE_URL . "/home'>Go to Home</a></p>";
    exit();
}

// Load controller
require_once $controllerFile;

// Check if controller class exists
if (!class_exists($controllerName)) {
    http_response_code(500);
    echo "<h1>500 - Internal Server Error</h1>";
    echo "<p>Controller class <strong>$controllerName</strong> not found in file.</p>";
    exit();
}

// Instantiate controller
try {
    $controller = new $controllerName();
    
    // Check if action/method exists
    if (!method_exists($controller, $action)) {
        // Try to use index method
        if (method_exists($controller, 'index')) {
            $action = 'index';
        } else {
            http_response_code(404);
            echo "<h1>404 - Page Not Found</h1>";
            echo "<p>Method <strong>$action</strong> not found in $controllerName.</p>";
            echo "<p>Available methods:</p>";
            echo "<ul>";
            $methods = get_class_methods($controller);
            foreach ($methods as $method) {
                if ($method[0] !== '_') { // Skip private/protected methods
                    echo "<li>$method</li>";
                }
            }
            echo "</ul>";
            exit();
        }
    }
    
    // Call the controller method with parameters
    if (!empty($params)) {
        call_user_func_array([$controller, $action], $params);
    } else {
        $controller->$action();
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo "<h1>500 - Internal Server Error</h1>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>