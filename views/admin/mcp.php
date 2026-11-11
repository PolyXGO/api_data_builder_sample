<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <h4 class="tw-font-bold">
                    <i class="fa fa-microchip text-success"></i> MCP Server (Model Context Protocol) - AI Agent Demos
                    <a href="<?php echo admin_url('api_data_builder_sample'); ?>" class="btn btn-default btn-sm pull-right">
                        <i class="fa fa-arrow-left"></i> Back to Dashboard
                    </a>
                </h4>
                <hr>
            </div>
        </div>

        <!-- Overview Banner -->
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info" style="border-left:4px solid #10b981; background:#ecfdf5; color:#065f46; margin-bottom:20px;">
                    <strong><i class="fa fa-magic"></i> 3-Pillar MCP Endpoint (<code>POST /api/v1/mcp</code> - JSON-RPC 2.0):</strong>
                    Connect <strong>Anthropic Claude</strong>, <strong>Google Gemini</strong>, <strong>DeepSeek V3/R1</strong>, <strong>OpenAI / Codex</strong>, <strong>Cursor / VS Code / Windsurf</strong>, and <strong>n8n / Dify / Make</strong> directly to Perfex CRM &amp; <a href="https://codecanyon.net/item/data-builder-for-perfex-crm/62297963" target="_blank" style="color:#047857;font-weight:600;text-decoration:underline;">Data Builder Custom BI Reports</a>. See the <a href="https://databuilder.polyxgo.com/api_docs#mcp" target="_blank" style="color:#047857;font-weight:600;text-decoration:underline;">MCP Server Documentation</a> for full AI setup guides.
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left: JSON-RPC 2.0 Request Builder & Sample Code -->
            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body">
                        <h5 class="no-margin" style="margin-bottom:12px;">
                            <i class="fa fa-sliders"></i> MCP Request Builder (JSON-RPC 2.0)
                        </h5>

                        <div style="margin-bottom:12px;">
                            <label class="control-label" style="display:block; margin-bottom:6px;">Quick Presets (3 Pillars):</label>
                            <div style="display:flex; flex-wrap:wrap; gap:5px;">
                                <button class="btn btn-default btn-xs" onclick="loadMcpPreset('initialize')"><i class="fa fa-handshake-o"></i> initialize</button>
                                <button class="btn btn-info btn-xs" onclick="loadMcpPreset('tools_list')"><i class="fa fa-wrench"></i> tools/list</button>
                                <button class="btn btn-success btn-xs" onclick="loadMcpPreset('call_crm_stats')"><i class="fa fa-bar-chart"></i> Call: crm_statistics</button>
                                <button class="btn btn-success btn-xs" onclick="loadMcpPreset('call_reports_list')"><i class="fa fa-table"></i> Call: reports_list</button>
                                <button class="btn btn-success btn-xs" onclick="loadMcpPreset('call_global_search')"><i class="fa fa-search"></i> Call: global_search</button>
                                <button class="btn btn-warning btn-xs" onclick="loadMcpPreset('call_create_task')"><i class="fa fa-plus"></i> Call: create_tasks (Dry-Run)</button>
                                <button class="btn btn-primary btn-xs" onclick="loadMcpPreset('resources_list')"><i class="fa fa-database"></i> resources/list</button>
                                <button class="btn btn-primary btn-xs" onclick="loadMcpPreset('resources_read')"><i class="fa fa-book"></i> Read: perfex://crm/statistics</button>
                                <button class="btn btn-default btn-xs" onclick="loadMcpPreset('prompts_list')"><i class="fa fa-commenting-o"></i> prompts/list</button>
                                <button class="btn btn-default btn-xs" onclick="loadMcpPreset('prompts_get')"><i class="fa fa-lightbulb-o"></i> Prompt: executive_crm_briefing</button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label>JSON-RPC Method</label>
                                    <select id="mcpMethod" class="form-control input-sm" onchange="onMcpMethodChange()">
                                        <option value="initialize">initialize</option>
                                        <option value="ping">ping</option>
                                        <option value="tools/list" selected>tools/list</option>
                                        <option value="tools/call">tools/call</option>
                                        <option value="resources/list">resources/list</option>
                                        <option value="resources/read">resources/read</option>
                                        <option value="prompts/list">prompts/list</option>
                                        <option value="prompts/get">prompts/get</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group" style="padding-top:24px;">
                                    <label style="cursor:pointer; font-weight:600; color:#d97706;">
                                        <input type="checkbox" id="mcpDryRun" checked onchange="updateMcpSampleCode()">
                                        <i class="fa fa-shield"></i> Dry-Run Sandbox
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>JSON-RPC <code>params</code> Object:</label>
                            <textarea id="mcpParams" class="form-control" rows="10" style="font-family:monospace; font-size:12px; background:#1e1e1e; color:#d4d4d4; border:1px solid #333; resize:vertical;">{}</textarea>
                        </div>

                        <div class="text-right">
                            <button class="btn btn-success" onclick="executeMcpCall()" id="btnExecMcp">
                                <i class="fa fa-play"></i> Execute MCP Request
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Multi-Language Sample Code Generator -->
                <div class="panel_s">
                    <div class="panel-body">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                            <h5 class="no-margin"><i class="fa fa-code"></i> Client Integration Code</h5>
                            <div>
                                <div class="btn-group btn-group-xs" style="margin-right:6px;">
                                    <button type="button" class="btn btn-info active" id="tabCodePhp" onclick="switchCodeTab('php')">PHP</button>
                                    <button type="button" class="btn btn-default" id="tabCodeDeepseek" onclick="switchCodeTab('deepseek')">DeepSeek (Py)</button>
                                    <button type="button" class="btn btn-default" id="tabCodeGemini" onclick="switchCodeTab('gemini')">Gemini (Py)</button>
                                    <button type="button" class="btn btn-default" id="tabCodeConfig" onclick="switchCodeTab('config')">mcp.json</button>
                                    <button type="button" class="btn btn-default" id="tabCodeCurl" onclick="switchCodeTab('curl')">cURL</button>
                                </div>
                                <button class="btn btn-default btn-xs" onclick="copyMcpCode()"><i class="fa fa-copy"></i> Copy</button>
                            </div>
                        </div>
                        <pre id="mcpSampleCode" style="background:#1e1e1e; color:#d4d4d4; padding:12px; border-radius:4px; font-size:11px; max-height:320px; overflow:auto; white-space:pre-wrap;"></pre>
                    </div>
                </div>
            </div>

            <!-- Right: Parsed Result & Raw JSON-RPC Response -->
            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body">
                        <h5 class="no-margin" style="margin-bottom:10px;">
                            <i class="fa fa-cubes"></i> Parsed MCP Result
                            <span class="label label-default pull-right" id="mcpStatusBadge">-</span>
                        </h5>
                        <div id="mcpParsedContainer" style="max-height:380px; overflow:auto; border:1px solid #eee; border-radius:4px; padding:12px; background:#fafafa;">
                            <p class="text-muted text-center" style="margin:20px 0;">Click <strong>Execute MCP Request</strong> to inspect tools, resources, prompts, or tool execution output.</p>
                        </div>
                    </div>
                </div>

                <div class="panel_s">
                    <div class="panel-body">
                        <h5 class="no-margin" style="margin-bottom:10px;">
                            <i class="fa fa-file-code-o"></i> Raw JSON-RPC 2.0 Response
                        </h5>
                        <pre id="mcpRawResponse" style="background:#1e1e1e; color:#d4d4d4; padding:12px; border-radius:4px; font-size:11px; max-height:380px; overflow:auto; white-space:pre-wrap;">Waiting for MCP call...</pre>
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
var CURRENT_CODE_TAB = 'php';

