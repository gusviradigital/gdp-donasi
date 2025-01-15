<?php
/**
 * Template part for displaying donation form with card style
 * 
 * @package GusviraDigital
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

$program_id = get_the_ID();
$suggested_amounts = get_post_meta($program_id, '_suggested_amounts', true);
if (empty($suggested_amounts)) {
    $suggested_amounts = [50000, 100000, 250000, 500000];
}
?>

<form id="gdp-donation-form" class="gdp-donation-form">
    <?php wp_nonce_field('gdp_donation_nonce', 'donation_nonce'); ?>
    <input type="hidden" name="action" value="gdp_create_donation">
    <input type="hidden" name="program_id" value="<?php echo esc_attr($program_id); ?>">

    <!-- Suggested Amounts -->
    <div class="grid grid-cols-2 gap-4 mb-6">
        <?php foreach ($suggested_amounts as $amount) : ?>
            <button type="button" class="amount-option bg-white border-2 border-gray-200 rounded-lg p-4 text-center hover:border-primary focus:outline-none focus:border-primary transition-colors" data-amount="<?php echo esc_attr($amount); ?>">
                <span class="block font-semibold text-gray-900"><?php echo gdp_format_rupiah($amount); ?></span>
            </button>
        <?php endforeach; ?>
    </div>

    <!-- Custom Amount -->
    <div class="mb-6">
        <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
            <input type="text" name="amount" id="donation-amount" class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-primary transition-colors" placeholder="Nominal Lainnya" inputmode="numeric">
        </div>
    </div>

    <!-- Personal Info -->
    <div class="space-y-4 mb-6">
        <div>
            <label for="donor-name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" name="name" id="donor-name" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-primary transition-colors" required>
        </div>
        <div>
            <label for="donor-email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" id="donor-email" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-primary transition-colors" required>
        </div>
        <div>
            <label for="donor-phone" class="block text-sm font-medium text-gray-700 mb-1">No. WhatsApp</label>
            <input type="tel" name="phone" id="donor-phone" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-primary transition-colors" required>
        </div>
        <div>
            <label for="donor-message" class="block text-sm font-medium text-gray-700 mb-1">Doa/Dukungan (Opsional)</label>
            <textarea name="message" id="donor-message" rows="3" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-primary transition-colors"></textarea>
        </div>
        <div class="flex items-center">
            <input type="checkbox" name="is_anonymous" id="is-anonymous" class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
            <label for="is-anonymous" class="ml-2 text-sm text-gray-700">Sembunyikan nama saya (Hamba Allah)</label>
        </div>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="gdp-button gdp-button-primary w-full" id="donation-submit">
        Lanjutkan Pembayaran
    </button>
</form>

<script>
jQuery(document).ready(function($) {
    // Format rupiah
    function formatRupiah(angka) {
        var number_string = angka.toString().replace(/[^,\d]/g, ''),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        return 'Rp ' + rupiah;
    }

    // Handle amount option selection
    $('.amount-option').on('click', function() {
        $('.amount-option').removeClass('border-primary');
        $(this).addClass('border-primary');
        
        var amount = $(this).data('amount');
        $('#donation-amount').val(formatRupiah(amount));
    });

    // Format custom amount input
    $('#donation-amount').on('input', function() {
        var value = $(this).val().replace(/[^0-9]/g, '');
        if (value) {
            $(this).val(formatRupiah(value));
            $('.amount-option').removeClass('border-primary');
        }
    });

    // Handle form submission
    $('#gdp-donation-form').on('submit', function(e) {
        e.preventDefault();

        var $form = $(this);
        var $submit = $('#donation-submit');
        var amount = $('#donation-amount').val().replace(/[^0-9]/g, '');

        if (!amount || amount < 1000) {
            alert('Minimal donasi Rp 1.000');
            return;
        }

        $submit.prop('disabled', true).text('Memproses...');

        var formData = new FormData(this);
        formData.set('amount', amount);
        formData.append('nonce', '<?php echo wp_create_nonce("gdp_donation_nonce"); ?>');

        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Create payment
                    $.ajax({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        type: 'POST',
                        data: { 
                            action: 'gdp_create_payment',
                            donation_id: response.data.donation_id,
                            payment_method: 'midtrans',
                            nonce: '<?php echo wp_create_nonce("gdp_payment_nonce"); ?>'
                        },
                        success: function(response) {
                            if (response.success) {
                                // Redirect to payment page
                                window.location.href = response.data.checkout_url;
                            } else {
                                alert(response.data.message || 'Terjadi kesalahan saat memproses pembayaran');
                                $submit.prop('disabled', false).text('Lanjutkan Pembayaran');
                            }
                        },
                        error: function() {
                            alert('Terjadi kesalahan saat memproses pembayaran');
                            $submit.prop('disabled', false).text('Lanjutkan Pembayaran');
                        }
                    });
                } else {
                    alert(response.data.message || 'Terjadi kesalahan saat membuat donasi');
                    $submit.prop('disabled', false).text('Lanjutkan Pembayaran');
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat membuat donasi');
                $submit.prop('disabled', false).text('Lanjutkan Pembayaran');
            }
        });
    });
});
</script> 