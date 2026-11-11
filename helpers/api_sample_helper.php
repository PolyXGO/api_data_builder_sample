<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * ApiSampleClient - HTTP client wrapper for Data Builder REST API & GraphQL.
 *
 * Handles:
 *   - Bearer token authentication
 *   - HMAC-SHA256 request signing (when HMAC secret is configured)
 *   - SSL certificate verification toggle
 *   - Standardized JSON response parsing
 *
 * @since 1.0.0
 */
class ApiSampleClient
{
    private $base_url;
    private $token;
    private $hmac_secret;
    private $verify_ssl;

    public function __construct()
    {
        $this->base_url    = rtrim(get_option('api_sample_base_url') ?: '', '/');
        $this->token       = get_option('api_sample_api_token') ?: '';
        $this->hmac_secret = get_option('api_sample_hmac_secret') ?: '';
        $this->verify_ssl  = (bool) (int) get_option('api_sample_verify_ssl');
    }

    /**
     * Check if the client is configured (has URL + token).
     */
    public function isConfigured(): bool
    {
        return !empty($this->base_url) && !empty($this->token);
    }

    /**
     * Get the configured base URL.
     */
    public function getBaseUrl(): string
    {
        return $this->base_url;
    }

    // ─── REST API Methods ────────────────────────────────────────────────

    /**
     * GET request.
     *
     * @param  string $endpoint  e.g. '/api/v1/projects'
     * @param  array  $params    Query string parameters
     * @return array  ['success' => bool, 'status' => int, 'data' => mixed, 'raw' => string]
     */
    public function get(string $endpoint, array $params = []): array
    {
        $endpoint = '/' . ltrim($endpoint, '/');
        $url = $this->base_url . $endpoint;
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        return $this->request('GET', $url);
    }

    /**
     * POST request (supports optional Idempotency-Key for safe write retries in v2.5.0+).
     *
     * @param  string      $endpoint        e.g. '/api/v1/invoices'
     * @param  array       $data            JSON body payload
     * @param  string|null $idempotency_key Optional unique key (UUID) for idempotent POST
     * @param  array       $extra_headers   Optional extra HTTP headers
     * @return array
     */
    public function post(string $endpoint, array $data, ?string $idempotency_key = null, array $extra_headers = []): array
    {
        $endpoint = '/' . ltrim($endpoint, '/');
        $url  = $this->base_url . $endpoint;
        $body = json_encode($data);
        if (!empty($idempotency_key)) {
            $extra_headers[] = 'Idempotency-Key: ' . $idempotency_key;
        }
        return $this->request('POST', $url, $body, $extra_headers);
    }

    /**
     * PUT request.
     */
    public function put(string $endpoint, array $data): array
    {
        $endpoint = '/' . ltrim($endpoint, '/');
        $url  = $this->base_url . $endpoint;
        $body = json_encode($data);
        return $this->request('PUT', $url, $body);
    }

    /**
     * DELETE request.
     */
    public function delete(string $endpoint): array
    {
        $endpoint = '/' . ltrim($endpoint, '/');
        $url = $this->base_url . $endpoint;
        return $this->request('DELETE', $url);
    }

    // ─── Batch API & Report KPI (v2.5.0+) ────────────────────────────────

    /**
     * Execute multiple REST operations in a single request via POST /api/v1/batch.
     *
     * @param  array $operations Array of ['method' => 'GET|POST|PUT|DELETE', 'path' => '/api/v1/...', 'body' => [...]]
     * @param  bool  $atomic     When true, rolls back the entire transaction if any operation fails
     * @return array
     */
    public function batch(array $operations, bool $atomic = false): array
    {
        return $this->post('/api/v1/batch', [
            'atomic'     => $atomic,
            'operations' => $operations,
        ]);
    }

    /**
     * Compute full-dataset server-side KPI stats (SUM, AVG, MIN, MAX, COUNT) for a custom report view.
     *
     * @param  string      $slug   Report route slug
     * @param  string|null $column Optional visible column alias
     * @return array
     */
    public function reportKpi(string $slug, ?string $column = null): array
    {
        $params = $column ? ['column' => $column] : [];
        return $this->get('/api/v1/reports/' . rawurlencode($slug) . '/kpi', $params);
    }

    // ─── MCP Server (JSON-RPC 2.0 for AI Agents - v2.5.0+) ───────────────

