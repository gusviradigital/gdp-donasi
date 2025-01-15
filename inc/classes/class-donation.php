<?php
/**
 * Donation Class
 * 
 * @package GusviraDigital
 * @version 1.0.0
 */

namespace GDP\Classes;

if (!defined('ABSPATH')) {
    exit;
}

class Donation {
    /**
     * Instance
     */
    private static $instance = null;

    /**
     * Table name
     */
    private $table;

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
        global $wpdb;
        $this->table = $wpdb->prefix . 'donations';
        
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // AJAX actions
        add_action('wp_ajax_gdp_create_donation', [$this, 'ajax_create_donation']);
        add_action('wp_ajax_nopriv_gdp_create_donation', [$this, 'ajax_create_donation']);
        
        // Load more donations
        add_action('wp_ajax_gdp_load_more_donations', [$this, 'ajax_load_more_donations']);
        add_action('wp_ajax_nopriv_gdp_load_more_donations', [$this, 'ajax_load_more_donations']);
    }

    /**
     * Create donation via AJAX
     */
    public function ajax_create_donation() {
        try {
            // Verify nonce
            if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'gdp_donation_nonce')) {
                throw new \Exception(__('Invalid security token sent.', 'gusviradigital'));
            }

            // Validate required fields
            $required_fields = ['program_id', 'name', 'email', 'phone', 'amount'];
            foreach ($required_fields as $field) {
                if (empty($_POST[$field])) {
                    throw new \Exception(sprintf(__('Field %s is required.', 'gusviradigital'), $field));
                }
            }

            // Sanitize input
            $data = [
                'program_id' => absint($_POST['program_id']),
                'name' => sanitize_text_field($_POST['name']),
                'email' => sanitize_email($_POST['email']),
                'phone' => sanitize_text_field($_POST['phone']),
                'amount' => floatval($_POST['amount']),
                'message' => isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '',
                'is_anonymous' => isset($_POST['is_anonymous']) ? 1 : 0,
                'user_id' => get_current_user_id(),
            ];

            // Create donation
            $donation_id = $this->create($data);

            // Return success response
            wp_send_json_success([
                'donation_id' => $donation_id,
                'message' => __('Donation created successfully.', 'gusviradigital'),
            ]);

        } catch (\Exception $e) {
            wp_send_json_error([
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Create donation
     */
    public function create($data) {
        global $wpdb;

        // Validate program
        $program = get_post($data['program_id']);
        if (!$program || $program->post_type !== 'program') {
            throw new \Exception(__('Invalid program ID.', 'gusviradigital'));
        }

        // Insert donation
        $result = $wpdb->insert(
            $this->table,
            $data,
            [
                '%d', // program_id
                '%s', // name
                '%s', // email
                '%s', // phone
                '%f', // amount
                '%s', // message
                '%d', // is_anonymous
                '%d', // user_id
            ]
        );

        if (false === $result) {
            throw new \Exception(__('Failed to create donation.', 'gusviradigital'));
        }

        $donation_id = $wpdb->insert_id;

        // Update program collected amount
        $collected = get_post_meta($data['program_id'], '_donation_collected', true);
        $collected = floatval($collected) + $data['amount'];
        update_post_meta($data['program_id'], '_donation_collected', $collected);

        do_action('gdp_donation_created', $donation_id, $data);

        return $donation_id;
    }

    /**
     * Get donation by ID
     */
    public function get($id) {
        global $wpdb;

        $donation = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM {$this->table} WHERE id = %d",
                $id
            )
        );

        if (!$donation) {
            return false;
        }

        return $donation;
    }

    /**
     * Update donation status
     */
    public function update_status($id, $status, $payment_data = null) {
        global $wpdb;

        $data = ['payment_status' => $status];
        $format = ['%s'];

        if ($payment_data) {
            $data['payment_data'] = json_encode($payment_data);
            $format[] = '%s';
        }

        $result = $wpdb->update(
            $this->table,
            $data,
            ['id' => $id],
            $format,
            ['%d']
        );

        if (false === $result) {
            return false;
        }

        do_action('gdp_donation_status_updated', $id, $status, $payment_data);

        return true;
    }

    /**
     * Get donations by program
     */
    public function get_by_program($program_id, $args = []) {
        global $wpdb;

        $defaults = [
            'status' => 'completed',
            'limit' => 10,
            'offset' => 0,
            'orderby' => 'created_at',
            'order' => 'DESC',
        ];

        $args = wp_parse_args($args, $defaults);

        $query = $wpdb->prepare(
            "SELECT * FROM {$this->table} 
            WHERE program_id = %d 
            AND payment_status = %s
            ORDER BY {$args['orderby']} {$args['order']}
            LIMIT %d OFFSET %d",
            $program_id,
            $args['status'],
            $args['limit'],
            $args['offset']
        );

        return $wpdb->get_results($query);
    }

    /**
     * Count donations by program
     */
    public function count_by_program($program_id, $status = 'completed') {
        global $wpdb;

        return $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM {$this->table} 
                WHERE program_id = %d 
                AND payment_status = %s",
                $program_id,
                $status
            )
        );
    }

    /**
     * Get total amount by program
     */
    public function get_total_amount($program_id, $status = 'completed') {
        global $wpdb;

        return $wpdb->get_var(
            $wpdb->prepare(
                "SELECT SUM(amount) FROM {$this->table} 
                WHERE program_id = %d 
                AND payment_status = %s",
                $program_id,
                $status
            )
        );
    }

    /**
     * Load more donations via AJAX
     */
    public function ajax_load_more_donations() {
        try {
            // Verify nonce
            if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'gdp_nonce')) {
                throw new \Exception(__('Invalid security token sent.', 'gusviradigital'));
            }

            // Get parameters
            $program_id = isset($_POST['program_id']) ? absint($_POST['program_id']) : 0;
            $page = isset($_POST['page']) ? absint($_POST['page']) : 1;
            
            if (!$program_id) {
                throw new \Exception(__('Invalid program ID.', 'gusviradigital'));
            }

            // Get donations
            $donations = $this->get_by_program($program_id, [
                'status' => 'completed',
                'limit' => 10,
                'offset' => $page * 10,
                'orderby' => 'created_at',
                'order' => 'DESC'
            ]);

            // Start output buffer
            ob_start();
            
            // Generate HTML
            foreach ($donations as $donation) {
                $donor_name = gdp_get_donor_name($donation);
                $amount = gdp_format_rupiah($donation->amount);
                $date = date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($donation->created_at));
                $message = !empty($donation->message) ? $donation->message : '';
                ?>
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <h4 class="font-semibold text-gray-900"><?php echo esc_html($donor_name); ?></h4>
                            <p class="text-sm text-gray-600"><?php echo esc_html($date); ?></p>
                            <?php if (!empty($message)) : ?>
                                <p class="mt-2 text-gray-700"><?php echo esc_html($message); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="text-right">
                            <span class="font-semibold text-primary"><?php echo esc_html($amount); ?></span>
                        </div>
                    </div>
                </div>
                <?php
            }

            $html = ob_get_clean();

            // Count total donations
            $total_donations = $this->count_by_program($program_id, ['status' => 'completed']);
            $has_more = ($page + 1) * 10 < $total_donations;

            wp_send_json_success([
                'html' => $html,
                'has_more' => $has_more
            ]);

        } catch (\Exception $e) {
            wp_send_json_error([
                'message' => $e->getMessage()
            ]);
        }
    }
}

// Initialize
Donation::get_instance(); 