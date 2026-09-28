# Mobile API Token Refresh

This flow is for the mobile API only. Browser login continues to use PHP sessions and is not changed by these endpoints.

## Database setup

Before deploying the API changes, run `database/mobile_refresh_tokens.sql` on the application database. The server stores only SHA-256 hashes of refresh tokens; the mobile app must store the returned raw refresh token securely on the device.

## Login

`POST /api/auth/login`

```json
{"phone":"...","password":"..."}
```

On success, the response includes `access_token` (also returned in the legacy `token` field), `expires_in` (900 seconds), `refresh_token`, and `refresh_expires_in` (604800 seconds, one week). Send the access token as `Authorization: Bearer <access_token>` for protected API calls.

## Refresh

When an API request returns 401 because its access token expired, send:

`POST /api/auth/refresh`

```json
{"refresh_token":"<stored refresh token>"}
```

On success, the response contains a new access token and a **rotated** refresh token. Replace both stored values. A refresh token can be used once; concurrent requests should share a single refresh operation, then retry their original requests once with the new access token. Do not retry the refresh endpoint itself.

Each successful refresh starts a new one-week refresh-token validity period. If it has expired, been revoked, or already used, the endpoint returns 401 and the user must sign in again. The mobile client should clear its stored tokens when refresh fails.

## Logout

`POST /api/auth/logout`

```json
{"refresh_token":"<stored refresh token>"}
```

Logout revokes that refresh token. The current short-lived access JWT remains usable until its 15-minute expiry.