<?php
/**
 * Donation Form - Packaged Style
 *
 * @package GusviraDigital
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$package_amount = get_post_meta(get_the_ID(), '_package_amount', true);
$package_title = get_post_meta(get_the_ID(), '_package_title', true);

// Default values
if (!$package_amount) $package_amount = [50000, 100000, 250000, 500000];
if (!$package_title) $package_title = ['Paket Hemat', 'Paket Regular', 'Paket Premium', 'Paket VIP'];
?>

<form class="donation-form" method="post">
    <!-- Package Selection -->
    <div class="space-y-3 mb-6">
        <?php foreach ($package_amount as $index => $amount): ?>
            <label class="block">
                <input type="radio" 
                       name="package" 
                       value="<?php echo esc_attr($amount); ?>"
                       class="hidden peer"
                       <?php echo $index === 0 ? 'checked' : ''; ?>>
                <div class="p-4 border-2 border-gray-200 rounded-lg cursor-pointer 
                            peer-checked:border-primary peer-checked:bg-primary/5 
                            hover:border-primary hover:bg-primary/5 transition-all duration-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">
                                <?php echo esc_html($package_title[$index]); ?>
                            </h3>
                            <p class="text-sm text-gray-500">
                                <?php echo sprintf(__('Donasi sebesar %s', 'gusviradigital'), 
                                    'Rp ' . number_format($amount, 0, ',', '.')); ?>
                            </p>
                        </div>
                        <div class="w-5 h-5 rounded-full border-2 border-gray-300 
                                  peer-checked:border-primary peer-checked:bg-primary 
                                  flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" viewBox="0 0 12 12" fill="none">
                                <path d="M10 3L4.5 8.5L2 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </label>
        <?php endforeach; ?>
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