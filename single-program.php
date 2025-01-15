<?php
/**
 * Single Program Template
 *
 * @package GusviraDigital
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

get_header();

// Get program meta
$target = get_post_meta( get_the_ID(), '_donation_target', true );
$collected = get_post_meta( get_the_ID(), '_donation_collected', true );
$deadline = get_post_meta( get_the_ID(), '_donation_deadline', true );
$form_type = get_post_meta( get_the_ID(), '_form_type', true );
$form_style = get_post_meta( get_the_ID(), '_form_style', true );

// Calculate progress
$progress = 0;
if ($target > 0) {
    $progress = ($collected / $target) * 100;
}

// Calculate time left
$time_left = '';
if ($deadline) {
    $deadline_date = new DateTime($deadline);
    $now = new DateTime();
    $interval = $now->diff($deadline_date);
    
    if ($deadline_date < $now) {
        $time_left = 'Program telah berakhir';
    } else {
        $time_left = $interval->days . ' hari tersisa';
    }
}
?>

<main class="container mx-auto px-4 py-8 lg:py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Program Details -->
        <div class="lg:col-span-2">
            <!-- Featured Image -->
            <?php if (has_post_thumbnail()): ?>
                <div class="rounded-xl overflow-hidden mb-6">
                    <?php the_post_thumbnail('full', ['class' => 'w-full h-auto']); ?>
                </div>
            <?php endif; ?>

            <!-- Title & Progress -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-4"><?php the_title(); ?></h1>
                
                <!-- Progress Bar -->
                <div class="mb-4">
                    <div class="h-4 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-primary transition-all duration-300" style="width: <?php echo esc_attr($progress); ?>%"></div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-500">Terkumpul</p>
                        <p class="text-lg font-bold text-gray-900">Rp <?php echo number_format($collected, 0, ',', '.'); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Target</p>
                        <p class="text-lg font-bold text-gray-900">Rp <?php echo number_format($target, 0, ',', '.'); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Sisa Waktu</p>
                        <p class="text-lg font-bold text-gray-900"><?php echo esc_html($time_left); ?></p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="bg-white rounded-xl shadow-sm p-6 prose max-w-none">
                <?php the_content(); ?>
            </div>
        </div>

        <!-- Donation Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm p-6 sticky top-4">
                <h2 class="text-xl font-bold text-gray-900 mb-4">
                    <?php echo $form_type === 'zakat' ? 'Bayar Zakat' : 'Donasi Sekarang'; ?>
                </h2>

                <?php 
                if ($form_type === 'zakat') {
                    get_template_part('template-parts/forms/zakat', $form_style);
                } else {
                    get_template_part('template-parts/forms/donation', $form_style);
                }
                ?>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?> 