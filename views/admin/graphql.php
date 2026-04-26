<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <h4 class="tw-font-bold">
                    <i class="fa fa-share-alt"></i> GraphQL API Demos
                    <a href="<?php echo admin_url('api_data_builder_sample'); ?>" class="btn btn-default btn-sm pull-right">
                        <i class="fa fa-arrow-left"></i> Back to Dashboard
                    </a>
                </h4>
                <hr>
            </div>
        </div>

        <div class="row">
            <!-- Left: Query Editor -->
            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body">
                        <h5 class="no-margin" style="margin-bottom:10px;">
                            <i class="fa fa-pencil-square-o"></i> GraphQL Query Editor
                        </h5>

                        <div style="margin-bottom:10px;">
                            <label class="control-label" style="margin-right:8px;">Quick Templates:</label>
                            <div class="btn-group">
                                <button class="btn btn-default btn-xs" onclick="loadTemplate('projects_list')">Projects</button>
                                <button class="btn btn-default btn-xs" onclick="loadTemplate('tasks_list')">Tasks</button>
                                <button class="btn btn-default btn-xs" onclick="loadTemplate('staff_list')">Staff</button>
                                <button class="btn btn-default btn-xs" onclick="loadTemplate('invoices_list')">Invoices</button>
                                <button class="btn btn-default btn-xs" onclick="loadTemplate('ping')">Ping</button>
                            </div>
                            <div class="btn-group" style="margin-left:8px;">
                                <button class="btn btn-warning btn-xs" onclick="loadTemplate('create_task')">Create Task</button>
                                <button class="btn btn-warning btn-xs" onclick="loadTemplate('create_staff')">Create Staff</button>
                                <button class="btn btn-warning btn-xs" onclick="loadTemplate('delete_invoice')">Delete Invoice</button>
                            </div>
                        </div>

                        <textarea id="gqlQuery" class="form-control" rows="14" style="font-family:monospace; font-size:12px; background:#1e1e1e; color:#d4d4d4; border:1px solid #333; resize:vertical;">query {
  projects(limit: 10, sort: "id", sort_dir: "DESC") {
    id
    name
    status
    clientid
    start_date
    deadline
  }
}</textarea>

                        <div style="margin-top:10px;">
                            <label>Variables (JSON, optional):</label>
                            <textarea id="gqlVars" class="form-control" rows="3" style="font-family:monospace; font-size:12px; background:#1e1e1e; color:#d4d4d4; border:1px solid #333;">{}</textarea>
                        </div>

                        <div style="margin-top:12px;" class="text-right">
                            <button class="btn btn-primary" onclick="executeGraphQL()">
                                <i class="fa fa-play"></i> Execute Query
                            </button>
                        </div>
                    </div>
                </div>

                <div class="panel_s">
                    <div class="panel-body">
                        <h5 class="no-margin" style="margin-bottom:10px;">
                            <i class="fa fa-code"></i> Sample Code
                            <button class="btn btn-default btn-xs pull-right" onclick="copyGqlCode()"><i class="fa fa-copy"></i> Copy</button>
                        </h5>
                        <pre id="gqlSampleCode" style="background:#1e1e1e; color:#d4d4d4; padding:12px; border-radius:4px; font-size:11px; max-height:250px; overflow:auto; white-space:pre-wrap;">Click "Execute Query" to generate sample code.</pre>
                    </div>
                </div>
            </div>

            <!-- Right: Results -->
            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body">
                        <h5 class="no-margin" style="margin-bottom:10px;">
                            <i class="fa fa-table"></i> Result Data
                            <span class="label label-default pull-right" id="gqlStatus">—</span>
                        </h5>
                        <div id="gqlTableContainer" style="max-height:400px; overflow:auto;">
                            <p class="text-muted text-center" style="padding:30px;">Execute a query to see results here.</p>
                        </div>
                    </div>
                </div>

                <div class="panel_s">
                    <div class="panel-body">
                        <h5 class="no-margin" style="margin-bottom:10px;">
                            <i class="fa fa-file-code-o"></i> Raw Response
                        </h5>
                        <pre id="gqlRawResponse" style="background:#1e1e1e; color:#d4d4d4; padding:12px; border-radius:4px; font-size:11px; max-height:400px; overflow:auto; white-space:pre-wrap;">Waiting for query execution...</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
