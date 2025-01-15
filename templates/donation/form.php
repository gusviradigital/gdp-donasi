<?php
/**
 * Donation Form Template
 * 
 * @package GusviraDigital
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get form type and style
$form_type = get_post_meta($post->ID, '_form_type', true);
$form_style = get_post_meta($post->ID, '_form_style', true);

// Get suggested amounts
$suggested_amounts = get_post_meta($post->ID, '_suggested_amounts', true);
$custom_amount = get_post_meta($post->ID, '_custom_amount', true);

// Get zakat settings if form type is zakat
$zakat_type = '';
$zakat_percent = '';
$zakat_custom_percent = '';
if ($form_type === 'zakat') {
    $zakat_type = get_post_meta($post->ID, '_zakat_type', true);
    $zakat_percent = get_post_meta($post->ID, '_zakat_percent', true);
    $zakat_custom_percent = get_post_meta($post->ID, '_zakat_custom_percent', true);
}
?>

<div class="gdp-donation-form" data-form-type="<?php echo esc_attr($form_type); ?>" data-form-style="<?php echo esc_attr($form_style); ?>">
    <form id="donation-form" method="post">
        <?php wp_nonce_field('gdp_donation_nonce', 'donation_nonce'); ?>
        <input type="hidden" name="program_id" value="<?php echo esc_attr($post->ID); ?>">
        <input type="hidden" name="form_type" value="<?php echo esc_attr($form_type); ?>">
        
        <!-- Amount Section -->
        <div class="form-section amount-section">
            <h3><?php esc_html_e('Nominal Donasi', 'gusviradigital'); ?></h3>
            
            <?php if ($form_style === 'card' && !empty($suggested_amounts)): ?>
                <!-- Card Style -->
                <div class="amount-options">
                    <?php foreach ($suggested_amounts as $amount): ?>
                        <label class="amount-option">
                            <input type="radio" name="amount" value="<?php echo esc_attr($amount); ?>">
                            <span class="amount-label"><?php echo gdp_format_rupiah($amount); ?></span>
                        </label>
                    <?php endforeach; ?>
                    
                    <?php if ($custom_amount === 'yes'): ?>
                        <label class="amount-option custom">
                            <input type="radio" name="amount" value="custom">
                            <span class="amount-label"><?php esc_html_e('Nominal Lain', 'gusviradigital'); ?></span>
                            <input type="text" name="custom_amount" class="custom-amount" placeholder="<?php esc_attr_e('Masukkan nominal', 'gusviradigital'); ?>" disabled>
                        </label>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <!-- Typing Style -->
                <div class="amount-input">
                    <input type="text" name="amount" class="amount-field" placeholder="<?php esc_attr_e('Masukkan nominal donasi', 'gusviradigital'); ?>" required>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($form_type === 'zakat'): ?>
            <!-- Zakat Section -->
            <div class="form-section zakat-section">
                <h3><?php esc_html_e('Perhitungan Zakat', 'gusviradigital'); ?></h3>
                
                <?php if ($zakat_type): ?>
                    <div class="zakat-type">
                        <label><?php esc_html_e('Jenis Zakat', 'gusviradigital'); ?></label>
                        <select name="zakat_type" required>
                            <option value="penghasilan" <?php selected($zakat_type, 'penghasilan'); ?>><?php esc_html_e('Zakat Penghasilan', 'gusviradigital'); ?></option>
                            <option value="maal" <?php selected($zakat_type, 'maal'); ?>><?php esc_html_e('Zakat Maal', 'gusviradigital'); ?></option>
                            <option value="fitrah" <?php selected($zakat_type, 'fitrah'); ?>><?php esc_html_e('Zakat Fitrah', 'gusviradigital'); ?></option>
                        </select>
                    </div>
                <?php endif; ?>

                <?php if ($zakat_percent): ?>
                    <div class="zakat-percent">
                        <label><?php esc_html_e('Persentase Zakat', 'gusviradigital'); ?></label>
                        <select name="zakat_percent" required>
                            <option value="2.5" <?php selected($zakat_percent, '2.5'); ?>>2.5%</option>
                            <?php if ($zakat_custom_percent === 'yes'): ?>
                                <option value="custom"><?php esc_html_e('Kustom', 'gusviradigital'); ?></option>
                            <?php endif; ?>
                        </select>
                        <?php if ($zakat_custom_percent === 'yes'): ?>
                            <input type="number" name="custom_percent" class="custom-percent" placeholder="<?php esc_attr_e('Masukkan persentase', 'gusviradigital'); ?>" step="0.1" min="0" max="100" disabled>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <div class="zakat-calculation">
                    <p class="calculation-result">
                        <?php esc_html_e('Zakat yang harus dibayarkan:', 'gusviradigital'); ?>
                        <span class="zakat-amount">Rp 0</span>
                    </p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Personal Info Section -->
        <div class="form-section personal-info-section">
            <h3><?php esc_html_e('Informasi Pribadi', 'gusviradigital'); ?></h3>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_anonymous" value="1">
                    <?php esc_html_e('Sembunyikan identitas saya (Hamba Allah)', 'gusviradigital'); ?>
                </label>
            </div>

            <div class="personal-fields">
                <div class="form-group">
                    <label><?php esc_html_e('Nama Lengkap', 'gusviradigital'); ?></label>
                    <input type="text" name="name" required>
                </div>

                <div class="form-group">
                    <label><?php esc_html_e('Email', 'gusviradigital'); ?></label>
                    <input type="email" name="email" required>
                </div>

                <div class="form-group">
                    <label><?php esc_html_e('No. WhatsApp', 'gusviradigital'); ?></label>
                    <input type="tel" name="phone" required>
                </div>

                <div class="form-group">
                    <label><?php esc_html_e('Pesan (Opsional)', 'gusviradigital'); ?></label>
                    <textarea name="message" rows="3"></textarea>
                </div>
            </div>
        </div>

        <!-- Payment Method Section -->
        <div class="form-section payment-section">
            <h3><?php esc_html_e('Metode Pembayaran', 'gusviradigital'); ?></h3>
            
            <?php 
            $payment_methods = gdp_get_payment_methods();
            $gateway = gdp_get_active_payment_gateway();
            
            if ($gateway && !empty($payment_methods[$gateway])): ?>
                <div class="payment-methods">
                    <?php foreach ($payment_methods[$gateway] as $method => $label): ?>
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="<?php echo esc_attr($method); ?>" required>
                            <span class="method-label"><?php echo esc_html($label); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="payment-error">
                    <?php esc_html_e('Tidak ada metode pembayaran yang tersedia.', 'gusviradigital'); ?>
                </p>
            <?php endif; ?>
        </div>

        <!-- Submit Button -->
        <div class="form-submit">
            <button type="submit" class="submit-button">
                <?php esc_html_e('Lanjutkan Pembayaran', 'gusviradigital'); ?>
            </button>
        </div>
    </form>
</div>

<style>
.gdp-donation-form {
    max-width: 600px;
    margin: 0 auto;
    padding: 2rem;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.form-section {
    margin-bottom: 2rem;
}

.form-section h3 {
    margin: 0 0 1rem;
    font-size: 1.25rem;
    color: #1a1a1a;
}

/* Amount Section */
.amount-options {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 1rem;
}

