<?php
/**
 * CodeIgniter Configuration File
 */

// Base URL (dynamic) - works for localhost:8000, localhost/jnanasudha, admin.jnanasudha.com
// Leave empty and compute in helpers, or set dynamically here for legacy CI
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
$scriptDir = isset($_SERVER['SCRIPT_NAME']) ? rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') : '';
if ($scriptDir === '/' || $scriptDir === '\\') { $scriptDir = ''; }
$config['base_url'] = $scheme . '://' . $host . ($scriptDir ? $scriptDir . '/' : '/');

// Index File
$config['index_page'] = 'index.php';

// URI Protocol
$config['uri_protocol'] = 'REQUEST_URI';

// Character Set
$config['charset'] = 'UTF-8';

// Language
$config['language'] = 'english';

// Enable Query Strings
$config['enable_query_strings'] = FALSE;

// URL suffix
$config['url_suffix'] = '';

// Log Threshold
$config['log_threshold'] = 1;

// Log Date Format
$config['log_date_format'] = 'Y-m-d H:i:s';

// Cache on
$config['cache_on'] = FALSE;

// Session Configuration
$config['sess_driver'] = 'files';
$config['sess_cookie_name'] = 'ci_session';
$config['sess_expiration'] = 86400;  // 24 hours
$config['sess_save_path'] = sys_get_temp_dir();  // Use system temp directory
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = FALSE;

// Cookie Settings
$config['cookie_prefix'] = '';
$config['cookie_domain'] = '';
$config['cookie_path'] = '/';
$config['cookie_secure'] = FALSE;
$config['cookie_httponly'] = FALSE;

// CSRF Protection
$config['csrf_protection'] = FALSE;
$config['csrf_token_name'] = 'csrf_token';
$config['csrf_cookie_name'] = 'csrf_cookie';
$config['csrf_expire'] = 7200;
$config['csrf_regenerate'] = TRUE;
$config['csrf_exclude_uris'] = array();

// XSS Filtering
$config['global_xss_filtering'] = FALSE;

// Output Compression
$config['compress_output'] = FALSE;

// Profiler
$config['profiler_enabled'] = FALSE;
