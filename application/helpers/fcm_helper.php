<?php
/**
 * Firebase Cloud Messaging helper.
 * FCM HTTP v1 API — Service Account JWT auth.
 */

function fcm_get_access_token()
{
    if (!empty($_SESSION['_fcm_token']) && !empty($_SESSION['_fcm_token_exp']) && time() < $_SESSION['_fcm_token_exp']) {
        return $_SESSION['_fcm_token'];
    }

    $key_path = APPPATH . 'config/firebase_service_account.json';

    if (!file_exists($key_path)) {
        error_log('[FCM] Service account JSON not found at: ' . $key_path);
        return null;
    }

    $sa = json_decode(file_get_contents($key_path), true);
    if (empty($sa['private_key']) || empty($sa['client_email'])) {
        error_log('[FCM] Invalid service account JSON');
        return null;
    }

    $now     = time();
    $header  = _fcm_base64url(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
    $payload = _fcm_base64url(json_encode([
        'iss'   => $sa['client_email'],
        'sub'   => $sa['client_email'],
        'aud'   => 'https://oauth2.googleapis.com/token',
        'iat'   => $now,
        'exp'   => $now + 3600,
        'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
    ]));

    openssl_sign($header . '.' . $payload, $signature, $sa['private_key'], OPENSSL_ALGO_SHA256);
    $jwt = $header . '.' . $payload . '.' . _fcm_base64url($signature);

    $resp = _fcm_curl_post('https://oauth2.googleapis.com/token', http_build_query([
        'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
        'assertion'  => $jwt,
    ]), ['Content-Type: application/x-www-form-urlencoded']);

    $data = json_decode(isset($resp['body']) ? $resp['body'] : '', true);
    if (empty($data['access_token'])) {
        error_log('[FCM] Failed to get access token: ' . (isset($resp['body']) ? $resp['body'] : ''));
        return null;
    }

    $_SESSION['_fcm_token']     = $data['access_token'];
    $_SESSION['_fcm_token_exp'] = $now + 3000;
    return $data['access_token'];
}

function fcm_send_multicast(array $tokens, $title, $body, array $data = [], $image = null)
{
    $result = ['success' => 0, 'failed' => 0, 'errors' => []];
    if (empty($tokens)) return $result;

    $access_token = fcm_get_access_token();
    if (!$access_token) {
        $result['failed'] = count($tokens);
        $result['errors'][] = 'Could not obtain FCM access token';
        return $result;
    }

    $key_path   = APPPATH . 'config/firebase_service_account.json';
    $sa         = json_decode(file_get_contents($key_path), true);
    $project_id = isset($sa['project_id']) ? $sa['project_id'] : '';
    $url        = "https://fcm.googleapis.com/v1/projects/{$project_id}/messages:send";

    $headers = [
        'Authorization: Bearer ' . $access_token,
        'Content-Type: application/json',
    ];

    foreach ($tokens as $token) {
        $token = trim($token);
        if ($token === '') { $result['failed']++; continue; }

        $notification = ['title' => $title, 'body' => $body];
        if ($image) $notification['image'] = $image;

        $android_notification = array(
            'sound'        => 'default',
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        );
        if ($image) $android_notification['image'] = $image;

        $apns_headers = array('apns-priority' => '10');
        $apns_payload = array('aps' => array('sound' => 'default', 'mutable-content' => 1));
        if ($image) $apns_payload['fcm_options'] = array('image' => $image);

        $msg = [
            'message' => [
                'token'        => $token,
                'notification' => $notification,
                'android' => [
                    'notification' => $android_notification,
                ],
                'apns' => [
                    'headers' => $apns_headers,
                    'payload' => $apns_payload,
                ],
            ],
        ];

        if (!empty($data)) {
            $str_data = [];
            foreach ($data as $k => $v) $str_data[(string)$k] = (string)$v;
            $msg['message']['data'] = $str_data;
        }

        $resp = _fcm_curl_post($url, json_encode($msg), $headers);

        if ($resp['http_code'] === 200) {
            $result['success']++;
        } else {
            $result['failed']++;
            $decoded = json_decode(isset($resp['body']) ? $resp['body'] : '', true);
            $err     = isset($decoded['error']['message']) ? $decoded['error']['message'] : (isset($resp['body']) ? $resp['body'] : 'Unknown error');
            $result['errors'][] = substr($token, 0, 20) . '...: ' . $err;
            error_log('[FCM] Send failed: ' . $err);
        }
    }

    return $result;
}

function _fcm_base64url($data)
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function _fcm_curl_post($url, $body, array $headers = [])
{
    $ch = curl_init($url);
    $ca_candidates = array(
        'C:/xamppnew/php/extras/ssl/cacert.pem',
        'C:/xampp/php/extras/ssl/cacert.pem',
        'C:/xamppnew/apache/bin/curl-ca-bundle.crt',
        'C:/xampp/apache/bin/curl-ca-bundle.crt',
        '/etc/ssl/certs/ca-certificates.crt',
        '/etc/pki/tls/certs/ca-bundle.crt',
    );
    $ca_file = '';
    foreach ($ca_candidates as $c) {
        if (file_exists($c)) { $ca_file = $c; break; }
    }
    $ssl_opts = $ca_file
        ? array(CURLOPT_SSL_VERIFYPEER => true, CURLOPT_CAINFO => $ca_file)
        : array(CURLOPT_SSL_VERIFYPEER => false);

    curl_setopt_array($ch, $ssl_opts + array(
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $body,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 15,
    ));
    $response  = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err       = curl_error($ch);
    curl_close($ch);
    if ($err) error_log('[FCM] cURL error: ' . $err);
    return ['body' => $response, 'http_code' => $http_code];
}
