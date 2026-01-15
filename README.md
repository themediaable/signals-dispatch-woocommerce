# Signals Dispatch for WooCommerce

WooCommerce order updates → templated utility messages with queueing, logs, and webhook status updates.

## Local development (wp-env)
1. From repo root, start WordPress:
   - `wp-env start`
2. Install plugin dependencies (from the plugin directory):
   - `composer install`
3. Visit http://localhost:8888/wp-admin
4. Activate the plugin:
   - **Plugins → Signals Dispatch for WooCommerce → Activate**

> If wp-env is not available, install it with `npm i -g @wordpress/env`.

## Plugin setup
1. **Signals → Setup → Step 1**
   - Phone Number ID
   - WABA ID
   - Access Token
   - Webhook Verify Token
2. **Signals → Setup → Step 2**
   - Webhook endpoint shown in the UI
3. **Signals → Setup → Step 3**
   - Send a test message

## Webhook endpoint
`/wp-json/tmasd/v1/webhook`

- Send the verify token via header `X-TMASD-Verify-Token` or request param `verify_token`.

## Dispatch Rules (template mapping)
Each event key maps to a WhatsApp template and a JSON array of resolver keys:

Example mapping:
```json
["order_number", "order_total", "status"]
```
Available resolver keys:
- `order_id`, `order_number`, `order_total`, `order_currency`
- `billing_first_name`, `billing_last_name`, `billing_phone`, `billing_email`
- `shipping_first_name`, `shipping_last_name`
- `status`, `site_name`

## Troubleshooting
- **No messages sent**: verify credentials, confirm Action Scheduler is available (WooCommerce provides it).
- **Webhook updates missing**: verify token mismatch or endpoint misconfigured.
- **Logs empty**: ensure Dispatch Rules are enabled for the status and the order has a billing phone.