var MCP_PRESETS = {
    initialize: {
        method: 'initialize',
        params: {
            protocolVersion: '2025-03-26',
            clientInfo: { name: 'api-data-builder-sample', version: '1.1.0' }
        }
    },
    tools_list: {
        method: 'tools/list',
        params: {}
    },
    call_crm_stats: {
        method: 'tools/call',
        params: {
            name: 'crm_statistics',
            arguments: {}
        }
    },
    call_reports_list: {
        method: 'tools/call',
        params: {
            name: 'reports_list',
            arguments: {}
        }
    },
    call_global_search: {
        method: 'tools/call',
        params: {
            name: 'global_search',
            arguments: {
                query: 'test',
                limit: 5
            }
        }
    },
    call_create_task: {
        method: 'tools/call',
        params: {
            name: 'create_tasks',
            arguments: {
                name: 'AI Agent Generated Follow-up Task',
                startdate: new Date().toISOString().slice(0, 10),
                priority: '2',
                status: '1'
            }
        }
    },
    resources_list: {
        method: 'resources/list',
        params: {}
    },
    resources_read: {
        method: 'resources/read',
        params: {
            uri: 'perfex://crm/statistics'
        }
    },
    prompts_list: {
        method: 'prompts/list',
        params: {}
    },
    prompts_get: {
        method: 'prompts/get',
        params: {
            name: 'executive_crm_briefing',
            arguments: {
                period: 'this_month'
            }
        }
    }
};

