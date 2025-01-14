<?php
Redux::setSection( 'gdp_options', array(
    'title' => esc_html__( 'General', 'gusviradigital' ),
    'id' => 'general',
    'icon' => 'el el-home',
    'fields' => array(
        array(
            'id' => 'site_logo',
            'type' => 'media',
            'title' => esc_html__('Logo Website (Light)', 'gusviradigital'),
            'subtitle' => esc_html__('Upload logo website untuk mode terang (PNG, JPG)', 'gusviradigital'),
            'default' => array(
                'url' => GDP_IMAGES . '/logo-gdp-white.png'
            )
        ),
        array(
            'id' => 'site_logo_dark',
            'type' => 'media',
            'title' => esc_html__('Logo Website (Dark)', 'gusviradigital'),
            'subtitle' => esc_html__('Upload logo website untuk mode gelap (PNG, JPG)', 'gusviradigital'),
            'default' => array(
                'url' => GDP_IMAGES . '/logo_gdp_dark.png'
            )
        ),
        array(
            'id' => 'site_favicon',
            'type' => 'media',
            'title' => esc_html__('Favicon', 'gusviradigital'),
            'subtitle' => esc_html__('Upload favicon website Anda (ICO, PNG)', 'gusviradigital'),
            'default' => array(
                'url' => GDP_IMAGES . '/icon_gdp.png'
            )
        ),
        array(
            'id' => 'logo_height',
            'type' => 'dimensions',
            'units' => array('px'),
            'title' => esc_html__('Ukuran Logo', 'gusviradigital'),
            'subtitle' => esc_html__('Atur tinggi logo (dalam pixel)', 'gusviradigital'),
            'width' => false,
            'default' => array(
                'height' => '50',
                'units' => 'px'
            )
        ),
        array(
            'id' => 'enable_dark_mode',
            'type' => 'switch',
            'title' => esc_html__('Dark Mode', 'gusviradigital'),
            'subtitle' => esc_html__('Aktifkan fitur dark mode pada website', 'gusviradigital'),
            'default' => true
        ),
        array(
            'id' => 'default_theme_mode',
            'type' => 'select',
            'title' => esc_html__('Mode Tema Default', 'gusviradigital'),
            'subtitle' => esc_html__('Pilih mode tema default website', 'gusviradigital'),
            'options' => array(
                'light' => esc_html__('Light Mode', 'gusviradigital'),
                'dark' => esc_html__('Dark Mode', 'gusviradigital'),
                'auto' => esc_html__('Auto (System)', 'gusviradigital')
            ),
            'default' => 'light',
            'required' => array('enable_dark_mode', 'equals', true)
        ),
        array(
            'id' => 'show_mode_switcher',
            'type' => 'switch',
            'title' => esc_html__('Tampilkan Tombol Switch Mode', 'gusviradigital'),
            'subtitle' => esc_html__('Tampilkan tombol untuk mengganti mode tema', 'gusviradigital'),
            'default' => true,
            'required' => array('enable_dark_mode', 'equals', true)
        ),
        array(
            'id' => 'preloader',
            'type' => 'switch',
            'title' => esc_html__('Preloader', 'gusviradigital'),
            'subtitle' => esc_html__('Aktifkan preloader saat website dimuat', 'gusviradigital'),
            'default' => true
        ),
        array(
            'id' => 'preloader_style',
            'type' => 'select',
            'title' => esc_html__('Jenis Preloader', 'gusviradigital'),
            'subtitle' => esc_html__('Pilih jenis animasi preloader', 'gusviradigital'),
            'options' => array(
                'logo' => esc_html__('Logo', 'gusviradigital'),
                'spinner' => esc_html__('Spinner', 'gusviradigital'),
                'progress-bar' => esc_html__('Progress Bar', 'gusviradigital'),
                'dots' => esc_html__('Dots', 'gusviradigital'),
                'custom' => esc_html__('Custom HTML', 'gusviradigital')
            ),
            'default' => 'logo',
            'required' => array('preloader', 'equals', true)
        ),
        array(
            'id' => 'preloader_logo',
            'type' => 'media',
            'title' => esc_html__('Logo Preloader', 'gusviradigital'),
            'subtitle' => esc_html__('Upload logo untuk preloader (PNG, GIF)', 'gusviradigital'),
            'default' => array(
                'url' => GDP_IMAGES . '/logo-gdp-white.png'
            ),
            'required' => array(
                array('preloader', 'equals', true),
                array('preloader_style', 'equals', 'logo')
            )
        ),
        array(
            'id' => 'preloader_color',
            'type' => 'color',
            'title' => esc_html__('Warna Preloader', 'gusviradigital'),
            'subtitle' => esc_html__('Pilih warna untuk animasi preloader', 'gusviradigital'),
            'default' => '#0088cc',
            'transparent' => false,
            'required' => array(
                array('preloader', 'equals', true),
                array('preloader_style', 'not', 'logo')
            )
        ),
        array(
            'id' => 'preloader_background',
            'type' => 'color',
            'title' => esc_html__('Warna Background Preloader', 'gusviradigital'),
            'subtitle' => esc_html__('Pilih warna background untuk preloader', 'gusviradigital'),
            'default' => '#ffffff',
            'transparent' => false,
            'required' => array('preloader', 'equals', true)
        ),
        array(
            'id' => 'preloader_custom',
            'type' => 'ace_editor',
            'title' => esc_html__('Custom HTML Preloader', 'gusviradigital'),
            'subtitle' => esc_html__('Masukkan kode HTML kustom untuk preloader', 'gusviradigital'),
            'mode' => 'html',
            'theme' => 'monokai',
            'required' => array(
                array('preloader', 'equals', true),
                array('preloader_style', 'equals', 'custom')
            )
        ),
        array(
            'id' => 'preloader_duration',
            'type' => 'slider',
            'title' => esc_html__('Durasi Minimum Preloader', 'gusviradigital'),
            'subtitle' => esc_html__('Atur durasi minimum tampilan preloader (dalam milidetik)', 'gusviradigital'),
            'min' => 100,
            'max' => 5000,
            'step' => 100,
            'default' => 1000,
            'display_value' => 'text',
            'required' => array('preloader', 'equals', true)
        ),
        array(
            'id' => 'back_to_top',
            'type' => 'switch',
            'title' => esc_html__('Back to Top', 'gusviradigital'),
            'subtitle' => esc_html__('Tampilkan tombol back to top', 'gusviradigital'),
            'default' => true
        ),
        array(
            'id' => 'smooth_scroll',
            'type' => 'switch',
            'title' => esc_html__('Smooth Scroll', 'gusviradigital'),
            'subtitle' => esc_html__('Aktifkan efek smooth scroll pada website', 'gusviradigital'),
            'default' => true
        ),
        array(
            'id' => 'site_layout',
            'type' => 'select',
            'title' => esc_html__('Layout Website', 'gusviradigital'),
            'subtitle' => esc_html__('Pilih layout default website', 'gusviradigital'),
            'options' => array(
                'full-width' => esc_html__('Full Width', 'gusviradigital'),
                'boxed' => esc_html__('Boxed', 'gusviradigital')
            ),
            'default' => 'full-width'
        ),
        array(
            'id' => 'container_width',
            'type' => 'slider',
            'title' => esc_html__('Lebar Container', 'gusviradigital'),
            'subtitle' => esc_html__('Atur lebar maksimum container (dalam pixel)', 'gusviradigital'),
            'min' => 1140,
            'max' => 1920,
            'step' => 10,
            'default' => 1320,
            'display_value' => 'text'
        ),
        array(
            'id' => 'enable_rtl',
            'type' => 'switch',
            'title' => esc_html__('RTL Support', 'gusviradigital'),
            'subtitle' => esc_html__('Aktifkan dukungan RTL (Right to Left)', 'gusviradigital'),
            'default' => false
        )
    )
) );
