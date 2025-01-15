<?php
/**
 * Donation Form - Typing Style
 *
 * @package GusviraDigital
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?>

<form class="donation-form" method="post">
    <!-- Amount Input -->
    <div class="mb-6">
        <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-2xl font-bold text-gray-900">Rp</span>
            <input type="text" 
                   name="amount" 
                   class="w-full pl-16 pr-4 py-6 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-0 text-3xl font-bold text-gray-900 text-right"
                   placeholder="0"
                   required
                   pattern="[0-9]*"
                   inputmode="numeric">
        </div>
        <p class="mt-2 text-sm text-gray-500 text-right amount-in-words"></p>
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

    function terbilang(bilangan) {
        bilangan = String(bilangan);
        var angka = new Array('0','1','2','3','4','5','6','7','8','9','10','11');
        var kata = new Array('','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan','Sepuluh','Sebelas');
        var tingkat = new Array('','Ribu','Juta','Milyar','Triliun');

        var panjang_bilangan = bilangan.length;
        var pemisah = '';
        var i = 0;
        var j = 0;
        var kaLimat = '';
        var kata1 = '';
        var kata2 = '';
        var kata3 = '';
        var subkaLimat = '';
        var kata_sempurna = '';

        while (i < panjang_bilangan) {
            subkaLimat = '';
            kata1 = '';
            kata2 = '';
            kata3 = '';

            var angka1 = parseFloat(bilangan.substr(-(i+3),3));

            if (angka1 != '0'){
                ka = Math.floor(angka1/100);
                kb = Math.floor((angka1%100)/10);
                kc = angka1%10;

                if (ka > 0){
                    if(ka == 1){kata1 = 'Seratus ';}
                    else {kata1 = kata[ka]+' Ratus ';}
                }

                if (kb > 0){
                    if(kb == 1){
                        if(kc == 0){kata2 = 'Sepuluh ';}
                        else if(kc == 1){kata2 = 'Sebelas ';}
                        else {kata2 = kata[kc]+' Belas ';}
                    }
                    else {
                        kata2 = kata[kb]+' Puluh ';
                    }
                }

                if (kc > 0){
                    if(kb != 1){
                        kata3 = kata[kc];
                    }
                }

                subkaLimat = kata1+kata2+kata3+tingkat[j]+' ';
                kaLimat = subkaLimat+kaLimat;
            }

            i = i + 3;
            j = j + 1;
        }

        if(kaLimat.length > 0){
            kaLimat = kaLimat.replace(/Satu Ribu/gi,'Seribu');
            return kaLimat + 'Rupiah';
        } else {
            return 'Nol Rupiah';
        }
    }

    // Handle amount input
    $('input[name="amount"]').on('input', function() {
        var value = $(this).val().replace(/[^0-9]/g, '');
        if(value) {
            $(this).val(formatRupiah(value));
            $('.amount-in-words').text(terbilang(value));
        } else {
            $('.amount-in-words').text('');
        }
    });
});
</script> 