function loadMcpPreset(key) {
    var preset = MCP_PRESETS[key];
    if (!preset) return;
    document.getElementById('mcpMethod').value = preset.method;
    document.getElementById('mcpParams').value = JSON.stringify(preset.params, null, 2);
    updateMcpSampleCode();
}

function onMcpMethodChange() {
    var m = document.getElementById('mcpMethod').value;
    if (m === 'tools/list' || m === 'resources/list' || m === 'prompts/list' || m === 'ping') {
        document.getElementById('mcpParams').value = '{}';
    }
    updateMcpSampleCode();
}

async function computeHmac(method, path, queryString, body) {
    if (!HMAC_SECRET) return {};
    var timestamp = Math.floor(Date.now() / 1000).toString();
    var bodyBytes = new TextEncoder().encode(body || '');
    var bodyHashBuf = await crypto.subtle.digest('SHA-256', bodyBytes);
    var bodyHash = Array.from(new Uint8Array(bodyHashBuf)).map(function(b) { return b.toString(16).padStart(2, '0'); }).join('');
    var canonical = [method, path, queryString, bodyHash, timestamp].join('\n');
    var key = await crypto.subtle.importKey('raw', new TextEncoder().encode(HMAC_SECRET), {name: 'HMAC', hash: 'SHA-256'}, false, ['sign']);
    var sigBuf = await crypto.subtle.sign('HMAC', key, new TextEncoder().encode(canonical));
    var signature = Array.from(new Uint8Array(sigBuf)).map(function(b) { return b.toString(16).padStart(2, '0'); }).join('');
    return {'X-Signature': signature, 'X-Timestamp': timestamp};
}

