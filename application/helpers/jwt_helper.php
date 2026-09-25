<?php
/*
 * Minimal HS256 JWT helper.
 * Secret is read from the JWT_SECRET env var, falling back to a value in
 * application/config/jwt.php  (define JWT_SECRET constant there).
 */

function _jwt_secret()
{
    $env = getenv('JWT_SECRET');
    if ($env) return $env;
    $cfg = APPPATH . 'config/jwt.php';
    if (file_exists($cfg)) require_once $cfg;
    if (defined('JWT_SECRET')) return JWT_SECRET;
    trigger_error('[JWT] JWT_SECRET not configured', E_USER_WARNING);
    return 'changeme_set_JWT_SECRET';
}

function _jwt_b64url_encode($data)
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function _jwt_b64url_decode($data)
{
    return base64_decode(strtr($data, '-_', '+/'));
}

function jwt_encode(array $payload, $ttl_seconds = 86400)
{
    $payload['iat'] = time();
    $payload['exp'] = time() + $ttl_seconds;

    $header  = _jwt_b64url_encode(json_encode(array('alg' => 'HS256', 'typ' => 'JWT')));
    $body    = _jwt_b64url_encode(json_encode($payload));
    $sig     = _jwt_b64url_encode(hash_hmac('sha256', $header . '.' . $body, _jwt_secret(), true));

    return $header . '.' . $body . '.' . $sig;
}

/* Returns decoded payload array, or null on failure. */
function jwt_decode($token)
{
    if (!$token) return null;
    $parts = explode('.', $token);
    if (count($parts) !== 3) return null;

    list($header, $body, $sig) = $parts;

    $expected = _jwt_b64url_encode(hash_hmac('sha256', $header . '.' . $body, _jwt_secret(), true));
    if (!hash_equals($expected, $sig)) return null;

    $payload = json_decode(_jwt_b64url_decode($body), true);
    if (!is_array($payload)) return null;
    if (isset($payload['exp']) && time() > $payload['exp']) return null;

    return $payload;
}

/* Extract and validate JWT from Authorization: Bearer header. Returns payload or null. */
function jwt_from_request()
{
    $header = '';
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $header = $_SERVER['HTTP_AUTHORIZATION'];
    } elseif (function_exists('getallheaders')) {
        $all = getallheaders();
        foreach ($all as $k => $v) {
            if (strtolower($k) === 'authorization') { $header = $v; break; }
        }
    }
    if (!$header) return null;
    if (stripos($header, 'Bearer ') !== 0) return null;
    return jwt_decode(substr($header, 7));
}
