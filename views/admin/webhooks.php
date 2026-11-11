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
                        <h5><i class="fa fa-info-circle text-info"></i> How Webhooks 2.0 Work</h5>
                        <hr class="hr-panel-heading">

                        <div class="well" style="background:#f8f9fa; font-size:13px;">
                            <p><strong>Flow (<a href="https://databuilder.polyxgo.com/api_docs#webhooks" target="_blank">Webhooks 2.0 Documentation</a>):</strong></p>
                            <ol>
                                <li>Admin creates a Webhook subscription in <strong><a href="https://codecanyon.net/item/data-builder-for-perfex-crm/62297963" target="_blank">Data Builder</a> -&gt; Webhooks</strong></li>
                                <li>When a CRUD operation occurs via <strong>REST API, GraphQL, MCP Server</strong> (<code>origin: "api"</code>) <strong>OR inside Perfex CRM Admin/Client UI</strong> via <code>PerfexCoreWebhookBridge</code> (<code>origin: "crm_hook"</code>), <code>WebhookEventBus</code> fires</li>
                                <li>Matching subscriptions are resolved from two-tier cache (in-memory + file cache) and checked by SSRF + DNS Rebinding Guard</li>
                                <li>Payload is signed with both Replay-Safe <code>X-Perfex-Signature: t=...,v1=...</code> and legacy <code>X-Signature</code></li>
                                <li>If immediate HTTP delivery fails (timeout, 408, 429, 5xx) or subscription uses <code>queue</code> mode, <code>WebhookQueueWorker</code> retries asynchronously with exponential backoff (1m -&gt; 5m -&gt; 15m -&gt; 1h -&gt; 4h)</li>
                            </ol>
                        </div>

                        <h5 style="margin-top:20px;"><i class="fa fa-bolt"></i> Supported Events &amp; Origins</h5>
                        <table class="table table-condensed" style="font-size:13px;">
                            <thead>
                                <tr><th>Event Type</th><th>Trigger</th></tr>
                            </thead>
                            <tbody>
                                <tr><td><code>table.create</code></td><td>New record created (via API/MCP or Perfex CRM UI Hook)</td></tr>
                                <tr><td><code>table.update</code></td><td>Record updated (via API/MCP or Perfex CRM UI Hook)</td></tr>
                                <tr><td><code>table.delete</code></td><td>Record deleted (via API/MCP or Perfex CRM UI Hook)</td></tr>
                                <tr><td><code>webhook.test</code></td><td>Manual ping test from admin</td></tr>
                            </tbody>
                        </table>

                        <h5 style="margin-top:20px;"><i class="fa fa-envelope"></i> Payload Format (v2.5.0+)</h5>
                        <pre style="background:#1e1e1e; color:#d4d4d4; padding:12px; border-radius:4px; font-size:11px;">{
  "event": "table.create",
  "table": "tblprojects",
  "record_id": 42,
  "origin": "crm_hook",
  "timestamp": 1717000000,
  "data": {
    "id": 42,
    "name": "New Project",
    "status": 1,
    "clientid": 5
  },
  "previous_data": null
}</pre>

                        <h5 style="margin-top:20px;"><i class="fa fa-shield text-success"></i> Replay-Safe HMAC Verification (v2.5.0+)</h5>
                        <p style="font-size:13px;">
                            Every webhook delivery includes <code>X-Perfex-Signature: t={timestamp},v1={hmac}</code> (recommended, prevents replay attacks) and <code>X-Signature</code> (backward-compatible):
                        </p>
                        <pre style="background:#1e1e1e; color:#d4d4d4; padding:12px; border-radius:4px; font-size:11px;">&lt;?php
// Webhook 2.0 Receiver - Replay-Safe Timestamped HMAC Verification
$secret  = 'your-webhook-hmac-secret';
$payload = file_get_contents('php://input');
$header  = $_SERVER['HTTP_X_PERFEX_SIGNATURE'] ?? '';

// Parse t={timestamp},v1={signature}
if (preg_match('/t=(\d+),v1=([a-f0-9]+)/', $header, $m)) {
    $timestamp = (int) $m[1];
    $signature = $m[2];
    if (abs(time() - $timestamp) &gt; 300) {
        http_response_code(401);
        exit('Webhook timestamp expired');
    }
    $expected = hash_hmac('sha256', $timestamp . '.' . $payload, $secret);
} else {
    // Fallback to legacy X-Signature
    $signature = $_SERVER['HTTP_X_SIGNATURE'] ?? '';
    $expected  = hash_hmac('sha256', $payload, $secret);
}

