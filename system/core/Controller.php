<?php
/**
 * CodeIgniter Application Controller Base Class
 */

#[\AllowDynamicProperties]
class CI_Controller {
    public $load;

    public function __construct()
    {
        // Set the global CI reference
        $GLOBALS['CI'] = &$this;
        
        // Initialize the loader
        $this->load = new CI_Loader();
    }

    public function __get($key)
    {
        return isset($this->$key) ? $this->$key : null;
    }

    public function __set($key, $value)
    {
        // Allow Loader to assign models without triggering dynamic property deprecation warnings
        $this->$key = $value;
    }
}
