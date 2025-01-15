<?php
/**
 * Front page template
 *
 * @package GusviraDigital
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

get_header();

$layout_front = \GDP\Core\Layout_Front::get_instance();

// Hero Section
$layout_front->render_hero();

// Featured Programs Section
$layout_front->render_featured_programs();

// Statistics Section
$layout_front->render_stats();

// Latest Programs Section
$layout_front->render_latest_programs();

// Testimonials Section
$layout_front->render_testimonials();

// Partners Section
$layout_front->render_partners();

// CTA Section
$layout_front->render_cta();

get_footer(); 