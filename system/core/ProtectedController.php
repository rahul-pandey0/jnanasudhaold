<?php
/**
 * Protected Controller Base Class
 * Requires authentication for all methods
 */

#[\AllowDynamicProperties]
class CI_ProtectedController extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        
        // Start session if not already started
        if (!isset($_SESSION)) {
            session_start();
        }
        
        // Check if user is logged in
        if (empty($_SESSION['logged_in'])) {
            // Redirect to login
            header('Location: ' . $this->get_base_url() . 'auth/login');
            exit;
        }
    }
    
    /**
     * Get base URL
     */
    protected function get_base_url()
    {
        // Prefer dynamic detection based on current request (works for php -S and XAMPP)
        if (isset($_SERVER['HTTP_HOST'])) {
            $scheme = 'http';
            if (
                (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
                (isset($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443)
            ) {
                $scheme = 'https';
            }
            $host = $_SERVER['HTTP_HOST']; // may include :port
            $script = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '/';
            $basePath = str_replace('\\', '/', dirname($script));
            if ($basePath === '.' || $basePath === '' || $basePath === '\\') {
                $basePath = '/';
            }
            if (substr($basePath, -1) !== '/') {
                $basePath .= '/';
            }
            return $scheme . '://' . $host . $basePath;
        }

        // Fallback: read from config (avoid require_once to ensure local $config is populated)
        $config = array();
        @include APPPATH . 'config/config.php';
        if (isset($config['base_url']) && $config['base_url']) {
            return $config['base_url'];
        }
        return 'http://localhost/';
    }
}