    /**
     * Call the Data Builder MCP Server (`POST /api/v1/mcp`) using JSON-RPC 2.0.
     *
     * Supported methods:
     *   - 'initialize'
     *   - 'ping'
     *   - 'tools/list'
     *   - 'tools/call'     (params: ['name' => '...', 'arguments' => [...]])
     *   - 'resources/list'
     *   - 'resources/read' (params: ['uri' => 'perfex://...'])
     *   - 'prompts/list'
     *   - 'prompts/get'    (params: ['name' => '...', 'arguments' => [...]])
     *
     * @param  string     $rpc_method JSON-RPC method name
     * @param  array      $params     Method parameters
     * @param  bool       $dry_run    When true, simulates write mutations in sandbox mode
     * @param  int|string $id         JSON-RPC request ID
     * @return array
     */
    public function mcp(string $rpc_method, array $params = [], bool $dry_run = false, $id = 1): array
    {
        $payload = [
            'jsonrpc' => '2.0',
            'id'      => $id,
            'method'  => $rpc_method,
            'params'  => !empty($params) ? $params : new \stdClass(),
        ];
        $extra_headers = $dry_run ? ['X-Dry-Run: true'] : [];
        return $this->post('/api/v1/mcp', $payload, null, $extra_headers);
    }

    // ─── GraphQL ─────────────────────────────────────────────────────────

    /**
     * Execute a GraphQL query/mutation.
     *
     * @param  string $query     GraphQL query string
     * @param  array  $variables GraphQL variables
     * @return array
     */
    public function graphql(string $query, array $variables = []): array
    {
        $payload = ['query' => $query];
        if (!empty($variables)) {
            $payload['variables'] = $variables;
        }
        return $this->post('/api/v1/graphql', $payload);
    }

    // ─── Auth Test ───────────────────────────────────────────────────────

    /**
     * Test connection via GET /api/v1/auth/test.
     */
    public function testConnection(): array
    {
        return $this->get('/api/v1/auth/test');
    }

    // ─── Internal HTTP Engine ────────────────────────────────────────────

    /**
     * Execute an HTTP request via cURL.
     *
     * @param  string      $method        HTTP method
     * @param  string      $url           Full URL
     * @param  string|null $body          Request body (JSON)
     * @param  array       $extra_headers Optional additional HTTP headers
     * @return array
     */
    private function request(string $method, string $url, ?string $body = null, array $extra_headers = []): array
    {
        $headers = [
            'Authorization: Bearer ' . $this->token,
            'Content-Type: application/json',
            'Accept: application/json',
        ];
        if (!empty($extra_headers)) {
            $headers = array_merge($headers, $extra_headers);
        }

        // HMAC signing
        if (!empty($this->hmac_secret)) {
            $timestamp = time();
            $parsed    = parse_url($url);
            $path      = $parsed['path'] ?? '/';
            $query     = $parsed['query'] ?? '';
            $body_hash = hash('sha256', $body ?? '');

            $canonical = implode("\n", [$method, $path, $query, $body_hash, $timestamp]);
            $signature = hash_hmac('sha256', $canonical, $this->hmac_secret);

            $headers[] = 'X-Signature: ' . $signature;
            $headers[] = 'X-Timestamp: ' . $timestamp;
        }

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_SSL_VERIFYPEER => $this->verify_ssl,
            CURLOPT_SSL_VERIFYHOST => $this->verify_ssl ? 2 : 0,
            CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4,
            CURLOPT_FOLLOWLOCATION => false,
        ]);

        // Force localhost → 127.0.0.1 resolution to avoid loopback DNS issues
        $parsed_host = parse_url($url, PHP_URL_HOST) ?? '';
        $parsed_port = parse_url($url, PHP_URL_PORT) ?? (parse_url($url, PHP_URL_SCHEME) === 'https' ? 443 : 80);
        if (in_array($parsed_host, ['localhost', '127.0.0.1', '::1'])) {
            curl_setopt($ch, CURLOPT_RESOLVE, [$parsed_host . ':' . $parsed_port . ':127.0.0.1']);
        }

        if ($body !== null && in_array($method, ['POST', 'PUT', 'PATCH'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }

        $raw_response  = curl_exec($ch);
        $http_code     = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error    = curl_error($ch);
        $total_time_ms = (int) round(curl_getinfo($ch, CURLINFO_TOTAL_TIME) * 1000);
        curl_close($ch);

        if ($raw_response === false) {
            return [
                'success'       => false,
                'status'        => 0,
                'data'          => null,
                'error'         => $curl_error ?: 'cURL request failed',
                'raw'           => '',
                'time_ms'       => $total_time_ms,
            ];
        }

        $decoded = json_decode($raw_response, true);

        return [
            'success'       => $http_code >= 200 && $http_code < 300,
            'status'        => $http_code,
            'data'          => $decoded['data'] ?? $decoded ?? null,
            'meta'          => $decoded['meta'] ?? null,
            'links'         => $decoded['links'] ?? null,
            'error'         => $decoded['detail'] ?? $decoded['title'] ?? null,
            'raw'           => $raw_response,
            'time_ms'       => $total_time_ms,
        ];
    }
}
