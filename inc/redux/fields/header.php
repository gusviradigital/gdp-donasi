<?php
Redux::setSection( 'gdp_options', array(
    'title'  => esc_html__( 'Header', 'gusviradigital' ),
    'id'     => 'header',
    'icon'   => 'el el-home',
    'fields' => array(
        // Top Bar Settings
        array(
            'id'       => 'enable_top_bar',
            'type'     => 'switch',
            'title'    => esc_html__('Top Bar', 'gusviradigital'),
            'subtitle' => esc_html__('Aktifkan top bar di atas header', 'gusviradigital'),
            'default'  => false
        ),
        array(
            'id'       => 'top_bar_content',
            'type'     => 'textarea',
            'title'    => esc_html__('Konten Top Bar', 'gusviradigital'),
            'subtitle' => esc_html__('Masukkan teks atau shortcode untuk konten top bar', 'gusviradigital'),
            'default'  => esc_html__('Selamat datang di GDP Donasi', 'gusviradigital'),
            'required' => array('enable_top_bar', '=', true)
        ),
        array(
            'id'       => 'top_bar_bg_color',
            'type'     => 'color',
            'title'    => esc_html__('Warna Latar Top Bar', 'gusviradigital'),
            'subtitle' => esc_html__('Pilih warna latar untuk top bar', 'gusviradigital'),
            'default'  => '#f8f9fa',
            'required' => array('enable_top_bar', '=', true)
        ),
        array(
            'id'       => 'top_bar_text_color',
            'type'     => 'color',
            'title'    => esc_html__('Warna Teks Top Bar', 'gusviradigital'),
            'subtitle' => esc_html__('Pilih warna teks untuk top bar', 'gusviradigital'),
            'default'  => '#6c757d',
            'required' => array('enable_top_bar', '=', true)
        ),
        // Header Style
        array(
            'id'       => 'header_style',
            'type'     => 'select',
            'title'    => esc_html__('Gaya Header', 'gusviradigital'),
            'subtitle' => esc_html__('Pilih gaya tampilan header', 'gusviradigital'),
            'options'  => array(
                'style1' => 'Style 1 - Default',
                'style2' => 'Style 2 - Centered',
                'style3' => 'Style 3 - Minimal'
            ),
            'default'  => 'style1'
        ),
        // Header Features
        array(
            'id'       => 'header_sticky',
            'type'     => 'switch',
            'title'    => esc_html__('Header Sticky', 'gusviradigital'),
            'subtitle' => esc_html__('Aktifkan header sticky saat scroll', 'gusviradigital'),
            'default'  => true
        ),
        array(
            'id'       => 'header_transparent',
            'type'     => 'switch',
            'title'    => esc_html__('Header Transparan', 'gusviradigital'),
            'subtitle' => esc_html__('Aktifkan header transparan di halaman beranda', 'gusviradigital'),
            'default'  => false
        ),
        array(
            'id'       => 'header_search',
            'type'     => 'switch',
            'title'    => esc_html__('Tombol Pencarian', 'gusviradigital'),
            'subtitle' => esc_html__('Tampilkan tombol pencarian di header', 'gusviradigital'),
            'default'  => true
        ),
        // Mobile Menu Settings
        array(
            'id'       => 'mobile_menu_style',
            'type'     => 'select',
            'title'    => esc_html__('Gaya Menu Mobile', 'gusviradigital'),
            'subtitle' => esc_html__('Pilih gaya tampilan menu mobile', 'gusviradigital'),
            'options'  => array(
                'slide' => 'Slide dari Samping',
                'dropdown' => 'Dropdown',
                'fullscreen' => 'Fullscreen Overlay'
            ),
            'default'  => 'slide'
        ),
        array(
            'id'       => 'mobile_menu_position',
            'type'     => 'select',
            'title'    => esc_html__('Posisi Menu Mobile', 'gusviradigital'),
            'subtitle' => esc_html__('Pilih posisi menu mobile saat terbuka', 'gusviradigital'),
            'options'  => array(
                'left' => 'Kiri',
                'right' => 'Kanan'
            ),
            'default'  => 'right',
            'required' => array('mobile_menu_style', '=', 'slide')
        ),
        // CTA Button
        array(
            'id'       => 'header_cta_button',
            'type'     => 'switch',
            'title'    => esc_html__('Tombol CTA', 'gusviradigital'),
            'subtitle' => esc_html__('Tampilkan tombol Call-to-Action di header', 'gusviradigital'),
            'default'  => true
        ),
        array(
            'id'       => 'header_cta_text',
            'type'     => 'text',
            'title'    => esc_html__('Teks Tombol CTA', 'gusviradigital'),
            'subtitle' => esc_html__('Masukkan teks untuk tombol CTA', 'gusviradigital'),
            'default'  => 'Donasi Sekarang',
            'required' => array('header_cta_button', '=', true)
        ),
        array(
            'id'       => 'header_cta_url',
            'type'     => 'text',
            'title'    => esc_html__('URL Tombol CTA', 'gusviradigital'),
            'subtitle' => esc_html__('Masukkan URL untuk tombol CTA', 'gusviradigital'),
            'default'  => '#',
            'required' => array('header_cta_button', '=', true)
        ),
        array(
            'id'       => 'header_cta_style',
            'type'     => 'select',
            'title'    => esc_html__('Gaya Tombol CTA', 'gusviradigital'),
            'subtitle' => esc_html__('Pilih gaya tampilan tombol CTA', 'gusviradigital'),
            'options'  => array(
                'primary' => 'Primary',
                'secondary' => 'Secondary',
                'outline' => 'Outline',
                'custom' => 'Custom'
            ),
            'default'  => 'primary',
            'required' => array('header_cta_button', '=', true)
        ),
        array(
            'id'       => 'header_cta_custom_color',
            'type'     => 'color',
            'title'    => esc_html__('Warna Custom CTA', 'gusviradigital'),
            'subtitle' => esc_html__('Pilih warna custom untuk tombol CTA', 'gusviradigital'),
            'default'  => '#ff5722',
            'required' => array('header_cta_style', '=', 'custom')
        ),
        // Visual Settings
        array(
            'id'          => 'header_padding',
            'type'        => 'spacing',
            'mode'        => 'padding',
            'all'         => false,
            'units'       => array('px'),
            'title'       => esc_html__('Padding Header', 'gusviradigital'),
            'subtitle'    => esc_html__('Atur padding untuk area header', 'gusviradigital'),
            'default'     => array(
                'padding-top'    => '20px',
                'padding-right'  => '30px',
                'padding-bottom' => '20px',
                'padding-left'   => '30px'
            )
        ),
        array(
            'id'       => 'header_bg_color',
            'type'     => 'color',
            'title'    => esc_html__('Warna Latar Header', 'gusviradigital'),
            'subtitle' => esc_html__('Pilih warna latar untuk header', 'gusviradigital'),
            'default'  => '#ffffff',
            'transparent' => false
        ),
        array(
            'id'       => 'header_text_color',
            'type'     => 'color',
            'title'    => esc_html__('Warna Teks Header', 'gusviradigital'),
            'subtitle' => esc_html__('Pilih warna teks untuk header', 'gusviradigital'),
            'default'  => '#333333',
            'transparent' => false
        ),
        array(
            'id'       => 'header_link_color',
            'type'     => 'link_color',
            'title'    => esc_html__('Warna Link Menu', 'gusviradigital'),
            'subtitle' => esc_html__('Atur warna link menu header (normal/hover/active)', 'gusviradigital'),
            'default'  => array(
                'regular' => '#333333',
                'hover'   => '#007bff',
                'active'  => '#0056b3',
            )
        ),
        array(
            'id'       => 'header_border',
            'type'     => 'border',
            'title'    => esc_html__('Border Header', 'gusviradigital'),
            'subtitle' => esc_html__('Atur border untuk area header', 'gusviradigital'),
            'default'  => array(
                'border-color'  => '#eeeeee',
                'border-style'  => 'solid',
                'border-top'    => '0',
                'border-right'  => '0',
                'border-bottom' => '1px',
                'border-left'   => '0'
            )
        ),
        array(
            'id'       => 'mobile_header_breakpoint',
            'type'     => 'slider',
            'title'    => esc_html__('Breakpoint Header Mobile', 'gusviradigital'),
            'subtitle' => esc_html__('Atur ukuran breakpoint untuk header mobile (dalam pixel)', 'gusviradigital'),
            'default'  => 768,
            'min'      => 320,
            'max'      => 1200,
            'step'     => 1
        )
    )
) );

