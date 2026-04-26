<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <h4 class="tw-font-bold tw-flex tw-items-center tw-gap-2">
                    <i class="fa fa-plug"></i> API Data Builder Sample
                    <span class="label label-default" style="font-size:11px;">v1.0.0</span>
                    <small class="text-muted tw-ml-2">Powered by Data Builder</small>
                </h4>
                <hr>
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
                                        <td id="connMetaCell">—</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5><i class="fa fa-book"></i> Quick Links</h5>
                                <div class="list-group" style="margin-bottom:0;">
                                    <a href="<?php echo admin_url('api_data_builder_sample/rest?resource=projects'); ?>" class="list-group-item">
                                        <i class="fa fa-exchange text-info"></i> REST API — Projects Demo
                                    </a>
                                    <a href="<?php echo admin_url('api_data_builder_sample/rest?resource=staff'); ?>" class="list-group-item">
                                        <i class="fa fa-users text-success"></i> REST API — Staff Management
                                    </a>
                                    <a href="<?php echo admin_url('api_data_builder_sample/graphql'); ?>" class="list-group-item">
                                        <i class="fa fa-share-alt text-primary"></i> GraphQL API Demos
                                    </a>
                                    <a href="<?php echo admin_url('api_data_builder_sample/webhooks'); ?>" class="list-group-item">
                                        <i class="fa fa-rss text-warning"></i> Webhook Integration Guide
                                    </a>
                                    <a href="<?php echo admin_url('api_data_builder_sample/settings'); ?>" class="list-group-item">
                                        <i class="fa fa-cog text-muted"></i> Settings
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
            <div class="col-md-4">
                <div class="panel_s">
                    <div class="panel-body text-center" style="padding:30px 20px;">
                        <i class="fa fa-exchange fa-3x text-info" style="margin-bottom:15px;"></i>
                        <h4>RESTful API</h4>
                        <p class="text-muted">Full CRUD operations on Projects, Tasks, Staff, Invoices, Payments via standard HTTP methods.</p>
                        <a href="<?php echo admin_url('api_data_builder_sample/rest'); ?>" class="btn btn-info">
                            Explore REST API <i class="fa fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel_s">
                    <div class="panel-body text-center" style="padding:30px 20px;">
                        <i class="fa fa-share-alt fa-3x text-primary" style="margin-bottom:15px;"></i>
                        <h4>GraphQL API</h4>
                        <p class="text-muted">Query exactly what you need with flexible filtering, mutations, and nested resource access.</p>
                        <a href="<?php echo admin_url('api_data_builder_sample/graphql'); ?>" class="btn btn-primary">
                            Explore GraphQL <i class="fa fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel_s">
                    <div class="panel-body text-center" style="padding:30px 20px;">
                        <i class="fa fa-rss fa-3x text-warning" style="margin-bottom:15px;"></i>
                        <h4>Webhooks</h4>
                        <p class="text-muted">Real-time event notifications on data changes. HMAC-signed payloads for secure integration.</p>
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

    var url = API_BASE + '/api/v1/projects?per_page=1';
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
        var canonical = ['GET', '/api/v1/projects', 'per_page=1', bodyHash, timestamp].join('\n');
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
            document.getElementById('connMetaCell').textContent = (json.meta ? json.meta.total + ' projects accessible' : 'OK');
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
