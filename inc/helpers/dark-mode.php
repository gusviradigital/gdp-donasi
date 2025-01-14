<?php
/**
 * Dark Mode Helper Functions
 *
 * @package GusviraDigital
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Check if dark mode is enabled
 */
function gdp_is_dark_mode_enabled() {
    return gdp_options('enable_dark_mode', true);
}

/**
 * Get current theme mode
 */
function gdp_get_theme_mode() {
    if (!gdp_is_dark_mode_enabled()) {
        return 'light';
    }
    return gdp_options('default_theme_mode', 'light');
}

/**
 * Add dark mode class to body
 */
function gdp_dark_mode_body_class($classes) {
    if (gdp_is_dark_mode_enabled()) {
        $mode = gdp_get_theme_mode();
        if ($mode === 'dark') {
            $classes[] = 'dark-mode';
        } elseif ($mode === 'auto') {
            $classes[] = 'auto-dark-mode';
        }
    }
    return $classes;
}
add_filter('body_class', 'gdp_dark_mode_body_class');

/**
 * Add dark mode switcher script
 */
function gdp_dark_mode_switcher() {
    if (!gdp_is_dark_mode_enabled() || !gdp_options('show_mode_switcher', true)) {
        return;
    }

    $mode = gdp_get_theme_mode();
    ?>
    <script>
    (function() {
        var darkMode = {
            init: function() {
                this.mode = '<?php echo esc_js($mode); ?>';
                this.createSwitcher();
                this.setupEventListeners();
                this.checkSystemPreference();
            },

            createSwitcher: function() {
                var switcher = document.createElement('button');
                switcher.id = 'dark-mode-switcher';
                switcher.innerHTML = '<i class="fas fa-' + (this.isDarkMode() ? 'sun' : 'moon') + '"></i>';
                switcher.style.cssText = 'position:fixed;top:20px;right:20px;z-index:99;width:40px;height:40px;border-radius:50%;background:#0088cc;color:#fff;border:none;cursor:pointer;';
                document.body.appendChild(switcher);
            },

            setupEventListeners: function() {
                var self = this;
                document.getElementById('dark-mode-switcher').addEventListener('click', function() {
                    self.toggleMode();
                });

                if (this.mode === 'auto') {
                    window.matchMedia('(prefers-color-scheme: dark)').addListener(function(e) {
                        self.updateMode(e.matches ? 'dark' : 'light');
                    });
                }
            },

            checkSystemPreference: function() {
                if (this.mode === 'auto') {
                    this.updateMode(window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                }
            },

            isDarkMode: function() {
                return document.body.classList.contains('dark-mode');
            },

            toggleMode: function() {
                this.updateMode(this.isDarkMode() ? 'light' : 'dark');
            },

            updateMode: function(mode) {
                document.body.classList.toggle('dark-mode', mode === 'dark');
                var icon = document.querySelector('#dark-mode-switcher i');
                if (icon) {
                    icon.className = 'fas fa-' + (mode === 'dark' ? 'sun' : 'moon');
                }
                this.updateLogos(mode);
            },

            updateLogos: function(mode) {
                var logos = document.querySelectorAll('.site-logo img');
                logos.forEach(function(logo) {
                    var darkLogo = logo.getAttribute('data-dark-src');
                    var lightLogo = logo.getAttribute('data-light-src');
                    if (darkLogo && lightLogo) {
                        logo.src = mode === 'dark' ? darkLogo : lightLogo;
                    }
                });
            }
        };

        window.addEventListener('load', function() {
            darkMode.init();
        });
    })();
    </script>
    <?php
}
add_action('wp_footer', 'gdp_dark_mode_switcher'); 