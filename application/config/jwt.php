<?php
/*
 * JWT secret key. Override with the JWT_SECRET environment variable in production.
 * Must be at least 32 random characters.
 */
define('JWT_SECRET', getenv('JWT_SECRET') ?: 'jnanasudha_jwt_secret_change_me_32chars');
