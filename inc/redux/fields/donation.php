<?php
/**
 * Donation Settings
 *
 * @package GusviraDigital
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

Redux::set_section(
    'gdp_options',
    [
        'title'  => __( 'Pengaturan Donasi', 'gusviradigital' ),
        'id'     => 'donation',
        'desc'   => __( 'Pengaturan untuk halaman donasi', 'gusviradigital' ),
        'icon'   => 'el el-gift',
        'fields' => [
            [
                'id'       => 'donation_page',
                'type'     => 'select',
                'title'    => __( 'Halaman Donasi', 'gusviradigital' ),
                'desc'     => __( 'Pilih halaman yang akan digunakan sebagai halaman donasi', 'gusviradigital' ),
                'data'     => 'pages',
            ],
            [
                'id'       => 'donation_currency',
                'type'     => 'text',
                'title'    => __( 'Mata Uang', 'gusviradigital' ),
                'desc'     => __( 'Mata uang yang digunakan (default: Rp)', 'gusviradigital' ),
                'default'  => 'Rp',
            ],
            [
                'id'       => 'donation_min_amount',
                'type'     => 'spinner',
                'title'    => __( 'Minimal Donasi', 'gusviradigital' ),
                'desc'     => __( 'Minimal jumlah donasi yang dapat diberikan', 'gusviradigital' ),
                'default'  => '10000',
                'min'      => '0',
                'step'     => '1000',
                'max'      => '1000000',
            ],
            [
                'id'       => 'donation_suggested_amounts',
                'type'     => 'multi_text',
                'title'    => __( 'Saran Jumlah Donasi', 'gusviradigital' ),
                'desc'     => __( 'Tambahkan saran jumlah donasi (pisahkan dengan enter)', 'gusviradigital' ),
                'default'  => [
                    '50000',
                    '100000',
                    '250000',
                    '500000',
                    '1000000',
                ],
            ],
            [
                'id'       => 'payment_gateway',
                'type'     => 'section',
                'title'    => __( 'Payment Gateway', 'gusviradigital' ),
                'subtitle' => __( 'Pengaturan payment gateway', 'gusviradigital' ),
                'indent'   => true,
            ],
            [
                'id'       => 'payment_gateway_type',
                'type'     => 'select',
                'title'    => __( 'Pilih Payment Gateway', 'gusviradigital' ),
                'desc'     => __( 'Pilih payment gateway yang akan digunakan', 'gusviradigital' ),
                'options'  => [
                    'midtrans' => 'Midtrans',
                    'xendit'   => 'Xendit',
                    'tripay'   => 'Tripay',
                ],
                'default'  => 'midtrans',
            ],
            // Midtrans Settings
            [
                'id'       => 'midtrans_mode',
                'type'     => 'select',
                'title'    => __( 'Mode Midtrans', 'gusviradigital' ),
                'options'  => [
                    'sandbox'    => 'Sandbox (Testing)',
                    'production' => 'Production',
                ],
                'default'  => 'sandbox',
                'required' => ['payment_gateway_type', '=', 'midtrans'],
            ],
            [
                'id'       => 'midtrans_server_key',
                'type'     => 'text',
                'title'    => __( 'Server Key Midtrans', 'gusviradigital' ),
                'desc'     => __( 'Masukkan Server Key dari Midtrans Dashboard', 'gusviradigital' ),
                'required' => ['payment_gateway_type', '=', 'midtrans'],
            ],
            [
                'id'       => 'midtrans_client_key',
                'type'     => 'text',
                'title'    => __( 'Client Key Midtrans', 'gusviradigital' ),
                'desc'     => __( 'Masukkan Client Key dari Midtrans Dashboard', 'gusviradigital' ),
                'required' => ['payment_gateway_type', '=', 'midtrans'],
            ],
            // Xendit Settings
            [
                'id'       => 'xendit_mode',
                'type'     => 'select',
                'title'    => __( 'Mode Xendit', 'gusviradigital' ),
                'options'  => [
                    'sandbox'    => 'Sandbox (Testing)',
                    'production' => 'Production',
                ],
                'default'  => 'sandbox',
                'required' => ['payment_gateway_type', '=', 'xendit'],
            ],
            [
                'id'       => 'xendit_secret_key',
                'type'     => 'text',
                'title'    => __( 'Secret Key Xendit', 'gusviradigital' ),
                'desc'     => __( 'Masukkan Secret Key dari Xendit Dashboard', 'gusviradigital' ),
                'required' => ['payment_gateway_type', '=', 'xendit'],
            ],
            [
                'id'       => 'xendit_public_key',
                'type'     => 'text',
                'title'    => __( 'Public Key Xendit', 'gusviradigital' ),
                'desc'     => __( 'Masukkan Public Key dari Xendit Dashboard', 'gusviradigital' ),
                'required' => ['payment_gateway_type', '=', 'xendit'],
            ],
            // Tripay Settings
            [
                'id'       => 'tripay_mode',
                'type'     => 'select',
                'title'    => __( 'Mode Tripay', 'gusviradigital' ),
                'options'  => [
                    'sandbox'    => 'Sandbox (Testing)',
                    'production' => 'Production',
                ],
                'default'  => 'sandbox',
                'required' => ['payment_gateway_type', '=', 'tripay'],
            ],
            [
                'id'       => 'tripay_api_key',
                'type'     => 'text',
                'title'    => __( 'API Key Tripay', 'gusviradigital' ),
                'desc'     => __( 'Masukkan API Key dari Tripay Dashboard', 'gusviradigital' ),
                'required' => ['payment_gateway_type', '=', 'tripay'],
            ],
            [
                'id'       => 'tripay_private_key',
                'type'     => 'text',
                'title'    => __( 'Private Key Tripay', 'gusviradigital' ),
                'desc'     => __( 'Masukkan Private Key dari Tripay Dashboard', 'gusviradigital' ),
                'required' => ['payment_gateway_type', '=', 'tripay'],
            ],
            [
                'id'       => 'tripay_merchant_code',
                'type'     => 'text',
                'title'    => __( 'Kode Merchant Tripay', 'gusviradigital' ),
                'desc'     => __( 'Masukkan Kode Merchant dari Tripay Dashboard', 'gusviradigital' ),
                'required' => ['payment_gateway_type', '=', 'tripay'],
            ],
            [
                'id'       => 'donation_notification',
                'type'     => 'section',
                'title'    => __( 'Notifikasi', 'gusviradigital' ),
                'subtitle' => __( 'Pengaturan notifikasi donasi', 'gusviradigital' ),
                'indent'   => true,
            ],
            [
                'id'       => 'donation_email_notification',
                'type'     => 'switch',
                'title'    => __( 'Email Notifikasi', 'gusviradigital' ),
                'desc'     => __( 'Kirim email notifikasi saat ada donasi baru', 'gusviradigital' ),
                'default'  => true,
            ],
            [
                'id'       => 'donation_email_recipient',
                'type'     => 'text',
                'title'    => __( 'Email Penerima', 'gusviradigital' ),
                'desc'     => __( 'Email yang akan menerima notifikasi donasi', 'gusviradigital' ),
                'validate' => 'email',
                'required' => ['donation_email_notification', '=', '1'],
            ],
            [
                'id'       => 'donation_whatsapp_notification',
                'type'     => 'switch',
                'title'    => __( 'WhatsApp Notifikasi', 'gusviradigital' ),
                'desc'     => __( 'Kirim notifikasi WhatsApp saat ada donasi baru', 'gusviradigital' ),
                'default'  => false,
            ],
            [
                'id'       => 'donation_whatsapp_number',
                'type'     => 'text',
                'title'    => __( 'Nomor WhatsApp', 'gusviradigital' ),
                'desc'     => __( 'Nomor WhatsApp yang akan menerima notifikasi (format: 628xxxxxxxxxx)', 'gusviradigital' ),
                'required' => ['donation_whatsapp_notification', '=', '1'],
            ],
            [
                'id'       => 'donation_messages',
                'type'     => 'section',
                'title'    => __( 'Pesan', 'gusviradigital' ),
                'subtitle' => __( 'Pengaturan pesan donasi', 'gusviradigital' ),
                'indent'   => true,
            ],
            [
                'id'       => 'donation_success_message',
                'type'     => 'editor',
                'title'    => __( 'Pesan Sukses', 'gusviradigital' ),
                'desc'     => __( 'Pesan yang ditampilkan saat donasi berhasil', 'gusviradigital' ),
                'default'  => __( 'Terima kasih atas donasi Anda. Kami akan segera memproses donasi Anda.', 'gusviradigital' ),
            ],
            [
                'id'       => 'donation_failed_message',
                'type'     => 'editor',
                'title'    => __( 'Pesan Gagal', 'gusviradigital' ),
                'desc'     => __( 'Pesan yang ditampilkan saat donasi gagal', 'gusviradigital' ),
                'default'  => __( 'Maaf, donasi Anda gagal diproses. Silakan coba lagi.', 'gusviradigital' ),
            ],
            [
                'id'       => 'donation_pending_message',
                'type'     => 'editor',
                'title'    => __( 'Pesan Pending', 'gusviradigital' ),
                'desc'     => __( 'Pesan yang ditampilkan saat donasi dalam proses', 'gusviradigital' ),
                'default'  => __( 'Donasi Anda sedang dalam proses. Silakan selesaikan pembayaran sesuai instruksi yang diberikan.', 'gusviradigital' ),
            ],
        ],
    ]
);