var API_BASE = '<?php echo htmlspecialchars(rtrim(get_option("api_sample_base_url") ?: "", "/")); ?>';
var API_TOKEN = '<?php echo htmlspecialchars(get_option("api_sample_api_token") ?: ""); ?>';
var HMAC_SECRET = '<?php echo htmlspecialchars(get_option("api_sample_hmac_secret") ?: ""); ?>';

// ─── HMAC Signing ────────────────────────────────────────────────────────

async function computeHmac(method, path, queryString, body) {
    if (!HMAC_SECRET) return {};
    var timestamp = Math.floor(Date.now() / 1000).toString();
    var bodyBytes = new TextEncoder().encode(body || '');
    var bodyHashBuf = await crypto.subtle.digest('SHA-256', bodyBytes);
    var bodyHash = Array.from(new Uint8Array(bodyHashBuf)).map(b => b.toString(16).padStart(2, '0')).join('');
    var canonical = [method, path, queryString, bodyHash, timestamp].join('\n');
    var key = await crypto.subtle.importKey('raw', new TextEncoder().encode(HMAC_SECRET), {name: 'HMAC', hash: 'SHA-256'}, false, ['sign']);
    var sigBuf = await crypto.subtle.sign('HMAC', key, new TextEncoder().encode(canonical));
    var signature = Array.from(new Uint8Array(sigBuf)).map(b => b.toString(16).padStart(2, '0')).join('');
    return {'X-Signature': signature, 'X-Timestamp': timestamp};
}

async function apiCall(method, endpoint, body) {
    var url = API_BASE + endpoint;
    var parsed = new URL(url);
    var bodyStr = body ? (typeof body === 'string' ? body : JSON.stringify(body)) : '';
    var hmacHeaders = await computeHmac(method, parsed.pathname, parsed.search ? parsed.search.substring(1) : '', bodyStr);
    var headers = {'Authorization': 'Bearer ' + API_TOKEN, 'Content-Type': 'application/json', 'Accept': 'application/json', ...hmacHeaders};
    var opts = {method: method, headers: headers};
    if (bodyStr && (method === 'POST' || method === 'PUT')) opts.body = bodyStr;

    var t0 = Date.now();
    try {
        var resp = await fetch(url, opts);
        var text = await resp.text();
        var json;
        try {
            json = JSON.parse(text);
        } catch (parseErr) {
            return {success: false, status: resp.status, data: null, error: 'Server returned non-JSON (HTTP ' + resp.status + ')', raw: text.substring(0, 500), time_ms: Date.now() - t0};
        }
        return {success: resp.ok, status: resp.status, data: json.data || json, raw: JSON.stringify(json, null, 2), time_ms: Date.now() - t0};
    } catch (e) {
        return {success: false, status: 0, data: null, error: 'Network error: ' + e.message, raw: '', time_ms: Date.now() - t0};
    }
}

// ─── Templates ───────────────────────────────────────────────────────────

var GQL_TEMPLATES = {
    projects_list: 'query {\n  projects(limit: 20, sort: "id", sort_dir: "DESC") {\n    id\n    name\n    status\n    clientid\n    billing_type\n    start_date\n    deadline\n  }\n}',
    tasks_list: 'query {\n  tasks(limit: 20, sort: "id", sort_dir: "DESC") {\n    id\n    name\n    status\n    priority\n    startdate\n    duedate\n    rel_type\n    rel_id\n  }\n}',
    staff_list: 'query {\n  staff(limit: 50) {\n    staffid\n    firstname\n    lastname\n    email\n    role\n    active\n    last_login\n  }\n}',
    invoices_list: 'query {\n  invoices(limit: 20, sort: "id", sort_dir: "DESC") {\n    id\n    number\n    clientid\n    total\n    status\n    date\n    duedate\n  }\n}',
    ping: 'query {\n  _ping\n}',
    create_task: 'mutation {\n  create_tasks(input: {\n    name: "Sample Task from GraphQL"\n    startdate: "2026-05-01"\n    duedate: "2026-05-15"\n    priority: 2\n    status: 1\n    rel_type: "project"\n    rel_id: 1\n  }) {\n    id\n    name\n    status\n    startdate\n    duedate\n  }\n}',
    create_staff: 'mutation {\n  create_staff(input: {\n    firstname: "John"\n    lastname: "Doe"\n    email: "john.doe@example.com"\n    password: "SecurePass123!"\n  }) {\n    staffid\n    firstname\n    lastname\n    email\n  }\n}',
    delete_invoice: 'mutation {\n  delete_invoices(id: 99) {\n    success\n    message\n    affected_rows\n  }\n}',
};

