<?php
/**
 * CodeIgniter Loader Class
 */

class CI_Loader {
    protected $ci;
    protected $loaded_views = array();

    public function __construct()
    {
        $this->ci = &$GLOBALS['CI'];
    }

    public function view($view, $data = array(), $return = FALSE)
    {
        $view_path = APPPATH . 'views/' . $view . '.php';

        if (!file_exists($view_path)) {
            show_error('Unable to locate the view file: ' . $view);
        }

        // Make $this available in the view
        $this_ref = &$GLOBALS['CI'];

        if (is_array($data)) {
            extract($data);
        }

        if ($return === FALSE) {
            include $view_path;
        } else {
            ob_start();
            include $view_path;
            return ob_get_clean();
        }
    }

    public function model($model, $name = NULL)
    {
        $model_path = APPPATH . 'models/' . ucfirst($model) . '.php';

        if (!file_exists($model_path)) {
            show_error('Unable to locate the model file: ' . $model);
        }

        require_once $model_path;
        $model_name = ucfirst($model);

        if (!class_exists($model_name)) {
            show_error('Unable to locate the model class: ' . $model_name);
        }

        $name = ($name === NULL) ? $model : $name;
        $this->ci->$name = new $model_name();
    }

    public function helper($helper)
    {
        // Allow array of helpers similar to CI behavior
        if (is_array($helper)) {
            foreach ($helper as $h) {
                $this->helper($h);
            }
            return;
        }

        if (!is_string($helper) || $helper === '') {
            show_error('Invalid helper name provided');
        }

        $helper_path = APPPATH . 'helpers/' . $helper . '_helper.php';

        if (!file_exists($helper_path)) {
            show_error('Unable to locate the helper file: ' . $helper);
        }

        require_once $helper_path;
    }
}
