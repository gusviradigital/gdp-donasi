<?php
/**
 * Donation History Class
 * 
 * @package GusviraDigital
 * @version 1.0.0
 */

namespace GDP\Classes;

if (!defined('ABSPATH')) {
    exit;
}

class Donation_History {
    /**
     * Instance
     */
    private static $instance = null;

    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Add menu page
        add_action('admin_menu', [$this, 'add_menu_page']);

        // Add AJAX handlers
        add_action('wp_ajax_gdp_get_donation_history', [$this, 'ajax_get_donation_history']);
        add_action('wp_ajax_gdp_export_donation_history', [$this, 'ajax_export_donation_history']);
    }

    /**
     * Add menu page
     */
    public function add_menu_page() {
        add_submenu_page(
            'edit.php?post_type=program',
            __('Riwayat Donasi', 'gusviradigital'),
            __('Riwayat Donasi', 'gusviradigital'),
            'manage_options',
            'donation-history',
            [$this, 'render_page']
        );
    }

    /**
     * Render page
     */
    public function render_page() {
        // Get donation data
        $donations = $this->get_donations([
            'limit' => 10,
            'offset' => 0,
        ]);

        // Get total donations
        $total = $this->get_total_donations();

        // Get total amount
        $total_amount = $this->get_total_amount();

        // Include template
        include GDP_PATH . '/templates/admin/donation-history.php';
    }

    /**
     * Get donations
     */
    public function get_donations($args = []) {
        global $wpdb;

        $defaults = [
            'limit' => 10,
            'offset' => 0,
            'orderby' => 'created_at',
            'order' => 'DESC',
            'status' => '',
            'program_id' => '',
            'search' => '',
            'start_date' => '',
            'end_date' => '',
        ];

        $args = wp_parse_args($args, $defaults);

        // Build query
        $query = "SELECT d.*, p.post_title as program_name 
                 FROM {$wpdb->prefix}donations d
                 LEFT JOIN {$wpdb->posts} p ON d.program_id = p.ID
                 WHERE 1=1";

        // Add filters
        if ($args['status']) {
            $query .= $wpdb->prepare(" AND d.payment_status = %s", $args['status']);
        }

        if ($args['program_id']) {
            $query .= $wpdb->prepare(" AND d.program_id = %d", $args['program_id']);
        }

        if ($args['search']) {
            $query .= $wpdb->prepare(
                " AND (d.name LIKE %s OR d.email LIKE %s OR d.phone LIKE %s)",
                '%' . $wpdb->esc_like($args['search']) . '%',
                '%' . $wpdb->esc_like($args['search']) . '%',
                '%' . $wpdb->esc_like($args['search']) . '%'
            );
        }

        if ($args['start_date'] && $args['end_date']) {
            $query .= $wpdb->prepare(
                " AND DATE(d.created_at) BETWEEN %s AND %s",
                $args['start_date'],
                $args['end_date']
            );
        }

        // Add order
        $query .= " ORDER BY d.{$args['orderby']} {$args['order']}";

        // Add limit
        $query .= $wpdb->prepare(" LIMIT %d OFFSET %d", $args['limit'], $args['offset']);

        return $wpdb->get_results($query);
    }

    /**
     * Get total donations
     */
    public function get_total_donations($args = []) {
        global $wpdb;

        $defaults = [
            'status' => '',
            'program_id' => '',
            'search' => '',
            'start_date' => '',
            'end_date' => '',
        ];

        $args = wp_parse_args($args, $defaults);

        // Build query
        $query = "SELECT COUNT(*) FROM {$wpdb->prefix}donations d WHERE 1=1";

        // Add filters
        if ($args['status']) {
            $query .= $wpdb->prepare(" AND payment_status = %s", $args['status']);
        }

        if ($args['program_id']) {
            $query .= $wpdb->prepare(" AND program_id = %d", $args['program_id']);
        }

        if ($args['search']) {
            $query .= $wpdb->prepare(
                " AND (name LIKE %s OR email LIKE %s OR phone LIKE %s)",
                '%' . $wpdb->esc_like($args['search']) . '%',
                '%' . $wpdb->esc_like($args['search']) . '%',
                '%' . $wpdb->esc_like($args['search']) . '%'
            );
        }

        if ($args['start_date'] && $args['end_date']) {
            $query .= $wpdb->prepare(
                " AND DATE(created_at) BETWEEN %s AND %s",
                $args['start_date'],
                $args['end_date']
            );
        }

        return $wpdb->get_var($query);
    }

    /**
     * Get total amount
     */
    public function get_total_amount($args = []) {
        global $wpdb;

        $defaults = [
            'status' => 'completed',
            'program_id' => '',
            'search' => '',
            'start_date' => '',
            'end_date' => '',
        ];

        $args = wp_parse_args($args, $defaults);

        // Build query
        $query = "SELECT SUM(amount) FROM {$wpdb->prefix}donations d WHERE 1=1";

        // Add filters
        if ($args['status']) {
            $query .= $wpdb->prepare(" AND payment_status = %s", $args['status']);
        }

        if ($args['program_id']) {
            $query .= $wpdb->prepare(" AND program_id = %d", $args['program_id']);
        }

        if ($args['search']) {
            $query .= $wpdb->prepare(
                " AND (name LIKE %s OR email LIKE %s OR phone LIKE %s)",
                '%' . $wpdb->esc_like($args['search']) . '%',
                '%' . $wpdb->esc_like($args['search']) . '%',
                '%' . $wpdb->esc_like($args['search']) . '%'
            );
        }

        if ($args['start_date'] && $args['end_date']) {
            $query .= $wpdb->prepare(
                " AND DATE(created_at) BETWEEN %s AND %s",
                $args['start_date'],
                $args['end_date']
            );
        }

        return $wpdb->get_var($query);
    }

    /**
     * Get donation history via AJAX
     */
    public function ajax_get_donation_history() {
        try {
            // Verify nonce
            if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'gdp_admin_nonce')) {
                throw new \Exception(__('Invalid security token sent.', 'gusviradigital'));
            }

            // Get donations
            $donations = $this->get_donations([
                'limit' => isset($_POST['limit']) ? absint($_POST['limit']) : 10,
                'offset' => isset($_POST['offset']) ? absint($_POST['offset']) : 0,
                'status' => isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '',
                'program_id' => isset($_POST['program_id']) ? absint($_POST['program_id']) : '',
                'search' => isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '',
                'start_date' => isset($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) : '',
                'end_date' => isset($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : '',
            ]);

            // Get total
            $total = $this->get_total_donations([
                'status' => isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '',
                'program_id' => isset($_POST['program_id']) ? absint($_POST['program_id']) : '',
                'search' => isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '',
                'start_date' => isset($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) : '',
                'end_date' => isset($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : '',
            ]);

            // Get total amount
            $total_amount = $this->get_total_amount([
                'status' => isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '',
                'program_id' => isset($_POST['program_id']) ? absint($_POST['program_id']) : '',
                'search' => isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '',
                'start_date' => isset($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) : '',
                'end_date' => isset($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : '',
            ]);

            // Return success response
            wp_send_json_success([
                'donations' => $donations,
                'total' => $total,
                'total_amount' => $total_amount,
            ]);

        } catch (\Exception $e) {
            wp_send_json_error([
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Export donation history via AJAX
     */
    public function ajax_export_donation_history() {
        try {
            // Verify nonce
            if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'gdp_admin_nonce')) {
                throw new \Exception(__('Invalid security token sent.', 'gusviradigital'));
            }

            // Get donations
            $donations = $this->get_donations([
                'limit' => -1,
                'status' => isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '',
                'program_id' => isset($_POST['program_id']) ? absint($_POST['program_id']) : '',
                'search' => isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '',
                'start_date' => isset($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) : '',
                'end_date' => isset($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : '',
            ]);

            // Prepare CSV data
            $csv_data = [];
            $csv_data[] = [
                __('ID', 'gusviradigital'),
                __('Program', 'gusviradigital'),
                __('Nama', 'gusviradigital'),
                __('Email', 'gusviradigital'),
                __('No. WhatsApp', 'gusviradigital'),
                __('Jumlah', 'gusviradigital'),
                __('Status', 'gusviradigital'),
                __('Metode Pembayaran', 'gusviradigital'),
                __('Tanggal', 'gusviradigital'),
            ];

            foreach ($donations as $donation) {
                $csv_data[] = [
                    $donation->id,
                    $donation->program_name,
                    $donation->is_anonymous ? __('Hamba Allah', 'gusviradigital') : $donation->name,
                    $donation->email,
                    $donation->phone,
                    $donation->amount,
                    gdp_get_donation_status_label($donation->payment_status),
                    $donation->payment_method,
                    $donation->created_at,
                ];
            }

            // Generate CSV file
            $filename = 'donation-history-' . date('Y-m-d') . '.csv';
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');

            $fp = fopen('php://output', 'w');
            foreach ($csv_data as $row) {
                fputcsv($fp, $row);
            }
            fclose($fp);

            exit();

        } catch (\Exception $e) {
            wp_send_json_error([
                'message' => $e->getMessage(),
            ]);
        }
    }
}

// Initialize
Donation_History::get_instance(); 