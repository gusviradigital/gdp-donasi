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
            $classes[] = 'dark-mode dark:bg-gray-900';
            $classes[] = 'dark';
        } elseif ($mode === 'auto') {
            $classes[] = 'auto-dark-mode dark:bg-gray-900';
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
    // Inisialisasi awal dark mode sebelum DOM ready
    (function() {
        try {
            var savedMode = localStorage.getItem('gdp-theme-mode');
            var defaultMode = '<?php echo esc_js($mode); ?>';
            var isDark = false;

            if (defaultMode === 'auto') {
                isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            } else if (savedMode) {
                isDark = savedMode === 'dark';
            } else {
                isDark = defaultMode === 'dark';
            }

            if (isDark) {
                document.documentElement.classList.add('dark');
                document.documentElement.classList.add('dark-mode');
            }
        } catch (e) {
            console.log('Dark mode initialization error:', e);
        }
    })();

    // Dark mode functionality setelah DOM ready
    document.addEventListener('DOMContentLoaded', function() {
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
                switcher.className = 'gdp-dark-mode-switcher';
                switcher.innerHTML = '<i class="fas fa-' + (this.isDarkMode() ? 'sun' : 'moon') + '"></i>';
                document.body.appendChild(switcher);
            },

            setupEventListeners: function() {
                var self = this;
                var switcher = document.getElementById('dark-mode-switcher');
                if (switcher) {
                    switcher.addEventListener('click', function() {
                        self.toggleMode();
                    });
                }

                // Listen for system color scheme changes
                var mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
                if (mediaQuery.addEventListener) {
                    mediaQuery.addEventListener('change', function(e) {
                        if (self.mode === 'auto') {
                            self.updateMode(e.matches ? 'dark' : 'light');
                        }
                    });
                } else {
                    // Fallback for older browsers
                    mediaQuery.addListener(function(e) {
                        if (self.mode === 'auto') {
                            self.updateMode(e.matches ? 'dark' : 'light');
                        }
                    });
                }

                // Store user preference
                window.addEventListener('beforeunload', function() {
                    if (self.mode !== 'auto') {
                        localStorage.setItem('gdp-theme-mode', self.isDarkMode() ? 'dark' : 'light');
                    }
                });
            },

            checkSystemPreference: function() {
                var savedMode = localStorage.getItem('gdp-theme-mode');
                
                if (this.mode === 'auto') {
                    this.updateMode(window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                } else if (savedMode) {
                    this.updateMode(savedMode);
                }
            },

            isDarkMode: function() {
                var html = document.documentElement;
                var body = document.body;
                return (html && html.classList.contains('dark')) || 
                       (body && body.classList.contains('dark-mode'));
            },

            toggleMode: function() {
                this.updateMode(this.isDarkMode() ? 'light' : 'dark');
            },

            updateMode: function(mode) {
                var html = document.documentElement;
                var body = document.body;

                if (html) {
                    html.classList.toggle('dark', mode === 'dark');
                    html.classList.toggle('dark-mode', mode === 'dark');
                }
                if (body) {
                    body.classList.toggle('dark-mode', mode === 'dark');
                }
                
                var icon = document.querySelector('#dark-mode-switcher i');
                if (icon) {
                    icon.className = 'fas fa-' + (mode === 'dark' ? 'sun' : 'moon');
                }
                
                this.updateLogos(mode);
                this.updateColors(mode);
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
            },

            updateColors: function(mode) {
                // Update preloader colors if exists
                var preloader = document.getElementById('gdp-preloader');
                if (preloader) {
                    preloader.style.backgroundColor = mode === 'dark' ? '#1a1a1a' : '#ffffff';
                }

                // Update other elements that need color changes
                var elements = document.querySelectorAll('[data-dark-color]');
                elements.forEach(function(el) {
                    var darkColor = el.getAttribute('data-dark-color');
                    var lightColor = el.getAttribute('data-light-color');
                    if (darkColor && lightColor) {
                        el.style.color = mode === 'dark' ? darkColor : lightColor;
                    }
                });
            }
        };

        darkMode.init();
    });
    </script>
    <style>
    .gdp-dark-mode-switcher {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 99;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--primary-color, #0088cc);
        color: #fff;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    .gdp-dark-mode-switcher:hover {
        transform: scale(1.1);
        box-shadow: 0 3px 8px rgba(0,0,0,0.3);
    }
    .dark-mode .gdp-dark-mode-switcher {
        background: var(--primary-color-dark, #006699);
    }
    </style>
    <?php
}
add_action('wp_footer', 'gdp_dark_mode_switcher'); 