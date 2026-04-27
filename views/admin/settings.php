<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php
// Demo restriction: disable all fields for non-primary admins on demo sites
$_api_disabled = !empty($is_demo_site) && empty($is_primary_admin);
$_dis = $_api_disabled ? ' disabled' : '';
?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin">
                            <i class="fa fa-cog"></i> API Connection Settings
                            <small class="pull-right text-muted">API Data Builder Sample v1.0.0</small>
                        </h4>
                        <hr class="hr-panel-heading">

                        <?php if ($_api_disabled): ?>
                        <div class="alert alert-warning" style="display:flex;align-items:center;gap:10px;margin-bottom:20px;">
                            <i class="fa fa-lock fa-lg"></i>
                            <div>
                                <strong>Read-only mode</strong> — All settings on this page are restricted on the demo site. Only the primary admin can modify them.
                            </div>
                        </div>
                        <?php endif; ?>

                        <div id="settingsForm">
                            <div class="form-group">
                                <label class="control-label">API Base URL <span class="text-danger">*</span></label>
                                <input type="url" name="api_sample_base_url" class="form-control" id="apiBaseUrl"
                                       value="<?php echo htmlspecialchars(get_option('api_sample_base_url') ?: ''); ?>"
                                       placeholder="https://your-crm.com"<?php echo $_dis; ?>>
                                <p class="help-block">The base URL of your Perfex CRM installation where Data Builder is installed.</p>
                            </div>

                            <div class="form-group">
                                <label class="control-label">API Token <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="api_sample_api_token" class="form-control" id="apiToken"
                                           value="<?php echo htmlspecialchars(get_option('api_sample_api_token') ?: ''); ?>"
                                           placeholder="dba_xxxxxxxxxxxxxxxxxxxx"<?php echo $_dis; ?>>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default" onclick="toggleTokenVisibility()" title="Toggle visibility">
                                            <i class="fa fa-eye" id="tokenToggleIcon"></i>
                                        </button>
                                    </span>
                                </div>
                                <p class="help-block">Bearer token from Data Builder → API Tokens.</p>
                            </div>

                            <div class="form-group">
                                <label class="control-label">HMAC Secret <small class="text-muted">(optional)</small></label>
                                <input type="password" name="api_sample_hmac_secret" class="form-control" id="hmacSecret"
                                       value="<?php echo htmlspecialchars(get_option('api_sample_hmac_secret') ?: ''); ?>"
                                       placeholder="Leave blank if HMAC is not enabled on the token"<?php echo $_dis; ?>>
                                <p class="help-block">If your API token has HMAC signing enabled, paste the HMAC secret here.</p>
                            </div>

                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="api_sample_verify_ssl" id="verifySSL" value="1"
                                        <?php echo get_option('api_sample_verify_ssl') ? 'checked' : ''; ?><?php echo $_dis; ?>>
                                    Verify SSL Certificate
                                </label>
                                <p class="help-block text-warning">
                                    <i class="fa fa-exclamation-triangle"></i>
                                    Disable for local development with self-signed certificates. Always enable in production.
                                </p>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-6">
                                 <?php if (!$_api_disabled): ?>
                                    <button type="button" class="btn btn-info" onclick="saveSettings()">
                                        <i class="fa fa-save"></i> Save Settings
                                    </button>
                                 <?php else: ?>
                                    <button type="button" class="btn btn-info" disabled>
                                        <i class="fa fa-lock"></i> Save Settings
                                    </button>
                                 <?php endif; ?>
                                </div>
                                <div class="col-md-6 text-right">
                                    <button type="button" class="btn btn-success" onclick="testConnection()" id="btnTestConn">
                                        <i class="fa fa-plug"></i> Test Connection
                                    </button>
                                </div>
                            </div>

                            <!-- Connection Test Result -->
                            <div id="connectionResult" style="margin-top:20px; display:none;">
                                <div class="alert" id="connectionAlert">
                                    <pre id="connectionResponse" style="background:#1e1e1e; color:#d4d4d4; padding:12px; border-radius:4px; max-height:300px; overflow:auto; font-size:12px;"></pre>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
