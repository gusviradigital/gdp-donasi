<?php
/**
 * Zakat Form
 *
 * @package GusviraDigital
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$zakat_type = get_post_meta(get_the_ID(), '_zakat_type', true);
$zakat_percent = get_post_meta(get_the_ID(), '_zakat_percent', true);
$zakat_custom_percent = get_post_meta(get_the_ID(), '_zakat_custom_percent', true);
$zakat_expense = get_post_meta(get_the_ID(), '_zakat_expense', true);
$zakat_expense_title = get_post_meta(get_the_ID(), '_zakat_expense_title', true);

// Default values
if (!$zakat_type) $zakat_type = 'penghasilan';
if (!$zakat_percent) $zakat_percent = 'default';
if (!$zakat_custom_percent) $zakat_custom_percent = '2.5';
?>

<form class="zakat-form" method="post">
    <!-- Zakat Type -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Zakat</label>
        <div class="grid grid-cols-3 gap-2">
            <label class="relative">
                <input type="radio" 
                       name="zakat_type" 
                       value="penghasilan" 
                       class="hidden peer"
                       <?php checked($zakat_type, 'penghasilan'); ?>>
                <div class="text-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer
                            peer-checked:border-primary peer-checked:bg-primary/5
                            hover:border-primary hover:bg-primary/5 transition-all duration-200">
                    <span class="text-sm font-medium">Penghasilan</span>
                </div>
            </label>
            <label class="relative">
                <input type="radio" 
                       name="zakat_type" 
                       value="maal" 
                       class="hidden peer"
                       <?php checked($zakat_type, 'maal'); ?>>
                <div class="text-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer
                            peer-checked:border-primary peer-checked:bg-primary/5
                            hover:border-primary hover:bg-primary/5 transition-all duration-200">
                    <span class="text-sm font-medium">Maal</span>
                </div>
            </label>
            <label class="relative">
                <input type="radio" 
                       name="zakat_type" 
                       value="fitrah" 
                       class="hidden peer"
                       <?php checked($zakat_type, 'fitrah'); ?>>
                <div class="text-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer
                            peer-checked:border-primary peer-checked:bg-primary/5
                            hover:border-primary hover:bg-primary/5 transition-all duration-200">
                    <span class="text-sm font-medium">Fitrah</span>
                </div>
            </label>
        </div>
    </div>

    <!-- Amount Input -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">Harta/Penghasilan</label>
        <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
            <input type="text" 
                   name="amount" 
                   class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-0 text-lg font-bold text-gray-900"
                   placeholder="0"
                   required
                   pattern="[0-9]*"
                   inputmode="numeric">
        </div>
    </div>

    <!-- Zakat Percent -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">Persentase Zakat</label>
        <div class="space-y-2">
            <label class="flex items-center">
                <input type="radio" 
                       name="zakat_percent" 
                       value="default"
                       class="w-4 h-4 text-primary border-gray-300 focus:ring-primary"
                       <?php checked($zakat_percent, 'default'); ?>>
                <span class="ml-2 text-sm">2.5% (Standar)</span>
            </label>
            <label class="flex items-center">
                <input type="radio" 
                       name="zakat_percent" 
                       value="custom"
                       class="w-4 h-4 text-primary border-gray-300 focus:ring-primary"
                       <?php checked($zakat_percent, 'custom'); ?>>
                <span class="ml-2 text-sm">Kustom</span>
            </label>
            <div class="custom-percent-input ml-6" style="display: <?php echo $zakat_percent === 'custom' ? 'block' : 'none'; ?>">
                <div class="relative w-24">
                    <input type="number" 
                           name="custom_percent" 
                           value="<?php echo esc_attr($zakat_custom_percent); ?>"
                           class="w-full pr-8 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring-0"
                           step="0.1"
                           min="0"
                           max="100">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500">%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Zakat Amount -->
    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
        <div class="flex justify-between items-center">
            <span class="text-sm text-gray-600">Jumlah Zakat</span>
            <span class="text-lg font-bold text-primary zakat-amount">Rp 0</span>
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
    function formatRupiah(angka) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        return split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    }

    function calculateZakat() {
        var amount = $('input[name="amount"]').val().replace(/[^0-9]/g, '');
        var percent = $('input[name="zakat_percent"]:checked').val() === 'default' 
            ? 2.5 
            : parseFloat($('input[name="custom_percent"]').val());
        
        if (amount && percent) {
            var zakatAmount = (amount * percent) / 100;
            $('.zakat-amount').text('Rp ' + formatRupiah(zakatAmount.toString()));
        } else {
            $('.zakat-amount').text('Rp 0');
        }
    }

    // Handle amount input
    $('input[name="amount"]').on('input', function() {
        var value = $(this).val().replace(/[^0-9]/g, '');
        if(value) {
            $(this).val(formatRupiah(value));
            calculateZakat();
        }
    });

    // Handle percent change
    $('input[name="zakat_percent"]').on('change', function() {
        if ($(this).val() === 'custom') {
            $('.custom-percent-input').show();
        } else {
            $('.custom-percent-input').hide();
        }
        calculateZakat();
    });

    // Handle custom percent input
    $('input[name="custom_percent"]').on('input', function() {
        calculateZakat();
    });
});
</script> 