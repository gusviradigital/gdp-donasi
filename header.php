<?php
/**
 * The header for our theme
 *
 * @package GusviraDigital
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$header = \GDP\Core\Header::get_instance();
$theme_options = \GDP\Core\Theme_Options::get_instance();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php echo $theme_options->is_dark_mode() ? 'dark' : ''; ?>">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <?php if ($header->get_top_bar_content()): ?>
    <div class="header-top-bar bg-gray-100 dark:bg-gray-800 py-2">
        <div class="container mx-auto px-4">
            <div class="text-sm text-gray-600 dark:text-gray-300">
                <?php echo wp_kses_post($header->get_top_bar_content()); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <header class="<?php echo esc_attr($header->get_header_classes()); ?> relative">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="block">
                        <img src="<?php echo esc_url($theme_options->get_logo_url()); ?>" 
                             alt="<?php echo esc_attr(get_bloginfo('name')); ?>"
                             class="h-12 w-auto">
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden lg:flex items-center space-x-8">
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'primary',
                        'container' => false,
                        'menu_class' => 'flex space-x-8',
                        'items_wrap' => '<ul class="%2$s">%3$s</ul>',
                        'fallback_cb' => false,
                        'walker' => new \GDP\Navigation\Menu_Walker(),
                    ]);
                    ?>

                    <?php if ($header->get_cta_button()): ?>
                        <div class="ml-8">
                            <?php echo $header->get_cta_button(); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (gdp_options('header_search', true)): ?>
                        <button type="button" class="text-gray-600 hover:text-primary-500 dark:text-gray-300 dark:hover:text-primary-400">
                            <span class="sr-only"><?php esc_html_e('Search', 'gusviradigital'); ?></span>
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    <?php endif; ?>
                </nav>

                <!-- Mobile Navigation Button -->
                <div class="lg:hidden">
                    <button type="button" class="mobile-menu-button inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-primary-500 dark:text-gray-300 dark:hover:text-primary-400">
                        <span class="sr-only"><?php esc_html_e('Open menu', 'gusviradigital'); ?></span>
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div class="mobile-menu hidden lg:hidden">
            <div class="container mx-auto px-4 py-4">
                <?php
                wp_nav_menu([
                    'theme_location' => 'mobile',
                    'container' => false,
                    'menu_class' => 'space-y-4',
                    'fallback_cb' => false,
                    'walker' => new \GDP\Navigation\Menu_Walker(),
                ]);
                ?>

                <?php if ($header->get_cta_button()): ?>
                    <div class="mt-6">
                        <?php echo $header->get_cta_button(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div id="content" class="site-content">