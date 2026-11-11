# API Data Builder Sample - Official REST API, GraphQL, Webhooks 2.0 & AI MCP Showcase for Perfex CRM

> 🚀 **Powered by [Data Builder - Visual Reporting, REST API, GraphQL & MCP Server for Perfex CRM](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963)**
>
> **API Data Builder Sample** is the official free companion module and developer SDK blueprint for [**Data Builder for Perfex CRM**](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963). It provides an interactive admin playground, a production-ready PHP SDK (`ApiSampleClient`), and copy-paste integration examples showing how to connect **Perfex CRM Core tables**, **Custom Visual BI Reports**, and **any 3rd-party module tables (SaaS, Accounting, Warehouse, Inventory, Orders, Products, HRM, POS, etc.)** to external applications and AI Agents via [**REST API**](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963), [**GraphQL**](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963), [**Native MCP Server (Model Context Protocol)**](https://databuilder.polyxgo.com/api_docs#mcp), [**Batch API**](https://databuilder.polyxgo.com/api_docs), [**OpenAPI 3.1.0**](https://databuilder.polyxgo.com/api_docs#openapi), and [**Webhooks 2.0**](https://databuilder.polyxgo.com/api_docs#webhooks).
>
> 👉 **[Get Data Builder for Perfex CRM on CodeCanyon](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963)** | 📖 **[Explore Live Interactive API & MCP Documentation](https://databuilder.polyxgo.com/api_docs)**

---

## Table of Contents

1. [Why Upgrade Perfex CRM with Data Builder?](#-why-upgrade-perfex-crm-with-data-builder)
2. [Key Capabilities Demonstrated](#-key-capabilities-demonstrated)
3. [Universal Database & 3rd-Party Module Integration (Core, SaaS, Accounting, Inventory, Orders, Products)](#-universal-database--3rd-party-module-integration)
4. [Native MCP Server (Model Context Protocol) - Multi-Platform AI Integration](#-native-mcp-server-model-context-protocol---multi-platform-ai-integration)
   - [1. Anthropic Claude (Claude Code CLI, Claude Desktop & Claude Web)](#1-anthropic-claude-claude-code-cli-claude-desktop--claude-web)
   - [2. Google Gemini (Gemini CLI, Antigravity IDE, Vertex AI & Python SDK)](#2-google-gemini-gemini-cli-antigravity-ide-vertex-ai--python-sdk)
   - [3. DeepSeek (DeepSeek-V3 & DeepSeek-R1 via Cherry Studio, Cline, Roo Code & Python SDK)](#3-deepseek-deepseek-v3--deepseek-r1-via-cherry-studio-anythingllm-cline-roo-code--python-sdk)
   - [4. OpenAI ChatGPT (Responses API, Developer Mode Connectors, Codex CLI & Custom GPT Actions)](#4-openai-chatgpt-responses-api-developer-mode-connectors-codex-cli--custom-gpt-actions)
   - [5. AI Coding IDEs (Cursor, VS Code GitHub Copilot & Windsurf)](#5-ai-coding-ides-cursor-vs-code-github-copilot--windsurf)
   - [6. AI Workflow Automation (n8n, Dify.ai, Flowise, Make.com & Zapier)](#6-ai-workflow-automation-n8n-difyai-flowise-makecom--zapier)
5. [REST API, Batch Operations, Write Safety & BI Report KPIs](#-rest-api-batch-operations-write-safety--bi-report-kpis)
6. [GraphQL API Playground](#-graphql-api-playground)
7. [Webhooks 2.0 (Core CRM Hooks Bridge & Replay-Safe HMAC)](#-webhooks-20-core-crm-hooks-bridge--replay-safe-hmac)
8. [PHP SDK Reference (`ApiSampleClient`)](#-php-sdk-reference-apisampleclient)
9. [Installation & Automatic Upgrade](#-installation--automatic-upgrade)
10. [Module Directory Structure](#-module-directory-structure)

---

## 💡 Why Upgrade Perfex CRM with Data Builder?

Most Perfex CRM API add-ons only expose a few static endpoints (`leads`, `clients`, `invoices`) and break as soon as you install custom modules or need analytical reporting. [**Data Builder for Perfex CRM**](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963) is an all-in-one **No-Code Visual BI Reporting Studio + Enterprise API Gateway + Native AI Agent MCP Server**:

- **Visual Multi-Table Report Builder**: Build complex SQL reports with drag-and-drop table JOINs, calculated metrics, interactive charts, pivot tables, scheduled email exports, and instant REST/MCP endpoints.
- **100% Database Coverage (Core + Any 3rd-Party Module)**: Automatically expose any `tbl*` table in your Perfex CRM database over [**REST API**](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963) and [**GraphQL**](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963) with granular per-token, per-table RBAC permissions.
- **Native AI Agent Connectivity (MCP 2025-03-26)**: Turn your Perfex CRM into an AI-ready data platform for **Claude, ChatGPT, Google Gemini, DeepSeek, Cursor, and n8n** via the built-in [MCP Server Documentation](https://databuilder.polyxgo.com/api_docs#mcp).
- **Enterprise Reliability & Security**: Scoped Bearer Tokens, Optional HMAC-SHA256 Request Signing, `Idempotency-Key` replay protection, Rate Limiting, IP Whitelisting, and [RFC 9457 Problem Details Error Standard](https://databuilder.polyxgo.com/api_docs#errors).

> 🎯 **Want to unlock the full power of REST API, GraphQL, Visual BI Reports, and AI Agents on your Perfex CRM?**
> 👉 **[Purchase Data Builder for Perfex CRM on CodeCanyon](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963)** or test the endpoints live in the **[Interactive API Documentation Portal](https://databuilder.polyxgo.com/api_docs)**.

---

## ✨ Key Capabilities Demonstrated

| Feature Area | Endpoint / Protocol | Live Docs & Reference | Highlights in This Sample Module |
|---|---|---|---|
| **[RESTful CRUD & Search](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963)** | `GET/POST/PUT/PATCH/DELETE /api/v1/{resource}` | [REST API Docs](https://databuilder.polyxgo.com/api_docs) | Live CRUD demos for Projects, Knowledge Base, Tasks, Staff, Invoices, Payments, and any whitelisted table |
| **[BI Reports & KPI Stats](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963)** | `GET /api/v1/reports/{slug}` & `/kpi` & `/aggregate` | [Report API Docs](https://databuilder.polyxgo.com/api_docs) | Full-dataset SQL KPI aggregation (`COUNT`, `SUM`, `AVG`, `MIN`, `MAX`) and dimensional `GROUP BY` breakdown |
| **[Native MCP Server](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963)** | `POST /api/v1/mcp` (Streamable HTTP JSON-RPC 2.0) | [MCP Server Guide](https://databuilder.polyxgo.com/api_docs#mcp) | Interactive 3-Pillar playground (`tools`, `resources`, `prompts`) + Dry-Run sandbox + 5-language client code generator |
| **[Write Safety & Normalization](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963)** | `Idempotency-Key` & `items[]` Normalizer | [API Auth & Headers](https://databuilder.polyxgo.com/api_docs#auth) | 24-hour replay protection for `POST/PUT/PATCH` and automatic Sales `items[]` to `newitems[]` normalization |
| **[Batch Operations](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963)** | `POST /api/v1/batch` | [API Overview Docs](https://databuilder.polyxgo.com/api_docs#overview) | Execute up to 50 REST operations in a single HTTP request in-process |
| **[GraphQL Engine](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963)** | `POST /api/v1/graphql` | [GraphQL API Docs](https://databuilder.polyxgo.com/api_docs) | Interactive query/mutation runner with variables, schema introspection, and auto-generated PHP code |
| **[Webhooks 2.0](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963)** | Outbound HTTP + `X-Perfex-Signature` | [Webhooks 2.0 Docs](https://databuilder.polyxgo.com/api_docs#webhooks) | Covers both CRM Admin/Portal UI events (`origin: "crm_hook"`) and API writes (`origin: "api"`) with live HMAC simulator |
| **[OpenAPI 3.1.0 & Postman](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963)** | `/api_docs/export/openapi` & `/postman` | [OpenAPI 3.1.0 Docs](https://databuilder.polyxgo.com/api_docs#openapi) | Auto-generated OpenAPI 3.1.0 YAML (with top-level `webhooks`) and [Postman Collection v2.1](https://databuilder.polyxgo.com/api_docs#postman) |

---

## 🌐 Universal Database & 3rd-Party Module Integration

Unlike static API modules that only hardcode a handful of default CRM endpoints, [**Data Builder for Perfex CRM**](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963) features a **Dynamic Schema Introspection & Auto-Resolution Engine** (`Schema_model` + `ResourceNameResolver`).

Any table in your Perfex CRM database (`tbl*`) - whether it belongs to **Perfex CRM Core**, a **3rd-party CodeCanyon module**, a **SaaS multi-tenant module**, or your own **custom in-house database tables** - can be exposed immediately across [**REST API**](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963), [**GraphQL**](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963), [**Custom BI Report Views**](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963) (with visual multi-table JOINs), [**OpenAPI 3.1.0**](https://databuilder.polyxgo.com/api_docs#openapi), [**Webhooks 2.0**](https://databuilder.polyxgo.com/api_docs#webhooks), and [**MCP AI Agent Tools**](https://databuilder.polyxgo.com/api_docs#mcp) simply by enabling it in your API Token's **Allowed Tables** whitelist.

### 1. Core Perfex CRM Tables (Out-of-the-Box)
- **CRM & Sales**: Customers (`tblclients`), Contacts (`tblcontacts`), Leads (`tblleads`), Invoices (`tblinvoices`), Estimates (`tblestimates`), Proposals (`tblproposals`), Credit Notes (`tblcreditnotes`), Payments (`tblinvoicepaymentrecords`), Subscriptions (`tblsubscriptions`), Items/Products (`tblitems`), Taxes (`tbltaxes`), Currencies (`tblcurrencies`).
- **Delivery & Operations**: Projects (`tblprojects`), Tasks (`tbltasks`), Task Checklists (`tbltask_checklist_items`), Milestones (`tblmilestones`), Timesheets (`tbltaskstimers`), Expenses (`tblexpenses`), Contracts (`tblcontracts`).
- **Support & Knowledge**: Support Tickets (`tbltickets`), Ticket Replies (`tblticket_replies`), Knowledge Base (`tblknowledge_base`), Staff (`tblstaff`), Roles (`tblroles`), Custom Fields (`tblcustomfields`, `tblcustomfieldsvalues`).

### 2. Third-Party & Industry Vertical Modules (Zero-Code Auto-Integration)
When you install third-party modules in Perfex CRM, [**Data Builder**](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963) automatically detects their `tbl*` tables and makes them available for:
1. **Direct REST & GraphQL CRUD** (`/api/v1/{table_name_without_tbl}`) - see the [Interactive API Reference](https://databuilder.polyxgo.com/api_docs)
2. **Multi-Table Visual BI Reports** (JOINing 3rd-party module tables with core `tblclients`, `tblstaff`, or `tblinvoices` and exposing the joined view at `/api/v1/reports/{slug}` and `/api/v1/reports/{slug}/kpi`)
3. **MCP AI Agent Discovery** (AI assistants automatically discover and query your custom module tables and BI reports via [MCP Server](https://databuilder.polyxgo.com/api_docs#mcp))
4. **Webhooks 2.0** (Trigger real-time [Webhook Events](https://databuilder.polyxgo.com/api_docs#webhooks) whenever records are created/updated/deleted in those tables)

| Module Category | Common Perfex Modules & Tables | Example API & AI Integration Use Cases with [Data Builder](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963) |
|---|---|---|
| **SaaS & Multi-Tenancy** | `perfex_saas`, Tenant Management (`tblperfex_saas_companies`, `tblperfex_saas_packages`, `tblperfex_saas_subscriptions`) | Provision or inspect tenant instances, monitor quota usage, analyze MRR/ARR across SaaS plans via `/api/v1/reports/{slug}/kpi`, or let AI agents check tenant status. |
| **Accounting & Bookkeeping** | `accounting` (`tblacc_accounts`, `tblacc_account_history`, `tblacc_journal_entries`, `tblacc_budgets`, `tblacc_tax_mappings`) | Sync general ledger entries with external ERPs (SAP, Odoo, QuickBooks, Xero), query trial balances, and run AI-assisted cashflow & P&L anomaly audits. |
| **Inventory & Warehouse** | `warehouse` (`tblgoods_receipt`, `tblgoods_delivery`, `tblinventory_manage`, `tblwh_loss_adjustment`, `tblwarehouses`) | Real-time stock level lookups (`GET /api/v1/inventory_manage`), automated low-stock alerts, warehouse transfer tracking, and barcode/WMS synchronization. |
| **Purchase & Vendor Management** | `purchase` (`tblpur_vendor`, `tblpur_orders`, `tblpur_request`, `tblpur_invoices`, `tblpur_contracts`) | Automate procurement workflows, sync supplier catalogs, and build unified Accounts Payable vs. Accounts Receivable BI reports. |
| **Orders, POS & E-Commerce** | `omni_sales`, WooCommerce/Shopify connectors (`tblomni_cart`, `tblomni_orders`, `tblomni_trade_discount`, `tblpos_transactions`) | Push online orders from Shopify/WooCommerce/mobile apps into Perfex CRM, synchronize order fulfillment status, and calculate daily omnichannel GMV KPIs. |
| **Products, Manufacturing & BOM** | `manufacturing`, `items` (`tblitems`, `tblitems_groups`, `tblmrp_bom`, `tblmrp_manufacturing_orders`, `tblmrp_work_orders`) | Query product catalogs, variants, pricing tiers, bills of materials (BOM), and production order progress from external shop-floor or B2B portals. |
| **HRM, Payroll & Timesheets** | `hr_profile`, `hr_payroll`, `timesheets` (`tblhr_job_position`, `tblhr_payslips`, `tbltimesheets_timesheet`, `tbltimesheets_requisition_leave`) | Integrate biometric attendance devices, automate monthly payroll calculations, and allow employees/managers to query leave balances via AI assistants. |
| **Fixed Assets & Fleet** | `fixed_equipment`, `fleet` (`tblfe_assets`, `tblfe_check_in_out`, `tblfleet_vehicles`, `tblfleet_maintenance`) | Track asset assignments, maintenance schedules, depreciation, and vehicle telemetry via REST/GraphQL. |
| **Custom In-House Tables** | Any custom table `tbl_{your_custom_entity}` | Immediately accessible at `/api/v1/{your_custom_entity}` and in GraphQL without writing custom controllers. |

### Example: Querying Third-Party Module Tables & Custom Joined BI Reports with `ApiSampleClient`

```php
$this->load->helper('api_data_builder_sample/api_sample');
$api = new ApiSampleClient();

// 1. Query a 3rd-party Warehouse / Inventory table directly (e.g., tblinventory_manage)
$stock = $api->get('inventory_manage', [
    'per_page' => 50,
    'sort'     => '-inventory_number',
]);

// 2. Query a 3rd-party Omni-Sales / Orders table (e.g., tblomni_orders)
$orders = $api->get('omni_orders', [
    'page'     => 1,
    'per_page' => 20,
    'sort'     => '-id',
]);

// 3. Query a Custom BI Report built in Data Builder that JOINs tblclients + tblinvoices + tblomni_orders + tblacc_account_history
$report = $api->report('omnichannel-revenue-by-client', [
    'page'     => 1,
    'per_page' => 25,
]);

// 4. Compute full-dataset SQL KPI statistics (COUNT, SUM, AVG, MIN, MAX) on any Custom Report
$kpi = $api->reportKpi('omnichannel-revenue-by-client', 'total_order_value');
// Returns: row_count, value_count, sum_value, avg_value, min_value, max_value
```

> 🔗 **Need to expose your Accounting, Inventory, SaaS, or Custom Module tables via API?**
> **[Get Data Builder for Perfex CRM on CodeCanyon](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963)** and browse all auto-generated endpoints in the **[Live API Documentation](https://databuilder.polyxgo.com/api_docs)**.

---

## 🤖 Native MCP Server (Model Context Protocol) - Multi-Platform AI Integration

[**Data Builder for Perfex CRM**](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963) includes a **Native Model Context Protocol (MCP 2025-03-26) Server** over **Streamable HTTP JSON-RPC 2.0** at:

```http
POST https://your-domain.com/api/v1/mcp
GET  https://your-domain.com/api/v1/mcp   (Server Discovery & Capability Count)
```

For full protocol specifications, interactive sandbox testing, and wire references, visit the **[Live MCP Server Documentation (`databuilder.polyxgo.com/api_docs#mcp`)](https://databuilder.polyxgo.com/api_docs#mcp)**.

No external Node.js/Python proxy or intermediate database connector is required for HTTP-capable MCP clients. Your AI assistants connect directly to Perfex CRM using your existing [**Data Builder**](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963) **API Token** (`Authorization: Bearer YOUR_TOKEN` or `X-API-Key: YOUR_TOKEN`).

### 3 MCP Pillars Supported
1. **Tools (`tools/list`, `tools/call`)**:
   - **5 Built-in BI Report Tools**: `reports_list`, `report_schema`, `report_execute`, `report_kpi_stats`, `report_aggregate` (allows AI agents to discover and run any custom multi-table BI report you build in [**Data Builder**](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963), including reports over SaaS, Accounting, Inventory, or Order tables).
   - **Dynamic CRM CRUD & Search Tools**: `{resource}_list`, `{resource}_get`, `{resource}_search`, `{resource}_create`, `{resource}_update`, `{resource}_delete` filtered automatically by the token's permissions.
   - **MCP Tool Annotations**: Every tool includes `readOnlyHint`, `destructiveHint`, and `idempotentHint` so AI clients know which tools are safe to run automatically and which require human confirmation.
2. **Resources (`resources/list`, `resources/read`)**:
   - `databuilder://system/overview` (alias `polyxgo://crm/overview`) - Server version, token scopes, and capabilities.
   - `databuilder://catalog/resources` - Full catalog of accessible tables, primary keys, and CRUD permissions.
   - `databuilder://catalog/reports` - Full catalog of published [**Data Builder**](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963) custom BI reports and visible column aliases.
   - `databuilder://schema/{resource}` - Live column schema for any permitted resource.
3. **Prompts (`prompts/list`, `prompts/get`)**:
   - Pre-built analytical slash commands: `executive_kpi_summary`, `analyze_custom_report` (accepts `report_slug`), `overdue_debt_followup`, and `lead_pipeline_review`.

### Enterprise AI Safety & Compatibility Features
- **Dry-Run Sandbox Mode (`X-Dry-Run: true`)**: Pass header `X-Dry-Run: true` (or `"dry_run": true` in tool arguments) to validate and simulate `create`, `update`, and `delete` tool calls without modifying the database. Try it live in the [Interactive MCP Sandbox](https://databuilder.polyxgo.com/api_docs#mcp).
- **SEP-1303 LLM Self-Correction**: Validation errors inside `tools/call` return structured `isError: true` content so the LLM can inspect the error message and self-correct its parameters on the next turn.
- **Google Gemini Strict Draft-07 Schema Normalizer**: Strips union type arrays (`["string", "null"]`) and `$ref` pointers so 100% of tools register cleanly in Gemini CLI, Antigravity IDE, and Vertex AI.
- **DeepSeek V3/R1 Smart Argument Type Coercer**: Automatically coerces stringified integers (`"25"` -> `25`), floats, booleans (`"true"` -> `true`), and JSON-encoded filter strings emitted by DeepSeek and local open-weight models.

---

### 1. Anthropic Claude (Claude Code CLI, Claude Desktop & Claude Web)

#### Option A - Claude Code CLI (One-Liner)
```bash
claude mcp add perfex-crm \
  --transport http https://your-domain.com/api/v1/mcp \
  --header "Authorization: Bearer YOUR_TOKEN"
```

#### Option B - Claude Desktop (`claude_desktop_config.json`)
Add to `%APPDATA%\Claude\claude_desktop_config.json` (Windows) or `~/Library/Application Support/Claude/claude_desktop_config.json` (macOS):
```json
{
  "mcpServers": {
    "perfex-crm-bi": {
      "command": "npx",
      "args": [
        "-y",
        "mcp-remote",
        "https://your-domain.com/api/v1/mcp",
        "--header",
        "Authorization: Bearer YOUR_TOKEN"
      ]
    }
  }
}
```

---

### 2. Google Gemini (Gemini CLI, Antigravity IDE, Vertex AI & Python SDK)

#### Option A - Gemini CLI & Google Antigravity IDE (`~/.gemini/settings.json`)
```json
{
  "mcpServers": {
    "perfex-crm-bi": {
      "httpUrl": "https://your-domain.com/api/v1/mcp",
      "headers": {
        "Authorization": "Bearer YOUR_TOKEN"
      }
    }
  }
}
```

#### Option B - Google Gemini Python SDK (`google-genai`)
```python
import requests
from google import genai
from google.genai import types

MCP_URL = "https://your-domain.com/api/v1/mcp"
HEADERS = {"Authorization": "Bearer YOUR_TOKEN", "Content-Type": "application/json"}

# 1. Discover Data Builder MCP tools (already normalized for Gemini Draft-07)
mcp_tools = requests.post(
    MCP_URL,
    headers=HEADERS,
    json={"jsonrpc": "2.0", "id": 1, "method": "tools/list", "params": {}}
).json()["result"]["tools"]

declarations = [
    types.FunctionDeclaration(
        name=t["name"],
        description=t["description"],
        parameters=t["inputSchema"]
    )
    for t in mcp_tools
]

client = genai.Client(api_key="YOUR_GEMINI_API_KEY")
response = client.models.generate_content(
    model="gemini-2.5-pro",
    contents="List our custom BI reports and calculate KPI stats for our sales report.",
    config=types.GenerateContentConfig(tools=[types.Tool(function_declarations=declarations)])
)
```

---

### 3. DeepSeek (`DeepSeek-V3` & `DeepSeek-R1` via Cherry Studio, AnythingLLM, Cline, Roo Code & Python SDK)

#### Option A - Cherry Studio / AnythingLLM / Open WebUI / Cline / Roo Code
In **Settings -> MCP Servers -> Add Server**, select **Streamable HTTP** (`streamableHttp`) and use:
```json
{
  "mcpServers": {
    "perfex-deepseek-bi": {
      "type": "streamableHttp",
      "url": "https://your-domain.com/api/v1/mcp",
      "headers": {
        "Authorization": "Bearer YOUR_TOKEN"
      }
    }
  }
}
```

#### Option B - Direct Python OpenAI SDK + DeepSeek API Bridge
```python
import json, requests
from openai import OpenAI

MCP_URL = "https://your-domain.com/api/v1/mcp"
MCP_HEADERS = {"Authorization": "Bearer YOUR_TOKEN", "Content-Type": "application/json"}

def mcp_rpc(method, params=None):
    resp = requests.post(
        MCP_URL,
        headers=MCP_HEADERS,
        json={"jsonrpc": "2.0", "id": 1, "method": method, "params": params or {}}
    )
    return resp.json()["result"]

# Convert MCP tools to OpenAI/DeepSeek function-calling format
openai_tools = [
    {
        "type": "function",
        "function": {
            "name": t["name"],
            "description": t["description"],
            "parameters": t["inputSchema"]
        }
    }
    for t in mcp_rpc("tools/list")["tools"]
]

client = OpenAI(api_key="YOUR_DEEPSEEK_API_KEY", base_url="https://api.deepseek.com")
messages = [{"role": "user", "content": "Show our custom BI reports and top 5 recent invoices."}]

res = client.chat.completions.create(model="deepseek-chat", messages=messages, tools=openai_tools)
msg = res.choices[0].message

if msg.tool_calls:
    messages.append(msg)
    for tc in msg.tool_calls:
        out = mcp_rpc("tools/call", {"name": tc.function.name, "arguments": json.loads(tc.function.arguments)})
        messages.append({"role": "tool", "tool_call_id": tc.id, "content": out["content"][0]["text"]})
    final = client.chat.completions.create(model="deepseek-chat", messages=messages)
    print(final.choices[0].message.content)
```

---

### 4. OpenAI ChatGPT (Responses API, Developer Mode Connectors, Codex CLI & Custom GPT Actions)

#### Option A - OpenAI Responses API (Native Remote MCP)
```javascript
import OpenAI from "openai";
const client = new OpenAI();

const response = await client.responses.create({
  model: "gpt-4.1",
  tools: [
    {
      type: "mcp",
      server_label: "perfex_crm_bi",
      server_url: "https://your-domain.com/api/v1/mcp",
      headers: {
        Authorization: "Bearer YOUR_TOKEN"
      },
      require_approval: "never"
    }
  ],
  input: "Run executive_kpi_summary and check overdue invoices."
});
console.log(response.output_text);
```

#### Option B - Custom GPT Actions via OpenAPI 3.1.0 Export
Visit the [Testing with OpenAPI 3.1.0 Documentation](https://databuilder.polyxgo.com/api_docs#openapi) (or **Admin -> Data Builder -> API Explorer -> Export -> OpenAPI 3.1.0 YAML**), download `DataBuilder_API_OpenAPI_3.1.0.yaml` (or select specific core/3rd-party tables via **Custom Export**), and import it directly into **ChatGPT GPT Editor -> Actions**.

---

### 5. AI Coding IDEs (Cursor, VS Code GitHub Copilot & Windsurf)

Save to `.cursor/mcp.json` (Cursor), `.vscode/mcp.json` (VS Code Copilot Agent Mode), or `~/.codeium/windsurf/mcp_config.json` (Windsurf):
```json
{
  "mcpServers": {
    "perfex-crm": {
      "url": "https://your-domain.com/api/v1/mcp",
      "headers": {
        "Authorization": "Bearer YOUR_TOKEN"
      }
    }
  }
}
```

---

### 6. AI Workflow Automation (n8n, Dify.ai, Flowise, Make.com & Zapier)

- **n8n (`MCP Client Tool` Node)**:
  1. Add an **AI Agent** node to your workflow and attach an **MCP Client Tool** node.
  2. Set **Transport** to `HTTP Streamable`.
  3. Set **MCP Endpoint URL** to `https://your-domain.com/api/v1/mcp`.
  4. Set **Authentication** to `Header Auth` (`Authorization: Bearer YOUR_TOKEN`).
- **Dify.ai**:
  Go to **Tools -> MCP -> Add MCP Server (HTTP)**, enter `https://your-domain.com/api/v1/mcp` and your Bearer token header. Dify will automatically discover all [**Data Builder**](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963) BI Report and CRM tools.
- **Make.com / Zapier**:
  Use `GET /api/v1/auth/test` for connection validation, `?since=2026-01-01T00:00:00Z` query parameter for polling triggers, or [**Webhooks 2.0**](https://databuilder.polyxgo.com/api_docs#webhooks) for instant event-driven triggers.

> 🤖 **Ready to connect Claude, Gemini, DeepSeek, ChatGPT, and n8n to your Perfex CRM?**
> **[Purchase Data Builder for Perfex CRM on CodeCanyon](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963)** | **[Test the Live MCP Sandbox at databuilder.polyxgo.com/api_docs#mcp](https://databuilder.polyxgo.com/api_docs#mcp)**

---

## 🔌 REST API, Batch Operations, Write Safety & BI Report KPIs

Powered by the [**Data Builder REST API Engine**](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963) (full reference in the [Interactive REST API Documentation](https://databuilder.polyxgo.com/api_docs)), the **REST API Demos** page (`admin/api_data_builder_sample/rest`) demonstrates:
1. **Standard CRUD & Filtering**: `GET`, `POST`, `PUT`, `PATCH`, `DELETE`, and `GET /{resource}/schema` across Projects, Knowledge Base (`auto-resolved table`), Tasks, Staff, Invoices, Payments, and any whitelisted 3rd-party module table.
2. **Idempotency-Key Replay Protection**: Toggle `Idempotency-Key: <uuid>` on `POST` requests so network retries within 24 hours return the cached response (`X-Idempotent-Replay: true`) instead of creating duplicate records.
3. **Sales `items[]` Line-Item Normalizer**: Create Invoices, Estimates, Proposals, or Credit Notes by passing a clean `items: [{description, long_description, qty, rate, taxname}]` array; [**Data Builder**](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963) automatically normalizes it to Perfex's internal `newitems[]` format and calculates `subtotal` and `total`.
4. **Sub-Resources**: Manage nested entities such as Task Checklists (`GET/POST /api/v1/tasks/{id}/checklist_items`), Project Notes, Milestones, and Ticket Replies.
5. **BI Reports & Single-Query SQL KPIs (`Report & KPI` Tab)**:
   - List all published custom BI Views created in [**Data Builder**](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963) (`GET /api/v1/reports`)
   - Execute paginated report rows (`GET /api/v1/reports/{slug}`)
   - Compute full-dataset SQL KPIs (`GET /api/v1/reports/{slug}/kpi?column=total`) returning `row_count`, `value_count`, `sum_value`, `avg_value`, `min_value`, `max_value` in a single SQL pass.
6. **Batch API (`POST /api/v1/batch`)**: Execute up to 50 REST operations in a single HTTP request without network round-trip overhead.

---

## 📊 GraphQL API Playground

Powered by the [**Data Builder GraphQL Engine**](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963) (documented in the [Online API Docs](https://databuilder.polyxgo.com/api_docs)), the **GraphQL** page (`admin/api_data_builder_sample/graphql`) provides:
- Interactive Query & Mutation editor with JSON Variables support
- One-click templates for querying Projects, Tasks, Staff, Invoices, Knowledge Base, and `_ping`
- Dynamic GraphQL schema resolution for all whitelisted Core and 3rd-party module tables
- Mutation templates for creating, updating, and deleting records
- Live PHP integration code generator (`$api->graphql($query, $variables)`)

---

## 🔔 Webhooks 2.0 (Core CRM Hooks Bridge & Replay-Safe HMAC)

Powered by [**Data Builder Webhooks 2.0**](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963) (see the [Webhooks 2.0 Documentation & Payload Reference](https://databuilder.polyxgo.com/api_docs#webhooks)), the **Webhooks 2.0** page (`admin/api_data_builder_sample/webhooks`) demonstrates:
- **Dual Event Origin (`origin: "crm_hook" | "api"`)**: Webhooks fire both when records are modified via REST/GraphQL/MCP API (`origin: "api"`) and when staff or clients create/update/delete records inside the Perfex CRM UI (`origin: "crm_hook"` / `"crm_ui"`).
- **Timestamped HMAC-SHA256 Verification**:
  - `X-Perfex-Signature: t={unix_timestamp},v1={hmac_sha256(timestamp . "." . raw_body, secret)}`
  - Legacy `X-Signature: sha256={hmac_sha256(raw_body, secret)}`
- **Interactive Webhook 2.0 HMAC Simulator**: Test and verify timestamped HMAC signatures directly in your browser.
- **Async Retry Queue & SSRF Protection**: Failed webhook deliveries (`5xx`, `429`, timeouts) are queued in `tblpolydb_webhook_queue` with exponential backoff (`1m -> 5m -> 15m -> 60m -> 240m`).

---

## 💻 PHP SDK Reference (`ApiSampleClient`)

Located at `modules/api_data_builder_sample/helpers/api_sample_helper.php` (and documented in the [API Samples Module Guide](https://databuilder.polyxgo.com/api_docs#samples)), `ApiSampleClient` can be copied into any external PHP application to interact with [**Data Builder for Perfex CRM**](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963):

```php
$this->load->helper('api_data_builder_sample/api_sample');
$api = new ApiSampleClient();

// 1. Test connection & token scopes
$auth = $api->testConnection();

// 2. REST CRUD (works for any Core or 3rd-party module table exposed by Data Builder)
$projects = $api->get('projects', ['page' => 1, 'per_page' => 10, 'sort' => '-id']);
$created  = $api->post('invoices', [
    'clientid' => 1,
    'date'     => date('Y-m-d'),
    'duedate'  => date('Y-m-d', strtotime('+30 days')),
    'currency' => 1,
    'items'    => [
        ['description' => 'SaaS Enterprise Plan', 'qty' => 1, 'rate' => 499.00],
    ],
], 'idem-key-' . uniqid()); // Optional Idempotency-Key

// 3. Custom BI Reports & Full-Dataset KPI Stats
$reportRows = $api->report('monthly-sales-report', ['per_page' => 25]);
$reportKpi  = $api->reportKpi('monthly-sales-report', 'total');

// 4. Batch API (up to 50 operations in one HTTP request)
$batch = $api->batch([
    ['method' => 'GET', 'path' => 'projects?per_page=3'],
    ['method' => 'GET', 'path' => 'invoices?per_page=3'],
    ['method' => 'GET', 'path' => 'reports'],
]);

// 5. GraphQL Query / Mutation
$gql = $api->graphql('query ($limit: Int) { projects(limit: $limit) { id name status } }', ['limit' => 5]);

// 6. Native MCP JSON-RPC 2.0 Call (with optional Dry-Run mode)
$mcpTools = $api->mcp('tools/list');
$mcpCall  = $api->mcp('tools/call', [
    'name'      => 'report_kpi_stats',
    'arguments' => ['report_slug' => 'monthly-sales-report', 'column' => 'total'],
], true); // true = sends X-Dry-Run: true
```

---

## 🚀 Installation & Automatic Upgrade

### Requirements
- **Perfex CRM** `3.0.0` or later
- **[Data Builder - Visual Reporting, REST API, GraphQL & MCP Server for Perfex CRM](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963)** installed and activated on your target Perfex CRM instance
- An **API Token** generated in **Data Builder -> API Management -> API Tokens** (see [Authentication Setup Guide](https://databuilder.polyxgo.com/api_docs#auth))

### Fresh Installation or Update
1. Copy the `api_data_builder_sample` folder into `modules/api_data_builder_sample/`.
2. In Perfex CRM Admin, go to **Setup -> Modules**, locate **API Data Builder Sample**, and click **Activate** (if upgrading from `v1.0.0`, the module includes `migrations/110_version_110.php` and automatic version synchronization so database upgrades complete seamlessly without manual SQL).
3. Go to **API Samples -> Settings** in the admin sidebar.
4. Enter your **API Base URL** (defaults to your current site's `/api/v1`), **API Token**, and optional **HMAC Secret**.
5. Click **Save Settings** and **Test Connection**.

---

## 📁 Module Directory Structure

```text
modules/api_data_builder_sample/
├── api_data_builder_sample.php                  # Module registration, hooks, auto-version sync & sidebar menu
├── install.php                                  # Idempotent default options installer
├── migrations/
│   └── 110_version_110.php                      # Perfex CRM database migration for v1.1.0
├── controllers/
│   └── Api_data_builder_sample.php              # Admin controller (dashboard, rest, graphql, mcp, webhooks, settings, ajax_call)
├── helpers/
│   └── api_sample_helper.php                    # ApiSampleClient SDK (REST, GraphQL, MCP, Batch, Report KPI, HMAC, Idempotency)
├── language/
│   ├── english/
│   │   └── api_data_builder_sample_lang.php     # English localization
│   └── vietnamese/
│       └── api_data_builder_sample_lang.php     # Vietnamese localization
├── views/admin/
│   ├── dashboard.php                            # Connection status, quick links & code overview
│   ├── rest.php                                 # REST CRUD, Sales items[], Idempotency-Key, Report KPI & Batch API demos
│   ├── graphql.php                              # Interactive GraphQL query/mutation playground
│   ├── mcp.php                                  # 3-Pillar MCP JSON-RPC 2.0 playground & 5-language AI client generator
│   ├── webhooks.php                             # Webhooks 2.0 guide & interactive HMAC-SHA256 simulator
│   └── settings.php                             # API URL, Bearer Token, HMAC Secret & SSL settings
└── README.md                                    # Documentation
```

---

## 🛒 Get Data Builder for Perfex CRM & Official Resources

- 🚀 **Download & License Data Builder**: **[Data Builder - Visual Reporting, REST API, GraphQL & MCP Server for Perfex CRM on CodeCanyon](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963)**
- 📖 **Live Interactive API & MCP Documentation**: **[Perfex CRM Data Builder API Docs (`databuilder.polyxgo.com/api_docs`)](https://databuilder.polyxgo.com/api_docs)**
  - [Authentication & Scoped Tokens Guide](https://databuilder.polyxgo.com/api_docs#auth)
  - [Native MCP Server & AI Agent Integration Guide](https://databuilder.polyxgo.com/api_docs#mcp)
  - [OpenAPI 3.1.0 Spec & Custom GPT Actions Guide](https://databuilder.polyxgo.com/api_docs#openapi)
  - [Postman Collection v2.1 Guide](https://databuilder.polyxgo.com/api_docs#postman)
  - [Webhooks 2.0 & HMAC Verification Guide](https://databuilder.polyxgo.com/api_docs#webhooks)
- 👨‍💻 **Author Portfolio**: **[PolyXGO on CodeCanyon](https://codecanyon.net/user/polyxgo)**
- 📄 **License (Sample Module)**: MIT License - free to use, modify, and adapt as a reference blueprint for your own Perfex CRM integrations powered by [**Data Builder**](https://codecanyon.net/item/data-builder-for-perfex-crm/62297963).
