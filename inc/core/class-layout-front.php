<?php
/**
 * Layout Front Class
 *
 * @package GusviraDigital
 * @version 1.0.0
 */

namespace GDP\Core;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Layout Front Class
 */
class Layout_Front {
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
    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
    }

    /**
     * Enqueue scripts and styles.
     */
    public function enqueue_scripts() {
        if ( is_front_page() ) {
            wp_enqueue_style( 'gdp-swiper', 'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css', [], GDP_VERSION );
            wp_enqueue_script( 'gdp-swiper', 'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js', [], GDP_VERSION, true );
            wp_enqueue_script( 'gdp-front', GDP_JS . '/front.js', ['jquery', 'gdp-swiper'], GDP_VERSION, true );
        }
    }

    /**
     * Render hero section
     */
    public function render_hero() {
        if ( ! gdp_options( 'front_hero_enable' ) ) {
            return;
        }

        $style = gdp_options( 'front_hero_style', 'style1' );
        $title = gdp_options( 'front_hero_title' );
        $description = gdp_options( 'front_hero_description' );
        $height = gdp_options( 'front_hero_height', 'large' );
        $overlay = gdp_options( 'front_hero_overlay', ['color' => '#000000', 'alpha' => '0.5'] );
        $buttons = gdp_options( 'front_hero_buttons', [] );

        $classes = [
            'gdp-hero relative w-full',
            'gdp-hero--' . $style,
            'gdp-hero--' . $height,
        ];

        $height_class = 'h-[600px]';
        if ($height === 'full') {
            $height_class = 'h-screen';
        } elseif ($height === 'large') {
            $height_class = 'h-[800px]';
        } elseif ($height === 'medium') {
            $height_class = 'h-[600px]';
        } elseif ($height === 'small') {
            $height_class = 'h-[400px]';
        }

        $classes[] = $height_class;

        $background = '';
        if ( $style === 'style1' || $style === 'style2' ) {
            $image = gdp_options( 'front_hero_image' );
            if ( ! empty( $image['url'] ) ) {
                $background = 'style="background-image: url(' . esc_url( $image['url'] ) . ')"';
            }
        }

        $overlay_style = '';
        if ( ! empty( $overlay ) ) {
            $rgba = 'rgba(' . 
                hexdec( substr( $overlay['color'], 1, 2 ) ) . ',' . 
                hexdec( substr( $overlay['color'], 3, 2 ) ) . ',' . 
                hexdec( substr( $overlay['color'], 5, 2 ) ) . ',' . 
                $overlay['alpha'] . ')';
            $overlay_style = 'style="background-color: ' . $rgba . '"';
        }

        ?>
        <section class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" <?php echo $background; ?>>
            <?php if ( $style === 'style3' && $video_url = gdp_options( 'front_hero_video' ) ) : ?>
                <div class="gdp-hero__video absolute inset-0 w-full h-full object-cover">
                    <?php echo wp_oembed_get( $video_url ); ?>
                </div>
            <?php endif; ?>

            <div class="gdp-hero__overlay absolute inset-0 w-full h-full" <?php echo $overlay_style; ?>></div>
            
            <div class="gdp-hero__content container mx-auto px-4 relative z-10 h-full flex flex-col justify-center items-center text-center text-white">
                <?php if ( $title ) : ?>
                    <h1 class="gdp-hero__title text-4xl md:text-5xl lg:text-6xl font-bold mb-4"><?php echo esc_html( $title ); ?></h1>
                <?php endif; ?>

                <?php if ( $description ) : ?>
                    <div class="gdp-hero__description text-lg md:text-xl max-w-2xl mb-8">
                        <?php echo wp_kses_post( $description ); ?>
                    </div>
                <?php endif; ?>

                <?php if ( ! empty( $buttons ) ) : ?>
                    <div class="gdp-hero__buttons flex flex-wrap gap-4 justify-center">
                        <?php foreach ( $buttons as $button ) : 
                            $button_parts = explode( '|', $button );
                            if ( count( $button_parts ) === 2 ) :
                                $button_text = $button_parts[0];
                                $button_url = $button_parts[1];
                            ?>
                                <a href="<?php echo esc_url( $button_url ); ?>" class="gdp-button inline-block px-8 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                                    <?php echo esc_html( $button_text ); ?>
                                </a>
                            <?php endif;
                        endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <?php
    }

    /**
     * Render featured programs section
     */
    public function render_featured_programs() {
        if ( ! gdp_options( 'front_featured_enable' ) ) {
            return;
        }

        $style = gdp_options( 'front_featured_style', 'grid' );
        $title = gdp_options( 'front_featured_title' );
        $description = gdp_options( 'front_featured_description' );
        $count = gdp_options( 'front_featured_count', 3 );
        $columns = gdp_options( 'front_featured_columns', '3' );
        $orderby = gdp_options( 'front_featured_orderby', 'date' );
        $order = gdp_options( 'front_featured_order', 'DESC' );

        $args = [
            'post_type' => 'program',
            'posts_per_page' => $count,
            'orderby' => $orderby,
            'order' => $order,
            'meta_query' => [
                [
                    'key' => '_is_featured',
                    'value' => '1',
                    'compare' => '='
                ]
            ]
        ];

        $programs = new \WP_Query( $args );

        if ( ! $programs->have_posts() ) {
            return;
        }

        $grid_classes = 'grid gap-6';
        if ($style === 'grid') {
            if ($columns === '2') {
                $grid_classes .= ' grid-cols-1 md:grid-cols-2';
            } elseif ($columns === '3') {
                $grid_classes .= ' grid-cols-1 md:grid-cols-2 lg:grid-cols-3';
            } elseif ($columns === '4') {
                $grid_classes .= ' grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4';
            }
        }

        $classes = [
            'gdp-featured py-16',
            'gdp-featured--' . $style,
        ];

        ?>
        <section class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
            <div class="container mx-auto px-4">
                <?php if ( $title ) : ?>
                    <h2 class="gdp-featured__title text-3xl md:text-4xl font-bold text-center mb-4"><?php echo esc_html( $title ); ?></h2>
                <?php endif; ?>

                <?php if ( $description ) : ?>
                    <div class="gdp-featured__description text-lg text-center text-gray-600 max-w-2xl mx-auto mb-12">
                        <?php echo wp_kses_post( $description ); ?>
                    </div>
                <?php endif; ?>

                <div class="gdp-featured__items <?php echo $style === 'grid' ? $grid_classes : ''; ?>">
                    <?php while ( $programs->have_posts() ) : $programs->the_post(); ?>
                        <div class="gdp-featured__item">
                            <?php get_template_part( 'template-parts/content', 'program' ); ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
        <?php
        wp_reset_postdata();
    }

    /**
     * Render statistics section
     */
    public function render_stats() {
        if ( ! gdp_options( 'front_stats_enable' ) ) {
            return;
        }

        $style = gdp_options( 'front_stats_style', 'style1' );
        $title = gdp_options( 'front_stats_title' );
        $description = gdp_options( 'front_stats_description' );
        $items = gdp_options( 'front_stats_items', [] );
        $background = gdp_options( 'front_stats_background', [] );

        if ( empty( $items ) ) {
            return;
        }

        $classes = [
            'gdp-stats py-16',
            'gdp-stats--' . $style,
        ];

        $background_style = '';
        if ( ! empty( $background ) ) {
            if ( ! empty( $background['background-image'] ) ) {
                $background_style .= 'background-image: url(' . esc_url( $background['background-image'] ) . ');';
            }
            if ( ! empty( $background['background-color'] ) ) {
                $background_style .= 'background-color: ' . esc_attr( $background['background-color'] ) . ';';
            }
        }

        ?>
        <section class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" <?php echo $background_style ? 'style="' . $background_style . '"' : ''; ?>>
            <div class="container mx-auto px-4">
                <?php if ( $title ) : ?>
                    <h2 class="gdp-stats__title text-3xl md:text-4xl font-bold text-center mb-4"><?php echo esc_html( $title ); ?></h2>
                <?php endif; ?>

                <?php if ( $description ) : ?>
                    <div class="gdp-stats__description text-lg text-center text-gray-600 max-w-2xl mx-auto mb-12">
                        <?php echo wp_kses_post( $description ); ?>
                    </div>
                <?php endif; ?>

                <div class="gdp-stats__items grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <?php foreach ( $items as $item ) : ?>
                        <div class="gdp-stats__item text-center">
                            <?php if ( $style !== 'style1' && ! empty( $item['icon'] ) ) : ?>
                                <div class="gdp-stats__icon text-4xl text-primary mb-4">
                                    <i class="<?php echo esc_attr( $item['icon'] ); ?>"></i>
                                </div>
                            <?php endif; ?>

                            <?php if ( ! empty( $item['number'] ) ) : ?>
                                <div class="gdp-stats__number text-4xl font-bold text-primary mb-2">
                                    <?php echo esc_html( $item['number'] ); ?>
                                </div>
                            <?php endif; ?>

                            <?php if ( ! empty( $item['label'] ) ) : ?>
                                <div class="gdp-stats__label text-lg text-gray-600">
                                    <?php echo esc_html( $item['label'] ); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php
    }

    /**
     * Render latest programs section
     */
    public function render_latest_programs() {
        if ( ! gdp_options( 'front_latest_enable' ) ) {
            return;
        }

        $title = gdp_options( 'front_latest_title' );
        $description = gdp_options( 'front_latest_description' );
        $count = gdp_options( 'front_latest_count', 6 );

        $args = [
            'post_type' => 'program',
            'posts_per_page' => $count,
            'orderby' => 'date',
            'order' => 'DESC'
        ];

        $programs = new \WP_Query( $args );

        if ( ! $programs->have_posts() ) {
            return;
        }

        ?>
        <section class="gdp-latest py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <?php if ( $title ) : ?>
                    <h2 class="gdp-latest__title text-3xl md:text-4xl font-bold text-center mb-4"><?php echo esc_html( $title ); ?></h2>
                <?php endif; ?>

                <?php if ( $description ) : ?>
                    <div class="gdp-latest__description text-lg text-center text-gray-600 max-w-2xl mx-auto mb-12">
                        <?php echo wp_kses_post( $description ); ?>
                    </div>
                <?php endif; ?>

                <div class="gdp-latest__items grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php while ( $programs->have_posts() ) : $programs->the_post(); ?>
                        <div class="gdp-latest__item">
                            <?php get_template_part( 'template-parts/content', 'program' ); ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
        <?php
        wp_reset_postdata();
    }

    /**
     * Render testimonials section
     */
    public function render_testimonials() {
        if ( ! gdp_options( 'front_testimonials_enable' ) ) {
            return;
        }

        $title = gdp_options( 'front_testimonials_title' );
        $description = gdp_options( 'front_testimonials_description' );
        $items = gdp_options( 'front_testimonials_items', [] );

        if ( empty( $items ) ) {
            return;
        }

        ?>
        <section class="gdp-testimonials py-16">
            <div class="container mx-auto px-4">
                <?php if ( $title ) : ?>
                    <h2 class="gdp-testimonials__title text-3xl md:text-4xl font-bold text-center mb-4"><?php echo esc_html( $title ); ?></h2>
                <?php endif; ?>

                <?php if ( $description ) : ?>
                    <div class="gdp-testimonials__description text-lg text-center text-gray-600 max-w-2xl mx-auto mb-12">
                        <?php echo wp_kses_post( $description ); ?>
                    </div>
                <?php endif; ?>

                <div class="gdp-testimonials__items swiper">
                    <div class="swiper-wrapper">
                        <?php foreach ( $items as $item ) : ?>
                            <div class="gdp-testimonials__item swiper-slide bg-white p-6 rounded-lg shadow-lg">
                                <?php if ( ! empty( $item['image'] ) ) : ?>
                                    <div class="gdp-testimonials__image w-20 h-20 mx-auto mb-4">
                                        <img class="w-full h-full object-cover rounded-full" src="<?php echo esc_url( $item['image']['url'] ); ?>" alt="<?php echo esc_attr( $item['name'] ); ?>">
                                    </div>
                                <?php endif; ?>

                                <?php if ( ! empty( $item['content'] ) ) : ?>
                                    <div class="gdp-testimonials__content text-gray-600 text-center mb-4">
                                        <?php echo wp_kses_post( $item['content'] ); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ( ! empty( $item['name'] ) ) : ?>
                                    <div class="gdp-testimonials__name text-lg font-semibold text-center">
                                        <?php echo esc_html( $item['name'] ); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ( ! empty( $item['role'] ) ) : ?>
                                    <div class="gdp-testimonials__role text-sm text-gray-500 text-center">
                                        <?php echo esc_html( $item['role'] ); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="swiper-pagination mt-8"></div>
                </div>
            </div>
        </section>
        <?php
    }

    /**
     * Render partners section
     */
    public function render_partners() {
        if ( ! gdp_options( 'front_partners_enable' ) ) {
            return;
        }

        $title = gdp_options( 'front_partners_title' );
        $description = gdp_options( 'front_partners_description' );
        $items = gdp_options( 'front_partners_items', [] );

        if ( empty( $items ) ) {
            return;
        }

        ?>
        <section class="gdp-partners py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <?php if ( $title ) : ?>
                    <h2 class="gdp-partners__title text-3xl md:text-4xl font-bold text-center mb-4"><?php echo esc_html( $title ); ?></h2>
                <?php endif; ?>

                <?php if ( $description ) : ?>
                    <div class="gdp-partners__description text-lg text-center text-gray-600 max-w-2xl mx-auto mb-12">
                        <?php echo wp_kses_post( $description ); ?>
                    </div>
                <?php endif; ?>

                <div class="gdp-partners__items grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8 items-center">
                    <?php foreach ( $items as $item ) : ?>
                        <div class="gdp-partners__item">
                            <?php if ( ! empty( $item['url'] ) ) : ?>
                                <a href="<?php echo esc_url( $item['url'] ); ?>" target="_blank" rel="noopener" class="block transition-opacity hover:opacity-75">
                            <?php endif; ?>

                            <?php if ( ! empty( $item['logo'] ) ) : ?>
                                <img class="w-full h-auto" src="<?php echo esc_url( $item['logo']['url'] ); ?>" alt="<?php echo esc_attr( $item['name'] ); ?>">
                            <?php endif; ?>

                            <?php if ( ! empty( $item['url'] ) ) : ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php
    }

    /**
     * Render CTA section
     */
    public function render_cta() {
        if ( ! gdp_options( 'front_cta_enable' ) ) {
            return;
        }

        $style = gdp_options( 'front_cta_style', 'style1' );
        $title = gdp_options( 'front_cta_title' );
        $description = gdp_options( 'front_cta_description' );
        $button_text = gdp_options( 'front_cta_button_text' );
        $button_url = gdp_options( 'front_cta_button_url' );
        $button_style = gdp_options( 'front_cta_button_style', 'primary' );

        $classes = [
            'gdp-cta py-16',
            'gdp-cta--' . $style,
        ];

        $background = '';
        if ($style === 'style2') {
            $image = gdp_options('front_cta_image');
            if (!empty($image['url'])) {
                $background = ' style="background-image: url(' . esc_url($image['url']) . ')"';
            }
        }

        if ($style === 'style3') {
            $classes[] = 'bg-primary text-white';
        }

        ?>
        <section class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"<?php echo $background; ?>>
            <div class="container mx-auto px-4">
                <?php if ( $title ) : ?>
                    <h2 class="gdp-cta__title text-3xl md:text-4xl font-bold text-center mb-4"><?php echo esc_html( $title ); ?></h2>
                <?php endif; ?>

                <?php if ( $description ) : ?>
                    <div class="gdp-cta__description text-lg text-center max-w-2xl mx-auto mb-8">
                        <?php echo wp_kses_post( $description ); ?>
                    </div>
                <?php endif; ?>

                <?php if ( $button_text && $button_url ) : ?>
                    <div class="gdp-cta__button text-center">
                        <a href="<?php echo esc_url( $button_url ); ?>" class="gdp-button inline-block px-8 py-3 bg-white text-primary rounded-lg hover:bg-gray-100 transition-colors">
                            <?php echo esc_html( $button_text ); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <?php
    }
}

// Initialize Layout Front
Layout_Front::get_instance(); 