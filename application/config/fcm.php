<?php
/**
 * Firebase / FCM configuration.
 *
 * IMPORTANT:
 * - Never commit the real service account JSON to the repository.
 * - Prefer environment variables or a file outside the public web root.
 */

if (!function_exists('get_fcm_config')) {
    function get_fcm_config()
    {
        $config = [
            'project_id' => getenv('FIREBASE_PROJECT_ID') ?: '',
            'client_email' => getenv('FIREBASE_CLIENT_EMAIL') ?: '',
            'private_key' => getenv('FIREBASE_PRIVATE_KEY') ?: '',
            'service_account_path' => getenv('FIREBASE_CONFIG_PATH') ?: '',
        ];

        if (!empty($config['service_account_path']) && file_exists($config['service_account_path'])) {
            $json = json_decode(file_get_contents($config['service_account_path']), true);
            if (is_array($json)) {
                $config['project_id'] = $json['project_id'] ?? $config['project_id'];
                $config['client_email'] = $json['client_email'] ?? $config['client_email'];
                $config['private_key'] = $json['private_key'] ?? $config['private_key'];
            }
        }

        return $config;
    }
}
