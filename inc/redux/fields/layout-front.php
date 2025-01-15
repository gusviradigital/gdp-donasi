<?php
/**
 * Layout Front Settings
 *
 * @package GusviraDigital
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

Redux::set_section(
    'gdp_options',
    [
        'title'  => __( 'Layout Front', 'gusviradigital' ),
        'id'     => 'layout_front',
        'icon'   => 'el el-home',
        'fields' => [
            // Hero Section
            [
                'id'       => 'front_hero_enable',
                'type'     => 'switch',
                'title'    => __( 'Enable Hero Section', 'gusviradigital' ),
                'subtitle' => __( 'Enable or disable hero section on front page', 'gusviradigital' ),
                'default'  => true,
            ],
            [
                'id'       => 'front_hero_style',
                'type'     => 'select',
                'title'    => __( 'Hero Style', 'gusviradigital' ),
                'subtitle' => __( 'Select hero section style', 'gusviradigital' ),
                'options'  => [
                    'style1' => __( 'Style 1 - Full Width Image', 'gusviradigital' ),
                    'style2' => __( 'Style 2 - Split Content', 'gusviradigital' ),
                    'style3' => __( 'Style 3 - Video Background', 'gusviradigital' ),
                ],
                'default'  => 'style1',
                'required' => ['front_hero_enable', '=', true],
            ],
            [
                'id'       => 'front_hero_title',
                'type'     => 'text',
                'title'    => __( 'Hero Title', 'gusviradigital' ),
                'subtitle' => __( 'Enter hero section title', 'gusviradigital' ),
                'default'  => __( 'Donasi untuk Kebaikan', 'gusviradigital' ),
                'required' => ['front_hero_enable', '=', true],
            ],
            [
                'id'       => 'front_hero_description',
                'type'     => 'textarea',
                'title'    => __( 'Hero Description', 'gusviradigital' ),
                'subtitle' => __( 'Enter hero section description', 'gusviradigital' ),
                'default'  => __( 'Mari bergabung dalam gerakan kebaikan dengan berdonasi untuk mereka yang membutuhkan', 'gusviradigital' ),
                'required' => ['front_hero_enable', '=', true],
            ],
            [
                'id'       => 'front_hero_image',
                'type'     => 'media',
                'title'    => __( 'Hero Background Image', 'gusviradigital' ),
                'subtitle' => __( 'Upload hero section background image', 'gusviradigital' ),
                'required' => ['front_hero_enable', '=', true],
            ],
            [
                'id'       => 'front_hero_video',
                'type'     => 'text',
                'title'    => __( 'Hero Background Video URL', 'gusviradigital' ),
                'subtitle' => __( 'Enter YouTube or Vimeo video URL', 'gusviradigital' ),
                'required' => ['front_hero_style', '=', 'style3'],
            ],
            [
                'id'       => 'front_hero_overlay',
                'type'     => 'color_rgba',
                'title'    => __( 'Hero Overlay Color', 'gusviradigital' ),
                'subtitle' => __( 'Select overlay color and opacity', 'gusviradigital' ),
                'default'  => [
                    'color' => '#000000',
                    'alpha' => '0.5',
                ],
                'required' => ['front_hero_enable', '=', true],
            ],
            [
                'id'       => 'front_hero_height',
                'type'     => 'select',
                'title'    => __( 'Hero Height', 'gusviradigital' ),
                'options'  => [
                    'full'    => __( 'Full Screen', 'gusviradigital' ),
                    'large'   => __( 'Large (800px)', 'gusviradigital' ),
                    'medium'  => __( 'Medium (600px)', 'gusviradigital' ),
                    'small'   => __( 'Small (400px)', 'gusviradigital' ),
                ],
                'default'  => 'large',
                'required' => ['front_hero_enable', '=', true],
            ],
            [
                'id'       => 'front_hero_buttons',
                'type'     => 'multi_text',
                'title'    => __( 'Hero Buttons', 'gusviradigital' ),
                'subtitle' => __( 'Add multiple buttons (Format: Button Text|Button URL)', 'gusviradigital' ),
                'default'  => [
                    'Donasi Sekarang|/donasi',
                    'Pelajari Lebih Lanjut|#featured',
                ],
                'required' => ['front_hero_enable', '=', true],
            ],

            // Featured Programs Section
            [
                'id'       => 'front_featured_enable',
                'type'     => 'switch',
                'title'    => __( 'Enable Featured Programs', 'gusviradigital' ),
                'subtitle' => __( 'Enable or disable featured programs section', 'gusviradigital' ),
                'default'  => true,
            ],
            [
                'id'       => 'front_featured_style',
                'type'     => 'select',
                'title'    => __( 'Featured Programs Style', 'gusviradigital' ),
                'options'  => [
                    'grid'    => __( 'Grid Layout', 'gusviradigital' ),
                    'slider'  => __( 'Slider Layout', 'gusviradigital' ),
                    'masonry' => __( 'Masonry Layout', 'gusviradigital' ),
                ],
                'default'  => 'grid',
                'required' => ['front_featured_enable', '=', true],
            ],
            [
                'id'       => 'front_featured_title',
                'type'     => 'text',
                'title'    => __( 'Featured Section Title', 'gusviradigital' ),
                'subtitle' => __( 'Enter featured section title', 'gusviradigital' ),
                'default'  => __( 'Program Pilihan', 'gusviradigital' ),
                'required' => ['front_featured_enable', '=', true],
            ],
            [
                'id'       => 'front_featured_description',
                'type'     => 'textarea',
                'title'    => __( 'Featured Section Description', 'gusviradigital' ),
                'subtitle' => __( 'Enter featured section description', 'gusviradigital' ),
                'default'  => __( 'Program donasi pilihan yang sedang berjalan', 'gusviradigital' ),
                'required' => ['front_featured_enable', '=', true],
            ],
            [
                'id'       => 'front_featured_count',
                'type'     => 'spinner',
                'title'    => __( 'Number of Featured Programs', 'gusviradigital' ),
                'subtitle' => __( 'Select number of featured programs to display', 'gusviradigital' ),
                'default'  => 3,
                'min'      => 1,
                'max'      => 12,
                'step'     => 1,
                'required' => ['front_featured_enable', '=', true],
            ],
            [
                'id'       => 'front_featured_columns',
                'type'     => 'select',
                'title'    => __( 'Featured Programs Columns', 'gusviradigital' ),
                'options'  => [
                    '2' => __( '2 Columns', 'gusviradigital' ),
                    '3' => __( '3 Columns', 'gusviradigital' ),
                    '4' => __( '4 Columns', 'gusviradigital' ),
                ],
                'default'  => '3',
                'required' => ['front_featured_enable', '=', true],
            ],
            [
                'id'       => 'front_featured_orderby',
                'type'     => 'select',
                'title'    => __( 'Order Programs By', 'gusviradigital' ),
                'options'  => [
                    'date'     => __( 'Date', 'gusviradigital' ),
                    'title'    => __( 'Title', 'gusviradigital' ),
                    'modified' => __( 'Last Modified', 'gusviradigital' ),
                    'rand'     => __( 'Random', 'gusviradigital' ),
                ],
                'default'  => 'date',
                'required' => ['front_featured_enable', '=', true],
            ],
            [
                'id'       => 'front_featured_order',
                'type'     => 'select',
                'title'    => __( 'Sort Order', 'gusviradigital' ),
                'options'  => [
                    'DESC' => __( 'Descending', 'gusviradigital' ),
                    'ASC'  => __( 'Ascending', 'gusviradigital' ),
                ],
                'default'  => 'DESC',
                'required' => ['front_featured_enable', '=', true],
            ],

            // Statistics Section
            [
                'id'       => 'front_stats_enable',
                'type'     => 'switch',
                'title'    => __( 'Enable Statistics Section', 'gusviradigital' ),
                'subtitle' => __( 'Enable or disable statistics section', 'gusviradigital' ),
                'default'  => true,
            ],
            [
                'id'       => 'front_stats_style',
                'type'     => 'select',
                'title'    => __( 'Statistics Style', 'gusviradigital' ),
                'options'  => [
                    'style1' => __( 'Style 1 - Numbers Only', 'gusviradigital' ),
                    'style2' => __( 'Style 2 - With Icons', 'gusviradigital' ),
                    'style3' => __( 'Style 3 - With Images', 'gusviradigital' ),
                ],
                'default'  => 'style1',
                'required' => ['front_stats_enable', '=', true],
            ],
            [
                'id'       => 'front_stats_title',
                'type'     => 'text',
                'title'    => __( 'Statistics Section Title', 'gusviradigital' ),
                'subtitle' => __( 'Enter statistics section title', 'gusviradigital' ),
                'default'  => __( 'Dampak Kebaikan', 'gusviradigital' ),
                'required' => ['front_stats_enable', '=', true],
            ],
            [
                'id'       => 'front_stats_description',
                'type'     => 'textarea',
                'title'    => __( 'Statistics Section Description', 'gusviradigital' ),
                'subtitle' => __( 'Enter statistics section description', 'gusviradigital' ),
                'default'  => __( 'Bersama kita telah memberikan dampak positif', 'gusviradigital' ),
                'required' => ['front_stats_enable', '=', true],
            ],
            [
                'id'       => 'front_stats_items',
                'type'     => 'repeater',
                'title'    => __( 'Statistics Items', 'gusviradigital' ),
                'subtitle' => __( 'Add statistics items', 'gusviradigital' ),
                'group_values' => true,
                'fields'   => [
                    [
                        'id'       => 'icon',
                        'type'     => 'text',
                        'title'    => __( 'Icon Class', 'gusviradigital' ),
                        'subtitle' => __( 'Enter Font Awesome icon class', 'gusviradigital' ),
                    ],
                    [
                        'id'       => 'number',
                        'type'     => 'text',
                        'title'    => __( 'Number', 'gusviradigital' ),
                        'subtitle' => __( 'Enter statistic number', 'gusviradigital' ),
                    ],
                    [
                        'id'       => 'label',
                        'type'     => 'text',
                        'title'    => __( 'Label', 'gusviradigital' ),
                        'subtitle' => __( 'Enter statistic label', 'gusviradigital' ),
                    ],
                ],
                'required' => ['front_stats_enable', '=', true],
            ],
            [
                'id'       => 'front_stats_background',
                'type'     => 'background',
                'title'    => __( 'Statistics Background', 'gusviradigital' ),
                'subtitle' => __( 'Section background with image, color, etc.', 'gusviradigital' ),
                'required' => ['front_stats_enable', '=', true],
            ],

            // Latest Programs Section
            [
                'id'       => 'front_latest_enable',
                'type'     => 'switch',
                'title'    => __( 'Enable Latest Programs', 'gusviradigital' ),
                'subtitle' => __( 'Enable or disable latest programs section', 'gusviradigital' ),
                'default'  => true,
            ],
            [
                'id'       => 'front_latest_title',
                'type'     => 'text',
                'title'    => __( 'Latest Programs Title', 'gusviradigital' ),
                'default'  => __( 'Program Terbaru', 'gusviradigital' ),
                'required' => ['front_latest_enable', '=', true],
            ],
            [
                'id'       => 'front_latest_description',
                'type'     => 'textarea',
                'title'    => __( 'Latest Programs Description', 'gusviradigital' ),
                'default'  => __( 'Program donasi terbaru yang membutuhkan bantuan Anda', 'gusviradigital' ),
                'required' => ['front_latest_enable', '=', true],
            ],
            [
                'id'       => 'front_latest_count',
                'type'     => 'spinner',
                'title'    => __( 'Number of Latest Programs', 'gusviradigital' ),
                'default'  => 6,
                'min'      => 1,
                'max'      => 12,
                'required' => ['front_latest_enable', '=', true],
            ],

            // Testimonials Section
            [
                'id'       => 'front_testimonials_enable',
                'type'     => 'switch',
                'title'    => __( 'Enable Testimonials', 'gusviradigital' ),
                'subtitle' => __( 'Enable or disable testimonials section', 'gusviradigital' ),
                'default'  => true,
            ],
            [
                'id'       => 'front_testimonials_title',
                'type'     => 'text',
                'title'    => __( 'Testimonials Title', 'gusviradigital' ),
                'default'  => __( 'Apa Kata Mereka', 'gusviradigital' ),
                'required' => ['front_testimonials_enable', '=', true],
            ],
            [
                'id'       => 'front_testimonials_description',
                'type'     => 'textarea',
                'title'    => __( 'Testimonials Description', 'gusviradigital' ),
                'default'  => __( 'Testimoni dari para donatur dan penerima manfaat', 'gusviradigital' ),
                'required' => ['front_testimonials_enable', '=', true],
            ],
            [
                'id'       => 'front_testimonials_items',
                'type'     => 'repeater',
                'title'    => __( 'Testimonial Items', 'gusviradigital' ),
                'group_values' => true,
                'fields'   => [
                    [
                        'id'       => 'image',
                        'type'     => 'media',
                        'title'    => __( 'Photo', 'gusviradigital' ),
                    ],
                    [
                        'id'       => 'name',
                        'type'     => 'text',
                        'title'    => __( 'Name', 'gusviradigital' ),
                    ],
                    [
                        'id'       => 'role',
                        'type'     => 'text',
                        'title'    => __( 'Role', 'gusviradigital' ),
                    ],
                    [
                        'id'       => 'content',
                        'type'     => 'textarea',
                        'title'    => __( 'Testimonial', 'gusviradigital' ),
                    ],
                ],
                'required' => ['front_testimonials_enable', '=', true],
            ],

            // Partners Section
            [
                'id'       => 'front_partners_enable',
                'type'     => 'switch',
                'title'    => __( 'Enable Partners', 'gusviradigital' ),
                'subtitle' => __( 'Enable or disable partners section', 'gusviradigital' ),
                'default'  => true,
            ],
            [
                'id'       => 'front_partners_title',
                'type'     => 'text',
                'title'    => __( 'Partners Title', 'gusviradigital' ),
                'default'  => __( 'Mitra Kami', 'gusviradigital' ),
                'required' => ['front_partners_enable', '=', true],
            ],
            [
                'id'       => 'front_partners_description',
                'type'     => 'textarea',
                'title'    => __( 'Partners Description', 'gusviradigital' ),
                'default'  => __( 'Berkolaborasi dengan berbagai mitra terpercaya', 'gusviradigital' ),
                'required' => ['front_partners_enable', '=', true],
            ],
            [
                'id'       => 'front_partners_items',
                'type'     => 'repeater',
                'title'    => __( 'Partner Items', 'gusviradigital' ),
                'group_values' => true,
                'fields'   => [
                    [
                        'id'       => 'logo',
                        'type'     => 'media',
                        'title'    => __( 'Partner Logo', 'gusviradigital' ),
                    ],
                    [
                        'id'       => 'name',
                        'type'     => 'text',
                        'title'    => __( 'Partner Name', 'gusviradigital' ),
                    ],
                    [
                        'id'       => 'url',
                        'type'     => 'text',
                        'title'    => __( 'Partner URL', 'gusviradigital' ),
                    ],
                ],
                'required' => ['front_partners_enable', '=', true],
            ],

            // CTA Section
            [
                'id'       => 'front_cta_enable',
                'type'     => 'switch',
                'title'    => __( 'Enable CTA Section', 'gusviradigital' ),
                'subtitle' => __( 'Enable or disable call to action section', 'gusviradigital' ),
                'default'  => true,
            ],
            [
                'id'       => 'front_cta_style',
                'type'     => 'select',
                'title'    => __( 'CTA Style', 'gusviradigital' ),
                'options'  => [
                    'style1' => __( 'Style 1 - Simple', 'gusviradigital' ),
                    'style2' => __( 'Style 2 - With Image', 'gusviradigital' ),
                    'style3' => __( 'Style 3 - Full Width', 'gusviradigital' ),
                ],
                'default'  => 'style1',
                'required' => ['front_cta_enable', '=', true],
            ],
            [
                'id'       => 'front_cta_title',
                'type'     => 'text',
                'title'    => __( 'CTA Title', 'gusviradigital' ),
                'subtitle' => __( 'Enter call to action title', 'gusviradigital' ),
                'default'  => __( 'Mari Berdonasi', 'gusviradigital' ),
                'required' => ['front_cta_enable', '=', true],
            ],
            [
                'id'       => 'front_cta_description',
                'type'     => 'textarea',
                'title'    => __( 'CTA Description', 'gusviradigital' ),
                'subtitle' => __( 'Enter call to action description', 'gusviradigital' ),
                'default'  => __( 'Setiap donasi Anda sangat berarti bagi mereka yang membutuhkan', 'gusviradigital' ),
                'required' => ['front_cta_enable', '=', true],
            ],
            [
                'id'       => 'front_cta_image',
                'type'     => 'media',
                'title'    => __( 'CTA Background Image', 'gusviradigital' ),
                'subtitle' => __( 'Upload background image for CTA section', 'gusviradigital' ),
                'required' => ['front_cta_style', '=', 'style2'],
            ],
            [
                'id'       => 'front_cta_button_text',
                'type'     => 'text',
                'title'    => __( 'CTA Button Text', 'gusviradigital' ),
                'subtitle' => __( 'Enter call to action button text', 'gusviradigital' ),
                'default'  => __( 'Donasi Sekarang', 'gusviradigital' ),
                'required' => ['front_cta_enable', '=', true],
            ],
            [
                'id'       => 'front_cta_button_url',
                'type'     => 'text',
                'title'    => __( 'CTA Button URL', 'gusviradigital' ),
                'subtitle' => __( 'Enter call to action button URL', 'gusviradigital' ),
                'default'  => '/donasi',
                'required' => ['front_cta_enable', '=', true],
            ],
            [
                'id'       => 'front_cta_button_style',
                'type'     => 'select',
                'title'    => __( 'CTA Button Style', 'gusviradigital' ),
                'options'  => [
                    'primary'   => __( 'Primary', 'gusviradigital' ),
                    'secondary' => __( 'Secondary', 'gusviradigital' ),
                    'outline'   => __( 'Outline', 'gusviradigital' ),
                ],
                'default'  => 'primary',
                'required' => ['front_cta_enable', '=', true],
            ],
        ],
    ]
);
