<?php
/**
 * Gusvira Digital Theme functions and definitions
 * functions and definitions not for added to file functions.php
 *
 * @package GusviraDigital
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Define theme constants
define('GDP_VERSION', '1.0.0');
define('GDP_DB_VERSION', '1.0.0');
define('GDP_DIR', get_template_directory());
define('GDP_URI', get_template_directory_uri());
define('GDP_ASSETS', GDP_URI . '/assets');
define('GDP_IMAGES', GDP_ASSETS . '/images');
define('GDP_CSS', GDP_ASSETS . '/css');
define('GDP_JS', GDP_ASSETS . '/js');
define('GDP_DIST', GDP_URI . '/dist');
define('GDP_DIST_CSS', GDP_DIST . '/css');
define('GDP_DIST_JS', GDP_DIST . '/js');
define('GDP_INC', GDP_DIR . '/inc');
define('GDP_CORE', GDP_INC . '/core');
define('GDP_CLASSES', GDP_INC . '/classes');
define('GDP_TRAITS', GDP_INC . '/traits');
define('GDP_INTERFACES', GDP_INC . '/interfaces');

// Load core theme files
require_once GDP_INC . '/class-autoloader.php';

/**
 * Load Composer autoload if exists
 */
if (file_exists(get_template_directory() . '/inc/libraries/autoload.php')) {
    require_once get_template_directory() . '/inc/libraries/autoload.php';
}

// Initialize theme
function gdp_init() {
    // Initialize autoloader first
    $autoloader = GDP\Autoloader::get_instance();
    
    return $autoloader;
}

function gdp_options($key = '', $default = '') {
    global $gdp_options;
    
    if (!isset($gdp_options) || empty($gdp_options)) {
        $gdp_options = get_option('gdp_options');
        
        // Ensure maintenance mode is properly loaded
        if ($key === 'maintenance_mode' && empty($gdp_options[$key])) {
            return get_option('gdp_options')['maintenance_mode'] ?? $default;
        }
    }
    
    if (!empty($key)) {
        return isset($gdp_options[$key]) ? $gdp_options[$key] : $default;
    }
    
    return $gdp_options;
}

gdp_init();