function loadTemplate(name) {
    if (GQL_TEMPLATES[name]) {
        document.getElementById('gqlQuery').value = GQL_TEMPLATES[name];
        document.getElementById('gqlVars').value = '{}';
    }
}

// ─── Execute ─────────────────────────────────────────────────────────────

async function executeGraphQL() {
    var query = document.getElementById('gqlQuery').value.trim();
    var varsText = document.getElementById('gqlVars').value.trim();
    var variables = {};
    try { variables = JSON.parse(varsText || '{}'); } catch(e) {}

    if (!query) { alert_float('warning', 'Please enter a GraphQL query.'); return; }

    var payload = {query: query};
    if (Object.keys(variables).length > 0) payload.variables = variables;

    document.getElementById('gqlStatus').textContent = '...';
    document.getElementById('gqlStatus').className = 'label label-default';

    var res = await apiCall('POST', '/api/v1/graphql', payload);

    document.getElementById('gqlRawResponse').textContent = res.raw || JSON.stringify(res, null, 2);
    var statusEl = document.getElementById('gqlStatus');
    statusEl.textContent = res.status || '—';
    statusEl.className = 'label ' + (res.success ? 'label-success' : 'label-danger');

    var gqlData = res.data || {};
    if (gqlData.data) gqlData = gqlData.data;

    var rendered = false;
    for (var key in gqlData) {
        if (Array.isArray(gqlData[key]) && gqlData[key].length > 0) {
            renderGqlTable(key, gqlData[key]);
            rendered = true;
            break;
        }
    }

    if (!rendered) {
        document.getElementById('gqlTableContainer').innerHTML =
            '<pre style="background:#f8f8f8; padding:12px; font-size:12px;">' +
            escHtml(JSON.stringify(gqlData, null, 2)) + '</pre>';
    }

    updateGqlSampleCode(query, variables);
}

function renderGqlTable(key, rows) {
    var cols = Object.keys(rows[0]);
    var html = '<p class="text-muted" style="font-size:11px;margin-bottom:5px;"><strong>' + key + '</strong> — ' + rows.length + ' rows</p>';
    html += '<table class="table table-striped table-condensed" style="font-size:12px;">';
    html += '<thead><tr>';
    cols.forEach(function(c) { html += '<th>' + c + '</th>'; });
    html += '</tr></thead><tbody>';
    rows.forEach(function(row) {
        html += '<tr>';
        cols.forEach(function(c) { html += '<td>' + escHtml(String(row[c] !== null ? row[c] : '')) + '</td>'; });
        html += '</tr>';
    });
    html += '</tbody></table>';
    document.getElementById('gqlTableContainer').innerHTML = html;
}

function updateGqlSampleCode(query, variables) {
    var code = '// PHP — Using ApiSampleClient\n$client = new ApiSampleClient();\n\n';
    code += "$query = <<<'GQL'\n" + query + "\nGQL;\n\n";
    if (Object.keys(variables).length > 0) {
        code += '$variables = ' + JSON.stringify(variables, null, 2) + ';\n';
        code += '$result = $client->graphql($query, $variables);\n';
    } else {
        code += '$result = $client->graphql($query);\n';
    }
    code += '\n// cURL equivalent\ncurl -X POST \\\n  -H "Authorization: Bearer YOUR_TOKEN" \\\n  -H "Content-Type: application/json" \\\n';
    code += "  -d '" + JSON.stringify({query: query, variables: variables}) + "' \\\n";
    code += '  "https://your-crm.com/api/v1/graphql"';
    document.getElementById('gqlSampleCode').textContent = code;
}

function copyGqlCode() {
    navigator.clipboard && navigator.clipboard.writeText(document.getElementById('gqlSampleCode').textContent);
    alert_float('success', 'Code copied!');
}

function escHtml(s) { var d = document.createElement('div'); d.textContent = s; return d.innerHTML; }
</script>
