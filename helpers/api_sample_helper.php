<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * ApiSampleClient — HTTP client wrapper for Data Builder REST API & GraphQL.
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
        $url = $this->base_url . $endpoint;
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        return $this->request('GET', $url);
    }

    /**
     * POST request.
     */
    public function post(string $endpoint, array $data): array
    {
        $url  = $this->base_url . $endpoint;
        $body = json_encode($data);
        return $this->request('POST', $url, $body);
    }

    /**
     * PUT request.
     */
    public function put(string $endpoint, array $data): array
    {
        $url  = $this->base_url . $endpoint;
        $body = json_encode($data);
        return $this->request('PUT', $url, $body);
    }

    /**
     * DELETE request.
     */
    public function delete(string $endpoint): array
    {
        $url = $this->base_url . $endpoint;
        return $this->request('DELETE', $url);
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
     * @param  string      $method  HTTP method
     * @param  string      $url     Full URL
     * @param  string|null $body    Request body (JSON)
     * @return array
     */
    private function request(string $method, string $url, ?string $body = null): array
    {
        $headers = [
            'Authorization: Bearer ' . $this->token,
            'Content-Type: application/json',
            'Accept: application/json',
        ];

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
