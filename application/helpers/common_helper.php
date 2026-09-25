<?php
/**
 * Common Helper Functions
 */

if (!function_exists('base_url')) {
    function base_url($uri = '')
    {
        // Prefer dynamic detection so switching between php -S and XAMPP needs no changes.
        static $computed_base = null;
        if ($computed_base === null) {
            $scheme = 'http';
            if (
                (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
                (isset($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443)
            ) {
                $scheme = 'https';
            }

            if (isset($_SERVER['HTTP_HOST'])) {
                $host = $_SERVER['HTTP_HOST']; // already includes port if any
                $script = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '/';
                $basePath = str_replace('\\', '/', dirname($script));
                if ($basePath === '.' || $basePath === '' || $basePath === '\\') {
                    $basePath = '/';
                }
                // Ensure trailing slash
                if (substr($basePath, -1) !== '/') {
                    $basePath .= '/';
                }
                $computed_base = $scheme . '://' . $host . $basePath;
            } else {
                // Fallback for CLI contexts: use config if available
                $config = array();
                if (defined('APPPATH')) {
                    @require APPPATH . 'config/config.php';
                }
                $computed_base = isset($config['base_url']) && $config['base_url'] ? $config['base_url'] : 'http://localhost/';
            }
        }

        return $computed_base . ltrim($uri, '/');
    }
}

if (!function_exists('site_url')) {
    function site_url($uri = '')
    {
        return base_url($uri);
    }
}

if (!function_exists('current_url')) {
    function current_url()
    {
        $scheme = 'http';
        if (
            (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
            (isset($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443)
        ) {
            $scheme = 'https';
        }
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
        $uri  = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
        return $scheme . '://' . $host . $uri;
    }
}

if (!function_exists('redirect')) {
    function redirect($location = '', $method = 'location', $http_response_code = 302)
    {
        if (!empty($location)) {
            header('Location: ' . $location, TRUE, $http_response_code);
            exit;
        }
    }
}

if (!function_exists('form_open')) {
    function form_open($action = '', $attributes = array())
    {
        $method = isset($attributes['method']) ? strtoupper($attributes['method']) : 'POST';
        $class = isset($attributes['class']) ? $attributes['class'] : '';
        
        $html = '<form action="' . $action . '" method="' . $method . '" class="' . $class . '">';
        return $html;
    }
}

if (!function_exists('form_close')) {
    function form_close()
    {
        return '</form>';
    }
}

if (!function_exists('form_input')) {
    function form_input($name = '', $value = '', $attributes = array())
    {
        $type = isset($attributes['type']) ? $attributes['type'] : 'text';
        $class = isset($attributes['class']) ? $attributes['class'] : 'form-control';
        $placeholder = isset($attributes['placeholder']) ? $attributes['placeholder'] : '';
        
        $html = '<input type="' . $type . '" name="' . $name . '" value="' . htmlspecialchars($value) . '" class="' . $class . '" placeholder="' . $placeholder . '">';
        return $html;
    }
}

if (!function_exists('form_textarea')) {
    function form_textarea($name = '', $value = '', $attributes = array())
    {
        $class = isset($attributes['class']) ? $attributes['class'] : 'form-control';
        $rows = isset($attributes['rows']) ? $attributes['rows'] : 3;
        
        $html = '<textarea name="' . $name . '" class="' . $class . '" rows="' . $rows . '">' . htmlspecialchars($value) . '</textarea>';
        return $html;
    }
}

if (!function_exists('form_button')) {
    function form_button($content = '', $name = '', $attributes = array())
    {
        $type = isset($attributes['type']) ? $attributes['type'] : 'button';
        $class = isset($attributes['class']) ? $attributes['class'] : 'btn btn-primary';
        
        $html = '<button type="' . $type . '" name="' . $name . '" class="' . $class . '">' . $content . '</button>';
        return $html;
    }
}

if (!function_exists('anchor')) {
    function anchor($uri = '', $title = '', $attributes = array())
    {
        $class = isset($attributes['class']) ? 'class="' . $attributes['class'] . '"' : '';
        $html = '<a href="' . site_url($uri) . '" ' . $class . '>' . $title . '</a>';
        return $html;
    }
}

if (!function_exists('img')) {
    function img($src = '', $alt = '', $attributes = array())
    {
        $class = isset($attributes['class']) ? 'class="' . $attributes['class'] . '"' : '';
        $html = '<img src="' . $src . '" alt="' . $alt . '" ' . $class . '>';
        return $html;
    }
}

if (!function_exists('json_response')) {
    function json_response($success = true, $message = '', $data = array())
    {
        header('Content-Type: application/json');
        echo json_encode(array(
            'success' => $success,
            'message' => $message,
            'data' => $data
        ));
        exit;
    }
}

if (!function_exists('format_date')) {
    function format_date($date, $format = 'Y-m-d H:i:s')
    {
        if (empty($date)) return '';
        return date($format, strtotime($date));
    }
}

if (!function_exists('truncate')) {
    function truncate($string, $length = 100, $suffix = '...')
    {
        if (strlen($string) <= $length) {
            return $string;
        }
        return substr($string, 0, $length) . $suffix;
    }
}
