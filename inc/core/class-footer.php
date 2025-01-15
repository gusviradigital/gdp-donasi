<?php
/**
 * Footer Class
 *
 * @package GusviraDigital
 * @version 1.0.0
 */

namespace GDP\Core;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Footer Class
 */
class Footer {
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
        add_action('wp_footer', [$this, 'render_footer']);
    }

    /**
     * Get footer top section
     */
    public function get_footer_top() {
        if (!gdp_options('enable_footer_top', true)) {
            return;
        }

        $background = gdp_options('footer_top_background', ['background-color' => '#f8f9fa']);
        $padding = gdp_options('footer_top_padding', [
            'padding-top' => '60px',
            'padding-right' => '0px',
            'padding-bottom' => '60px',
            'padding-left' => '0px'
        ]);

        $style = sprintf(
            'background-color: %s; padding: %s %s %s %s;',
            esc_attr($background['background-color']),
            esc_attr($padding['padding-top']),
            esc_attr($padding['padding-right']),
            esc_attr($padding['padding-bottom']),
            esc_attr($padding['padding-left'])
        );

        ob_start();
        ?>
        <div class="footer-top" style="<?php echo esc_attr($style); ?>">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <?php do_action('gdp_footer_top'); ?>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Get footer widgets section
     */
    public function get_footer_widgets() {
        if (!gdp_options('footer_widgets', true)) {
            return;
        }

        $columns = gdp_options('footer_columns', '4');
        $background = gdp_options('footer_widgets_background', ['background-color' => '#1a1a1a']);
        $padding = gdp_options('footer_widgets_padding', [
            'padding-top' => '80px',
            'padding-right' => '0px',
            'padding-bottom' => '50px',
            'padding-left' => '0px'
        ]);

        $style = sprintf(
            'background-color: %s; padding: %s %s %s %s;',
            esc_attr($background['background-color']),
            esc_attr($padding['padding-top']),
            esc_attr($padding['padding-right']),
            esc_attr($padding['padding-bottom']),
            esc_attr($padding['padding-left'])
        );

        $grid_class = 'grid-cols-1';
        switch ($columns) {
            case '2':
                $grid_class = 'md:grid-cols-2';
                break;
            case '3':
                $grid_class = 'md:grid-cols-3';
                break;
            case '4':
                $grid_class = 'md:grid-cols-4';
                break;
        }

        ob_start();
        ?>
        <div class="footer-widgets" style="<?php echo esc_attr($style); ?>">
            <div class="container mx-auto px-4">
                <div class="grid <?php echo esc_attr($grid_class); ?> gap-8">
                    <?php 
                    for ($i = 1; $i <= $columns; $i++) {
                        echo '<div class="footer-widget">';
                        dynamic_sidebar('footer-' . $i);
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Get social media icons
     */
    public function get_social_media() {
        $social_media = gdp_options('footer_social_media', []);
        if (empty($social_media)) {
            return;
        }

        $icons = [
            'facebook' => 'fab fa-facebook',
            'twitter' => 'fab fa-twitter',
            'instagram' => 'fab fa-instagram',
            'youtube' => 'fab fa-youtube',
            'linkedin' => 'fab fa-linkedin',
            'tiktok' => 'fab fa-tiktok'
        ];

        ob_start();
        ?>
        <div class="social-media flex gap-4">
            <?php foreach ($social_media as $platform => $enabled) : 
                if (!$enabled) continue;
                $url = gdp_options($platform . '_url', '');
                if (empty($url)) continue;
            ?>
                <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener noreferrer" 
                   class="text-white hover:text-gray-300 transition-colors">
                    <i class="<?php echo esc_attr($icons[$platform]); ?>"></i>
                </a>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Get footer bottom section
     */
    public function get_footer_bottom() {
        $copyright = gdp_options('footer_copyright', '© ' . date('Y') . ' GDP Donasi. All rights reserved.');
        $background = gdp_options('footer_bottom_background', ['background-color' => '#111111']);
        $padding = gdp_options('footer_bottom_padding', [
            'padding-top' => '20px',
            'padding-right' => '0px',
            'padding-bottom' => '20px',
            'padding-left' => '0px'
        ]);

        $style = sprintf(
            'background-color: %s; padding: %s %s %s %s;',
            esc_attr($background['background-color']),
            esc_attr($padding['padding-top']),
            esc_attr($padding['padding-right']),
            esc_attr($padding['padding-bottom']),
            esc_attr($padding['padding-left'])
        );

        ob_start();
        ?>
        <div class="footer-bottom" style="<?php echo esc_attr($style); ?>">
            <div class="container mx-auto px-4">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="copyright text-white">
                        <?php echo wp_kses_post($copyright); ?>
                    </div>
                    <?php echo $this->get_social_media(); ?>
                    <?php if (has_nav_menu('footer')) : ?>
                        <nav class="footer-menu">
                            <?php
                            wp_nav_menu([
                                'theme_location' => 'footer',
                                'menu_class' => 'flex gap-6 text-white',
                                'container' => false,
                                'depth' => 1,
                            ]);
                            ?>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render footer
     */
    public function render_footer() {
        // Add custom styles
        $text_color = gdp_options('footer_text_color', '#ffffff');
        $heading_color = gdp_options('footer_heading_color', '#ffffff');
        $link_colors = gdp_options('footer_link_color', [
            'regular' => '#ffffff',
            'hover' => '#cccccc',
            'active' => '#cccccc'
        ]);

        $custom_css = "
            .footer-widgets {
                color: {$text_color};
            }
            .footer-widgets h2,
            .footer-widgets h3,
            .footer-widgets h4 {
                color: {$heading_color};
            }
            .footer-widgets a {
                color: {$link_colors['regular']};
            }
            .footer-widgets a:hover {
                color: {$link_colors['hover']};
            }
            .footer-widgets a:active {
                color: {$link_colors['active']};
            }
        ";
        wp_add_inline_style('gusviradigital-style', $custom_css);

        // Render footer sections
        echo '<footer class="site-footer">';
        echo $this->get_footer_top();
        echo $this->get_footer_widgets();
        echo $this->get_footer_bottom();
        echo '</footer>';
    }
} 