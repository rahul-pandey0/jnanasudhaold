<?php
/**
 * CodeIgniter Common Functions
 */

$GLOBALS['CI'] = NULL;

function &get_instance()
{
    return $GLOBALS['CI'];
}

function show_error($message = '', $status_code = 500)
{
    http_response_code($status_code);
    echo '<h1>Error</h1>';
    echo '<p>' . htmlspecialchars($message) . '</p>';
    exit;
}

function log_message($level = 'error', $message = '')
{
    $log_path = APPPATH . 'logs/';
    
    if (!is_dir($log_path)) {
        mkdir($log_path, 0777, TRUE);
    }

    $file = $log_path . 'log-' . date('Y-m-d') . '.log';
    $message = date('Y-m-d H:i:s') . ' - ' . strtoupper($level) . ' - ' . $message . PHP_EOL;
    
    file_put_contents($file, $message, FILE_APPEND);
}

/**
 * Show a simple 404 message and exit
 */
function show_404()
{
    http_response_code(404);
    echo 'notfound';
    exit;
}
