<?php
/**
 * Example Helper File
 * 
 * Helper functions can be loaded with: $this->load->helper('helper_name');
 */

// Compute base URL from config or server environment
if (!function_exists('_compute_base_url')) {
    function _compute_base_url()
    {
        // Prefer config value if available
        if (isset($GLOBALS['config']) && is_array($GLOBALS['config']) && isset($GLOBALS['config']['base_url']) && !empty($GLOBALS['config']['base_url'])) {
            $base = $GLOBALS['config']['base_url'];
        } else {
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
            $scriptDir = isset($_SERVER['SCRIPT_NAME']) ? rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') : '';
            if ($scriptDir === '/' || $scriptDir === '\\') { $scriptDir = ''; }
            $base = $scheme . '://' . $host . ($scriptDir ? $scriptDir . '/' : '/');
        }
        // Ensure trailing slash
        if (substr($base, -1) !== '/') { $base .= '/'; }
        return $base;
    }
}

if (!function_exists('site_url')) {
    function site_url($uri = '')
    {
        $base = _compute_base_url();
        return $base . ltrim($uri, '/');
    }
}

if (!function_exists('base_url')) {
    function base_url($uri = '')
    {
        $base = _compute_base_url();
        return $base . ltrim($uri, '/');
    }
}

if (!function_exists('redirect')) {
    /**
     * Redirect Helper
     * Redirects to another page
     */
    function redirect($location = '', $status = 'location')
    {
        if (!empty($location)) {
            // If location is relative, prepend base URL
            if (strpos($location, 'http://') !== 0 && strpos($location, 'https://') !== 0) {
                $location = base_url($location);
            }
            header('Location: ' . $location);
            exit;
        }
    }
}
