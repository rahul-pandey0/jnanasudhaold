<?php
/**
 * CodeIgniter Router Class
 */

class CI_Router {
    protected $routes = array();
    protected $controller = 'welcome';
    protected $method = 'index';
    protected $params = array();

    public function __construct($routes = array())
    {
        // Support passing routes array or falling back to global $route
        if (!empty($routes)) {
            $this->routes = $routes;
        } elseif (isset($GLOBALS['route']) && is_array($GLOBALS['route'])) {
            $this->routes = $GLOBALS['route'];
        }
        $this->parse_routes();
    }

    protected function parse_routes()
    {
        $uri = $this->get_uri();
        $uri_norm = trim($uri, '/');

        // Empty URI → default controller (may include method)
        if ($uri === '' || $uri_norm === '') {
            $default = isset($this->routes['default_controller']) ? $this->routes['default_controller'] : 'welcome';
            $parts = explode('/', trim($default));
            $this->controller = strtolower($parts[0]);
            $this->method = isset($parts[1]) ? strtolower($parts[1]) : 'index';
            $this->params = array();
            return;
        }

        // Route matching: exact first, then wildcard patterns
        foreach ($this->routes as $key => $target) {
            if (in_array($key, array('default_controller', '404_override', 'translate_uri_dashes'))) continue;

            // Exact match
            if ($key === $uri_norm) {
                $tparts = explode('/', trim($target, '/'));
                $this->controller = strtolower(isset($tparts[0]) ? $tparts[0] : 'welcome');
                $this->method     = strtolower(isset($tparts[1]) ? $tparts[1] : 'index');
                $this->params     = array_slice($tparts, 2);
                return;
            }

            // Pattern match: convert (:any) and (:num) to regex, capture groups into $1 $2 …
            $pattern = str_replace(
                array(':any', ':num'),
                array('[^/]+', '[0-9]+'),
                $key
            );
            $pattern = '#^' . $pattern . '$#';
            if (preg_match($pattern, $uri_norm, $matches)) {
                // Replace $1, $2 … back-references in target
                $resolved = $target;
                for ($i = 1; $i < count($matches); $i++) {
                    $resolved = str_replace('$' . $i, $matches[$i], $resolved);
                }
                $tparts = explode('/', trim($resolved, '/'));
                $this->controller = strtolower(isset($tparts[0]) ? $tparts[0] : 'welcome');
                $this->method     = strtolower(isset($tparts[1]) ? $tparts[1] : 'index');
                $this->params     = array_slice($tparts, 2);
                return;
            }
        }

        // Default: parse URI segments
        $uri_parts = explode('/', $uri_norm);
        if (!empty($uri_parts[0])) {
            $this->controller = strtolower($uri_parts[0]);
        }
        if (!empty($uri_parts[1])) {
            $this->method = strtolower($uri_parts[1]);
        }
        $this->params = array_slice($uri_parts, 2);
    }

    protected function get_uri()
    {
        $uri = '';

        if (isset($_GET['route'])) {
            $uri = $_GET['route'];
        } elseif (isset($_SERVER['PATH_INFO'])) {
            $uri = $_SERVER['PATH_INFO'];
            // Remove the base path prefix (e.g., /jnanasudha)
            $base_path = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
            if (strpos($uri, $base_path) === 0) {
                $uri = substr($uri, strlen($base_path));
            }
        } elseif (isset($_SERVER['QUERY_STRING'])) {
            $uri = $_SERVER['QUERY_STRING'];
        }

        return $uri;
    }

    public function get_controller()
    {
        return $this->controller;
    }

    public function get_method()
    {
        return $this->method;
    }

    public function get_params()
    {
        return $this->params;
    }
}
