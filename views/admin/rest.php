<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <h4 class="tw-font-bold">
                    <i class="fa fa-exchange"></i> <?php echo _l('api_sample_rest'); ?>
                    <a href="<?php echo admin_url('api_data_builder_sample'); ?>" class="btn btn-default btn-sm pull-right">
                        <i class="fa fa-arrow-left"></i> <?php echo _l('api_sample_back_to_dashboard'); ?>
                    </a>
                </h4>
                <hr>
            </div>
        </div>

        <!-- Resource Tabs -->
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-pills" style="margin-bottom:15px;">
                    <?php
                    $resources = [
                        'projects'       => ['icon' => 'fa-briefcase',  'label' => 'Projects'],
                        'knowledge_base' => ['icon' => 'fa-book',       'label' => 'Knowledge Base'],
                        'tasks'          => ['icon' => 'fa-tasks',      'label' => 'Tasks (+Checklist)'],
                        'staff'          => ['icon' => 'fa-users',      'label' => 'Staff'],
                        'invoices'       => ['icon' => 'fa-file-text',  'label' => 'Invoices (+items[])'],
                        'payments'       => ['icon' => 'fa-credit-card','label' => 'Payments'],
                        'reports'        => ['icon' => 'fa-bar-chart',  'label' => _l('api_sample_report_kpi_demo')],
                    ];
                    foreach ($resources as $key => $r) {
                        $active = ($resource === $key) ? 'active' : '';
                        echo '<li class="' . $active . '"><a href="' . admin_url('api_data_builder_sample/rest?resource=' . $key) . '"><i class="fa ' . $r['icon'] . '"></i> ' . $r['label'] . '</a></li>';
                    }
                    ?>
                </ul>
            </div>
        </div>

        <!-- Demo Panel -->
        <div class="row">
            <!-- Left: Data Table -->
            <div class="col-md-8">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="tw-flex tw-items-center tw-justify-between tw-mb-3">
                            <h5 class="no-margin">
                                <i class="fa fa-table"></i> <?php echo ucfirst($resource); ?> - Live Data
                            </h5>
                            <div>
                                <?php if ($resource === 'staff') { ?>
                                <button class="btn btn-warning btn-sm" onclick="runAuthTest()">
                                    <i class="fa fa-key"></i> Test /api/v1/auth/test
                                </button>
                                <?php } ?>
                                <button class="btn btn-default btn-sm" onclick="runBatchDemo()" title="Execute multi-operation POST /api/v1/batch">
                                    <i class="fa fa-cubes text-primary"></i> <?php echo _l('api_sample_test_batch_api'); ?>
                                </button>
                                <button class="btn btn-success btn-sm" onclick="loadData()">
                                    <i class="fa fa-refresh"></i> <?php echo _l('api_sample_refresh'); ?>
                                </button>
                                <?php if (in_array($resource, ['tasks', 'staff', 'invoices', 'payments'])) { ?>
                                <button class="btn btn-info btn-sm" onclick="showCreateModal()">
                                    <i class="fa fa-plus"></i> <?php echo _l('api_sample_create_new'); ?>
                                </button>
                                <?php } ?>
                            </div>
                        </div>

                        <?php if ($resource === 'reports') { ?>
                        <!-- Reports & KPI v2.5.0 Controls -->
                        <div class="well" style="padding:12px; margin-bottom:12px; background:#f8fafc;">
                            <div class="row">
                                <div class="col-md-4">
                                    <label style="font-size:12px;">Report Route Slug</label>
                                    <input type="text" id="reportSlugInput" class="form-control input-sm" placeholder="e.g. invoice-summary">
                                </div>
                                <div class="col-md-4">
                                    <label style="font-size:12px;">KPI Column Alias (optional)</label>
                                    <input type="text" id="reportKpiColInput" class="form-control input-sm" placeholder="e.g. total">
                                </div>
                                <div class="col-md-4" style="padding-top:22px;">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-info" onclick="runReportEndpoint('data')"><i class="fa fa-table"></i> <?php echo _l('api_sample_run_report_data'); ?></button>
                                        <button type="button" class="btn btn-success" onclick="runReportEndpoint('kpi')"><i class="fa fa-calculator"></i> <?php echo _l('api_sample_run_kpi_stats'); ?></button>
                                        <button type="button" class="btn btn-default" onclick="runReportEndpoint('schema')"><i class="fa fa-columns"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>

                        <!-- Filters -->
                        <div class="row" style="margin-bottom:10px;">
                            <div class="col-md-4">
                                <input type="number" id="filterPerPage" class="form-control input-sm" placeholder="Per Page (default 25)" value="10">
                            </div>
                            <div class="col-md-4">
                                <input type="text" id="filterSort" class="form-control input-sm" placeholder="Sort: -id, name, status">
                            </div>
                            <div class="col-md-4">
                                <input type="text" id="filterCustom" class="form-control input-sm" placeholder="filter[status]=1">
                            </div>
                        </div>

                        <div id="dataTableContainer">
                            <div class="text-center text-muted" style="padding:40px;" id="loadingPlaceholder">
                                <i class="fa fa-spinner fa-spin fa-2x"></i>
                                <p style="margin-top:10px;">Loading data from API...</p>
                            </div>
                            <table class="table table-striped table-condensed" id="resultTable" style="display:none; font-size:12px;">
                                <thead id="resultThead"></thead>
                                <tbody id="resultTbody"></tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div id="paginationInfo" class="text-muted" style="font-size:12px; margin-top:10px;"></div>
                    </div>
                </div>
            </div>

            <!-- Right: Code + Response -->
            <div class="col-md-4">
                <div class="panel_s">
                    <div class="panel-body">
                        <h5 class="no-margin" style="margin-bottom:10px;">
                            <i class="fa fa-code"></i> Sample Code
                            <button class="btn btn-default btn-xs pull-right" onclick="copyCode()"><i class="fa fa-copy"></i> Copy</button>
                        </h5>
                        <pre id="sampleCode" style="background:#1e1e1e; color:#d4d4d4; padding:12px; border-radius:4px; font-size:11px; max-height:300px; overflow:auto; white-space:pre-wrap;"></pre>
                    </div>
                </div>
                <div class="panel_s">
                    <div class="panel-body">
                        <h5 class="no-margin" style="margin-bottom:10px;">
                            <i class="fa fa-file-code-o"></i> API Response
                            <span class="label label-default pull-right" id="responseStatus">-</span>
                        </h5>
                        <pre id="rawResponse" style="background:#1e1e1e; color:#d4d4d4; padding:12px; border-radius:4px; font-size:11px; max-height:400px; overflow:auto; white-space:pre-wrap;">Waiting for API call...</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><i class="fa fa-plus"></i> Create <?php echo ucfirst($resource); ?></h4>
        </div>
        <div class="modal-body" id="createFormBody"></div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-info" onclick="submitCreate()"><i class="fa fa-save"></i> Create</button>
        </div>
    </div></div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="detailModalTitle">Record Detail</h4>
        </div>
        <div class="modal-body">
            <pre id="detailContent" style="background:#1e1e1e; color:#d4d4d4; padding:12px; border-radius:4px; font-size:12px; max-height:500px; overflow:auto;"></pre>
        </div>
    </div></div>
</div>

<?php init_tail(); ?>
<script>
var RESOURCE = '<?php echo $resource; ?>';
var API_BASE = '<?php echo htmlspecialchars(rtrim(get_option("api_sample_base_url") ?: "", "/")); ?>';
var API_TOKEN = '<?php echo htmlspecialchars(get_option("api_sample_api_token") ?: ""); ?>';
var HMAC_SECRET = '<?php echo htmlspecialchars(get_option("api_sample_hmac_secret") ?: ""); ?>';

// Column configs
var RESOURCE_COLUMNS = {
    projects:       ['id', 'name', 'status', 'clientid', 'billing_type', 'start_date', 'deadline'],
    knowledge_base: ['articleid', 'subject', 'articlegroup', 'staff_article', 'active'],
    tasks:          ['id', 'name', 'status', 'priority', 'startdate', 'duedate', 'rel_type', 'rel_id'],
    staff:          ['staffid', 'firstname', 'lastname', 'email', 'role', 'active', 'last_login'],
    invoices:       ['id', 'number', 'clientid', 'total', 'status', 'date', 'duedate'],
    payments:       ['id', 'invoiceid', 'amount', 'paymentmode', 'date', 'transactionid'],
};

var STATUS_BADGES = {
    projects: {1: '<span class="label label-default">Not Started</span>', 2: '<span class="label label-info">In Progress</span>', 3: '<span class="label label-warning">On Hold</span>', 4: '<span class="label label-success">Finished</span>', 5: '<span class="label label-danger">Cancelled</span>'},
    tasks:    {1: '<span class="label label-default">Not Started</span>', 2: '<span class="label label-warning">Awaiting</span>', 3: '<span class="label label-info">Testing</span>', 4: '<span class="label label-info">In Progress</span>', 5: '<span class="label label-success">Complete</span>'},
    invoices: {1: '<span class="label label-default">Unpaid</span>', 2: '<span class="label label-success">Paid</span>', 3: '<span class="label label-warning">Partially Paid</span>', 4: '<span class="label label-danger">Overdue</span>', 5: '<span class="label label-info">Cancelled</span>', 6: '<span class="label label-primary">Draft</span>'},
};

var CREATE_FIELDS = {
    tasks: [
        {name: 'name', label: 'Task Name', type: 'text', required: true},
        {name: 'startdate', label: 'Start Date', type: 'date', required: true},
        {name: 'duedate', label: 'Due Date', type: 'date'},
        {name: 'priority', label: 'Priority', type: 'select', options: [{v:1,l:'Low'},{v:2,l:'Medium'},{v:3,l:'High'},{v:4,l:'Urgent'}]},
        {name: 'status', label: 'Status', type: 'select', options: [{v:1,l:'Not Started'},{v:4,l:'In Progress'},{v:5,l:'Complete'}]},
        {name: 'rel_type', label: 'Related Type', type: 'text', placeholder: 'project'},
        {name: 'rel_id', label: 'Related ID', type: 'number'},
    ],
    staff: [
        {name: 'firstname', label: 'First Name', type: 'text', required: true},
        {name: 'lastname', label: 'Last Name', type: 'text', required: true},
        {name: 'email', label: 'Email', type: 'email', required: true},
        {name: 'password', label: 'Password', type: 'password', required: true},
    ],
    invoices: [
        {name: 'clientid', label: 'Client ID', type: 'number', required: true, value: 1},
        {name: 'number', label: 'Invoice Number', type: 'number'},
        {name: 'date', label: 'Date', type: 'date', required: true, value: new Date().toISOString().slice(0, 10)},
        {name: 'duedate', label: 'Due Date', type: 'date'},
        {name: 'currency', label: 'Currency ID', type: 'number', value: 1},
        {name: 'status', label: 'Status', type: 'select', options: [{v:1,l:'Unpaid'},{v:6,l:'Draft'}]},
        {name: 'item_description', label: 'Line Item Description (items[] v2.5.0)', type: 'text', value: 'Enterprise Consulting Service'},
        {name: 'item_qty', label: 'Line Item Qty', type: 'number', value: 1},
        {name: 'item_rate', label: 'Line Item Rate', type: 'number', value: 500},
    ],
    payments: [
        {name: 'invoiceid', label: 'Invoice ID', type: 'number', required: true},
        {name: 'amount', label: 'Amount', type: 'number', required: true},
        {name: 'paymentmode', label: 'Payment Mode', type: 'text', placeholder: 'bank_transfer'},
        {name: 'date', label: 'Date', type: 'date', required: true},
        {name: 'transactionid', label: 'Transaction ID', type: 'text'},
    ],
};

// ─── HMAC Signing (Web Crypto API) ───────────────────────────────────────

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

async function apiCall(method, endpoint, body, extraHeaders) {
    var url = API_BASE + endpoint;
    var parsed = new URL(url);
    var path = parsed.pathname;
    var queryString = parsed.search ? parsed.search.substring(1) : '';
    var bodyStr = body ? JSON.stringify(body) : '';

    var hmacHeaders = await computeHmac(method, path, queryString, bodyStr);

    var headers = {
        'Authorization': 'Bearer ' + API_TOKEN,
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        ...hmacHeaders,
        ...(extraHeaders || {})
    };

    var opts = {method: method, headers: headers};
    if (body && (method === 'POST' || method === 'PUT')) {
        opts.body = bodyStr;
    }

    var t0 = Date.now();
    try {
        var resp = await fetch(url, opts);
        var text = await resp.text();
        var json;
        try {
            json = JSON.parse(text);
        } catch (parseErr) {
            // Non-JSON response (HTML error page, etc.)
            return {
                success: false,
                status: resp.status,
                data: null,
                error: 'Server returned non-JSON response (HTTP ' + resp.status + '). Check API endpoint and token permissions.',
                raw: text.substring(0, 500),
                time_ms: Date.now() - t0
            };
        }
        return {
            success: resp.ok,
            status: resp.status,
            data: json.data || json,
            meta: json.meta || null,
            links: json.links || null,
            error: json.detail || json.title || null,
            raw: JSON.stringify(json, null, 2),
            time_ms: Date.now() - t0
        };
    } catch (e) {
        return {success: false, status: 0, data: null, error: 'Network error: ' + e.message, raw: '', time_ms: Date.now() - t0};
    }
}

// ─── Load Data ───────────────────────────────────────────────────────────

async function loadData(page) {
    if (RESOURCE === 'reports') {
        await loadAvailableReports();
        return;
    }
    page = page || 1;
    var perPage = document.getElementById('filterPerPage').value || 10;
    var sort = document.getElementById('filterSort').value || '';
    var custom = document.getElementById('filterCustom').value || '';

    var endpoint = '/api/v1/' + RESOURCE + '?page=' + page + '&per_page=' + perPage;
    if (sort) endpoint += '&sort=' + sort;
    if (custom) endpoint += '&' + custom;

    updateSampleCode('GET', endpoint);
    document.getElementById('loadingPlaceholder').style.display = '';
    document.getElementById('resultTable').style.display = 'none';

    var res = await apiCall('GET', endpoint);

    document.getElementById('loadingPlaceholder').style.display = 'none';
    document.getElementById('rawResponse').textContent = res.raw || JSON.stringify(res, null, 2);

    var statusEl = document.getElementById('responseStatus');
    statusEl.textContent = res.status || '-';
    statusEl.className = 'label ' + (res.success ? 'label-success' : 'label-danger');

    if (res.success && Array.isArray(res.data)) {
        renderTable(res.data, res.meta);
    } else {
        document.getElementById('resultTbody').innerHTML = '<tr><td colspan="99" class="text-center text-danger">' + (res.error || 'No data') + '</td></tr>';
        document.getElementById('resultTable').style.display = '';
    }
}

function renderTable(data, meta) {
    var cols = RESOURCE_COLUMNS[RESOURCE] || Object.keys(data[0] || {}).slice(0, 8);
    var badges = STATUS_BADGES[RESOURCE] || {};

    var thead = '<tr>';
    cols.forEach(function(c) { thead += '<th>' + c + '</th>'; });
    thead += '<th style="width:80px;">Actions</th></tr>';
    document.getElementById('resultThead').innerHTML = thead;

    var tbody = '';
    data.forEach(function(row) {
        tbody += '<tr>';
        cols.forEach(function(c) {
            var val = row[c] !== null && row[c] !== undefined ? row[c] : '';
            if (c === 'status' && badges[val]) val = badges[val];
            else val = escHtml(String(val));
            tbody += '<td>' + val + '</td>';
        });
        var pk = row.id || row.staffid || row.articleid || row.ticketid || Object.values(row)[0];
        var actionsHtml = '<div class="btn-group btn-group-xs">';
        actionsHtml += '<button class="btn btn-default" title="View Detail" onclick="viewDetail(\'' + pk + '\')"><i class="fa fa-eye"></i></button>';
        if (RESOURCE === 'tasks') {
            actionsHtml += '<button class="btn btn-info" title="Task Checklist Sub-Resource (v2.5.0)" onclick="viewTaskChecklist(\'' + pk + '\')"><i class="fa fa-check-square-o"></i></button>';
            actionsHtml += '<button class="btn btn-success" title="Mark Complete (PUT)" onclick="quickUpdateStatus(\'' + pk + '\', 5)"><i class="fa fa-check"></i></button>';
        }
        if (RESOURCE === 'invoices' || RESOURCE === 'payments') {
            actionsHtml += '<button class="btn btn-danger" title="Delete (DELETE)" onclick="deleteRecord(\'' + pk + '\')"><i class="fa fa-trash"></i></button>';
        }
        actionsHtml += '</div>';
        tbody += '<td>' + actionsHtml + '</td>';
        tbody += '</tr>';
    });

    if (data.length === 0) {
        tbody = '<tr><td colspan="' + (cols.length + 1) + '" class="text-center text-muted">No records found</td></tr>';
    }

    document.getElementById('resultTbody').innerHTML = tbody;
    document.getElementById('resultTable').style.display = '';

    if (meta) {
        document.getElementById('paginationInfo').innerHTML =
            'Page ' + meta.page + ' of ' + meta.total_pages + ' - ' + meta.total + ' total records - ' + meta.execution_time_ms + 'ms';
    }
}

async function viewDetail(id) {
    var res = await apiCall('GET', '/api/v1/' + RESOURCE + '/' + id);
    document.getElementById('detailModalTitle').textContent = ucfirst(RESOURCE) + ' #' + id;
    document.getElementById('detailContent').textContent = JSON.stringify(res.data || res, null, 2);
    $('#detailModal').modal('show');

    updateSampleCode('GET', '/api/v1/' + RESOURCE + '/' + id);
    document.getElementById('rawResponse').textContent = res.raw || JSON.stringify(res, null, 2);
}

async function viewTaskChecklist(taskId) {
    var endpoint = '/api/v1/tasks/' + taskId + '/checklist';
    updateSampleCode('GET', endpoint);
    var res = await apiCall('GET', endpoint);
    document.getElementById('detailModalTitle').textContent = 'Task #' + taskId + ' - Checklist Sub-Resource (v2.5.0)';
    document.getElementById('detailContent').textContent = JSON.stringify(res.data || res, null, 2);
    $('#detailModal').modal('show');
    document.getElementById('rawResponse').textContent = res.raw || JSON.stringify(res, null, 2);
}

async function quickUpdateStatus(id, newStatus) {
    var endpoint = '/api/v1/' + RESOURCE + '/' + id;
    var payload = { status: newStatus };
    updateSampleCode('PUT', endpoint, payload);
    var res = await apiCall('PUT', endpoint, payload);
    document.getElementById('rawResponse').textContent = res.raw || JSON.stringify(res, null, 2);
    if (res.success) {
        alert_float('success', 'Updated #' + id + ' status to ' + newStatus);
        loadData();
    } else {
        alert_float('danger', res.error || 'Update failed.');
    }
}

async function deleteRecord(id) {
    if (!confirm('Delete ' + RESOURCE + ' #' + id + ' via DELETE /api/v1/' + RESOURCE + '/' + id + '?')) return;
    var endpoint = '/api/v1/' + RESOURCE + '/' + id;
    updateSampleCode('DELETE', endpoint);
    var res = await apiCall('DELETE', endpoint);
    document.getElementById('rawResponse').textContent = res.raw || JSON.stringify(res, null, 2);
    if (res.success) {
        alert_float('success', 'Deleted #' + id);
        loadData();
    } else {
        alert_float('danger', res.error || 'Delete failed.');
    }
}

async function runAuthTest() {
    var endpoint = '/api/v1/auth/test';
    updateSampleCode('GET', endpoint);
    var res = await apiCall('GET', endpoint);
    document.getElementById('detailModalTitle').textContent = 'Token & Scope Verification (GET /api/v1/auth/test)';
    document.getElementById('detailContent').textContent = JSON.stringify(res.data || res, null, 2);
    $('#detailModal').modal('show');
    document.getElementById('rawResponse').textContent = res.raw || JSON.stringify(res, null, 2);
}

async function loadAvailableReports() {
    document.getElementById('loadingPlaceholder').style.display = '';
    document.getElementById('resultTable').style.display = 'none';
    updateSampleCode('POST', '/api/v1/mcp', {jsonrpc: '2.0', id: 1, method: 'tools/call', params: {name: 'reports_list', arguments: {}}});
    var res = await apiCall('POST', '/api/v1/mcp', {
        jsonrpc: '2.0',
        id: 1,
        method: 'tools/call',
        params: { name: 'reports_list', arguments: {} }
    });
    document.getElementById('loadingPlaceholder').style.display = 'none';
    document.getElementById('rawResponse').textContent = res.raw || JSON.stringify(res, null, 2);
    var reports = [];
    try {
        var text = res.data.result.content[0].text;
        var parsed = JSON.parse(text);
        reports = parsed.reports || [];
    } catch (e) {}
    if (reports.length > 0 && document.getElementById('reportSlugInput') && !document.getElementById('reportSlugInput').value) {
        document.getElementById('reportSlugInput').value = reports[0].slug || '';
    }
    renderTable(reports.length ? reports : [{info: 'Enter a Report Route Slug above and click Run KPI Stats or Execute Report'}], null);
}

async function runReportEndpoint(mode) {
    var slugEl = document.getElementById('reportSlugInput');
    var colEl = document.getElementById('reportKpiColInput');
    var slug = slugEl ? slugEl.value.trim() : '';
    if (!slug) {
        alert_float('warning', 'Please enter a Report Route Slug.');
        return;
    }
    var endpoint = '/api/v1/reports/' + encodeURIComponent(slug);
    if (mode === 'kpi') {
        endpoint += '/kpi' + (colEl && colEl.value.trim() ? '?column=' + encodeURIComponent(colEl.value.trim()) : '');
    } else if (mode === 'schema') {
        endpoint += '/schema';
    }
    updateSampleCode('GET', endpoint);
    var res = await apiCall('GET', endpoint);
    document.getElementById('rawResponse').textContent = res.raw || JSON.stringify(res, null, 2);
    var statusEl = document.getElementById('responseStatus');
    statusEl.textContent = res.status || '-';
    statusEl.className = 'label ' + (res.success ? 'label-success' : 'label-danger');
    if (res.success) {
        if (Array.isArray(res.data)) {
            renderTable(res.data, res.meta);
        } else if (res.data && typeof res.data === 'object') {
            renderTable([res.data], res.meta);
        }
    } else {
        alert_float('danger', res.error || 'Report request failed.');
    }
}

// ─── Create ──────────────────────────────────────────────────────────────

function showCreateModal() {
    var fields = CREATE_FIELDS[RESOURCE] || [];
    var html = '';
    fields.forEach(function(f) {
        html += '<div class="form-group">';
        html += '<label>' + f.label + (f.required ? ' <span class="text-danger">*</span>' : '') + '</label>';
        if (f.type === 'select') {
            html += '<select class="form-control" id="create_' + f.name + '">';
            (f.options || []).forEach(function(o) { html += '<option value="' + o.v + '">' + o.l + '</option>'; });
            html += '</select>';
        } else {
            html += '<input type="' + f.type + '" class="form-control" id="create_' + f.name + '"' +
                    (f.placeholder ? ' placeholder="' + f.placeholder + '"' : '') +
                    (f.value ? ' value="' + f.value + '"' : '') +
                    (f.required ? ' required' : '') + '>';
        }
        html += '</div>';
    });
    var idemKey = 'idem-' + RESOURCE + '-' + Date.now();
    html += '<hr><div class="form-group" style="background:#f8fafc; padding:10px; border-radius:4px; border:1px solid #e2e8f0;">';
    html += '<label><input type="checkbox" id="use_idempotency_key" checked> Send <code>Idempotency-Key</code> Header (v2.5.0 Write Safety)</label>';
    html += '<input type="text" class="form-control input-sm" id="create_idempotency_key" value="' + idemKey + '" style="margin-top:5px; font-family:monospace;">';
    html += '</div>';
    document.getElementById('createFormBody').innerHTML = html;
    $('#createModal').modal('show');
}

async function submitCreate() {
    var fields = CREATE_FIELDS[RESOURCE] || [];
    var data = {};
    fields.forEach(function(f) {
        var el = document.getElementById('create_' + f.name);
        if (el && el.value) {
            data[f.name] = (f.type === 'number') ? Number(el.value) : el.value;
        }
    });

    // Convert invoice item_* fields into v2.5.0 items[] array
    if (RESOURCE === 'invoices' && data.item_description) {
        data.items = [{
            description: data.item_description,
            qty: Number(data.item_qty || 1),
            rate: Number(data.item_rate || 0)
        }];
        delete data.item_description;
        delete data.item_qty;
        delete data.item_rate;
    }

    var extraHeaders = {};
    var idemEl = document.getElementById('use_idempotency_key');
    var idemValEl = document.getElementById('create_idempotency_key');
    if (idemEl && idemEl.checked && idemValEl && idemValEl.value) {
        extraHeaders['Idempotency-Key'] = idemValEl.value.trim();
    }

    updateSampleCode('POST', '/api/v1/' + RESOURCE, data, extraHeaders['Idempotency-Key']);
    var res = await apiCall('POST', '/api/v1/' + RESOURCE, data, extraHeaders);

    document.getElementById('rawResponse').textContent = res.raw || JSON.stringify(res, null, 2);
    var statusEl = document.getElementById('responseStatus');
    statusEl.textContent = res.status || '-';
    statusEl.className = 'label ' + (res.success ? 'label-success' : 'label-danger');

    if (res.success) {
        alert_float('success', ucfirst(RESOURCE) + ' created successfully!');
        $('#createModal').modal('hide');
        loadData();
    } else {
        alert_float('danger', res.error || 'Creation failed.');
    }
}

async function runBatchDemo() {
    var batchBody = {
        atomic: false,
        operations: [
            { method: 'GET', path: '/api/v1/projects?per_page=2' },
            { method: 'GET', path: '/api/v1/tasks?per_page=2' },
            { method: 'GET', path: '/api/v1/invoices?per_page=2' }
        ]
    };
    updateSampleCode('POST', '/api/v1/batch', batchBody);
    var res = await apiCall('POST', '/api/v1/batch', batchBody);
    document.getElementById('rawResponse').textContent = res.raw || JSON.stringify(res, null, 2);
    var statusEl = document.getElementById('responseStatus');
    statusEl.textContent = res.status || '-';
    statusEl.className = 'label ' + (res.success ? 'label-success' : 'label-danger');
    if (res.success) {
        alert_float('success', 'Batch API executed 3 operations in 1 HTTP round-trip!');
    } else {
        alert_float('danger', res.error || 'Batch API call failed.');
    }
}

// ─── Helpers ─────────────────────────────────────────────────────────────

function updateSampleCode(method, endpoint, body, idempotencyKey) {
    var code = '// PHP - Using ApiSampleClient\n';
    code += '$client = new ApiSampleClient();\n\n';
    if (method === 'GET') code += "$result = $client->get('" + endpoint + "');\n";
    else if (method === 'POST' && endpoint === '/api/v1/batch') code += "$result = $client->batch(" + JSON.stringify((body && body.operations) || [], null, 2) + ", " + (body && body.atomic ? 'true' : 'false') + ");\n";
    else if (method === 'POST') code += "$result = $client->post('" + endpoint + "', " + JSON.stringify(body || {}, null, 2) + (idempotencyKey ? ", '" + idempotencyKey + "'" : "") + ");\n";
    else if (method === 'PUT') code += "$result = $client->put('" + endpoint + "', " + JSON.stringify(body || {}, null, 2) + ");\n";
    else if (method === 'DELETE') code += "$result = $client->delete('" + endpoint + "');\n";
    code += '\n// cURL equivalent\n';
    code += 'curl -X ' + method + ' \\\n  -H "Authorization: Bearer YOUR_TOKEN" \\\n  -H "Content-Type: application/json" \\\n';
    if (idempotencyKey) code += '  -H "Idempotency-Key: ' + idempotencyKey + '" \\\n';
    if (body) code += "  -d '" + JSON.stringify(body) + "' \\\n";
    code += '  "https://your-crm.com' + endpoint + '"';
    document.getElementById('sampleCode').textContent = code;
}

function copyCode() {
    navigator.clipboard && navigator.clipboard.writeText(document.getElementById('sampleCode').textContent);
    alert_float('success', 'Code copied!');
}

function escHtml(s) { var d = document.createElement('div'); d.textContent = s; return d.innerHTML; }
function ucfirst(s) { return s.charAt(0).toUpperCase() + s.slice(1); }

// Auto-load
$(function() { loadData(); });
</script>
