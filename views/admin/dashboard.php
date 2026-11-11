<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <h4 class="tw-font-bold tw-flex tw-items-center tw-gap-2">
                    <i class="fa fa-plug"></i> API Data Builder Sample
                    <small class="text-muted tw-ml-2">Powered by <a href="https://codecanyon.net/item/data-builder-for-perfex-crm/62297963" target="_blank" style="color:inherit;text-decoration:underline dotted;">Data Builder - Visual Reporting, REST API, GraphQL &amp; MCP for Perfex CRM</a></small>
                </h4>
                <hr>

                <!-- Module Intro Notice -->
                <div class="alert alert-info" style="border-left:4px solid #3498db;background:#eaf4fc;margin-bottom:20px;">
                    <div style="display:flex;align-items:flex-start;gap:12px;">
                        <i class="fa fa-info-circle" style="font-size:22px;color:#3498db;margin-top:2px;"></i>
                        <div>
                            <strong style="font-size:13px;">About this module</strong>
                            <p style="margin:6px 0 0;font-size:12.5px;line-height:1.7;color:#333;">
                                <strong>API Data Builder Sample</strong> is the official companion module that demonstrates how to connect to and consume the <strong>REST API</strong>, <strong>GraphQL</strong>, <strong>Native MCP Server (AI Agents)</strong>, and <strong>Webhooks 2.0</strong> provided by <a href="https://codecanyon.net/item/data-builder-for-perfex-crm/62297963" target="_blank" style="color:#3498db;font-weight:600;">Data Builder - Visual Reporting, REST API, GraphQL &amp; MCP for Perfex CRM</a> across all Core CRM and 3rd-party module tables (SaaS, Accounting, Inventory, Orders, Products, HRM).
                                Explore the <a href="https://databuilder.polyxgo.com/api_docs" target="_blank" style="color:#3498db;font-weight:600;">Live Interactive API &amp; MCP Documentation</a> for full endpoint schemas and AI setup guides.
                            </p>
                        </div>
                    </div>
                </div>
        </div>

        <?php if (!$is_configured) { ?>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="alert alert-warning text-center" style="padding:30px;">
                    <i class="fa fa-exclamation-triangle fa-3x" style="margin-bottom:15px;display:block;"></i>
                    <h4>API Not Configured</h4>
                    <p>Please configure your API connection settings to start using the demo features.</p>
                    <a href="<?php echo admin_url('api_data_builder_sample/settings'); ?>" class="btn btn-info btn-lg" style="margin-top:10px;">
                        <i class="fa fa-cog"></i> Configure API Settings
                    </a>
                </div>
            </div>
        </div>
        <?php } else { ?>

        <!-- Connection Status -->
        <div class="row" style="margin-bottom:20px;">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5><i class="fa fa-server"></i> Connection Status</h5>
                                <table class="table table-condensed no-margin">
                                    <tr>
                                        <td style="width:130px;"><strong>API URL</strong></td>
                                        <td><code><?php echo htmlspecialchars($api->getBaseUrl()); ?></code></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status</strong></td>
                                        <td id="connStatusCell">
                                            <i class="fa fa-spinner fa-spin"></i> <span class="text-muted">Testing connection...</span>
                                        </td>
                                    </tr>
                                    <tr id="connMetaRow" style="display:none;">
                                        <td><strong>Records</strong></td>
                                        <td id="connMetaCell">-</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5><i class="fa fa-book"></i> Quick Links</h5>
                                <div class="list-group" style="margin-bottom:0;">
                                    <a href="<?php echo admin_url('api_data_builder_sample/rest?resource=projects'); ?>" class="list-group-item">
                                        <i class="fa fa-exchange text-info"></i> REST API - Projects Demo
                                    </a>
                                    <a href="<?php echo admin_url('api_data_builder_sample/rest?resource=staff'); ?>" class="list-group-item">
                                        <i class="fa fa-users text-success"></i> REST API - Staff Management
                                    </a>
                                    <a href="<?php echo admin_url('api_data_builder_sample/graphql'); ?>" class="list-group-item">
                                        <i class="fa fa-share-alt text-primary"></i> GraphQL API Demos
                                    </a>
                                    <a href="<?php echo admin_url('api_data_builder_sample/mcp'); ?>" class="list-group-item">
                                        <i class="fa fa-microchip text-success"></i> MCP Server - AI Agents (Claude, Gemini, DeepSeek)
                                    </a>
                                    <a href="<?php echo admin_url('api_data_builder_sample/webhooks'); ?>" class="list-group-item">
                                        <i class="fa fa-rss text-warning"></i> Webhooks 2.0 Integration Guide
                                    </a>
                                    <a href="<?php echo admin_url('api_data_builder_sample/settings'); ?>" class="list-group-item">
                                        <i class="fa fa-cog text-muted"></i> Settings
                                    </a>
                                    <a href="https://databuilder.polyxgo.com/api_docs" target="_blank" class="list-group-item">
                                        <i class="fa fa-external-link text-primary"></i> Live API &amp; MCP Documentation (databuilder.polyxgo.com/api_docs)
                                    </a>
                                    <a href="https://codecanyon.net/item/data-builder-for-perfex-crm/62297963" target="_blank" class="list-group-item">
                                        <i class="fa fa-shopping-cart text-danger"></i> Get Data Builder for Perfex CRM on CodeCanyon
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feature Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="panel_s">
                    <div class="panel-body text-center" style="padding:25px 15px;">
                        <i class="fa fa-exchange fa-3x text-info" style="margin-bottom:15px;"></i>
                        <h4>RESTful API</h4>
                        <p class="text-muted">Full CRUD, Batch API, Idempotency-Key, Sales <code>items[]</code> &amp; Report KPIs.</p>
                        <a href="<?php echo admin_url('api_data_builder_sample/rest'); ?>" class="btn btn-info">
                            Explore REST API <i class="fa fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel_s">
                    <div class="panel-body text-center" style="padding:25px 15px;">
                        <i class="fa fa-share-alt fa-3x text-primary" style="margin-bottom:15px;"></i>
                        <h4>GraphQL API</h4>
                        <p class="text-muted">Query exactly what you need with flexible filtering, mutations, and nested access.</p>
                        <a href="<?php echo admin_url('api_data_builder_sample/graphql'); ?>" class="btn btn-primary">
                            Explore GraphQL <i class="fa fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel_s">
                    <div class="panel-body text-center" style="padding:25px 15px;">
                        <i class="fa fa-microchip fa-3x text-success" style="margin-bottom:15px;"></i>
                        <h4>MCP (AI Agents)</h4>
                        <p class="text-muted">3-Pillar JSON-RPC 2.0 MCP for Claude, Gemini, DeepSeek, Cursor &amp; n8n.</p>
                        <a href="<?php echo admin_url('api_data_builder_sample/mcp'); ?>" class="btn btn-success">
                            Explore MCP <i class="fa fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel_s">
                    <div class="panel-body text-center" style="padding:25px 15px;">
                        <i class="fa fa-rss fa-3x text-warning" style="margin-bottom:15px;"></i>
                        <h4>Webhooks 2.0</h4>
                        <p class="text-muted">Core Hooks Bridge, Async Retry Queue, SSRF Guard &amp; Replay-Safe HMAC.</p>
                        <a href="<?php echo admin_url('api_data_builder_sample/webhooks'); ?>" class="btn btn-warning">
                            Webhook Guide <i class="fa fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <?php } ?>
    </div>
</div>
<?php init_tail(); ?>
<?php if ($is_configured) { ?>
<script>
(async function() {
    var API_BASE = '<?php echo htmlspecialchars(rtrim(get_option("api_sample_base_url") ?: "", "/")); ?>';
    var API_TOKEN = '<?php echo htmlspecialchars(get_option("api_sample_api_token") ?: ""); ?>';
    var HMAC_SECRET = '<?php echo htmlspecialchars(get_option("api_sample_hmac_secret") ?: ""); ?>';

    var url = API_BASE + '/api/v1/auth/test';
    var headers = {
        'Authorization': 'Bearer ' + API_TOKEN,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    };

    if (HMAC_SECRET) {
        var timestamp = Math.floor(Date.now() / 1000).toString();
        var bodyBytes = new TextEncoder().encode('');
        var bodyHashBuf = await crypto.subtle.digest('SHA-256', bodyBytes);
        var bodyHash = Array.from(new Uint8Array(bodyHashBuf)).map(b => b.toString(16).padStart(2, '0')).join('');
        var canonical = ['GET', '/api/v1/auth/test', '', bodyHash, timestamp].join('\n');
        var key = await crypto.subtle.importKey('raw', new TextEncoder().encode(HMAC_SECRET), {name: 'HMAC', hash: 'SHA-256'}, false, ['sign']);
        var sigBuf = await crypto.subtle.sign('HMAC', key, new TextEncoder().encode(canonical));
        var signature = Array.from(new Uint8Array(sigBuf)).map(b => b.toString(16).padStart(2, '0')).join('');
        headers['X-Signature'] = signature;
        headers['X-Timestamp'] = timestamp;
    }

    try {
        var t0 = Date.now();
        var resp = await fetch(url, {method: 'GET', headers: headers});
        var json = await resp.json();
        var ms = Date.now() - t0;

        var cell = document.getElementById('connStatusCell');
        if (resp.ok) {
            cell.innerHTML = '<span class="label label-success"><i class="fa fa-check"></i> Connected</span>' +
                '<span class="text-muted" style="margin-left:8px;">' + ms + 'ms</span>';
            var metaRow = document.getElementById('connMetaRow');
            metaRow.style.display = '';
            var d = json.data || json;
            var scopes = Array.isArray(d.scopes) ? d.scopes.join(', ') : 'Active';
            document.getElementById('connMetaCell').textContent = 'Token: ' + (d.token_name || 'Valid') + ' | Scopes: ' + scopes;
        } else {
            cell.innerHTML = '<span class="label label-danger"><i class="fa fa-times"></i> Failed</span>' +
                '<span class="text-danger" style="margin-left:8px;">' + (json.detail || json.title || 'Status ' + resp.status) + '</span>';
        }
    } catch (e) {
        document.getElementById('connStatusCell').innerHTML =
            '<span class="label label-danger"><i class="fa fa-times"></i> Error</span>' +
            '<span class="text-danger" style="margin-left:8px;">' + e.message + '</span>';
    }
})();
</script>
<?php } ?>
