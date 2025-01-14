<?php
/**
 * Redux Framework Configuration
 *
 * @package GusviraDigital
 * @version 1.0.0
 */

namespace GDP\Redux;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Redux Configuration Class
 */
class Redux_Config {
    /**
     * Instance of this class.
     *
     * @var object
     */
    private static $instance;

    /**
     * Get instance of this class
     */
    public static function get_instance() {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        if ( ! class_exists( 'Redux' ) ) {
            return;
        }

        $this->init_redux();
    }

    /**
     * Initialize Redux Framework
     */
    private function init_redux() {
        $args = [
            'display_name'         => __( 'GDP Options', 'gusviradigital' ),
            'display_version'      => GDP_VERSION,
            'menu_type'           => 'menu',
            'allow_sub_menu'      => true,
            'menu_title'          => __( 'GDP Options', 'gusviradigital' ),
            'page_title'          => __( 'GDP Options', 'gusviradigital' ),
            'admin_bar'           => true,
            'admin_bar_icon'      => 'dashicons-admin-settings',
            'dev_mode'            => false,
            'update_notice'       => true,
            'customizer'          => false,
            'page_priority'       => null,
            'page_parent'         => 'themes.php',
            'page_permissions'    => 'manage_options',
            'menu_icon'           => '',
            'last_tab'            => '',
            'page_icon'           => 'dashicons-admin-settings',
            'page_slug'           => 'gdp_options',
            'save_defaults'       => true,
            'default_show'        => false,
            'default_mark'        => '',
            'show_import_export'  => true,
            'transient_time'      => 60 * MINUTE_IN_SECONDS,
            'output'              => true,
            'output_tag'          => true,
            'database'            => '',
            'use_cdn'             => true,
            'ajax_save'           => true,
            'hints'               => [
                'icon'          => 'el el-question-sign',
                'icon_position' => 'right',
                'icon_color'    => 'lightgray',
                'icon_size'     => 'normal',
                'tip_style'     => [
                    'color'   => 'light',
                ],
                'tip_position'  => [
                    'my' => 'top left',
                    'at' => 'bottom right',
                ],
                'tip_effect'    => [
                    'show' => [
                        'duration' => '500',
                        'event'    => 'mouseover',
                    ],
                    'hide' => [
                        'duration' => '500',
                        'event'    => 'mouseleave unfocus',
                    ],
                ],
            ],
        ];

        \Redux::set_args( 'gdp_options', $args );

        // Load option fields
        $this->load_option_fields();
    }

    /**
     * Load option fields
     */
    private function load_option_fields() {
        $field_files = [
            'general',        // General Settings
            'header',         // Header Settings
            'footer',         // Footer Settings
            'typography',     // Typography Settings
            'custom-code',    // Custom Code Settings
            'donation',       // Donation Settings
        ];

        foreach ( $field_files as $file ) {
            $field_file = GDP_INC . '/redux/fields/' . $file . '.php';
            if ( file_exists( $field_file ) ) {
                require_once $field_file;
            }
        }
    }
}

// Initialize Redux Config
Redux_Config::get_instance(); 