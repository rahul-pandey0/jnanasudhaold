<?php
/**
 * CodeIgniter Entry Point
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('BASEPATH', dirname(__FILE__) . '/system/');
define('APPPATH', dirname(__FILE__) . '/application/');
define('ENVIRONMENT', isset($_ENV['CI_ENV']) ? $_ENV['CI_ENV'] : 'development');

// Allow requests from the local frontend and handle CORS preflight globally.
$allowed_origins = array('http://localhost:5178', 'http://localhost:55912');
$request_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
if (in_array($request_origin, $allowed_origins, true)) {
    header('Access-Control-Allow-Origin: ' . $request_origin);
    header('Vary: Origin');
}
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Define FPDF font path globally if font directory exists
if (!defined('FPDF_FONTPATH')) {
    $fpdfFontDir = APPPATH . 'third_party/fpdf/font/';
    if (is_dir($fpdfFontDir)) {
        define('FPDF_FONTPATH', $fpdfFontDir);
    }
}

// Start session
session_start();

// Load core classes
require_once BASEPATH . 'core/Common.php';
require_once BASEPATH . 'core/Controller.php';
require_once BASEPATH . 'core/ProtectedController.php';
require_once BASEPATH . 'core/Model.php';
require_once BASEPATH . 'core/Loader.php';
require_once BASEPATH . 'core/Router.php';

// Load configuration
$config = array();
require_once APPPATH . 'config/config.php';

// Initialize router
$routes = array();
require_once APPPATH . 'config/routes.php';
// Ensure routes from config are passed to router
if (isset($route) && is_array($route)) {
    $routes = $route;
}

$router = new CI_Router($routes);
$controller_name = ucfirst($router->get_controller());
$method_name = $router->get_method();
$params = $router->get_params();

// Load the controller
$controller_file = APPPATH . 'controllers/' . $controller_name . '.php';

if (file_exists($controller_file)) {
    require_once $controller_file;
    
    if (class_exists($controller_name)) {
        // Create instance - this will set it as global in the constructor
        $controller = new $controller_name();
        
        if (method_exists($controller, $method_name)) {
            call_user_func_array(array($controller, $method_name), $params);
        } else {
            show_404();
        }
    } else {
        show_404();
    }
} else {
    show_404();
}
