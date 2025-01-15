<?php
/**
 * Payment Helper Functions
 * 
 * @package GusviraDigital
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get active payment gateway
 */
function gdp_get_active_payment_gateway() {
    return gdp_options('payment_gateway', 'midtrans');
}

/**
 * Get payment gateway settings
 */
function gdp_get_payment_gateway_settings() {
    $settings = [
        'test_mode' => gdp_options('payment_test_mode', true),
        'midtrans' => [
            'server_key' => gdp_options('midtrans_server_key', ''),
            'client_key' => gdp_options('midtrans_client_key', ''),
            'merchant_id' => gdp_options('midtrans_merchant_id', ''),
            'mode' => gdp_options('midtrans_mode', 'sandbox'),
        ],
        'xendit' => [
            'api_key' => gdp_options('xendit_api_key', ''),
            'callback_token' => gdp_options('xendit_callback_token', ''),
            'mode' => gdp_options('xendit_mode', 'sandbox'),
        ],
        'tripay' => [
            'api_key' => gdp_options('tripay_api_key', ''),
            'merchant_code' => gdp_options('tripay_merchant_code', ''),
            'private_key' => gdp_options('tripay_private_key', ''),
            'mode' => gdp_options('tripay_mode', 'sandbox'),
        ]
    ];

    return apply_filters('gdp_payment_gateway_settings', $settings);
}

/**
 * Check if payment gateway is ready
 */
function gdp_is_payment_gateway_ready() {
    $gateway = gdp_get_active_payment_gateway();
    $settings = gdp_get_payment_gateway_settings();

    switch ($gateway) {
        case 'midtrans':
            return !empty($settings['midtrans']['server_key']) && !empty($settings['midtrans']['client_key']);

        case 'xendit':
            return !empty($settings['xendit']['api_key']);

        case 'tripay':
            return !empty($settings['tripay']['api_key']) && !empty($settings['tripay']['private_key']) && !empty($settings['tripay']['merchant_code']);

        default:
            return false;
    }
}

/**
 * Get available payment methods
 */
function gdp_get_payment_methods() {
    $gateway = gdp_get_active_payment_gateway();
    $methods = [];

    switch ($gateway) {
        case 'midtrans':
            $methods = [
                'credit_card' => __('Kartu Kredit', 'gusviradigital'),
                'bank_transfer' => __('Transfer Bank', 'gusviradigital'),
                'gopay' => 'GoPay',
                'shopeepay' => 'ShopeePay'
            ];
            break;

        case 'xendit':
            $methods = [
                'credit_card' => __('Kartu Kredit', 'gusviradigital'),
                'bank_transfer' => __('Transfer Bank', 'gusviradigital'),
                'ewallet' => __('E-Wallet', 'gusviradigital'),
                'retail' => __('Retail', 'gusviradigital'),
                'qris' => 'QRIS'
            ];
            break;

        case 'tripay':
            $methods = [
                'BRIVA' => 'BRI Virtual Account',
                'MANDIRIBA' => 'Mandiri Virtual Account',
                'BCAVA' => 'BCA Virtual Account',
                'BNIVA' => 'BNI Virtual Account',
                'QRIS' => 'QRIS',
                'QRISC' => 'QRIS by CustomPay',
                'QRISOP' => 'QRIS OTTOPAY',
                'QRISCST' => 'QRIS CST',
                'OVO' => 'OVO',
                'DANA' => 'DANA',
                'LINKAJA' => 'LinkAja',
                'SHOPEEPAY' => 'ShopeePay'
            ];
            break;
    }

    return apply_filters('gdp_payment_methods', $methods);
}

/**
 * Get payment method label
 */
function gdp_get_payment_method_label($method) {
    $methods = gdp_get_payment_methods();
    return isset($methods[$method]) ? $methods[$method] : $method;
}

/**
 * Get payment status label
 */
function gdp_get_payment_status_label($status) {
    $labels = [
        'pending' => __('Menunggu Pembayaran', 'gusviradigital'),
        'processing' => __('Sedang Diproses', 'gusviradigital'),
        'completed' => __('Berhasil', 'gusviradigital'),
        'failed' => __('Gagal', 'gusviradigital'),
        'refunded' => __('Dikembalikan', 'gusviradigital'),
        'cancelled' => __('Dibatalkan', 'gusviradigital')
    ];

    return isset($labels[$status]) ? $labels[$status] : $status;
}

/**
 * Get payment status color
 */
function gdp_get_payment_status_color($status) {
    $colors = [
        'pending' => 'yellow',
        'processing' => 'blue',
        'completed' => 'green',
        'failed' => 'red',
        'refunded' => 'purple',
        'cancelled' => 'gray'
    ];

    return isset($colors[$status]) ? $colors[$status] : 'gray';
}

/**
 * Get payment form nonce
 */
function gdp_get_payment_form_nonce() {
    return wp_create_nonce('gdp_payment_nonce');
}

/**
 * Get payment ajax url
 */
function gdp_get_payment_ajax_url() {
    return admin_url('admin-ajax.php');
} 