async function executeMcpCall() {
    var method = document.getElementById('mcpMethod').value;
    var dryRun = document.getElementById('mcpDryRun').checked;
    var params = {};
    try {
        params = JSON.parse(document.getElementById('mcpParams').value || '{}');
    } catch (e) {
        alert_float('warning', 'Invalid JSON in params field.');
        return;
    }

    var rpcBody = {
        jsonrpc: '2.0',
        id: Date.now(),
        method: method,
        params: params
    };

    var btn = document.getElementById('btnExecMcp');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Calling MCP...';

    updateMcpSampleCode();

    var endpoint = '/api/v1/mcp';
    var url = API_BASE + endpoint;
    var parsed = new URL(url);
    var bodyStr = JSON.stringify(rpcBody);
    var hmacHeaders = await computeHmac('POST', parsed.pathname, '', bodyStr);

    var headers = Object.assign({
        'Authorization': 'Bearer ' + API_TOKEN,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }, hmacHeaders);

    if (dryRun) {
        headers['X-Dry-Run'] = 'true';
    }

    var t0 = Date.now();
    try {
        var resp = await fetch(url, { method: 'POST', headers: headers, body: bodyStr });
        var text = await resp.text();
        var ms = Date.now() - t0;
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-play"></i> Execute MCP Request';

        var json = JSON.parse(text);
        document.getElementById('mcpRawResponse').textContent = JSON.stringify(json, null, 2);

        var badge = document.getElementById('mcpStatusBadge');
        badge.textContent = resp.status + ' (' + ms + 'ms)';
        badge.className = 'label ' + (resp.ok && !json.error ? 'label-success' : 'label-danger') + ' pull-right';

        renderMcpParsedResult(method, json);
    } catch (err) {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-play"></i> Execute MCP Request';
        document.getElementById('mcpRawResponse').textContent = 'Error: ' + err.message;
    }
}

function renderMcpParsedResult(method, json) {
    var box = document.getElementById('mcpParsedContainer');
    if (json.error) {
        box.innerHTML = '<div class="alert alert-danger no-margin"><strong>JSON-RPC Error (' + escHtml(String(json.error.code)) + '):</strong> ' + escHtml(json.error.message || '') + '</div>';
        return;
    }
    var res = json.result || {};
    if (method === 'tools/list' && Array.isArray(res.tools)) {
        var html = '<p class="text-muted" style="font-size:12px;">Discovered <strong>' + res.tools.length + ' MCP Tools</strong> available for this token:</p>';
        html += '<table class="table table-striped table-condensed" style="font-size:12px;"><thead><tr><th>Tool Name</th><th>Description</th><th>Action</th></tr></thead><tbody>';
        res.tools.forEach(function(t) {
            html += '<tr><td><code>' + escHtml(t.name) + '</code></td><td>' + escHtml(t.description || '') + '</td>';
            html += '<td><button class="btn btn-xs btn-default" onclick="prefillToolCall(\'' + escHtml(t.name) + '\')">Select</button></td></tr>';
        });
        html += '</tbody></table>';
        box.innerHTML = html;
        return;
    }
    if (method === 'tools/call' && Array.isArray(res.content)) {
        var textBlocks = res.content.map(function(c) { return c.text || JSON.stringify(c); }).join('\n\n');
        try {
            var parsedInner = JSON.parse(textBlocks);
            textBlocks = JSON.stringify(parsedInner, null, 2);
        } catch (e) {}
        box.innerHTML = '<pre style="background:#1e1e1e; color:#a7f3d0; padding:12px; border-radius:4px; font-size:11.5px; margin:0;">' + escHtml(textBlocks) + '</pre>';
        return;
    }
    box.innerHTML = '<pre style="background:#fff; padding:10px; font-size:12px; margin:0;">' + escHtml(JSON.stringify(res, null, 2)) + '</pre>';
}

function prefillToolCall(toolName) {
    document.getElementById('mcpMethod').value = 'tools/call';
    document.getElementById('mcpParams').value = JSON.stringify({ name: toolName, arguments: {} }, null, 2);
    updateMcpSampleCode();
}

function switchCodeTab(tab) {
    CURRENT_CODE_TAB = tab;
    ['php', 'deepseek', 'gemini', 'config', 'curl'].forEach(function(t) {
        var btn = document.getElementById('tabCode' + t.charAt(0).toUpperCase() + t.slice(1));
        if (btn) btn.className = 'btn btn-xs ' + (t === tab ? 'btn-info active' : 'btn-default');
    });
    updateMcpSampleCode();
}

