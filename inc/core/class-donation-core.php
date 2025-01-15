<?php
/**
 * Donation Core Class
 * 
 * @package GusviraDigital
 * @version 1.0.0
 */

namespace GDP\Core;

if (!defined('ABSPATH')) {
    exit;
}

class Donation_Core {
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
        add_action('init', [$this, 'init']);
        add_action('admin_init', [$this, 'create_tables']);
        register_activation_hook(GDP_FILE, [$this, 'create_tables']);
    }

    /**
     * Initialize
     */
    public function init() {
        // Load classes
        $this->load_classes();
    }

    /**
     * Load classes
     */
    private function load_classes() {
        // Load donation class
        require_once GDP_INC . '/classes/class-donation.php';
        \GDP\Classes\Donation::get_instance();

        // Load donation history class
        require_once GDP_INC . '/classes/class-donation-history.php';
        \GDP\Classes\Donation_History::get_instance();

        // Load payment class
        require_once GDP_INC . '/classes/class-payment.php';
        \GDP\Classes\Payment::get_instance();
    }

    /**
     * Create tables
     */
    public function create_tables() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        // Donations table
        $table_donations = $wpdb->prefix . 'gdp_donations';
        $sql_donations = "CREATE TABLE IF NOT EXISTS $table_donations (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            program_id bigint(20) NOT NULL,
            user_id bigint(20) DEFAULT NULL,
            name varchar(100) NOT NULL,
            email varchar(100) NOT NULL,
            phone varchar(20) NOT NULL,
            amount decimal(15,2) NOT NULL,
            message text DEFAULT NULL,
            is_anonymous tinyint(1) DEFAULT 0,
            payment_method varchar(50) DEFAULT NULL,
            payment_status varchar(20) DEFAULT 'pending',
            payment_data text DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY program_id (program_id),
            KEY user_id (user_id),
            KEY payment_status (payment_status)
        ) $charset_collate;";

        // Payment History table
        $table_payment_history = $wpdb->prefix . 'gdp_payment_history';
        $sql_payment_history = "CREATE TABLE IF NOT EXISTS $table_payment_history (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            donation_id bigint(20) NOT NULL,
            payment_id varchar(100) NOT NULL,
            payment_method varchar(50) NOT NULL,
            amount decimal(15,2) NOT NULL,
            status varchar(20) NOT NULL,
            raw_response text DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY donation_id (donation_id),
            KEY payment_id (payment_id)
        ) $charset_collate;";

        // Include WordPress upgrade script
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        // Create tables
        dbDelta($sql_donations);
        dbDelta($sql_payment_history);
    }
}

// Initialize
Donation_Core::get_instance(); 