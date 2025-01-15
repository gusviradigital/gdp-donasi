<?php
/**
 * Donation Success Page Template
 * 
 * @package GusviraDigital
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get donation data
$donation_id = isset($_GET['donation_id']) ? absint($_GET['donation_id']) : 0;
$donation = $donation_id ? gdp_donation()->get($donation_id) : null;
?>

<div class="gdp-donation-success">
    <div class="success-content">
        <!-- Success Icon -->
        <div class="success-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="64" height="64">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
        </div>

        <!-- Success Message -->
        <h1><?php esc_html_e('Terima Kasih!', 'gusviradigital'); ?></h1>
        
        <?php if ($donation): ?>
            <p class="success-description">
                <?php 
                printf(
                    /* translators: %s: donation amount */
                    esc_html__('Donasi sebesar %s telah kami terima.', 'gusviradigital'),
                    gdp_format_rupiah($donation->amount)
                );
                ?>
            </p>

            <!-- Donation Details -->
            <div class="donation-details">
                <h2><?php esc_html_e('Detail Donasi', 'gusviradigital'); ?></h2>
                
                <div class="detail-item">
                    <span class="label"><?php esc_html_e('ID Donasi', 'gusviradigital'); ?></span>
                    <span class="value">#<?php echo esc_html($donation->id); ?></span>
                </div>

                <div class="detail-item">
                    <span class="label"><?php esc_html_e('Program', 'gusviradigital'); ?></span>
                    <span class="value"><?php echo esc_html(get_the_title($donation->program_id)); ?></span>
                </div>

                <div class="detail-item">
                    <span class="label"><?php esc_html_e('Nama', 'gusviradigital'); ?></span>
                    <span class="value">
                        <?php echo $donation->is_anonymous 
                            ? esc_html__('Hamba Allah', 'gusviradigital')
                            : esc_html($donation->name); ?>
                    </span>
                </div>

                <div class="detail-item">
                    <span class="label"><?php esc_html_e('Status', 'gusviradigital'); ?></span>
                    <span class="value status-<?php echo esc_attr($donation->payment_status); ?>">
                        <?php echo gdp_get_donation_status_label($donation->payment_status); ?>
                    </span>
                </div>

                <div class="detail-item">
                    <span class="label"><?php esc_html_e('Metode Pembayaran', 'gusviradigital'); ?></span>
                    <span class="value"><?php echo esc_html($donation->payment_method); ?></span>
                </div>

                <div class="detail-item">
                    <span class="label"><?php esc_html_e('Tanggal', 'gusviradigital'); ?></span>
                    <span class="value"><?php echo esc_html($donation->created_at); ?></span>
                </div>
            </div>

            <?php if ($donation->payment_status === 'pending'): ?>
                <!-- Payment Instructions -->
                <div class="payment-instructions">
                    <h2><?php esc_html_e('Instruksi Pembayaran', 'gusviradigital'); ?></h2>
                    <p><?php esc_html_e('Silakan selesaikan pembayaran Anda sesuai dengan metode yang dipilih.', 'gusviradigital'); ?></p>
                    <p><?php esc_html_e('Instruksi pembayaran telah dikirim ke email Anda.', 'gusviradigital'); ?></p>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <p class="success-description">
                <?php esc_html_e('Terima kasih atas donasi Anda. Kami akan segera memproses donasi Anda.', 'gusviradigital'); ?>
            </p>
        <?php endif; ?>

        <!-- Action Buttons -->
        <div class="success-actions">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="button home-button">
                <?php esc_html_e('Kembali ke Beranda', 'gusviradigital'); ?>
            </a>
            <a href="<?php echo esc_url(get_post_type_archive_link('program')); ?>" class="button programs-button">
                <?php esc_html_e('Lihat Program Lainnya', 'gusviradigital'); ?>
            </a>
        </div>
    </div>
</div>

<style>
.gdp-donation-success {
    max-width: 600px;
    margin: 4rem auto;
    padding: 2rem;
    text-align: center;
}

.success-icon {
    color: #10b981;
    margin-bottom: 1.5rem;
}

.success-content h1 {
    margin: 0 0 1rem;
    font-size: 2rem;
    color: #1a1a1a;
}

.success-description {
    font-size: 1.125rem;
    color: #4b5563;
    margin-bottom: 2rem;
}

.donation-details {
    text-align: left;
    background: #fff;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

.donation-details h2 {
    margin: 0 0 1rem;
    font-size: 1.25rem;
    color: #1a1a1a;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e5e7eb;
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-item .label {
    color: #6b7280;
}

.detail-item .value {
    font-weight: 500;
    color: #1a1a1a;
}

.status-pending {
    color: #eab308;
}

.status-completed {
    color: #10b981;
}

.status-failed {
    color: #ef4444;
}

.payment-instructions {
    text-align: left;
    background: #fff;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

.payment-instructions h2 {
    margin: 0 0 1rem;
    font-size: 1.25rem;
    color: #1a1a1a;
}

.payment-instructions p {
    margin: 0 0 0.5rem;
    color: #4b5563;
}

.success-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.button {
    display: inline-block;
    padding: 0.75rem 1.5rem;
    border-radius: 6px;
    font-weight: 500;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.home-button {
    background: #e5e7eb;
    color: #1f2937;
}

.home-button:hover {
    background: #d1d5db;
}

.programs-button {
    background: #2563eb;
    color: #fff;
}

.programs-button:hover {
    background: #1d4ed8;
}

@media (max-width: 640px) {
    .gdp-donation-success {
        margin: 2rem auto;
        padding: 1rem;
    }

    .success-actions {
        flex-direction: column;
    }

    .button {
        width: 100%;
        text-align: center;
    }
}
</style> 