<?php
Redux::setSection( 'gdp_options', array(
    'title' => esc_html__( 'Footer', 'gusviradigital' ),
    'id' => 'footer',
    'icon' => 'el el-home',
    'fields' => array(
        // Footer Top Section
        array(
            'id' => 'footer_top_section',
            'type' => 'section',
            'title' => esc_html__('Footer Top Section', 'gusviradigital'),
            'indent' => true
        ),
        array(
            'id' => 'enable_footer_top',
            'type' => 'switch',
            'title' => esc_html__('Enable Footer Top', 'gusviradigital'),
            'subtitle' => esc_html__('Aktifkan bagian atas footer', 'gusviradigital'),
            'default' => true
        ),
        array(
            'id' => 'footer_top_background',
            'type' => 'background',
            'title' => esc_html__('Footer Top Background', 'gusviradigital'),
            'subtitle' => esc_html__('Atur background bagian atas footer', 'gusviradigital'),
            'default' => array(
                'background-color' => '#f8f9fa'
            ),
            'required' => array('enable_footer_top', 'equals', true)
        ),
        array(
            'id' => 'footer_top_padding',
            'type' => 'spacing',
            'mode' => 'padding',
            'title' => esc_html__('Footer Top Padding', 'gusviradigital'),
            'subtitle' => esc_html__('Atur padding bagian atas footer', 'gusviradigital'),
            'default' => array(
                'padding-top' => '60px',
                'padding-right' => '0px',
                'padding-bottom' => '60px',
                'padding-left' => '0px'
            ),
            'required' => array('enable_footer_top', 'equals', true)
        ),

        // Footer Widgets Section
        array(
            'id' => 'footer_widgets_section',
            'type' => 'section',
            'title' => esc_html__('Footer Widgets Section', 'gusviradigital'),
            'indent' => true
        ),
        array(
            'id' => 'footer_widgets',
            'type' => 'switch',
            'title' => esc_html__('Enable Footer Widgets', 'gusviradigital'),
            'subtitle' => esc_html__('Aktifkan area widget di footer', 'gusviradigital'),
            'default' => true
        ),
        array(
            'id' => 'footer_columns',
            'type' => 'select',
            'title' => esc_html__('Jumlah Kolom Widget', 'gusviradigital'),
            'subtitle' => esc_html__('Pilih jumlah kolom untuk area widget footer', 'gusviradigital'),
            'options' => array(
                '1' => '1 Kolom',
                '2' => '2 Kolom',
                '3' => '3 Kolom',
                '4' => '4 Kolom'
            ),
            'default' => '4',
            'required' => array('footer_widgets', 'equals', true)
        ),
        array(
            'id' => 'footer_widgets_background',
            'type' => 'background',
            'title' => esc_html__('Footer Widgets Background', 'gusviradigital'),
            'subtitle' => esc_html__('Atur background area widget footer', 'gusviradigital'),
            'default' => array(
                'background-color' => '#1a1a1a'
            ),
            'required' => array('footer_widgets', 'equals', true)
        ),
        array(
            'id' => 'footer_widgets_padding',
            'type' => 'spacing',
            'mode' => 'padding',
            'title' => esc_html__('Footer Widgets Padding', 'gusviradigital'),
            'subtitle' => esc_html__('Atur padding area widget footer', 'gusviradigital'),
            'default' => array(
                'padding-top' => '80px',
                'padding-right' => '0px',
                'padding-bottom' => '50px',
                'padding-left' => '0px'
            ),
            'required' => array('footer_widgets', 'equals', true)
        ),

        // Footer Bottom Section
        array(
            'id' => 'footer_bottom_section',
            'type' => 'section',
            'title' => esc_html__('Footer Bottom Section', 'gusviradigital'),
            'indent' => true
        ),
        array(
            'id' => 'footer_copyright',
            'type' => 'editor',
            'title' => esc_html__('Copyright Text', 'gusviradigital'),
            'subtitle' => esc_html__('Masukkan teks copyright untuk footer', 'gusviradigital'),
            'default' => '© ' . date('Y') . ' GDP Donasi. All rights reserved.',
            'args' => array(
                'wpautop' => false,
                'media_buttons' => false,
                'textarea_rows' => 5,
                'teeny' => true,
                'quicktags' => false
            )
        ),
        array(
            'id' => 'footer_bottom_background',
            'type' => 'background',
            'title' => esc_html__('Footer Bottom Background', 'gusviradigital'),
            'subtitle' => esc_html__('Atur background bagian bawah footer', 'gusviradigital'),
            'default' => array(
                'background-color' => '#111111'
            )
        ),
        array(
            'id' => 'footer_bottom_padding',
            'type' => 'spacing',
            'mode' => 'padding',
            'title' => esc_html__('Footer Bottom Padding', 'gusviradigital'),
            'subtitle' => esc_html__('Atur padding bagian bawah footer', 'gusviradigital'),
            'default' => array(
                'padding-top' => '20px',
                'padding-right' => '0px',
                'padding-bottom' => '20px',
                'padding-left' => '0px'
            )
        ),

        // Social Media Section
        array(
            'id' => 'social_media_section',
            'type' => 'section',
            'title' => esc_html__('Social Media Settings', 'gusviradigital'),
            'indent' => true
        ),
        array(
            'id' => 'footer_social_media',
            'type' => 'sortable',
            'title' => esc_html__('Social Media Links', 'gusviradigital'),
            'subtitle' => esc_html__('Aktifkan dan atur urutan social media yang ingin ditampilkan', 'gusviradigital'),
            'label' => true,
            'options' => array(
                'facebook' => 'Facebook',
                'twitter' => 'Twitter',
                'instagram' => 'Instagram',
                'youtube' => 'YouTube',
                'linkedin' => 'LinkedIn',
                'tiktok' => 'TikTok'
            )
        ),
        array(
            'id' => 'facebook_url',
            'type' => 'text',
            'title' => esc_html__('Facebook URL', 'gusviradigital'),
            'subtitle' => esc_html__('Masukkan URL Facebook', 'gusviradigital'),
            'placeholder' => 'https://facebook.com/username',
            'validate' => 'url'
        ),
        array(
            'id' => 'twitter_url',
            'type' => 'text',
            'title' => esc_html__('Twitter URL', 'gusviradigital'),
            'subtitle' => esc_html__('Masukkan URL Twitter', 'gusviradigital'),
            'placeholder' => 'https://twitter.com/username',
            'validate' => 'url'
        ),
        array(
            'id' => 'instagram_url',
            'type' => 'text',
            'title' => esc_html__('Instagram URL', 'gusviradigital'),
            'subtitle' => esc_html__('Masukkan URL Instagram', 'gusviradigital'),
            'placeholder' => 'https://instagram.com/username',
            'validate' => 'url'
        ),
        array(
            'id' => 'youtube_url',
            'type' => 'text',
            'title' => esc_html__('YouTube URL', 'gusviradigital'),
            'subtitle' => esc_html__('Masukkan URL YouTube', 'gusviradigital'),
            'placeholder' => 'https://youtube.com/channel/id',
            'validate' => 'url'
        ),
        array(
            'id' => 'linkedin_url',
            'type' => 'text',
            'title' => esc_html__('LinkedIn URL', 'gusviradigital'),
            'subtitle' => esc_html__('Masukkan URL LinkedIn', 'gusviradigital'),
            'placeholder' => 'https://linkedin.com/company/username',
            'validate' => 'url'
        ),
        array(
            'id' => 'tiktok_url',
            'type' => 'text',
            'title' => esc_html__('TikTok URL', 'gusviradigital'),
            'subtitle' => esc_html__('Masukkan URL TikTok', 'gusviradigital'),
            'placeholder' => 'https://tiktok.com/@username',
            'validate' => 'url'
        ),

        // Typography Settings
        array(
            'id' => 'typography_section',
            'type' => 'section',
            'title' => esc_html__('Typography Settings', 'gusviradigital'),
            'indent' => true
        ),
        array(
            'id' => 'footer_heading_color',
            'type' => 'color',
            'title' => esc_html__('Footer Heading Color', 'gusviradigital'),
            'subtitle' => esc_html__('Pilih warna untuk heading di footer', 'gusviradigital'),
            'default' => '#ffffff',
            'transparent' => false
        ),
        array(
            'id' => 'footer_text_color',
            'type' => 'color',
            'title' => esc_html__('Footer Text Color', 'gusviradigital'),
            'subtitle' => esc_html__('Pilih warna untuk teks di footer', 'gusviradigital'),
            'default' => '#ffffff',
            'transparent' => false
        ),
        array(
            'id' => 'footer_link_color',
            'type' => 'link_color',
            'title' => esc_html__('Footer Link Color', 'gusviradigital'),
            'subtitle' => esc_html__('Pilih warna untuk link di footer', 'gusviradigital'),
            'default' => array(
                'regular' => '#ffffff',
                'hover' => '#cccccc',
                'active' => '#cccccc',
            )
        ),

        // Additional Settings
        array(
            'id' => 'additional_section',
            'type' => 'section',
            'title' => esc_html__('Additional Settings', 'gusviradigital'),
            'indent' => true
        ),
        array(
            'id' => 'footer_payment_icons',
            'type' => 'media',
            'title' => esc_html__('Payment Method Icons', 'gusviradigital'),
            'subtitle' => esc_html__('Upload gambar metode pembayaran yang didukung', 'gusviradigital'),
            'library_filter' => array('gif', 'jpg', 'jpg', 'jpeg', 'png', 'svg')
        ),
        array(
            'id' => 'footer_custom_css',
            'type' => 'ace_editor',
            'title' => esc_html__('Footer Custom CSS', 'gusviradigital'),
            'subtitle' => esc_html__('Tambahkan CSS kustom untuk footer', 'gusviradigital'),
            'mode' => 'css',
            'theme' => 'monokai'
        )
    )
) );
