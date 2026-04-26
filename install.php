<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Installation hook for API Data Builder Sample module.
 */

// Default settings
$defaults = [
    'api_sample_base_url'      => '',
    'api_sample_api_token'     => '',
    'api_sample_hmac_secret'   => '',
    'api_sample_verify_ssl'    => '0',
];

foreach ($defaults as $key => $value) {
    if (get_option($key) === false || get_option($key) === null) {
        add_option($key, $value);
    }
}