function toggleTokenVisibility() {
    var inp = document.getElementById('apiToken');
    var icon = document.getElementById('tokenToggleIcon');
    if (inp.type === 'password') { inp.type = 'text'; icon.className = 'fa fa-eye-slash'; }
    else { inp.type = 'password'; icon.className = 'fa fa-eye'; }
}

function saveSettings() {
    var data = {
        api_sample_base_url:    document.getElementById('apiBaseUrl').value,
        api_sample_api_token:   document.getElementById('apiToken').value,
        api_sample_hmac_secret: document.getElementById('hmacSecret').value,
        api_sample_verify_ssl:  document.getElementById('verifySSL').checked ? '1' : '0',
        <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
    };

    $.post(admin_url + 'api_data_builder_sample/settings', data, function(res) {
        if (res.success) {
            alert_float('success', 'Settings saved successfully!');
        } else {
            alert_float('danger', res.message || 'Failed to save.');
        }
    }, 'json').fail(function() {
        alert_float('danger', 'Request failed.');
    });
}

async function testConnection() {
    var btn = document.getElementById('btnTestConn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Testing...';

    // Save first
    saveSettings();
    await new Promise(r => setTimeout(r, 600));

    // Read current field values directly
    var baseUrl = document.getElementById('apiBaseUrl').value.replace(/\/$/, '');
    var token = document.getElementById('apiToken').value;
    var hmacSecret = document.getElementById('hmacSecret').value;

    if (!baseUrl || !token) {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-plug"></i> Test Connection';
        alert_float('warning', 'Please enter API URL and Token.');
        return;
    }

    // Test with /api/v1/projects?per_page=1 as a simple connectivity probe
    var endpoint = '/api/v1/projects?per_page=1';
    var url = baseUrl + endpoint;
    var headers = {
        'Authorization': 'Bearer ' + token,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    };

    // HMAC signing
    if (hmacSecret) {
        var timestamp = Math.floor(Date.now() / 1000).toString();
        var bodyBytes = new TextEncoder().encode('');
        var bodyHashBuf = await crypto.subtle.digest('SHA-256', bodyBytes);
        var bodyHash = Array.from(new Uint8Array(bodyHashBuf)).map(b => b.toString(16).padStart(2, '0')).join('');
        var canonical = ['GET', '/api/v1/projects', 'per_page=1', bodyHash, timestamp].join('\n');
        var key = await crypto.subtle.importKey('raw', new TextEncoder().encode(hmacSecret), {name: 'HMAC', hash: 'SHA-256'}, false, ['sign']);
        var sigBuf = await crypto.subtle.sign('HMAC', key, new TextEncoder().encode(canonical));
        var signature = Array.from(new Uint8Array(sigBuf)).map(b => b.toString(16).padStart(2, '0')).join('');
        headers['X-Signature'] = signature;
        headers['X-Timestamp'] = timestamp;
    }

    try {
        var resp = await fetch(url, {method: 'GET', headers: headers});
        var json = await resp.json();

        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-plug"></i> Test Connection';

        var resultDiv = document.getElementById('connectionResult');
        var alertDiv = document.getElementById('connectionAlert');
        var responseDiv = document.getElementById('connectionResponse');

        resultDiv.style.display = '';
        responseDiv.textContent = JSON.stringify({
            connected: resp.ok,
            status: resp.status,
            records: Array.isArray(json.data) ? json.data.length : 0,
            total: json.meta ? json.meta.total : null,
            execution_time_ms: json.meta ? json.meta.execution_time_ms : null,
            response: json
        }, null, 2);

        alertDiv.className = resp.ok ? 'alert alert-success' : 'alert alert-danger';
        if (resp.ok) {
            alert_float('success', 'Connection successful! ' + (json.meta ? json.meta.total + ' records accessible.' : ''));
        }
    } catch (e) {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-plug"></i> Test Connection';

        var resultDiv = document.getElementById('connectionResult');
        resultDiv.style.display = '';
        document.getElementById('connectionAlert').className = 'alert alert-danger';
        document.getElementById('connectionResponse').textContent = JSON.stringify({error: e.message}, null, 2);
        alert_float('danger', 'Connection failed: ' + e.message);
    }
}
</script>

