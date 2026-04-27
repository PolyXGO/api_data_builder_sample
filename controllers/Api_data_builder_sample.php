<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(__DIR__ . '/../helpers/api_sample_helper.php');

/**
 * Main Admin Controller for API Data Builder Sample module.
 */
class Api_data_builder_sample extends AdminController
{
    /** @var ApiSampleClient */
    private $api;

    public function __construct()
    {
        parent::__construct();

        if (!is_admin() && !has_permission(API_SAMPLE_MODULE, '', 'view')) {
            access_denied(API_SAMPLE_MODULE);
        }

        $this->api = new ApiSampleClient();
    }

    // ─── Dashboard ───────────────────────────────────────────────────────

    public function index()
    {
        $data = [
            'title'         => 'API Data Builder Sample — Dashboard',
            'api'           => $this->api,
            'is_configured' => $this->api->isConfigured(),
        ];

        $this->load->view('api_data_builder_sample/admin/dashboard', $data);
    }

    // ─── REST API Demos ──────────────────────────────────────────────────

    public function rest()
    {
        $resource = $this->input->get('resource') ?: 'projects';
        $action   = $this->input->get('action') ?: 'list';

        $data = [
            'title'    => 'REST API Demos — ' . ucfirst($resource),
            'api'      => $this->api,
            'resource' => $resource,
            'action'   => $action,
            'result'   => null,
        ];

        if (!$this->api->isConfigured()) {
            set_alert('warning', 'Please configure API settings first.');
            redirect(admin_url('api_data_builder_sample/settings'));
        }

        $this->load->view('api_data_builder_sample/admin/rest', $data);
    }

    // ─── GraphQL Demos ───────────────────────────────────────────────────

    public function graphql()
    {
        $data = [
            'title' => 'GraphQL API Demos',
            'api'   => $this->api,
        ];

        if (!$this->api->isConfigured()) {
            set_alert('warning', 'Please configure API settings first.');
            redirect(admin_url('api_data_builder_sample/settings'));
        }

        $this->load->view('api_data_builder_sample/admin/graphql', $data);
    }

    // ─── Webhooks ────────────────────────────────────────────────────────

    public function webhooks()
    {
        $data = [
            'title' => 'Webhook Integration Guide',
        ];

        $this->load->view('api_data_builder_sample/admin/webhooks', $data);
    }

    // ─── Settings ────────────────────────────────────────────────────────

    public function settings()
    {
        // Demo restriction: non-primary admins cannot modify settings on demo sites
        $_is_demo_site = file_exists(FCPATH . 'modules/demo_builder/demo_builder.php');
        $_is_primary_admin = (int) get_staff_user_id() === 1;

        if ($this->input->is_ajax_request() && $this->input->post()) {
            // Block save on demo site for non-primary admins
            if ($_is_demo_site && !$_is_primary_admin) {
                echo json_encode(['success' => false, 'message' => 'Settings are read-only on the demo site. Only the primary admin can modify them.']);
                return;
            }

            // Save settings
            $fields = ['api_sample_base_url', 'api_sample_api_token', 'api_sample_hmac_secret', 'api_sample_verify_ssl'];
            foreach ($fields as $key) {
                $val = $this->input->post($key);
                update_option($key, $val !== null ? $val : '');
            }
            // Ensure verify_ssl is 0 when unchecked
            if ($this->input->post('api_sample_verify_ssl') === null) {
                update_option('api_sample_verify_ssl', '0');
            }

            echo json_encode(['success' => true, 'message' => 'Settings saved.']);
            return;
        }

        $data = [
            'title'            => 'API Sample — Settings',
            'is_demo_site'     => $_is_demo_site,
            'is_primary_admin' => $_is_primary_admin,
        ];

        $this->load->view('api_data_builder_sample/admin/settings', $data);
    }

    // ─── AJAX: Execute API Call ──────────────────────────────────────────

    /**
     * AJAX proxy for executing API calls from the frontend demo panels.
     * POST body: { method, endpoint, body?, query? }
     */
    public function ajax_call()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        header('Content-Type: application/json');

        $method   = strtoupper($this->input->post('method') ?: 'GET');
        $endpoint = $this->input->post('endpoint') ?: '';
        $body     = $this->input->post('body') ?: '';
        $query    = $this->input->post('query_params') ?: '';

        if (empty($endpoint)) {
            echo json_encode(['success' => false, 'error' => 'Endpoint is required.']);
            return;
        }

        // Append query params
        if (!empty($query)) {
            $endpoint .= (strpos($endpoint, '?') !== false ? '&' : '?') . $query;
        }

        $result = null;
        switch ($method) {
            case 'GET':
                $result = $this->api->get($endpoint);
                break;
            case 'POST':
                $payload = !empty($body) ? json_decode($body, true) : [];
                $result  = $this->api->post($endpoint, $payload ?: []);
                break;
            case 'PUT':
                $payload = !empty($body) ? json_decode($body, true) : [];
                $result  = $this->api->put($endpoint, $payload ?: []);
                break;
            case 'DELETE':
                $result = $this->api->delete($endpoint);
                break;
            default:
                $result = ['success' => false, 'error' => 'Unsupported method: ' . $method];
        }

        echo json_encode($result);
    }

    /**
     * AJAX: Test connection
     */
    public function ajax_test_connection()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        header('Content-Type: application/json');

        // Reload client with latest settings
        $api = new ApiSampleClient();

        if (!$api->isConfigured()) {
            echo json_encode(['success' => false, 'error' => 'API URL and Token are required.']);
            return;
        }

        $result = $api->testConnection();
        echo json_encode($result);
    }
}
