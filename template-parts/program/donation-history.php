<?php
/**
 * Template part for displaying donation history
 * 
 * @package GusviraDigital
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

$program_id = get_the_ID();
$donations = gdp_donation()->get_by_program($program_id, [
    'status' => 'completed',
    'limit' => 10,
    'orderby' => 'created_at',
    'order' => 'DESC'
]);

if (empty($donations)) {
    return;
}
?>

<div class="gdp-donation-history mt-8">
    <h3 class="text-xl font-bold mb-4"><?php _e('Donatur Terakhir', 'gusviradigital'); ?></h3>
    
    <div class="space-y-4">
        <?php foreach ($donations as $donation) : 
            $donor_name = gdp_get_donor_name($donation);
            $amount = gdp_format_rupiah($donation->amount);
            $date = date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($donation->created_at));
            $message = !empty($donation->message) ? $donation->message : '';
        ?>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-start justify-between">
                    <div>
                        <h4 class="font-semibold text-gray-900"><?php echo esc_html($donor_name); ?></h4>
                        <p class="text-sm text-gray-600"><?php echo esc_html($date); ?></p>
                        <?php if (!empty($message)) : ?>
                            <p class="mt-2 text-gray-700"><?php echo esc_html($message); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="text-right">
                        <span class="font-semibold text-primary"><?php echo esc_html($amount); ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (count($donations) >= 10) : ?>
        <div class="text-center mt-4">
            <button type="button" class="gdp-button gdp-button-outline" id="load-more-donations" data-program="<?php echo esc_attr($program_id); ?>" data-page="1">
                <?php _e('Muat Lebih Banyak', 'gusviradigital'); ?>
            </button>
        </div>
    <?php endif; ?>
</div>

<script>
jQuery(document).ready(function($) {
    $('#load-more-donations').on('click', function() {
        var $button = $(this);
        var program_id = $button.data('program');
        var page = $button.data('page');
        
        $button.prop('disabled', true).text('<?php _e('Memuat...', 'gusviradigital'); ?>');
        
        $.ajax({
            url: gdp_vars.ajax_url,
            type: 'POST',
            data: {
                action: 'gdp_load_more_donations',
                program_id: program_id,
                page: page,
                nonce: gdp_vars.nonce
            },
            success: function(response) {
                if (response.success) {
                    if (response.data.html) {
                        $('.gdp-donation-history .space-y-4').append(response.data.html);
                        $button.data('page', page + 1);
                        
                        if (!response.data.has_more) {
                            $button.parent().remove();
                        }
                    }
                } else {
                    console.error(response.data.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            },
            complete: function() {
                $button.prop('disabled', false).text('<?php _e('Muat Lebih Banyak', 'gusviradigital'); ?>');
            }
        });
    });
});
</script> 