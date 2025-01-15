<?php
/**
 * Header Class
 *
 * @package GusviraDigital
 * @version 1.0.0
 */

namespace GDP\Core;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Header Class
 */
class Header {
    /**
     * Instance of this class
     *
     * @var object
     */
    private static $instance = null;

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
        add_action('wp_head', [$this, 'add_header_styles']);
        add_action('wp_footer', [$this, 'add_header_scripts']);
    }

    /**
     * Get header style
     */
    public function get_header_style() {
        return gdp_options('header_style', 'style1');
    }

    /**
     * Check if header is sticky
     */
    public function is_sticky() {
        return gdp_options('header_sticky', true);
    }

    /**
     * Check if header is transparent
     */
    public function is_transparent() {
        return gdp_options('header_transparent', false);
    }

    /**
     * Get header classes
     */
    public function get_header_classes() {
        $classes = ['site-header', 'gdp-header'];
        
        // Add header style class
        $classes[] = 'header-' . $this->get_header_style();
        
        // Add sticky class
        if ($this->is_sticky()) {
            $classes[] = 'header-sticky';
        }
        
        // Add transparent class
        if ($this->is_transparent() && is_front_page()) {
            $classes[] = 'header-transparent';
        }

        return implode(' ', $classes);
    }

    /**
     * Get top bar content
     */
    public function get_top_bar_content() {
        if (!gdp_options('enable_top_bar', false)) {
            return '';
        }

        return do_shortcode(gdp_options('top_bar_content', ''));
    }

    /**
     * Get CTA button HTML
     */
    public function get_cta_button() {
        if (!gdp_options('header_cta_button', true)) {
            return '';
        }

        $text = gdp_options('header_cta_text', 'Donasi Sekarang');
        $url = gdp_options('header_cta_url', '#');
        $style = gdp_options('header_cta_style', 'primary');
        
        $classes = ['header-cta-button', 'btn'];
        if ($style === 'custom') {
            $classes[] = 'btn-custom';
        } else {
            $classes[] = 'btn-' . $style;
        }

        return sprintf(
            '<a href="%s" class="%s">%s</a>',
            esc_url($url),
            esc_attr(implode(' ', $classes)),
            esc_html($text)
        );
    }

    /**
     * Add header styles
     */
    public function add_header_styles() {
        $styles = [];
        
        // Header styles
        $styles[] = '.site-header {';
        $styles[] = sprintf('background-color: %s;', gdp_options('header_bg_color', '#ffffff'));
        $styles[] = sprintf('color: %s;', gdp_options('header_text_color', '#333333'));
        
        // Padding
        $padding = gdp_options('header_padding', []);
        if (!empty($padding)) {
            foreach ($padding as $key => $value) {
                if (!empty($value)) {
                    $styles[] = sprintf('%s: %s;', $key, $value);
                }
            }
        }
        
        // Border
        $border = gdp_options('header_border', []);
        if (!empty($border)) {
            $styles[] = sprintf('border-style: %s;', $border['border-style']);
            $styles[] = sprintf('border-color: %s;', $border['border-color']);
            foreach (['top', 'right', 'bottom', 'left'] as $side) {
                if (isset($border['border-' . $side])) {
                    $styles[] = sprintf('border-%s-width: %spx;', $side, $border['border-' . $side]);
                }
            }
        }
        $styles[] = '}';

        // Menu link colors
        $link_colors = gdp_options('header_link_color', []);
        if (!empty($link_colors)) {
            $styles[] = '.site-header .menu-item a {';
            $styles[] = sprintf('color: %s;', $link_colors['regular']);
            $styles[] = '}';
            
            $styles[] = '.site-header .menu-item a:hover {';
            $styles[] = sprintf('color: %s;', $link_colors['hover']);
            $styles[] = '}';
            
            $styles[] = '.site-header .menu-item.current-menu-item a {';
            $styles[] = sprintf('color: %s;', $link_colors['active']);
            $styles[] = '}';
        }

        // Custom CTA button styles
        if (gdp_options('header_cta_style', 'primary') === 'custom') {
            $custom_color = gdp_options('header_cta_custom_color', '#ff5722');
            $styles[] = '.header-cta-button.btn-custom {';
            $styles[] = sprintf('background-color: %s;', $custom_color);
            $styles[] = sprintf('border-color: %s;', $custom_color);
            $styles[] = 'color: #ffffff;';
            $styles[] = '}';
        }

        // Top bar styles
        if (gdp_options('enable_top_bar', false)) {
            $styles[] = '.header-top-bar {';
            $styles[] = sprintf('background-color: %s;', gdp_options('top_bar_bg_color', '#f8f9fa'));
            $styles[] = sprintf('color: %s;', gdp_options('top_bar_text_color', '#6c757d'));
            $styles[] = '}';
        }

        printf('<style type="text/css">%s</style>', implode("\n", $styles));
    }

    /**
     * Add header scripts
     */
    public function add_header_scripts() {
        if ($this->is_sticky()) {
            ?>
            <script>
            (function($) {
                'use strict';
                
                $(window).on('scroll', function() {
                    var header = $('.header-sticky');
                    if ($(window).scrollTop() > 100) {
                        header.addClass('is-sticky');
                    } else {
                        header.removeClass('is-sticky');
                    }
                });
            })(jQuery);
            </script>
            <?php
        }
    }
} 