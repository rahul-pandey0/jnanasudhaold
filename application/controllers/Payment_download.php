<?php
/**
 * Public Payment Download Controller
 * Handles public download of payment receipts (PDF)
 */

// Polyfill for str_ends_with for PHP < 8.0
if (!function_exists('str_ends_with')) {
    function str_ends_with($haystack, $needle) {
        $len = strlen($needle);
        if ($len === 0) {
            return true;
        }
        return (substr($haystack, -$len) === $needle);
    }
}

class Payment_download extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Public download receipt PDF by file name (expects order_no.pdf)
     * URL examples:
     *  - payment/download?file=ORD123.pdf
     *  - payment/download/ORD123.pdf
     */
    public function index($file = null)
    {
        if ($file === null && isset($_GET['file'])) {
            $file = $_GET['file'];
        }
        if (!str_ends_with($file, '.pdf')) {
            $file .= '.pdf';
        }

        // Basic validation: only allow [A-Za-z0-9_-]+.pdf
        if (empty($file) || !preg_match('/^[A-Za-z0-9_-]+\.pdf$/', $file)) {
            http_response_code(400);
            echo 'Invalid file name';
            exit;
        }

        // Build path to public/pdf/invoices/<file>
        $root = dirname(dirname(dirname(__FILE__)));
        $path = $root . '/public/pdf/invoices/' . $file;
        // ...existing code...
                if (!file_exists($path) || !is_file($path)) {
            http_response_code(404);
            echo 'File not found';
            exit;
        }
        // Stream download
        $filename = basename($path);
        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($path));
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        readfile($path);
        exit;
    }
}
