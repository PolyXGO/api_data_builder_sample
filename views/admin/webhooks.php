<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <h4 class="tw-font-bold">
                    <i class="fa fa-rss"></i> Webhook Integration Guide
                    <a href="<?php echo admin_url('api_data_builder_sample'); ?>" class="btn btn-default btn-sm pull-right">
                        <i class="fa fa-arrow-left"></i> Back to Dashboard
                    </a>
                </h4>
                <hr>
            </div>
        </div>

        <div class="row">
            <!-- Left: How It Works -->
            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body">
                        <h5><i class="fa fa-info-circle text-info"></i> How Webhooks Work</h5>
                        <hr class="hr-panel-heading">

                        <div class="well" style="background:#f8f9fa; font-size:13px;">
                            <p><strong>Flow:</strong></p>
                            <ol>
                                <li>Admin creates a Webhook subscription in <strong>Data Builder → Webhooks</strong></li>
                                <li>When a CRUD operation occurs via REST or GraphQL API, the <code>WebhookEventBus</code> fires</li>
                                <li>Matching subscriptions are found (by event type + target tables)</li>
                                <li>Payload is sent to the configured URL via HTTP POST with HMAC-SHA256 signing</li>
                                <li>Delivery result is logged in <code>polydb_webhook_logs</code></li>
                            </ol>
                        </div>

                        <h5 style="margin-top:20px;"><i class="fa fa-bolt"></i> Supported Events</h5>
                        <table class="table table-condensed" style="font-size:13px;">
                            <thead>
                                <tr><th>Event Type</th><th>Trigger</th></tr>
                            </thead>
                            <tbody>
                                <tr><td><code>table.create</code></td><td>New record created via API</td></tr>
                                <tr><td><code>table.update</code></td><td>Record updated via API</td></tr>
                                <tr><td><code>table.delete</code></td><td>Record deleted via API</td></tr>
                                <tr><td><code>webhook.test</code></td><td>Manual ping test from admin</td></tr>
                            </tbody>
                        </table>

                        <h5 style="margin-top:20px;"><i class="fa fa-envelope"></i> Payload Format</h5>
                        <pre style="background:#1e1e1e; color:#d4d4d4; padding:12px; border-radius:4px; font-size:11px;">{
  "event": "table.create",
  "table": "tblprojects",
  "record_id": 42,
  "timestamp": 1717000000,
  "data": {
    "id": 42,
    "name": "New Project",
    "status": 1,
    "clientid": 5
  },
  "previous_data": null
}</pre>

                        <h5 style="margin-top:20px;"><i class="fa fa-shield text-success"></i> HMAC Verification</h5>
                        <p style="font-size:13px;">
                            Every webhook delivery includes an <code>X-Signature</code> header.
                            Verify it in your receiver to ensure the payload is authentic:
                        </p>
                        <pre style="background:#1e1e1e; color:#d4d4d4; padding:12px; border-radius:4px; font-size:11px;">&lt;?php
// Webhook Receiver — HMAC Verification
$secret    = 'your-webhook-hmac-secret';
$payload   = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_SIGNATURE'] ?? '';
$expected  = hash_hmac('sha256', $payload, $secret);

if (!hash_equals($expected, $signature)) {
    http_response_code(401);
    exit('Invalid signature');
}

// Process the webhook event
$event = json_decode($payload, true);
switch ($event['event']) {
    case 'table.create':
        // Handle new record
        break;
    case 'table.update':
        // Handle updated record
        $changes = array_diff_assoc(
            $event['data'],
            $event['previous_data'] ?? []
        );
        break;
    case 'table.delete':
        // Handle deleted record
        break;
}

http_response_code(200);
echo json_encode(['status' => 'ok']);</pre>
                    </div>
                </div>
            </div>

            <!-- Right: Use Cases + Best Practices -->
            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body">
                        <h5><i class="fa fa-lightbulb-o text-warning"></i> Production Use Cases</h5>
                        <hr class="hr-panel-heading">
                        <table class="table table-condensed" style="font-size:13px;">
                            <thead>
                                <tr><th>Use Case</th><th>Event</th><th>Description</th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>CRM → ERP Sync</strong></td>
                                    <td><code>table.create</code> on <code>tblinvoices</code></td>
                                    <td>Auto-create invoices in external ERP when created in CRM</td>
                                </tr>
                                <tr>
                                    <td><strong>Slack Notifications</strong></td>
                                    <td><code>table.create</code> on <code>tbltasks</code></td>
                                    <td>Post a notification to Slack when a new task is assigned</td>
                                </tr>
                                <tr>
                                    <td><strong>Audit Trail</strong></td>
                                    <td><code>table.update</code> on <code>tblstaff</code></td>
                                    <td>Log all staff profile changes for compliance</td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Receipts</strong></td>
                                    <td><code>table.create</code> on <code>tblpaymentsrecords</code></td>
                                    <td>Trigger receipt email via SendGrid/Mailgun</td>
                                </tr>
                                <tr>
                                    <td><strong>Data Warehouse</strong></td>
                                    <td><code>table.*</code> on <code>*</code></td>
                                    <td>Stream all changes to BigQuery or Snowflake</td>
                                </tr>
                                <tr>
                                    <td><strong>Zapier / Make.com</strong></td>
                                    <td><code>table.*</code></td>
                                    <td>No-code automation with webhook triggers</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="panel_s">
                    <div class="panel-body">
                        <h5><i class="fa fa-check-circle text-success"></i> Best Practices</h5>
                        <hr class="hr-panel-heading">
                        <ul style="font-size:13px; line-height:1.8;">
                            <li><strong>Always use HTTPS</strong> for your webhook receiver URL</li>
                            <li><strong>Verify HMAC signature</strong> on every incoming request</li>
                            <li><strong>Implement idempotency</strong> — webhooks may be retried; use <code>record_id + event + timestamp</code> as dedup key</li>
                            <li><strong>Respond with 200 quickly</strong> — process async; webhook has a timeout</li>
                            <li><strong>Use Field Mapping</strong> to reduce payload size (only receive needed fields)</li>
                            <li><strong>Filter target_tables</strong> — subscribe only to tables you need, avoid wildcard <code>*</code></li>
                            <li><strong>Monitor delivery logs</strong> in Data Builder → Webhooks → Logs</li>
                            <li><strong>Set up alerting</strong> for consecutive delivery failures</li>
                        </ul>
                    </div>
                </div>

                <div class="panel_s">
                    <div class="panel-body">
                        <h5><i class="fa fa-cogs"></i> Setup Steps</h5>
                        <hr class="hr-panel-heading">
                        <ol style="font-size:13px; line-height:2;">
                            <li>Go to <strong>Data Builder → Webhooks</strong></li>
                            <li>Click <strong>+ New Subscription</strong></li>
                            <li>Enter your receiver URL (e.g. <code>https://your-app.com/webhook</code>)</li>
                            <li>Select event types: <code>table.create</code>, <code>table.update</code>, <code>table.delete</code></li>
                            <li>Select target tables (e.g. <code>tblprojects</code>, <code>tbltasks</code>)</li>
                            <li>Enable and save</li>
                            <li>Click <strong>Ping</strong> to test delivery</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
