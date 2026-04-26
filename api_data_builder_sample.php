<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
Module Name: API Data Builder Sample
Description: Sample/showcase module demonstrating how to use Data Builder REST API, GraphQL, and Webhooks to perform CRUD operations on Perfex CRM data. Provides interactive demos with live code examples for customers who purchase the Data Builder module.
Version: 1.0.0
Requires at least: 3.0.0
Author: PolyXGO
Author URI: https://codecanyon.net/user/polyxgo
*/

define('API_SAMPLE_MODULE', 'api_data_builder_sample');
define('API_SAMPLE_VERSION', '1.0.0');

/**
 * Register activation hook
 */
register_activation_hook(API_SAMPLE_MODULE, 'api_sample_activation_hook');

function api_sample_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

/**
 * Register language files
 */
register_language_files(API_SAMPLE_MODULE, [API_SAMPLE_MODULE]);

/**
 * Main Module Class
 */
class Api_data_builder_sample_module
{
    private $ci;

    public function __construct()
    {
        $this->ci = &get_instance();
        $this->init_hooks();
    }

    private function init_hooks()
    {
        hooks()->add_action('admin_init', [$this, 'init_admin']);
        hooks()->add_filter('module_' . API_SAMPLE_MODULE . '_action_links', [$this, 'module_action_links']);
    }

    public function init_admin()
    {
        if (is_admin() || has_permission(API_SAMPLE_MODULE, '', 'view')) {
            $CI = &get_instance();

            $CI->app_menu->add_sidebar_menu_item(API_SAMPLE_MODULE, [
                'name'     => 'API Samples',
                'icon'     => 'fa fa-plug',
                'position' => 8,
            ]);

            $CI->app_menu->add_sidebar_children_item(API_SAMPLE_MODULE, [
                'slug'     => 'api_sample_dashboard',
                'name'     => 'Dashboard',
                'icon'     => 'fa fa-tachometer',
                'href'     => admin_url('api_data_builder_sample'),
                'position' => 1,
            ]);

            $CI->app_menu->add_sidebar_children_item(API_SAMPLE_MODULE, [
                'slug'     => 'api_sample_rest',
                'name'     => 'REST API',
                'icon'     => 'fa fa-exchange',
                'href'     => admin_url('api_data_builder_sample/rest'),
                'position' => 2,
            ]);

            $CI->app_menu->add_sidebar_children_item(API_SAMPLE_MODULE, [
                'slug'     => 'api_sample_graphql',
                'name'     => 'GraphQL',
                'icon'     => 'fa fa-share-alt',
                'href'     => admin_url('api_data_builder_sample/graphql'),
                'position' => 3,
            ]);

            $CI->app_menu->add_sidebar_children_item(API_SAMPLE_MODULE, [
                'slug'     => 'api_sample_webhooks',
                'name'     => 'Webhooks',
                'icon'     => 'fa fa-rss',
                'href'     => admin_url('api_data_builder_sample/webhooks'),
                'position' => 4,
            ]);

            $CI->app_menu->add_sidebar_children_item(API_SAMPLE_MODULE, [
                'slug'     => 'api_sample_settings',
                'name'     => _l('settings'),
                'icon'     => 'fa fa-cog',
                'href'     => admin_url('api_data_builder_sample/settings'),
                'position' => 10,
            ]);
        }
    }

    public function module_action_links($actions)
    {
        $actions[] = '<a href="' . admin_url('api_data_builder_sample/settings') . '">' . _l('settings') . '</a>';
        return $actions;
    }
}

// Boot module
new Api_data_builder_sample_module();