.amount-option {
    position: relative;
    display: block;
    cursor: pointer;
}

.amount-option input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.amount-label {
    display: block;
    padding: 1rem;
    text-align: center;
    background: #f3f4f6;
    border: 2px solid #e5e7eb;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.amount-option input[type="radio"]:checked + .amount-label {
    background: #2563eb;
    border-color: #2563eb;
    color: #fff;
}

.amount-option.custom {
    grid-column: 1 / -1;
}

.custom-amount {
    display: block;
    width: 100%;
    margin-top: 0.5rem;
    padding: 0.5rem;
    border: 1px solid #e5e7eb;
    border-radius: 4px;
}

.amount-input input {
    width: 100%;
    padding: 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 6px;
    font-size: 1.125rem;
}

/* Personal Info Section */
.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #4b5563;
}

.form-group input[type="text"],
.form-group input[type="email"],
.form-group input[type="tel"],
.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #e5e7eb;
    border-radius: 4px;
}

/* Payment Section */
.payment-methods {
    display: grid;
    gap: 1rem;
}

.payment-method {
    display: block;
    padding: 1rem;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    cursor: pointer;
}

.payment-method input[type="radio"] {
    margin-right: 0.5rem;
}

/* Submit Button */
.submit-button {
    width: 100%;
    padding: 1rem;
    background: #2563eb;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 1.125rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.submit-button:hover {
    background: #1d4ed8;
}

