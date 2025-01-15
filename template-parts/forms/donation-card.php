<?php
/**
 * Donation Form - Card Style
 *
 * @package GusviraDigital
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$suggested_amounts = get_post_meta(get_the_ID(), '_suggested_amounts', true);
$custom_amount = get_post_meta(get_the_ID(), '_custom_amount', true);
?>

<form class="donation-form" method="post">
    <!-- Suggested Amounts -->
    <div class="grid grid-cols-2 gap-3 mb-6">
        <?php foreach ($suggested_amounts as $amount): ?>
            <button type="button" 
                    class="amount-option p-4 text-center border-2 border-gray-200 rounded-lg hover:border-primary hover:bg-primary/5 transition-all duration-200"
                    data-amount="<?php echo esc_attr($amount); ?>">
                <span class="block text-lg font-bold text-gray-900">
                    Rp <?php echo number_format($amount, 0, ',', '.'); ?>
                </span>
            </button>
        <?php endforeach; ?>
        
        <!-- Custom Amount -->
        <div class="col-span-2">
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                <input type="number" 
                       name="custom_amount" 
                       class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-0 text-lg font-bold text-gray-900"
                       placeholder="Nominal Lainnya"
                       min="1000">
            </div>
        </div>
    </div>

    <!-- Personal Info -->
    <div class="space-y-4 mb-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" 
                   name="name" 
                   required
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-primary focus:ring-0">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" 
                   name="email" 
                   required
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-primary focus:ring-0">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">No. WhatsApp</label>
            <input type="tel" 
                   name="phone" 
                   required
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-primary focus:ring-0">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Doa/Dukungan (Opsional)</label>
            <textarea name="message" 
                      rows="3"
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-primary focus:ring-0"></textarea>
        </div>
    </div>

    <!-- Submit Button -->
    <button type="submit" 
            class="w-full bg-primary text-white font-bold py-4 px-6 rounded-lg hover:bg-primary-dark transition-colors duration-200">
        Lanjutkan Pembayaran
    </button>
</form>

<script>
jQuery(document).ready(function($) {
    // Handle amount selection
    $('.amount-option').on('click', function() {
        // Remove active state from all buttons
        $('.amount-option').removeClass('border-primary bg-primary/5');
        
        // Add active state to clicked button
        $(this).addClass('border-primary bg-primary/5');
        
        // Set amount in custom input
        $('input[name="custom_amount"]').val($(this).data('amount'));
    });

    // Handle custom amount input
    $('input[name="custom_amount"]').on('focus', function() {
        // Remove active state from all buttons
        $('.amount-option').removeClass('border-primary bg-primary/5');
    });
});
</script> 