function updateMcpSampleCode() {
    var method = document.getElementById('mcpMethod').value;
    var dryRun = document.getElementById('mcpDryRun').checked;
    var paramsObj = {};
    try { paramsObj = JSON.parse(document.getElementById('mcpParams').value || '{}'); } catch (e) {}

    var rpcPayload = { jsonrpc: '2.0', id: 1, method: method, params: paramsObj };
    var code = '';

    if (CURRENT_CODE_TAB === 'php') {
        code = "// PHP - Using ApiSampleClient::mcp()\n";
        code += "$client = new ApiSampleClient();\n\n";
        code += "$response = $client->mcp(\n";
        code += "    '" + method + "',\n";
        code += "    " + JSON.stringify(paramsObj, null, 4) + ",\n";
        code += "    " + (dryRun ? 'true' : 'false') + " // Dry-Run Sandbox\n";
        code += ");\n\n";
        code += "print_r($response['data']);";
    } else if (CURRENT_CODE_TAB === 'deepseek') {
        code = "# Python - DeepSeek V3 / R1 + Data Builder MCP Bridge\n";
        code += "import requests\nfrom openai import OpenAI\n\n";
        code += "MCP_URL = '" + API_BASE + "/api/v1/mcp'\n";
        code += "HEADERS = {'Authorization': 'Bearer YOUR_API_TOKEN', 'Content-Type': 'application/json'}\n\n";
        code += "# 1. Discover CRM & BI Report Tools from Data Builder MCP\n";
        code += "mcp_tools = requests.post(MCP_URL, headers=HEADERS, json={'jsonrpc':'2.0','id':1,'method':'tools/list','params':{}}).json()['result']['tools']\n";
        code += "openai_tools = [{'type':'function','function':{'name':t['name'],'description':t['description'],'parameters':t['inputSchema']}} for t in mcp_tools]\n\n";
        code += "# 2. Execute tool call requested by DeepSeek (smart type coercion is built into Data Builder v2.5.0)\n";
        code += "res = requests.post(MCP_URL, headers=HEADERS, json=" + JSON.stringify(rpcPayload) + ").json()\n";
        code += "print(res)";
    } else if (CURRENT_CODE_TAB === 'gemini') {
        code = "# Python - Google Gemini 2.5 Pro/Flash + Data Builder MCP\n";
        code += "import requests\nfrom google import genai\n\n";
        code += "MCP_URL = '" + API_BASE + "/api/v1/mcp'\n";
        code += "HEADERS = {'Authorization': 'Bearer YOUR_API_TOKEN', 'Content-Type': 'application/json'}\n\n";
        code += "# Call Data Builder MCP endpoint directly (flat JSON schemas ready for Gemini function declarations)\n";
        code += "response = requests.post(MCP_URL, headers=HEADERS, json=" + JSON.stringify(rpcPayload) + ")\n";
        code += "print(response.json())";
    } else if (CURRENT_CODE_TAB === 'config') {
        code = "// Claude Desktop / Cursor / VS Code / Windsurf (mcp.json)\n";
        code += JSON.stringify({
            mcpServers: {
                "perfex-data-builder": {
                    url: API_BASE + "/api/v1/mcp",
                    headers: {
                        Authorization: "Bearer YOUR_API_TOKEN"
                    }
                }
            }
        }, null, 2);
    } else {
        code = "curl -X POST \\\n";
        code += '  -H "Authorization: Bearer YOUR_API_TOKEN" \\\n';
        code += '  -H "Content-Type: application/json" \\\n';
        if (dryRun) code += '  -H "X-Dry-Run: true" \\\n';
        code += "  -d '" + JSON.stringify(rpcPayload) + "' \\\n";
        code += '  "' + API_BASE + '/api/v1/mcp"';
    }

    document.getElementById('mcpSampleCode').textContent = code;
}

function copyMcpCode() {
    navigator.clipboard && navigator.clipboard.writeText(document.getElementById('mcpSampleCode').textContent);
    alert_float('success', 'MCP sample code copied!');
}

function escHtml(s) { var d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

$(function() {
    updateMcpSampleCode();
    executeMcpCall();
});
</script>