/* Loading State */
.gdp-donation-form.loading {
    opacity: 0.7;
    pointer-events: none;
}

.gdp-donation-form.loading .submit-button {
    background: #9ca3af;
}

/* Responsive */
@media (max-width: 640px) {
    .gdp-donation-form {
        padding: 1rem;
    }

    .amount-options {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    var $form = $('#donation-form');
    var $amountSection = $('.amount-section');
    var $customAmount = $('.custom-amount');
    var $customPercent = $('.custom-percent');
    var $personalFields = $('.personal-fields');
    var $submitButton = $('.submit-button');

    // Format amount input
    function formatAmount(input) {
        var value = input.value.replace(/\D/g, '');
        if (value === '') return;
        
        var formatted = new Intl.NumberFormat('id-ID').format(value);
        input.value = formatted;
    }

    // Handle amount input formatting
    $('input[name="amount"], input[name="custom_amount"]').on('input', function() {
        formatAmount(this);
    });

    // Handle custom amount radio
    $('input[name="amount"][value="custom"]').on('change', function() {
        $customAmount.prop('disabled', !this.checked);
        if (this.checked) {
            $customAmount.focus();
        }
    });

    // Handle custom percent
    $('select[name="zakat_percent"]').on('change', function() {
        $customPercent.prop('disabled', this.value !== 'custom');
        if (this.value === 'custom') {
            $customPercent.focus();
        }
    });

    // Calculate zakat
    function calculateZakat() {
        var amount = parseInt($('input[name="amount"]:checked').val() === 'custom' 
            ? $customAmount.val().replace(/\D/g, '')
            : $('input[name="amount"]:checked').val());
        
        var percent = parseFloat($('select[name="zakat_percent"]').val() === 'custom'
            ? $customPercent.val()
            : $('select[name="zakat_percent"]').val());

        if (!isNaN(amount) && !isNaN(percent)) {
            var zakat = amount * (percent / 100);
            $('.zakat-amount').text('Rp ' + new Intl.NumberFormat('id-ID').format(zakat));
        }
    }

    // Handle zakat calculation
    $('input[name="amount"], input[name="custom_amount"], select[name="zakat_percent"], input[name="custom_percent"]')
        .on('change input', calculateZakat);

    // Handle anonymous donation
    $('input[name="is_anonymous"]').on('change', function() {
        $personalFields.toggleClass('anonymous', this.checked);
        $('input[name="name"]').prop('required', !this.checked);
    });

    // Handle form submission
    $form.on('submit', function(e) {
        e.preventDefault();

        // Validate form
        if (!this.checkValidity()) {
            return;
        }

        // Get form data
        var formData = new FormData(this);
        formData.append('action', 'gdp_create_donation');
        formData.append('nonce', gdp_donation.nonce);

        // Convert amount to number
        var amount = formData.get('amount');
        if (amount === 'custom') {
            amount = formData.get('custom_amount');
        }
        formData.set('amount', amount.replace(/\D/g, ''));

        // Show loading state
        $('.gdp-donation-form').addClass('loading');
        $submitButton.prop('disabled', true);

        // Send AJAX request
        $.ajax({
            url: gdp_donation.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Handle payment gateway
                    var gateway = response.data.payment_data;
                    
                    if (gateway.snap_token) {
                        // Midtrans
                        snap.pay(gateway.snap_token, {
                            onSuccess: function(result) {
                                window.location.href = gdp_donation.success_url;
                            },
                            onPending: function(result) {
                                window.location.href = gdp_donation.success_url;
                            },
                            onError: function(result) {
                                window.location.href = gdp_donation.failed_url;
                            },
                            onClose: function() {
                                $('.gdp-donation-form').removeClass('loading');
                                $submitButton.prop('disabled', false);
                            },
                        });
                    } else if (gateway.invoice_url) {
                        // Xendit
                        window.location.href = gateway.invoice_url;
                    } else if (gateway.checkout_url) {
                        // Tripay
                        window.location.href = gateway.checkout_url;
                    }
                } else {
                    alert(response.data.message);
                    $('.gdp-donation-form').removeClass('loading');
                    $submitButton.prop('disabled', false);
                }
            },
            error: function() {
                alert('Terjadi kesalahan. Silakan coba lagi.');
                $('.gdp-donation-form').removeClass('loading');
                $submitButton.prop('disabled', false);
            }
        });
    });
});
</script> 