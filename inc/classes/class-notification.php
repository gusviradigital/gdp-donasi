<?php
/**
 * Notification Class
 * 
 * @package GusviraDigital
 * @version 1.0.0
 */

namespace GDP\Classes;

if (!defined('ABSPATH')) {
    exit;
}

class Notification {
    /**
     * Instance
     */
    private static $instance = null;

    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Send notifications when donation status changes
        add_action('gdp_donation_status_changed', [$this, 'send_status_notifications'], 10, 3);
    }

    /**
     * Send status notifications
     */
    public function send_status_notifications($donation_id, $new_status, $old_status) {
        $donation = gdp_donation()->get($donation_id);
        if (!$donation) {
            return;
        }

        // Send email notifications
        $this->send_donor_email($donation, $new_status);
        $this->send_admin_email($donation, $new_status);

        // Send WhatsApp notifications if enabled
        if (gdp_get_option('enable_whatsapp_notification') === 'yes') {
            $this->send_donor_whatsapp($donation, $new_status);
            $this->send_admin_whatsapp($donation, $new_status);
        }
    }

    /**
     * Send donor email
     */
    private function send_donor_email($donation, $status) {
        // Get email template based on status
        $subject = $this->get_donor_email_subject($donation, $status);
        $message = $this->get_donor_email_message($donation, $status);

        // Send email
        $headers = ['Content-Type: text/html; charset=UTF-8'];
        wp_mail($donation->email, $subject, $message, $headers);
    }

    /**
     * Send admin email
     */
    private function send_admin_email($donation, $status) {
        // Get admin email
        $admin_email = gdp_get_option('admin_email');
        if (!$admin_email) {
            $admin_email = get_option('admin_email');
        }

        // Get email template
        $subject = $this->get_admin_email_subject($donation, $status);
        $message = $this->get_admin_email_message($donation, $status);

        // Send email
        $headers = ['Content-Type: text/html; charset=UTF-8'];
        wp_mail($admin_email, $subject, $message, $headers);
    }

    /**
     * Send donor WhatsApp
     */
    private function send_donor_whatsapp($donation, $status) {
        if (!$donation->phone) {
            return;
        }

        // Get message template
        $message = $this->get_donor_whatsapp_message($donation, $status);

        // Send WhatsApp
        $this->send_whatsapp($donation->phone, $message);
    }

    /**
     * Send admin WhatsApp
     */
    private function send_admin_whatsapp($donation, $status) {
        // Get admin phone
        $admin_phone = gdp_get_option('admin_phone');
        if (!$admin_phone) {
            return;
        }

        // Get message template
        $message = $this->get_admin_whatsapp_message($donation, $status);

        // Send WhatsApp
        $this->send_whatsapp($admin_phone, $message);
    }

    /**
     * Send WhatsApp message
     */
    private function send_whatsapp($phone, $message) {
        // Format phone number
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        // Get API settings
        $api_url = gdp_get_option('whatsapp_api_url');
        $api_key = gdp_get_option('whatsapp_api_key');

        if (!$api_url || !$api_key) {
            return;
        }

        // Send request to WhatsApp API
        wp_remote_post($api_url, [
            'headers' => [
                'Authorization' => $api_key,
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode([
                'phone' => $phone,
                'message' => $message,
            ]),
        ]);
    }

    /**
     * Get donor email subject
     */
    private function get_donor_email_subject($donation, $status) {
        $program_title = get_the_title($donation->program_id);

        switch ($status) {
            case 'pending':
                return sprintf(
                    /* translators: %s: program title */
                    __('[%s] Menunggu Pembayaran', 'gusviradigital'),
                    $program_title
                );

            case 'processing':
                return sprintf(
                    /* translators: %s: program title */
                    __('[%s] Pembayaran Sedang Diproses', 'gusviradigital'),
                    $program_title
                );

            case 'completed':
                return sprintf(
                    /* translators: %s: program title */
                    __('[%s] Pembayaran Berhasil', 'gusviradigital'),
                    $program_title
                );

            case 'failed':
                return sprintf(
                    /* translators: %s: program title */
                    __('[%s] Pembayaran Gagal', 'gusviradigital'),
                    $program_title
                );

            default:
                return sprintf(
                    /* translators: %s: program title */
                    __('[%s] Status Donasi Diperbarui', 'gusviradigital'),
                    $program_title
                );
        }
    }

    /**
     * Get donor email message
     */
    private function get_donor_email_message($donation, $status) {
        ob_start();

        // Get program data
        $program_title = get_the_title($donation->program_id);
        $program_url = get_permalink($donation->program_id);

        // Get donor name
        $donor_name = $donation->is_anonymous 
            ? __('Hamba Allah', 'gusviradigital')
            : $donation->name;
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
        </head>
        <body style="font-family: sans-serif; line-height: 1.5; color: #1a1a1a; margin: 0; padding: 0;">
            <div style="max-width: 600px; margin: 0 auto; padding: 2rem;">
                <!-- Header -->
                <div style="text-align: center; margin-bottom: 2rem;">
                    <img src="<?php echo esc_url(gdp_get_option('email_logo')); ?>" alt="Logo" style="max-width: 200px;">
                </div>

                <!-- Content -->
                <div style="background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <!-- Greeting -->
                    <p style="margin: 0 0 1rem;">
                        <?php 
                        printf(
                            /* translators: %s: donor name */
                            esc_html__('Assalamu\'alaikum %s,', 'gusviradigital'),
                            esc_html($donor_name)
                        );
                        ?>
                    </p>

                    <?php if ($status === 'pending'): ?>
                        <!-- Pending Payment -->
                        <p style="margin: 0 0 1rem;">
                            <?php
                            printf(
                                /* translators: 1: program title, 2: donation amount */
                                esc_html__('Terima kasih telah berdonasi untuk program "%1$s" sebesar %2$s.', 'gusviradigital'),
                                esc_html($program_title),
                                gdp_format_rupiah($donation->amount)
                            );
                            ?>
                        </p>
                        <p style="margin: 0 0 1rem;">
                            <?php esc_html_e('Silakan selesaikan pembayaran Anda sesuai dengan metode yang dipilih.', 'gusviradigital'); ?>
                        </p>
                        <p style="margin: 0 0 1rem;">
                            <?php esc_html_e('Detail pembayaran:', 'gusviradigital'); ?>
                        </p>
                        <div style="background: #f9fafb; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
                            <p style="margin: 0 0 0.5rem;">
                                <strong><?php esc_html_e('ID Donasi:', 'gusviradigital'); ?></strong>
                                #<?php echo esc_html($donation->id); ?>
                            </p>
                            <p style="margin: 0 0 0.5rem;">
                                <strong><?php esc_html_e('Jumlah:', 'gusviradigital'); ?></strong>
                                <?php echo gdp_format_rupiah($donation->amount); ?>
                            </p>
                            <p style="margin: 0;">
                                <strong><?php esc_html_e('Metode Pembayaran:', 'gusviradigital'); ?></strong>
                                <?php echo esc_html($donation->payment_method); ?>
                            </p>
                        </div>

                    <?php elseif ($status === 'processing'): ?>
                        <!-- Processing Payment -->
                        <p style="margin: 0 0 1rem;">
                            <?php esc_html_e('Pembayaran Anda sedang diproses. Kami akan segera mengkonfirmasi donasi Anda.', 'gusviradigital'); ?>
                        </p>

                    <?php elseif ($status === 'completed'): ?>
                        <!-- Completed Payment -->
                        <p style="margin: 0 0 1rem;">
                            <?php
                            printf(
                                /* translators: 1: program title, 2: donation amount */
                                esc_html__('Alhamdulillah, pembayaran donasi Anda untuk program "%1$s" sebesar %2$s telah berhasil.', 'gusviradigital'),
                                esc_html($program_title),
                                gdp_format_rupiah($donation->amount)
                            );
                            ?>
                        </p>
                        <p style="margin: 0 0 1rem;">
                            <?php esc_html_e('Semoga Allah membalas kebaikan Anda dengan berlipat ganda. Aamiin.', 'gusviradigital'); ?>
                        </p>

                    <?php elseif ($status === 'failed'): ?>
                        <!-- Failed Payment -->
                        <p style="margin: 0 0 1rem;">
                            <?php esc_html_e('Mohon maaf, pembayaran donasi Anda tidak berhasil diproses.', 'gusviradigital'); ?>
                        </p>
                        <p style="margin: 0 0 1rem;">
                            <?php esc_html_e('Silakan coba lagi atau hubungi tim support kami jika Anda membutuhkan bantuan.', 'gusviradigital'); ?>
                        </p>

                    <?php endif; ?>

                    <!-- Program Link -->
                    <p style="margin: 2rem 0;">
                        <a href="<?php echo esc_url($program_url); ?>" style="display: inline-block; padding: 0.75rem 1.5rem; background: #2563eb; color: #fff; text-decoration: none; border-radius: 6px;">
                            <?php esc_html_e('Lihat Program', 'gusviradigital'); ?>
                        </a>
                    </p>

                    <!-- Footer -->
                    <p style="margin: 0; color: #6b7280; font-size: 0.875rem;">
                        <?php esc_html_e('Email ini dikirim otomatis, mohon tidak membalas email ini.', 'gusviradigital'); ?>
                    </p>
                </div>

                <!-- Contact -->
                <div style="text-align: center; margin-top: 2rem; color: #6b7280; font-size: 0.875rem;">
                    <p style="margin: 0 0 0.5rem;">
                        <?php esc_html_e('Butuh bantuan? Silakan hubungi tim support kami:', 'gusviradigital'); ?>
                    </p>
                    <p style="margin: 0;">
                        <a href="https://wa.me/<?php echo esc_attr(gdp_get_option('whatsapp')); ?>" style="color: #2563eb; text-decoration: none;">
                            <?php esc_html_e('WhatsApp Support', 'gusviradigital'); ?>
                        </a>
                    </p>
                </div>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }

    /**
     * Get admin email subject
     */
    private function get_admin_email_subject($donation, $status) {
        $program_title = get_the_title($donation->program_id);

        switch ($status) {
            case 'pending':
                return sprintf(
                    /* translators: 1: program title, 2: donation amount */
                    __('[Donasi Baru] %1$s - %2$s', 'gusviradigital'),
                    $program_title,
                    gdp_format_rupiah($donation->amount)
                );

            case 'completed':
                return sprintf(
                    /* translators: 1: program title, 2: donation amount */
                    __('[Donasi Berhasil] %1$s - %2$s', 'gusviradigital'),
                    $program_title,
                    gdp_format_rupiah($donation->amount)
                );

            default:
                return sprintf(
                    /* translators: 1: program title, 2: status */
                    __('[Donasi] %1$s - %2$s', 'gusviradigital'),
                    $program_title,
                    gdp_get_donation_status_label($status)
                );
        }
    }

    /**
     * Get admin email message
     */
    private function get_admin_email_message($donation, $status) {
        ob_start();

        // Get program data
        $program_title = get_the_title($donation->program_id);
        $program_url = get_permalink($donation->program_id);

        // Get donor name
        $donor_name = $donation->is_anonymous 
            ? __('Hamba Allah', 'gusviradigital')
            : $donation->name;
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
        </head>
        <body style="font-family: sans-serif; line-height: 1.5; color: #1a1a1a; margin: 0; padding: 0;">
            <div style="max-width: 600px; margin: 0 auto; padding: 2rem;">
                <!-- Content -->
                <div style="background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <!-- Status -->
                    <p style="margin: 0 0 1rem;">
                        <?php
                        if ($status === 'pending') {
                            esc_html_e('Ada donasi baru yang menunggu pembayaran.', 'gusviradigital');
                        } elseif ($status === 'completed') {
                            esc_html_e('Ada donasi yang berhasil dibayarkan.', 'gusviradigital');
                        } else {
                            printf(
                                /* translators: %s: status */
                                esc_html__('Status donasi diperbarui menjadi: %s', 'gusviradigital'),
                                gdp_get_donation_status_label($status)
                            );
                        }
                        ?>
                    </p>

                    <!-- Donation Details -->
                    <div style="background: #f9fafb; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
                        <p style="margin: 0 0 0.5rem;">
                            <strong><?php esc_html_e('ID Donasi:', 'gusviradigital'); ?></strong>
                            #<?php echo esc_html($donation->id); ?>
                        </p>
                        <p style="margin: 0 0 0.5rem;">
                            <strong><?php esc_html_e('Program:', 'gusviradigital'); ?></strong>
                            <?php echo esc_html($program_title); ?>
                        </p>
                        <p style="margin: 0 0 0.5rem;">
                            <strong><?php esc_html_e('Nama:', 'gusviradigital'); ?></strong>
                            <?php echo esc_html($donor_name); ?>
                        </p>
                        <p style="margin: 0 0 0.5rem;">
                            <strong><?php esc_html_e('Email:', 'gusviradigital'); ?></strong>
                            <?php echo esc_html($donation->email); ?>
                        </p>
                        <p style="margin: 0 0 0.5rem;">
                            <strong><?php esc_html_e('No. WhatsApp:', 'gusviradigital'); ?></strong>
                            <?php echo esc_html($donation->phone); ?>
                        </p>
                        <p style="margin: 0 0 0.5rem;">
                            <strong><?php esc_html_e('Jumlah:', 'gusviradigital'); ?></strong>
                            <?php echo gdp_format_rupiah($donation->amount); ?>
                        </p>
                        <p style="margin: 0 0 0.5rem;">
                            <strong><?php esc_html_e('Metode Pembayaran:', 'gusviradigital'); ?></strong>
                            <?php echo esc_html($donation->payment_method); ?>
                        </p>
                        <p style="margin: 0;">
                            <strong><?php esc_html_e('Tanggal:', 'gusviradigital'); ?></strong>
                            <?php echo esc_html($donation->created_at); ?>
                        </p>
                    </div>

                    <!-- Program Link -->
                    <p style="margin: 2rem 0;">
                        <a href="<?php echo esc_url($program_url); ?>" style="display: inline-block; padding: 0.75rem 1.5rem; background: #2563eb; color: #fff; text-decoration: none; border-radius: 6px;">
                            <?php esc_html_e('Lihat Program', 'gusviradigital'); ?>
                        </a>
                    </p>
                </div>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }

    /**
     * Get donor WhatsApp message
     */
    private function get_donor_whatsapp_message($donation, $status) {
        $program_title = get_the_title($donation->program_id);
        $donor_name = $donation->is_anonymous 
            ? __('Hamba Allah', 'gusviradigital')
            : $donation->name;

        $message = sprintf(
            /* translators: %s: donor name */
            __("Assalamu'alaikum %s,\n\n", 'gusviradigital'),
            $donor_name
        );

        switch ($status) {
            case 'pending':
                $message .= sprintf(
                    /* translators: 1: program title, 2: donation amount */
                    __("Terima kasih telah berdonasi untuk program \"%1\$s\" sebesar %2\$s.\n\n", 'gusviradigital'),
                    $program_title,
                    gdp_format_rupiah($donation->amount)
                );
                $message .= __("Silakan selesaikan pembayaran Anda sesuai dengan metode yang dipilih.\n\n", 'gusviradigital');
                $message .= sprintf(
                    /* translators: 1: donation ID, 2: payment method */
                    __("Detail pembayaran:\nID Donasi: #%1\$s\nMetode Pembayaran: %2\$s\n\n", 'gusviradigital'),
                    $donation->id,
                    $donation->payment_method
                );
                break;

            case 'processing':
                $message .= __("Pembayaran Anda sedang diproses. Kami akan segera mengkonfirmasi donasi Anda.\n\n", 'gusviradigital');
                break;

            case 'completed':
                $message .= sprintf(
                    /* translators: 1: program title, 2: donation amount */
                    __("Alhamdulillah, pembayaran donasi Anda untuk program \"%1\$s\" sebesar %2\$s telah berhasil.\n\n", 'gusviradigital'),
                    $program_title,
                    gdp_format_rupiah($donation->amount)
                );
                $message .= __("Semoga Allah membalas kebaikan Anda dengan berlipat ganda. Aamiin.\n\n", 'gusviradigital');
                break;

            case 'failed':
                $message .= __("Mohon maaf, pembayaran donasi Anda tidak berhasil diproses.\n\n", 'gusviradigital');
                $message .= __("Silakan coba lagi atau hubungi tim support kami jika Anda membutuhkan bantuan.\n\n", 'gusviradigital');
                break;
        }

        return $message;
    }

    /**
     * Get admin WhatsApp message
     */
    private function get_admin_whatsapp_message($donation, $status) {
        $program_title = get_the_title($donation->program_id);
        $donor_name = $donation->is_anonymous 
            ? __('Hamba Allah', 'gusviradigital')
            : $donation->name;

        if ($status === 'pending') {
            $message = __("Ada donasi baru yang menunggu pembayaran.\n\n", 'gusviradigital');
        } elseif ($status === 'completed') {
            $message = __("Ada donasi yang berhasil dibayarkan.\n\n", 'gusviradigital');
        } else {
            $message = sprintf(
                /* translators: %s: status */
                __("Status donasi diperbarui menjadi: %s\n\n", 'gusviradigital'),
                gdp_get_donation_status_label($status)
            );
        }

        $message .= sprintf(
            /* translators: 1: donation ID, 2: program title, 3: donor name, 4: donation amount, 5: payment method */
            __("Detail donasi:\nID: #%1\$s\nProgram: %2\$s\nNama: %3\$s\nJumlah: %4\$s\nMetode: %5\$s\n\n", 'gusviradigital'),
            $donation->id,
            $program_title,
            $donor_name,
            gdp_format_rupiah($donation->amount),
            $donation->payment_method
        );

        return $message;
    }
}

// Initialize
Notification::get_instance(); 