<?php
/**
 * Helper functions for donation
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
    return GDP\Classes\Donation::get_instance();
}

/**
 * Get donor name
 */
function gdp_get_donor_name($donation) {
    if (empty($donation)) {
        return '';
    }

    if (!empty($donation->is_anonymous)) {
        return __('Hamba Allah', 'gusviradigital');
    }

    return $donation->name;
}

/**
 * Format amount to Rupiah
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
        'processing' => __('Diproses', 'gusviradigital'),
        'completed' => __('Selesai', 'gusviradigital'),
        'failed' => __('Gagal', 'gusviradigital'),
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
        'cancelled' => 'gray',
    ];

    return isset($colors[$status]) ? $colors[$status] : 'gray';
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
    $page_id = gdp_options('donation_success_page');
    return $page_id ? get_permalink($page_id) : home_url();
}

/**
 * Get donation failed page url
 */
function gdp_get_donation_failed_page_url() {
    $page_id = gdp_options('donation_failed_page');
    return $page_id ? get_permalink($page_id) : home_url();
} 