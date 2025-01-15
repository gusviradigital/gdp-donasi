<?php
/**
 * Donation Failed Page Template
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

<div class="gdp-donation-failed">
    <div class="failed-content">
        <!-- Failed Icon -->
        <div class="failed-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="64" height="64">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
            </svg>
        </div>

        <!-- Failed Message -->
        <h1><?php esc_html_e('Pembayaran Gagal', 'gusviradigital'); ?></h1>
        
        <?php if ($donation): ?>
            <p class="failed-description">
                <?php 
                printf(
                    /* translators: %s: donation amount */
                    esc_html__('Pembayaran donasi sebesar %s tidak berhasil diproses.', 'gusviradigital'),
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
                    <span class="label"><?php esc_html_e('Status', 'gusviradigital'); ?></span>
                    <span class="value status-<?php echo esc_attr($donation->payment_status); ?>">
                        <?php echo gdp_get_donation_status_label($donation->payment_status); ?>
                    </span>
                </div>

                <div class="detail-item">
                    <span class="label"><?php esc_html_e('Metode Pembayaran', 'gusviradigital'); ?></span>
                    <span class="value"><?php echo esc_html($donation->payment_method); ?></span>
                </div>
            </div>

            <!-- Error Message -->
            <div class="error-message">
                <h2><?php esc_html_e('Kenapa Pembayaran Gagal?', 'gusviradigital'); ?></h2>
                <ul>
                    <li><?php esc_html_e('Saldo tidak mencukupi', 'gusviradigital'); ?></li>
                    <li><?php esc_html_e('Transaksi ditolak oleh bank', 'gusviradigital'); ?></li>
                    <li><?php esc_html_e('Waktu pembayaran telah berakhir', 'gusviradigital'); ?></li>
                    <li><?php esc_html_e('Masalah teknis pada payment gateway', 'gusviradigital'); ?></li>
                </ul>
            </div>
        <?php else: ?>
            <p class="failed-description">
                <?php esc_html_e('Maaf, terjadi kesalahan saat memproses donasi Anda.', 'gusviradigital'); ?>
            </p>
        <?php endif; ?>

        <!-- Action Buttons -->
        <div class="failed-actions">
            <?php if ($donation): ?>
                <a href="<?php echo esc_url(get_permalink($donation->program_id)); ?>" class="button retry-button">
                    <?php esc_html_e('Coba Lagi', 'gusviradigital'); ?>
                </a>
            <?php endif; ?>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="button home-button">
                <?php esc_html_e('Kembali ke Beranda', 'gusviradigital'); ?>
            </a>
        </div>

        <!-- Contact Support -->
        <div class="contact-support">
            <p>
                <?php esc_html_e('Butuh bantuan? Silakan hubungi tim support kami:', 'gusviradigital'); ?>
                <br>
                <a href="https://wa.me/<?php echo esc_attr(gdp_get_option('whatsapp')); ?>" target="_blank" rel="noopener">
                    <?php esc_html_e('WhatsApp Support', 'gusviradigital'); ?>
                </a>
            </p>
        </div>
    </div>
</div>

<style>
.gdp-donation-failed {
    max-width: 600px;
    margin: 4rem auto;
    padding: 2rem;
    text-align: center;
}

.failed-icon {
    color: #ef4444;
    margin-bottom: 1.5rem;
}

.failed-content h1 {
    margin: 0 0 1rem;
    font-size: 2rem;
    color: #1a1a1a;
}

.failed-description {
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

.status-failed {
    color: #ef4444;
}

.error-message {
    text-align: left;
    background: #fee2e2;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
}

.error-message h2 {
    margin: 0 0 1rem;
    font-size: 1.25rem;
    color: #991b1b;
}

.error-message ul {
    margin: 0;
    padding-left: 1.5rem;
    color: #991b1b;
}

.error-message li {
    margin-bottom: 0.5rem;
}

.failed-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-bottom: 2rem;
}

.button {
    display: inline-block;
    padding: 0.75rem 1.5rem;
    border-radius: 6px;
    font-weight: 500;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.retry-button {
    background: #2563eb;
    color: #fff;
}

.retry-button:hover {
    background: #1d4ed8;
}

.home-button {
    background: #e5e7eb;
    color: #1f2937;
}

.home-button:hover {
    background: #d1d5db;
}

.contact-support {
    color: #6b7280;
}

.contact-support a {
    color: #2563eb;
    text-decoration: none;
}

.contact-support a:hover {
    text-decoration: underline;
}

@media (max-width: 640px) {
    .gdp-donation-failed {
        margin: 2rem auto;
        padding: 1rem;
    }

    .failed-actions {
        flex-direction: column;
    }

    .button {
        width: 100%;
        text-align: center;
    }
}
</style> 