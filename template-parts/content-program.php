<?php
/**
 * Template part for displaying program content
 *
 * @package GusviraDigital
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$target = get_post_meta( get_the_ID(), '_donation_target', true );
$collected = get_post_meta( get_the_ID(), '_donation_collected', true );
$deadline = get_post_meta( get_the_ID(), '_donation_deadline', true );

$percentage = 0;
if ( $target && $collected ) {
    $percentage = ( $collected / $target ) * 100;
    $percentage = min( 100, $percentage );
}

$time_left = '';
if ( $deadline ) {
    $deadline_date = strtotime( $deadline );
    $now = current_time( 'timestamp' );
    $diff = $deadline_date - $now;

    if ( $diff > 0 ) {
        $days = floor( $diff / ( 60 * 60 * 24 ) );
        $time_left = sprintf( 
            _n( '%s hari tersisa', '%s hari tersisa', $days, 'gusviradigital' ),
            number_format_i18n( $days )
        );
    } else {
        $time_left = __( 'Program telah berakhir', 'gusviradigital' );
    }
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'gdp-program bg-white rounded-lg shadow-lg overflow-hidden' ); ?>>
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="gdp-program__thumbnail relative pb-[60%]">
            <a href="<?php the_permalink(); ?>" class="block absolute inset-0">
                <?php the_post_thumbnail( 'medium', ['class' => 'w-full h-full object-cover'] ); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="gdp-program__content p-6">
        <header class="gdp-program__header mb-4">
            <?php the_title( '<h3 class="gdp-program__title text-xl font-semibold mb-2"><a href="' . esc_url( get_permalink() ) . '" class="hover:text-primary transition-colors">', '</a></h3>' ); ?>
        </header>

        <div class="gdp-program__excerpt text-gray-600 mb-6">
            <?php the_excerpt(); ?>
        </div>

        <?php if ( $target ) : ?>
            <div class="gdp-program__progress mb-4">
                <div class="gdp-program__progress-bar h-2 bg-gray-200 rounded-full overflow-hidden mb-2">
                    <div class="gdp-program__progress-fill h-full bg-primary" style="width: <?php echo esc_attr( $percentage ); ?>%"></div>
                </div>
                <div class="gdp-program__progress-info flex justify-between text-sm">
                    <div class="gdp-program__collected text-gray-600">
                        <?php
                        printf(
                            /* translators: %s: Collected amount */
                            __( 'Terkumpul: Rp %s', 'gusviradigital' ),
                            number_format_i18n( $collected, 0 )
                        );
                        ?>
                    </div>
                    <div class="gdp-program__percentage text-primary font-semibold">
                        <?php echo esc_html( number_format_i18n( $percentage, 1 ) ); ?>%
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ( $time_left ) : ?>
            <div class="gdp-program__deadline text-sm text-gray-500 mb-4">
                <i class="fas fa-clock mr-2"></i>
                <?php echo esc_html( $time_left ); ?>
            </div>
        <?php endif; ?>

        <div class="gdp-program__footer">
            <a href="<?php the_permalink(); ?>" class="gdp-button gdp-button--primary inline-block w-full py-3 text-center bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                <?php esc_html_e( 'Donasi Sekarang', 'gusviradigital' ); ?>
            </a>
        </div>
    </div>
</article> 