<?php
/**
 * Theme Setup Class
 *
 * @package GusviraDigital
 * @version 1.0.0
 */

namespace GDP;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

use GDP\Core\Theme_Options;

/**
 * Theme Setup Class
 */
class Theme_Setup {
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
        add_action( 'after_setup_theme', [ $this, 'setup' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        add_action( 'init', [ $this, 'register_menus' ] );
        add_action( 'widgets_init', [ $this, 'sidebars' ] );
        add_action( 'wp_body_open', [ $this, 'add_preloader' ] );
    }

    /**
     * Sets up theme defaults and registers support for various WordPress features.
     */
    public function setup() {
        // Add default posts and comments RSS feed links to head.
        add_theme_support( 'automatic-feed-links' );

        // Let WordPress manage the document title.
        add_theme_support( 'title-tag' );

        // Enable support for Post Thumbnails on posts and pages.
        add_theme_support( 'post-thumbnails' );

        // Add support for responsive embeds.
        add_theme_support( 'responsive-embeds' );

        // Add support for editor styles.
        add_theme_support( 'editor-styles' );

        // Add Support Language
        load_theme_textdomain( 'gusviradigital', get_template_directory() . '/languages' );

        // Add support for HTML5.
        add_theme_support(
            'html5',
            [
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'style',
                'script',
            ]
        );

        // Add support for custom logo
        add_theme_support( 'custom-logo', [
            'height'      => 60,
            'width'       => 200,
            'flex-height' => true,
            'flex-width'  => true,
        ] );
    }

    /**
     * Register navigation menus
     */
    public function register_menus() {
        register_nav_menus(
            [
                'primary' => esc_html__( 'Primary Menu', 'gusviradigital' ),
                'mobile' => esc_html__( 'Mobile Menu', 'gusviradigital' ),
                'footer'  => esc_html__( 'Footer Menu', 'gusviradigital' ),
            ]
        );
    }

    /**
     * Register sidebars
     */
    public function sidebars() {
        register_sidebar( [
            'name'          => esc_html__( 'Sidebar', 'gusviradigital' ),
            'id'            => 'sidebar-1',
            'description'   => esc_html__( 'Add widgets here.', 'gusviradigital' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ] );
    }

    /**
     * Add preloader to website
     */
    public function add_preloader() {
        if (class_exists('GDP\Core\Theme_Options')) {
            $theme_options = Theme_Options::get_instance();
            echo $theme_options->get_preloader_html();
        }
    }

    /**
     * Enqueue scripts and styles.
     */
    public function enqueue_scripts() {
        // Enqueue styles.
        wp_enqueue_style( 'gusviradigital-style', GDP_DIST_CSS . '/app.css', [], GDP_VERSION );
        wp_enqueue_style('gdp-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css', [], GDP_VERSION);
 
        // Enqueue scripts.
        wp_enqueue_script( 'gusviradigital-script', GDP_DIST_JS . '/app.js', [], GDP_VERSION, true );
        wp_enqueue_script('tailwind', 'https://cdn.tailwindcss.com', [], GDP_VERSION, true);

        // Add custom styles
        if (class_exists('GDP\Core\Theme_Options')) {
            $theme_options = Theme_Options::get_instance();
            add_action('wp_head', [$theme_options, 'add_custom_styles']);
            add_action('wp_footer', [$theme_options, 'add_custom_scripts']);
        }
    }
}

// Initialize theme setup
Theme_Setup::get_instance(); 