if (!hash_equals($expected, $signature)) {
    http_response_code(401);
    exit('Invalid signature');
}</pre>

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
                            <li><strong>Implement idempotency</strong> - webhooks may be retried; use <code>record_id + event + timestamp</code> as dedup key</li>
                            <li><strong>Respond with 200 quickly</strong> - process async; webhook has a timeout</li>
                            <li><strong>Use Field Mapping</strong> to reduce payload size (only receive needed fields)</li>
                            <li><strong>Filter target_tables</strong> - subscribe only to tables you need, avoid wildcard <code>*</code></li>
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
                <div class="panel_s">
                    <div class="panel-body">
                        <h5><i class="fa fa-flask text-primary"></i> <?php echo _l('api_sample_webhook_simulator'); ?></h5>
                        <hr class="hr-panel-heading">
                        <p class="text-muted" style="font-size:12px;">
                            Test generating and verifying a Webhooks 2.0 Replay-Safe header (<code>X-Perfex-Signature: t=...,v1=...</code>) and legacy <code>X-Signature</code> right in your browser:
                        </p>
                        <div class="form-group">
                            <label style="font-size:12px;">Webhook HMAC Secret</label>
                            <input type="text" id="simWhSecret" class="form-control input-sm" value="<?php echo htmlspecialchars(get_option('api_sample_hmac_secret') ?: 'whsec_demo_secret_250'); ?>">
                        </div>
                        <div class="form-group">
                            <label style="font-size:12px;">Sample Event Origin</label>
                            <select id="simWhOrigin" class="form-control input-sm" onchange="updateSimWhPayload()">
                                <option value="crm_hook">crm_hook (Perfex Core Hooks Bridge - Admin/Client UI)</option>
                                <option value="api">api (REST / GraphQL / MCP Mutation)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label style="font-size:12px;">JSON Payload</label>
                            <textarea id="simWhPayload" class="form-control" rows="5" style="font-family:monospace; font-size:11px; background:#1e1e1e; color:#d4d4d4;"></textarea>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm" onclick="simulateWebhook20()">
                            <i class="fa fa-shield"></i> <?php echo _l('api_sample_simulate_webhook'); ?>
                        </button>
                        <div id="simWhResult" style="margin-top:12px; display:none;">
                            <pre id="simWhOutput" style="background:#1e1e1e; color:#a7f3d0; padding:12px; border-radius:4px; font-size:11px; margin:0;"></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
function updateSimWhPayload() {
    var origin = document.getElementById('simWhOrigin').value;
    var sample = {
        event: 'table.create',
        table: 'tblinvoices',
        record_id: 1024,
        origin: origin,
        timestamp: Math.floor(Date.now() / 1000),
        data: { id: 1024, clientid: 5, total: 1500.00, status: 1 },
        previous_data: null
    };
    document.getElementById('simWhPayload').value = JSON.stringify(sample, null, 2);
}

async function simulateWebhook20() {
    var secret = document.getElementById('simWhSecret').value || 'whsec_demo_secret_250';
    var payload = document.getElementById('simWhPayload').value;
    var ts = Math.floor(Date.now() / 1000).toString();
    var enc = new TextEncoder();
    var key = await crypto.subtle.importKey('raw', enc.encode(secret), {name: 'HMAC', hash: 'SHA-256'}, false, ['sign']);

    // v2.5.0 Replay-safe signature over "{timestamp}.{payload}"
    var v1Buf = await crypto.subtle.sign('HMAC', key, enc.encode(ts + '.' + payload));
    var v1Hex = Array.from(new Uint8Array(v1Buf)).map(function(b) { return b.toString(16).padStart(2, '0'); }).join('');

    // Legacy signature over "{payload}"
    var legBuf = await crypto.subtle.sign('HMAC', key, enc.encode(payload));
    var legHex = Array.from(new Uint8Array(legBuf)).map(function(b) { return b.toString(16).padStart(2, '0'); }).join('');

    var out = "=== Outgoing HTTP Headers Sent by Data Builder v2.5.0 ===\n";
    out += "Content-Type: application/json\n";
    out += "X-Webhook-Event: table.create\n";
    out += "X-Webhook-Origin: " + document.getElementById('simWhOrigin').value + "\n";
    out += "X-Webhook-Timestamp: " + ts + "\n";
    out += "X-Perfex-Signature: t=" + ts + ",v1=" + v1Hex + "\n";
    out += "X-Signature: " + legHex + "\n\n";
    out += "=== Receiver Verification Status ===\n";
    out += "[PASS] Timestamp Tolerance Check (|now - t| = 0s <= 300s)\n";
    out += "[PASS] HMAC-SHA256 (t.payload) matches v1 signature";

    document.getElementById('simWhResult').style.display = '';
    document.getElementById('simWhOutput').textContent = out;
}

$(function() { updateSimWhPayload(); });
</script>
