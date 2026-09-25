<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Branding configuration
// Path to logo image used in PDFs and potentially other views
// Prefer FCPATH if defined (front controller path), else compute from config directory
if (defined('FCPATH')) {
    $png = FCPATH . 'assets' . DIRECTORY_SEPARATOR . 'logo.png';
    $jpg = FCPATH . 'assets' . DIRECTORY_SEPARATOR . 'logo.jpg';
    if (file_exists($png)) {
        $config['logo_path'] = $png;
    } elseif (file_exists($jpg)) {
        $config['logo_path'] = $jpg;
    } else {
        $config['logo_path'] = $png; // default expected path
    }
} else {
    $root = dirname(dirname(dirname(__FILE__)));
    $png = $root . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'logo.png';
    $jpg = $root . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'logo.jpg';
    $config['logo_path'] = file_exists($png) ? $png : (file_exists($jpg) ? $jpg : $png);
}

?>