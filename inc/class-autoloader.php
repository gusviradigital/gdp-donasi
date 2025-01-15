<?php
/**
 * Autoloader class for Gusvira Digital Theme
 *
 * @package GusviraDigital
 * @version 1.0.0
 */

namespace GDP;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Class Autoload
 */
class Autoloader {
    /**
     * Instance of this class
     *
     * @var object
     */
    private static $instance = null;

    /**
     * Stores all of the classes that need to be autoloaded.
     *
     * @var array
     */
    private static $classes = array();

    /**
     * Get instance of this class
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
    private function __construct() {
        if (function_exists('__autoload')) {
            spl_autoload_register('__autoload');
        }

        spl_autoload_register(array($this, 'autoload'));

        $this->register_classes();
        $this->check_dependencies();
        $this->load_helpers();

        $this->gdp_init_classes();
    }

    /**
     * Check required dependencies
     */
    private function check_dependencies() {
        add_action('admin_notices', function() {
            if (!class_exists('Redux')) {
                ?>
                <div class="notice notice-error">
                    <p>
                        <?php 
                        printf(
                            /* translators: %1$s: Theme name, %2$s: Link to Redux Framework */
                            esc_html__('%1$s theme requires %2$s plugin to be installed and activated.', 'gdp'),
                            '<strong>GDP Donasi</strong>',
                            '<a href="https://wordpress.org/plugins/redux-framework/" target="_blank">Redux Framework</a>'
                        );
                        ?>
                    </p>
                </div>
                <?php
            }
        });

        // Initialize Redux if available
        if (class_exists('Redux')) {
            require_once GDP_INC . '/redux/config.php';
        }
    }

    /**
     * Register classes map
     */
    private function register_classes() {
        self::$classes = array(
            'GDP\\Theme_Setup' => GDP_INC . '/class-theme-setup.php',
            'GDP\\Core\\Theme_Options' => GDP_INC . '/core/class-theme-options.php',
            'GDP\\Core\\Header' => GDP_INC . '/core/class-header.php',
            'GDP\\Core\\Footer' => GDP_INC . '/core/class-footer.php',
            'GDP\\Core\\Layout_Front' => GDP_INC . '/core/class-layout-front.php',
            'GDP\\Navigation\\Menu_Walker' => GDP_INC . '/navigation/class-menu-walker.php',
            'GDP\\Post_Types\\Program' => GDP_INC . '/post-types/class-program.php',
            'GDP\\Metabox\\Program_Meta' => GDP_INC . '/metabox/class-program-meta.php',
            'GDP\\Classes\\Donation' => GDP_INC . '/classes/class-donation.php',
            'GDP\\Classes\\Donation_History' => GDP_INC . '/classes/class-donation-history.php',
            'GDP\\Classes\\Payment' => GDP_INC . '/classes/class-payment.php',
            'GDP\\Classes\\Notification' => GDP_INC . '/classes/class-notification.php',
        );
    }

    /**
     * Load helper files
     */
    private function load_helpers() {
        $helper_files = [
            GDP_INC . '/helpers/dark-mode.php',
        ];

        foreach ( $helper_files as $file ) {
            if ( file_exists( $file ) ) {
                require_once $file;
            }
        }
    }

    /**
     * Initialize classes
     */
    public function gdp_init_classes() {
        \GDP\Theme_Setup::get_instance();
        \GDP\Core\Theme_Options::get_instance();
        \GDP\Core\Header::get_instance();
        \GDP\Core\Footer::get_instance();
        \GDP\Core\Layout_Front::get_instance();
        \GDP\Post_Types\Program::get_instance();
        \GDP\Metabox\Program_Meta::get_instance();
        \GDP\Classes\Donation::get_instance();
        \GDP\Classes\Donation_History::get_instance();
        \GDP\Classes\Payment::get_instance();
        \GDP\Classes\Notification::get_instance();
    }

    /**
     * Autoload classes
     *
     * @param string $class The class name to autoload.
     * @return bool
     */
    public function autoload($class) {
        if (isset(self::$classes[$class])) {
            require_once self::$classes[$class];
            return true;
        }

        // Try to autoload classes that might not be in the map
        $class_path = $this->get_class_path($class);
        if ($class_path && file_exists($class_path)) {
            require_once $class_path;
            return true;
        }

        return false;
    }

    /**
     * Get the path for a class
     *
     * @param string $class The class name.
     * @return string|bool
     */
    private function get_class_path($class) {
        // Convert namespace separators to directory separators
        $class_path = str_replace('\\', '/', $class);
        
        // Remove GDP namespace
        $class_path = str_replace('GDP/', '', $class_path);
        
        // Convert to lowercase and add .php extension
        $file = GDP_INC . '/' . strtolower($class_path) . '.php';
        
        return file_exists($file) ? $file : false;
    }
}

// Initialize Autoloader
Autoloader::get_instance();