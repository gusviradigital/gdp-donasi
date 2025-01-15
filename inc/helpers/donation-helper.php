<?php
/**
 * Donation Helper Functions
 * 
 * @package GusviraDigital
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get donation instance
 */
function gdp_donation() {
    return \GDP\Classes\Donation::get_instance();
}

/**
 * Get donation history instance
 */
function gdp_donation_history() {
    return \GDP\Classes\Donation_History::get_instance();
}

/**
 * Get payment instance
 */
function gdp_payment() {
    return \GDP\Classes\Payment::get_instance();
}

/**
 * Format rupiah
 */
function gdp_format_rupiah($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

/**
 * Get donation status label
 */
function gdp_get_donation_status_label($status) {
    $labels = [
        'pending' => __('Menunggu Pembayaran', 'gusviradigital'),
        'processing' => __('Sedang Diproses', 'gusviradigital'),
        'completed' => __('Berhasil', 'gusviradigital'),
        'failed' => __('Gagal', 'gusviradigital'),
        'refunded' => __('Dikembalikan', 'gusviradigital'),
        'cancelled' => __('Dibatalkan', 'gusviradigital'),
    ];

    return isset($labels[$status]) ? $labels[$status] : $status;
}

/**
 * Get donation status color
 */
function gdp_get_donation_status_color($status) {
    $colors = [
        'pending' => 'yellow',
        'processing' => 'blue',
        'completed' => 'green',
        'failed' => 'red',
        'refunded' => 'gray',
        'cancelled' => 'gray',
    ];

    return isset($colors[$status]) ? $colors[$status] : 'gray';
}

/**
 * Get donor name
 */
function gdp_get_donor_name($donation) {
    if ($donation->is_anonymous) {
        return __('Hamba Allah', 'gusviradigital');
    }

    return $donation->name;
}

/**
 * Get payment methods
 */
function gdp_get_payment_methods() {
    return [
        'bank_transfer' => [
            'label' => __('Transfer Bank', 'gusviradigital'),
            'description' => __('Pembayaran melalui transfer bank manual', 'gusviradigital'),
            'banks' => [
                'bca' => 'BCA',
                'mandiri' => 'Mandiri',
                'bni' => 'BNI',
                'bri' => 'BRI',
            ],
        ],
        'ewallet' => [
            'label' => __('E-Wallet', 'gusviradigital'),
            'description' => __('Pembayaran melalui dompet digital', 'gusviradigital'),
            'providers' => [
                'gopay' => 'GoPay',
                'ovo' => 'OVO',
                'dana' => 'DANA',
                'linkaja' => 'LinkAja',
            ],
        ],
        'qris' => [
            'label' => 'QRIS',
            'description' => __('Pembayaran melalui QRIS', 'gusviradigital'),
        ],
    ];
}

/**
 * Get payment gateway settings
 */
function gdp_get_payment_gateway_settings() {
    $settings = get_option('gdp_payment_gateway_settings');

    $defaults = [
        'enabled' => false,
        'test_mode' => true,
        'midtrans' => [
            'enabled' => false,
            'client_key' => '',
            'server_key' => '',
        ],
        'xendit' => [
            'enabled' => false,
            'public_key' => '',
            'secret_key' => '',
        ],
        'tripay' => [
            'enabled' => false,
            'api_key' => '',
            'private_key' => '',
            'merchant_code' => '',
        ],
    ];

    return wp_parse_args($settings, $defaults);
}

/**
 * Get active payment gateway
 */
function gdp_get_active_payment_gateway() {
    $settings = gdp_get_payment_gateway_settings();
    
    if ($settings['midtrans']['enabled']) {
        return 'midtrans';
    }
    
    if ($settings['xendit']['enabled']) {
        return 'xendit';
    }
    
    if ($settings['tripay']['enabled']) {
        return 'tripay';
    }
    
    return false;
}

/**
 * Check if payment gateway is ready
 */
function gdp_is_payment_gateway_ready() {
    return (bool) gdp_get_active_payment_gateway();
}

/**
 * Get donation form nonce
 */
function gdp_get_donation_form_nonce() {
    return wp_create_nonce('gdp_donation_nonce');
}

/**
 * Get donation ajax url
 */
function gdp_get_donation_ajax_url() {
    return admin_url('admin-ajax.php');
}

/**
 * Get donation success page url
 */
function gdp_get_donation_success_page_url() {
    $page_id = gdp_get_option('donation_success_page');
    return $page_id ? get_permalink($page_id) : home_url();
}

/**
 * Get donation failed page url
 */
function gdp_get_donation_failed_page_url() {
    $page_id = gdp_get_option('donation_failed_page');
    return $page_id ? get_permalink($page_id) : home_url();
}

/**
 * Get option
 */
function gdp_get_option($key, $default = '') {
    global $gdp_options;
    
    if (!isset($gdp_options)) {
        $gdp_options = get_option('gdp_options');
    }
    
    return isset($gdp_options[$key]) ? $gdp_options[$key] : $default;
} 