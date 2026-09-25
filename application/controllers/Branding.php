<?php
class Branding extends CI_ProtectedController {
    protected $brandingConfigFile;
    protected $webRoot;

    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION)) { session_start(); }
        $this->load->helper(array('url'));
        // Prepare branding config path and web root
        $this->brandingConfigFile = APPPATH . 'config' . DIRECTORY_SEPARATOR . 'branding.php';
        $this->webRoot = defined('FCPATH') ? rtrim(FCPATH, '/\\') : dirname(dirname(dirname(__FILE__)));
        // Load config via CI Config component if available
        if (isset($this->config) && is_object($this->config) && method_exists($this->config, 'load')) {
            $this->config->load('branding', true);
        }
    }

    public function upload()
    {
        $data = array();
        $data['title'] = 'Branding - Upload Logo';
        $data['user_email'] = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'Admin';
        $data['user_role'] = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $data['user_name'] = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';

        $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        if ($role_id) {
            $this->load->model('Menu_model', 'menu_model');
            $data['top_menus'] = $this->menu_model->get_top_menus($role_id);
        } else {
            $data['top_menus'] = array();
        }

        // Handle POST upload
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['logo'])) {
            $root = $this->webRoot;
            $assetsDir = $root . DIRECTORY_SEPARATOR . 'assets';
            if (!is_dir($assetsDir)) { @mkdir($assetsDir, 0777, true); }

            $tmp = isset($_FILES['logo']['tmp_name']) ? $_FILES['logo']['tmp_name'] : '';
            $name = isset($_FILES['logo']['name']) ? $_FILES['logo']['name'] : '';
            $type = isset($_FILES['logo']['type']) ? $_FILES['logo']['type'] : '';
            $size = isset($_FILES['logo']['size']) ? (int)$_FILES['logo']['size'] : 0;

            if (!is_uploaded_file($tmp)) {
                $data['error'] = 'No file uploaded.';
            } else if ($size > 2 * 1024 * 1024) {
                $data['error'] = 'File too large. Max 2MB.';
            } else {
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                if ($type === 'image/png' || $ext === 'png') {
                    $destPath = $assetsDir . DIRECTORY_SEPARATOR . 'logo.png';
                    if (@move_uploaded_file($tmp, $destPath)) {
                        $data['success'] = 'Logo uploaded successfully.';
                    } else {
                        $data['error'] = 'Failed to save the uploaded file.';
                    }
                } else if ($type === 'image/jpeg' || $type === 'image/jpg' || $ext === 'jpg' || $ext === 'jpeg') {
                    // Convert JPG to PNG if GD is available, else save as logo.jpg
                    if (function_exists('imagecreatefromjpeg') && function_exists('imagepng')) {
                        $img = @imagecreatefromjpeg($tmp);
                        if ($img) {
                            $destPath = $assetsDir . DIRECTORY_SEPARATOR . 'logo.png';
                            if (@imagepng($img, $destPath)) {
                                imagedestroy($img);
                                $data['success'] = 'Logo uploaded and converted to PNG.';
                            } else {
                                imagedestroy($img);
                                $data['error'] = 'Failed to convert image to PNG.';
                            }
                        } else {
                            $data['error'] = 'Invalid JPEG file.';
                        }
                    } else {
                        // Fallback: store as JPG if PNG conversion not possible
                        $destPath = $assetsDir . DIRECTORY_SEPARATOR . 'logo.jpg';
                        if (@move_uploaded_file($tmp, $destPath)) {
                            $data['success'] = 'Logo uploaded successfully (JPG).';
                        } else {
                            $data['error'] = 'Failed to save the uploaded JPG file.';
                        }
                    }
                } else {
                    $data['error'] = 'Unsupported format. Please upload PNG or JPG.';
                }
            }
        }

        $data['current_logo'] = (isset($this->config) && method_exists($this->config, 'item')) ? $this->config->item('logo_path', 'branding') : '';
        if (empty($data['current_logo']) && !empty($this->brandingConfigFile) && file_exists($this->brandingConfigFile)) {
            $config = array();
            include $this->brandingConfigFile;
            if (isset($config['logo_path'])) { $data['current_logo'] = $config['logo_path']; }
        }
        // Derive web URL for preview if file exists under assets
        $root = $this->webRoot;
        $pngPath = $root . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'logo.png';
        $jpgPath = $root . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'logo.jpg';
        if (file_exists($pngPath)) {
            $data['current_logo_url'] = base_url('assets/logo.png');
        } elseif (file_exists($jpgPath)) {
            $data['current_logo_url'] = base_url('assets/logo.jpg');
        } else {
            $data['current_logo_url'] = '';
        }
        $this->load->view('branding/upload', $data);
    }

    // View current branding/logo
    public function view()
    {
        $data = array();
        $data['title'] = 'Branding - View Logo';
        $data['user_email'] = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'Admin';
        $data['user_role'] = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $data['user_name'] = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';

        // Menu
        $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        if ($role_id) {
            $this->load->model('Menu_model', 'menu_model');
            $data['top_menus'] = $this->menu_model->get_top_menus($role_id);
        } else {
            $data['top_menus'] = array();
        }

        // Current logo
        $root = $this->webRoot;
        $data['current_logo_url'] = file_exists($root . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'logo.png')
            ? base_url('assets/logo.png') : '';
        $this->load->view('branding/view', $data);
    }

    // Theme options placeholder (colors, etc.)
    public function theme()
    {
        if (!isset($_SESSION)) { session_start(); }
        $data = array();
        $data['title'] = 'Branding - Theme Options';
        $data['user_email'] = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'Admin';
        $data['user_role'] = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $data['user_name'] = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';

        // Menu
        $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        if ($role_id) {
            $this->load->model('Menu_model', 'menu_model');
            $data['top_menus'] = $this->menu_model->get_top_menus($role_id);
        } else {
            $data['top_menus'] = array();
        }

        // Basic theme form (no persistence yet)
        // Persist theme in DB via Theme_model
        $this->load->model('Theme_model', 'theme_model');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $primary = isset($_POST['primary_color']) ? trim($_POST['primary_color']) : '#667eea';
            $secondary = isset($_POST['secondary_color']) ? trim($_POST['secondary_color']) : '#764ba2';
            $_SESSION['theme_primary'] = $primary;
            $_SESSION['theme_secondary'] = $secondary;
            $this->theme_model->set_theme($primary, $secondary);
            $data['success'] = 'Theme updated.';
        }

        // Load current theme (prefer session, fallback to DB)
        $theme = $this->theme_model->get_theme();
        $data['primary_color'] = isset($_SESSION['theme_primary']) ? $_SESSION['theme_primary'] : (isset($theme['theme_primary']) ? $theme['theme_primary'] : '#667eea');
        $data['secondary_color'] = isset($_SESSION['theme_secondary']) ? $_SESSION['theme_secondary'] : (isset($theme['theme_secondary']) ? $theme['theme_secondary'] : '#764ba2');
        $this->load->view('branding/theme', $data);
    }
}
?>