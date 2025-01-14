<?php
/**
 * Theme Options Class
 *
 * @package GusviraDigital
 * @version 1.0.0
 */

namespace GDP\Core;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Theme Options Class
 */
class Theme_Options {
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
    private function __construct() {
        add_action('wp_head', [$this, 'add_custom_styles']);
        add_action('wp_footer', [$this, 'add_custom_scripts']);
    }

    /**
     * Get logo URL based on current mode
     */
    public function get_logo_url() {
        $is_dark_mode = $this->is_dark_mode();
        if ($is_dark_mode) {
            return gdp_options('site_logo_dark')['url'] ?? GDP_IMAGES . '/logo_gdp_dark.png';
        }
        return gdp_options('site_logo')['url'] ?? GDP_IMAGES . '/logo-gdp-white.png';
    }

    /**
     * Check if dark mode is active
     */
    public function is_dark_mode() {
        if (!gdp_options('enable_dark_mode', true)) {
            return false;
        }

        $default_mode = gdp_options('default_theme_mode', 'light');
        if ($default_mode === 'auto') {
            // Implement system preference detection via JavaScript
            return false;
        }

        return $default_mode === 'dark';
    }

    /**
     * Get preloader HTML
     */
    public function get_preloader_html() {
        if (!gdp_options('preloader', true)) {
            return '';
        }

        $style = gdp_options('preloader_style', 'logo');
        $bg_color = gdp_options('preloader_background', '#ffffff');
        $color = gdp_options('preloader_color', '#0088cc');

        $html = '<div id="gdp-preloader" style="background-color: ' . esc_attr($bg_color) . ';">';
        
        switch ($style) {
            case 'logo':
                $logo = gdp_options('preloader_logo')['url'] ?? GDP_IMAGES . '/logo-gdp-white.png';
                $html .= '<img src="' . esc_url($logo) . '" alt="' . esc_attr(get_bloginfo('name')) . '">';
                break;
            
            case 'spinner':
                $html .= '<div class="gdp-spinner" style="border-color: ' . esc_attr($color) . '"></div>';
                break;
            
            case 'progress-bar':
                $html .= '<div class="gdp-progress-bar"><div style="background-color: ' . esc_attr($color) . '"></div></div>';
                break;
            
            case 'dots':
                $html .= '<div class="gdp-dots"><div style="background-color: ' . esc_attr($color) . '"></div><div style="background-color: ' . esc_attr($color) . '"></div><div style="background-color: ' . esc_attr($color) . '"></div></div>';
                break;
            
            case 'custom':
                $html .= gdp_options('preloader_custom', '');
                break;
        }

        $html .= '</div>';
        return $html;
    }

    /**
     * Add custom styles to header
     */
    public function add_custom_styles() {
        $styles = '<style id="gdp-custom-styles">';
        
        // Container width
        $container_width = gdp_options('container_width', 1320);
        $styles .= ".container { max-width: {$container_width}px; }";

        // Logo height
        $logo_height = gdp_options('logo_height', ['height' => '50', 'units' => 'px']);
        $styles .= ".site-logo img { height: {$logo_height['height']}{$logo_height['units']}; }";

        // Preloader styles
        if (gdp_options('preloader', true)) {
            $styles .= $this->get_preloader_styles();
        }

        $styles .= '</style>';
        echo $styles;
    }

    /**
     * Get preloader styles
     */
    private function get_preloader_styles() {
        $style = gdp_options('preloader_style', 'logo');
        $duration = gdp_options('preloader_duration', 1000);
        
        $styles = "
            #gdp-preloader {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: opacity 0.3s;
            }
        ";

        switch ($style) {
            case 'spinner':
                $styles .= "
                    .gdp-spinner {
                        width: 40px;
                        height: 40px;
                        border: 4px solid rgba(0,0,0,0.1);
                        border-left-color: currentColor;
                        border-radius: 50%;
                        animation: gdp-spin 1s linear infinite;
                    }
                    @keyframes gdp-spin {
                        to { transform: rotate(360deg); }
                    }
                ";
                break;
            
            case 'progress-bar':
                $styles .= "
                    .gdp-progress-bar {
                        width: 200px;
                        height: 4px;
                        background: rgba(0,0,0,0.1);
                        overflow: hidden;
                    }
                    .gdp-progress-bar div {
                        width: 100%;
                        height: 100%;
                        animation: gdp-progress {$duration}ms linear;
                        transform-origin: 0 50%;
                    }
                    @keyframes gdp-progress {
                        from { transform: scaleX(0); }
                        to { transform: scaleX(1); }
                    }
                ";
                break;
            
            case 'dots':
                $styles .= "
                    .gdp-dots {
                        display: flex;
                        gap: 8px;
                    }
                    .gdp-dots div {
                        width: 10px;
                        height: 10px;
                        border-radius: 50%;
                        animation: gdp-bounce 0.5s alternate infinite;
                    }
                    .gdp-dots div:nth-child(2) { animation-delay: 0.2s; }
                    .gdp-dots div:nth-child(3) { animation-delay: 0.4s; }
                    @keyframes gdp-bounce {
                        to { transform: translateY(-10px); }
                    }
                ";
                break;
        }

        return $styles;
    }

    /**
     * Add custom scripts to footer
     */
    public function add_custom_scripts() {
        // Preloader
        if (gdp_options('preloader', true)) {
            $duration = gdp_options('preloader_duration', 1000);
            ?>
            <script>
            (function() {
                var preloader = document.getElementById('gdp-preloader');
                if (preloader) {
                    window.addEventListener('load', function() {
                        setTimeout(function() {
                            preloader.style.opacity = '0';
                            setTimeout(function() {
                                preloader.remove();
                            }, 300);
                        }, <?php echo intval($duration); ?>);
                    });
                }
            })();
            </script>
            <?php
        }

        // Smooth Scroll
        if (gdp_options('smooth_scroll', true)) {
            ?>
            <script>
            document.documentElement.style.scrollBehavior = 'smooth';
            </script>
            <?php
        }

        // Back to Top
        if (gdp_options('back_to_top', true)) {
            ?>
            <script>
            (function() {
                var button = document.createElement('button');
                button.id = 'back-to-top';
                button.innerHTML = '<i class="fas fa-arrow-up"></i>';
                button.style.cssText = 'position:fixed;bottom:20px;right:20px;z-index:99;display:none;width:40px;height:40px;border-radius:50%;background:#0088cc;color:#fff;border:none;cursor:pointer;';
                document.body.appendChild(button);

                window.addEventListener('scroll', function() {
                    button.style.display = window.pageYOffset > 100 ? 'block' : 'none';
                });

                button.addEventListener('click', function() {
                    window.scrollTo({top: 0, behavior: 'smooth'});
                });
            })();
            </script>
            <?php
        }
    }
} 